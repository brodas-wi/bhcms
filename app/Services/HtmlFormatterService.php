<?php

namespace App\Services;

class HtmlFormatterService
{
    public function formatHtml(string $html, bool $extractFromBody = true): string
    {
        try {
            // Preservar los elementos de Font Awesome y otros elementos especiales
            $html = $this->preserveSpecialElements($html);

            // Crear nuevo DOMDocument
            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->encoding = 'UTF-8';
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;

            // Suprimir errores de warnings de DOM y cargar HTML
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();

            if ($extractFromBody) {
                // Buscar el contenido dentro del body
                $body = $dom->getElementsByTagName('body')->item(0);

                if ($body) {
                    // Crear un nuevo documento para el contenido limpio
                    $cleanDom = new \DOMDocument('1.0', 'UTF-8');
                    $cleanDom->formatOutput = true;

                    // Copiar todos los nodos hijos del body
                    foreach ($body->childNodes as $child) {
                        $cleanDom->appendChild($cleanDom->importNode($child, true));
                    }

                    // Obtener el HTML limpio
                    $html = $cleanDom->saveHTML();
                } else {
                    // Si no hay body, usar el documento completo
                    $html = $dom->saveHTML($dom->documentElement);
                }
            } else {
                // Si no queremos extraer del body, usar el documento completo
                $html = $dom->saveHTML($dom->documentElement);
            }

            // Restaurar los elementos especiales
            $html = $this->restoreSpecialElements($html);

            // Limpiar el HTML resultante
            $html = $this->cleanHtml($html);

            return trim($html);
        } catch (\Exception $e) {
            \Log::error("Error formateando HTML: " . $e->getMessage());
            return $html;
        }
    }

    protected function preserveSpecialElements(string $html): string
    {
        $patterns = [
            // Patrón más específico para Font Awesome
            '/<i\s+class="[^"]*(?:fa-[^"]+|fas|far|fab|fal|fad)[^"]*"[^>]*>(?:[^<]*)<\/i>/i',
            // Patrón para Font Awesome con contenido
            '/<i\s+class="[^"]*(?:fa-[^"]+|fas|far|fab|fal|fad)[^"]*"[^>]*>.*?<\/i>/is',
            // SVG y otros iconos
            '/<svg[^>]*>.*?<\/svg>/is',
            // Scripts
            '/<script\b[^>]*>(.*?)<\/script>/is',
            // Estilos
            '/<style\b[^>]*>(.*?)<\/style>/is'
        ];

        foreach ($patterns as $pattern) {
            $html = preg_replace_callback($pattern, function ($matches) {
                // Codificar el contenido completo y marcarlo para preservación
                return '<!--PRESERVED' . base64_encode($matches[0]) . 'PRESERVED-->';
            }, $html);
        }

        return $html;
    }

    protected function restoreSpecialElements(string $html): string
    {
        return preg_replace_callback('/<!--PRESERVED(.*?)PRESERVED-->/', function ($matches) {
            // Decodificar el contenido preservado
            $decodedContent = base64_decode($matches[1]);

            // Asegurarse de que los elementos de Font Awesome mantengan su estructura
            if (strpos($decodedContent, 'fa-') !== false) {
                // Limpiar cualquier espacio extra o formato que pudiera haberse introducido
                $decodedContent = preg_replace('/\s+/', ' ', trim($decodedContent));
            }

            return $decodedContent;
        }, $html);
    }

    protected function preserveSpecialContent(string $html): string
    {
        // Preservar scripts
        $html = preg_replace_callback('/<script\b[^>]*>(.*?)<\/script>/is', function ($matches) {
            return '<script>' . base64_encode($matches[1]) . '</script>';
        }, $html);

        // Preservar estilos
        $html = preg_replace_callback('/<style\b[^>]*>(.*?)<\/style>/is', function ($matches) {
            return '<style>' . base64_encode($matches[1]) . '</style>';
        }, $html);

        return $html;
    }

