<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ContentService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            // Procesar tags
            $tags = $this->processTags($data['tags'] ?? []);

            // Generar slug único
            $slug = $this->generateUniqueSlug($data['title']);

            // Procesar featured_image
            $featured_image = $this->processFeaturedImage($data['featured_image'] ?? null);

            // Crear el contenido
            $content = Content::create([
                'title' => $data['title'],
                'slug' => $slug,
                'content' => $data['content'],
                'type' => $data['type'],
                'status' => $data['status'],
                'published_at' => $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null,
                'user_id' => auth()->id(),
                'featured_image' => $featured_image,
                'excerpt' => $data['excerpt'] ?? null,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
            ]);

            // Sincronizar relaciones
            if (isset($data['categories'])) {
                $content->categories()->sync($data['categories']);
            }
            if ($tags) {
                $content->tags()->sync($tags);
            }

            // Crear primera versión
            $content->createVersion();

            DB::commit();
            return $content;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Content $content, array $data)
    {
        DB::beginTransaction();
        try {
            // Procesar tags
            $tags = $this->processTags($data['tags'] ?? []);

            // Crear nueva versión antes de actualizar
            $content->createVersion();

            // Generar nuevo slug solo si el título cambió
            $slug = $content->title !== $data['title']
                ? $this->generateUniqueSlug($data['title'])
                : $content->slug;

            // Procesar featured_image
            $featured_image = $this->processFeaturedImage($data['featured_image'] ?? $content->featured_image);

            // Actualizar contenido
            $content->update([
                'title' => $data['title'],
                'slug' => $slug,
                'content' => $data['content'],
                'type' => $data['type'],
                'status' => $data['status'],
                'published_at' => $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null,
                'featured_image' => $featured_image,
                'excerpt' => $data['excerpt'] ?? $content->excerpt,
                'meta_title' => $data['meta_title'] ?? $content->meta_title,
                'meta_description' => $data['meta_description'] ?? $content->meta_description,
            ]);

            // Sincronizar relaciones
            if (isset($data['categories'])) {
                $content->categories()->sync($data['categories']);
            }
            if ($tags) {
                $content->tags()->sync($tags);
            }

            DB::commit();
            return $content;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = 0;
        $originalSlug = $slug;

        while (Content::where('slug', $slug)->exists()) {
            $count++;
            $slug = $originalSlug . '-' . $count;
        }

        return $slug;
    }

    private function processTags(array $tagIds)
    {
        $processedTags = [];
        foreach ($tagIds as $tagId) {
            // Si el ID no es numérico, es un nuevo tag
            if (!is_numeric($tagId)) {
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($tagId)],
                    ['name' => $tagId]
                );
                $processedTags[] = $tag->id;
            } else {
                $processedTags[] = $tagId;
            }
        }
        return $processedTags;
    }

    private function processFeaturedImage(?string $featured_image): ?string
    {
        if (empty($featured_image)) {
            return null;
        }

        // Limpia la URL si es necesario
        $featured_image = trim($featured_image);

        // Si la imagen es una URL relativa, asegurarse de que comience con /
        if (!empty($featured_image) && !str_starts_with($featured_image, 'http') && !str_starts_with($featured_image, '/')) {
            $featured_image = '/' . $featured_image;
        }

        return $featured_image;
    }

    public function search($query, $type = null, $category = null)
    {
        return Content::query()
            ->when($query, function ($q) use ($query) {
                return $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->when($type, function ($q) use ($type) {
                return $q->where('type', $type);
            })
            ->when($category, function ($q) use ($category) {
                return $q->whereHas('categories', function ($q) use ($category) {
                    $q->where('categories.id', $category);
                });
            })
            ->published()
            ->latest('published_at')
            ->paginate(12);
    }
}
