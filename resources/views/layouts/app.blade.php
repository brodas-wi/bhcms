<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'BHCMS') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
            rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @viteReactRefresh
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        {{-- @stack('scripts') --}}
        @stack('styles')
        @yield('styles')
    </head>

    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/home') }}">
                        {{-- {{ config('app.name', 'BHCMS') }} --}}
                        <img src="{{ asset('images/logo.svg') }}" alt="BH Logo" height="24">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="navbar-collapse collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        @if (isset($navigation))
                            <ul class="navbar-nav me-auto">
                                @foreach ($navigation as $item)
                                    <li class="nav-item {{ $item->children->count() > 0 ? 'dropdown' : '' }}">
                                        @if ($item->children->count() > 0)
                                            <a class="nav-link dropdown-toggle" href="#"
                                                id="navbarDropdown{{ $item->id }}" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ $item->title }}
                                            </a>
                                            <ul class="dropdown-menu"
                                                aria-labelledby="navbarDropdown{{ $item->id }}">
                                                @foreach ($item->children as $child)
                                                    <li><a class="dropdown-item"
                                                            href="{{ $child->page_id ? route('pages.display', $child->page->slug) : $child->url }}">{{ $child->title }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <a class="nav-link"
                                                href="{{ $item->page_id ? route('pages.display', $item->page->slug) : $item->url }}">{{ $item->title }}</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav align-items-center ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link text-light" href="{{ route('login') }}">{{ __('Acceder') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link text-light"
                                            href="{{ route('register') }}">{{ __('Registro') }}</a>
                                    </li>
                                @endif
                            @else
                                @auth
                                    <div class="d-flex align-items-center justify-content-between mx-4 gap-2">
                                        {{-- @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_users'))
                                            <li class="nav-item align-items-center">
                                                <a class="nav-link text-light"
                                                    href="{{ route('users.index') }}">{{ __('Usuarios') }}</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_roles'))
                                            <li class="nav-item align-items-center">
                                                <a class="nav-link text-light"
                                                    href="{{ route('roles.index') }}">{{ __('Roles') }}</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_permissions'))
                                            <li class="nav-item align-items-center">
                                                <a class="nav-link text-light"
                                                    href="{{ route('permissions.index') }}">{{ __('Permisos') }}</a>
                                            </li>
                                        @endif --}}
                                        @if (Auth::user()->hasRole('admin') ||
                                                Auth::user()->can('view_users') ||
                                                Auth::user()->can('view_roles') ||
                                                Auth::user()->can('view_permissions'))
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle text-white" href="#"
                                                    id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Administración
                                                </a>
                                                <ul class="dropdown-menu custom-dropdown" aria-labelledby="navbarDropdownAdmin">
                                                    @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_users'))
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('users.index') }}">{{ __('Usuarios') }}</a>
                                                        </li>
                                                    @endif
                                                    @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_roles'))
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('roles.index') }}">{{ __('Roles') }}</a></li>
                                                    @endif
                                                    @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_permissions'))
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('permissions.index') }}">{{ __('Permisos') }}</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </li>
                                        @endif
                                        {{-- @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_pages'))
                                            <li class="nav-item align-items-center">
                                                <a class="nav-link text-light"
                                                    href="{{ route('pages.index') }}">{{ __('Páginas') }}</a>
                                            </li>
                                        @endif --}}
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_pages'))
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle text-white" href="#"
                                                    id="navbarDropdownContent" role="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Contenido
                                                </a>
                                                <ul class="dropdown-menu custom-dropdown"
                                                    aria-labelledby="navbarDropdownContent">
                                                    @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_pages'))
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('pages.index') }}">{{ __('Páginas') }}</a></li>
                                                    @endif
                                                    @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_pages'))
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('navbars.index') }}">{{ __('Navbars') }}</a>
                                                        </li>
                                                    @endif
                                                    @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_pages'))
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('footers.index') }}">{{ __('Footers') }}</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </li>
                                        @endif
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_templates'))
                                            <li class="nav-item align-items-center">
                                                <a class="nav-link text-light"
                                                    href="{{ route('templates.index') }}">{{ __('Plantillas') }}</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->can('manage_plugins'))
                                            <li class="nav-item align-items-center">
                                                <a class="nav-link text-light"
                                                    href="{{ route('plugin.index') }}">{{ __('Plugins') }}</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->hasRole('admin') || Auth::user()->can('view_articles'))
                                            <li class="nav-item align-items-center">
                                                <a class="nav-link text-light"
                                                    href="{{ route('contents.index') }}">{{ __('Articulos') }}</a>
                                            </li>
                                        @endif
                                    </div>
                                @endauth

                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link text-light dropdown-toggle" href="#"
                                        role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false" v-pre>
                                        {{ ucfirst(strtolower(Auth::user()->name)) }}
                                    </a>

                                    <div class="dropdown-menu custom-dropdown dropdown-menu-end"
                                        aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item py-1" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Cerrar sesión') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>
        </div>

        @stack('scripts')
        @yield('scripts')
    </body>

</html>
