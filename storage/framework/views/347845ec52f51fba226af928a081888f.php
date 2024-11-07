<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?php echo e(asset('favicon.svg')); ?>" type="image/svg">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title><?php echo e(config('app.name', 'BHCMS')); ?></title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
            rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')->reactRefresh(); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>

        
        <?php echo $__env->yieldPushContent('styles'); ?>
        <?php echo $__env->yieldContent('styles'); ?>
    </head>

    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="<?php echo e(url('/home')); ?>">
                        
                        <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="BH Logo" height="24">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="navbar-collapse collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <?php if(isset($navigation)): ?>
                            <ul class="navbar-nav me-auto">
                                <?php $__currentLoopData = $navigation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="nav-item <?php echo e($item->children->count() > 0 ? 'dropdown' : ''); ?>">
                                        <?php if($item->children->count() > 0): ?>
                                            <a class="nav-link dropdown-toggle" href="#"
                                                id="navbarDropdown<?php echo e($item->id); ?>" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <?php echo e($item->title); ?>

                                            </a>
                                            <ul class="dropdown-menu"
                                                aria-labelledby="navbarDropdown<?php echo e($item->id); ?>">
                                                <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><a class="dropdown-item"
                                                            href="<?php echo e($child->page_id ? route('pages.display', $child->page->slug) : $child->url); ?>"><?php echo e($child->title); ?></a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php else: ?>
                                            <a class="nav-link"
                                                href="<?php echo e($item->page_id ? route('pages.display', $item->page->slug) : $item->url); ?>"><?php echo e($item->title); ?></a>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav align-items-center ms-auto">
                            <!-- Authentication Links -->
                            <?php if(auth()->guard()->guest()): ?>
                                <?php if(Route::has('login')): ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-light" href="<?php echo e(route('login')); ?>"><?php echo e(__('Acceder')); ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if(Route::has('register')): ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-light"
                                            href="<?php echo e(route('register')); ?>"><?php echo e(__('Registro')); ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if(auth()->guard()->check()): ?>
                                    <div class="d-flex align-items-center justify-content-between mx-4 gap-2">
                                        
                                        <?php if(Auth::user()->hasRole('admin') ||
                                                Auth::user()->can('view_users') ||
                                                Auth::user()->can('view_roles') ||
                                                Auth::user()->can('view_permissions')): ?>
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle text-white" href="#"
                                                    id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Administración
                                                </a>
                                                <ul class="dropdown-menu custom-dropdown" aria-labelledby="navbarDropdownAdmin">
                                                    <?php if(Auth::user()->hasRole('admin') || Auth::user()->can('view_users')): ?>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo e(route('users.index')); ?>"><?php echo e(__('Usuarios')); ?></a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if(Auth::user()->hasRole('admin') || Auth::user()->can('view_roles')): ?>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo e(route('roles.index')); ?>"><?php echo e(__('Roles')); ?></a></li>
                                                    <?php endif; ?>
                                                    <?php if(Auth::user()->hasRole('admin') || Auth::user()->can('view_permissions')): ?>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo e(route('permissions.index')); ?>"><?php echo e(__('Permisos')); ?></a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php if(Auth::user()->hasRole('admin') || Auth::user()->can('view_pages')): ?>
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle text-white" href="#"
                                                    id="navbarDropdownContent" role="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Contenido
                                                </a>
                                                <ul class="dropdown-menu custom-dropdown"
                                                    aria-labelledby="navbarDropdownContent">
                                                    <?php if(Auth::user()->hasRole('admin') || Auth::user()->can('view_pages')): ?>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo e(route('pages.index')); ?>"><?php echo e(__('Páginas')); ?></a></li>
                                                    <?php endif; ?>
                                                    <?php if(Auth::user()->hasRole('admin') || Auth::user()->can('view_pages')): ?>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo e(route('navbars.index')); ?>"><?php echo e(__('Navbars')); ?></a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if(Auth::user()->hasRole('admin') || Auth::user()->can('view_pages')): ?>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo e(route('footers.index')); ?>"><?php echo e(__('Footers')); ?></a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(Auth::user()->hasRole('admin') || Auth::user()->can('view_templates')): ?>
                                            <li class="nav-item align-items-center">
                                                <a class="nav-link text-light"
                                                    href="<?php echo e(route('templates.index')); ?>"><?php echo e(__('Plantillas')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(Auth::user()->hasRole('admin') || Auth::user()->can('manage_plugins')): ?>
                                            <li class="nav-item align-items-center">
                                                <a class="nav-link text-light"
                                                    href="<?php echo e(route('plugin.index')); ?>"><?php echo e(__('Plugins')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(Auth::user()->hasRole('admin') || Auth::user()->can('view_articles')): ?>
                                            <li class="nav-item align-items-center">
                                                <a class="nav-link text-light"
                                                    href="<?php echo e(route('contents.index')); ?>"><?php echo e(__('Articulos')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link text-light dropdown-toggle" href="#"
                                        role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false" v-pre>
                                        <?php echo e(ucfirst(strtolower(Auth::user()->name))); ?>

                                    </a>

                                    <div class="dropdown-menu custom-dropdown dropdown-menu-end"
                                        aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item py-1" href="<?php echo e(route('logout')); ?>"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <?php echo e(__('Cerrar sesión')); ?>

                                        </a>

                                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                                            class="d-none">
                                            <?php echo csrf_field(); ?>
                                        </form>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>

        <?php echo $__env->yieldPushContent('scripts'); ?>
        <?php echo $__env->yieldContent('scripts'); ?>
    </body>

</html>
<?php /**PATH C:\Users\brian\Documentos\GitHub\bankhipo\website-institucional\bhcms\resources\views/layouts/app.blade.php ENDPATH**/ ?>