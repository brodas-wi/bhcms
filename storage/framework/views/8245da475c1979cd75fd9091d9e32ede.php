<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo e($page->description); ?>">
        <title><?php echo e($page->name); ?></title>

        <!-- Estilos externos -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Estilos de la plantilla -->
        <?php if($page->template && $page->template->styles): ?>
            <style type="text/css">
                /* Template Styles */
                <?php echo $page->template->styles; ?>

            </style>
        <?php endif; ?>

        <!-- Estilos del Navbar -->
        <?php if($page->navbar && $page->navbar->css): ?>
            <style type="text/css">
                /* Navbar Styles */
                <?php echo $page->navbar->css; ?>

            </style>
        <?php endif; ?>

        <!-- Estilos del Footer -->
        <?php if($page->footer && $page->footer->css): ?>
            <style type="text/css">
                /* Footer Styles */
                <?php echo $page->footer->css; ?>

            </style>
        <?php endif; ?>

        <!-- Estilos de plugins -->
        <?php if(!empty($pluginStyles)): ?>
            <style type="text/css">
                /* Plugin Styles */
                <?php echo $pluginStyles; ?>

            </style>
        <?php endif; ?>

        <!-- Hook para head -->
        <?php $hookSystem->doAction('head') ?>
    </head>

    <body>
        <!-- Navbar -->
        <?php if($page->navbar && $page->navbar->is_active): ?>
            <?php echo $page->navbar->content; ?>

        <?php endif; ?>

        <!-- Contenido principal -->
        <main class="page-content">
            <?php echo $content; ?>

        </main>

        <!-- Footer -->
        <?php if($page->footer): ?>
            <?php echo $page->footer->content; ?>

        <?php endif; ?>

        <!-- Scripts externos -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

        <!-- Scripts de plugins -->
        <?php if(!empty($pluginScripts)): ?>
            <script type="text/javascript">
                (function() {
                    'use strict';
                    /* Plugin Scripts */
                    <?php echo $pluginScripts; ?>

                })();
            </script>
        <?php endif; ?>

        <!-- Hook para footer -->
        <?php $hookSystem->doAction('footer') ?>
    </body>

</html>
<?php /**PATH C:\Users\brian\Documentos\GitHub\bankhipo\website-institucional\bhcms\resources\views/pages/display.blade.php ENDPATH**/ ?>