    protected function restoreSpecialContent(string $html): string
    {
        // Restaurar scripts
        $html = preg_replace_callback('/<script>(.*?)<\/script>/is', function ($matches) {
            return '<script>' . base64_decode($matches[1]) . '</script>';
        }, $html);

        // Restaurar estilos
        $html = preg_replace_callback('/<style>(.*?)<\/style>/is', function ($matches) {
            return '<style>' . base64_decode($matches[1]) . '</style>';
        }, $html);

        return $html;
    }

    protected function cleanHtml(string $html): string
    {
        // Eliminar espacios múltiples excepto en elementos preservados
        $html = preg_replace('/(?<!PRESERVED)>\s+</m', '><', $html);

        // Eliminar comentarios HTML excepto los preservados
        $html = preg_replace('/<!--(?!PRESERVED)(?!<!)[^\[>].*?-->/', '', $html);

        // Lista expandida de etiquetas vacías permitidas
        $allowedEmptyTags = ['i', 'span', 'br', 'hr', 'img', 'input', 'link', 'meta', 'area', 'base', 'col', 'embed', 'param', 'source', 'track', 'wbr'];

        // No eliminar etiquetas vacías que están en la lista permitida
        $pattern = '<(?!' . implode('|', $allowedEmptyTags) . '\b)([a-z]+)\s*(?:\s+[\w\-]+=(?:\'[^\']*\'|"[^"]*"))*\s*><\/\1>';
        $html = preg_replace('/' . $pattern . '/', '', $html);

        // Restaurar etiquetas vacías permitidas
        foreach ($allowedEmptyTags as $tag) {
            $html = preg_replace("/<$tag([^>]*)><\/$tag>/", "<$tag$1></$tag>", $html);
        }

        return trim($html);
    }

    public function formatStyles(string $css): string
    {
        try {
            // Eliminar etiquetas style si existen
            $css = preg_replace('/<style[^>]*>|<\/style>/', '', $css);

            // Eliminar comentarios CSS
            $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

            // Separar cada regla en líneas diferentes
            $css = preg_replace('/}\s*/', "}\n", $css);

            // Procesar cada regla CSS
            $rules = explode('}', $css);
            $formattedCss = '';

            foreach ($rules as $rule) {
                $rule = trim($rule);
                if (empty($rule)) continue;

                // Separar selector de propiedades
                $parts = explode('{', $rule);
                if (count($parts) != 2) continue;

                $selector = trim($parts[0]);
                $properties = trim($parts[1]);

                // Formatear propiedades
                $props = explode(';', $properties);
                $formattedProps = array_map(function ($prop) {
                    $prop = trim($prop);
                    return !empty($prop) ? "    " . $prop . ";" : "";
                }, $props);

                // Construir regla formateada
                $formattedCss .= $selector . " {\n" .
                    implode("\n", array_filter($formattedProps)) . "\n}\n\n";
            }

            return trim($formattedCss);
        } catch (\Exception $e) {
            \Log::error("Error formateando CSS: " . $e->getMessage());
            return $css;
        }
    }

    public function formatScripts(string $js): string
    {
        try {
            // Eliminar etiquetas script si existen
            $js = preg_replace('/<script[^>]*>|<\/script>/', '', $js);

            // Remover comentarios de una línea
            $js = preg_replace('/\/\/.*$/m', '', $js);

            // Remover comentarios multilínea
            $js = preg_replace('/\/\*.*?\*\//s', '', $js);

            // Añadir punto y coma al final de las líneas si falta
            $js = preg_replace('/}(?!\s*else)/', '};', $js);

            // Formatear bloques
            $js = preg_replace('/{\s*/', " {\n    ", $js);
            $js = preg_replace('/}\s*/', "\n}\n", $js);

            // Añadir sangría a las líneas dentro de bloques
            $lines = explode("\n", $js);
            $indentLevel = 0;
            $formattedJs = '';

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // Ajustar nivel de sangría
                $indentLevel -= substr_count($line, '}');
                if ($indentLevel < 0) $indentLevel = 0;

                // Añadir la línea con la sangría correcta
                $formattedJs .= str_repeat('    ', $indentLevel) . $line . "\n";

                $indentLevel += substr_count($line, '{');
            }

            return trim($formattedJs);
        } catch (\Exception $e) {
            \Log::error("Error formateando JavaScript: " . $e->getMessage());
            return $js;
        }
    }
}
