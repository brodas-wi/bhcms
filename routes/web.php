<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    ContentController,
    TagController,
    RoleController,
    PermissionController,
    UserController,
    TemplateController,
    MediaController,
    PageController,
    NavbarController,
    FooterController,
    PluginController,
    HomeController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // ROLES ROUTES
    // Route to create roles protected, only admin or create_role permissions
    Route::middleware(['auth', 'roleOrPermission:admin,create_role'])->group(function () {
        // Route to create role view URL
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        // Route to post or create role
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    });

    Route::middleware(['auth', 'roleOrPermission:admin,view_roles'])->group(function () {
        // Route to get all roles view URL
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    });

    Route::middleware(['auth', 'roleOrPermission:admin,edit_role'])->group(function () {
        // Route to get permissions to add role
        Route::get('roles/{id}/permissions/edit', [RoleController::class, 'editPermissions'])->name('roles.permissions.edit');
        // Route to put or update permissions for one role
        Route::put('roles/{id}/', [RoleController::class, 'update'])->name('roles.permissions.update');
    });

    Route::middleware(['auth', 'roleOrPermission:admin,delete_role'])->group(function () {
        // Route to delete or destroy role
        Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });
    // ROLES ROUTES

    // PERMISSIONS ROUTES
    Route::middleware(['auth', 'roleOrPermission:admin,create_permission'])->group(function () {
        // Route to create permissions view URL
        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        // Route to post or create permission
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    });
    // Route to get all permissions view URL
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');

    Route::middleware(['auth', 'roleOrPermission:admin,delete_permission'])->group(function () {
        Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });
    // PERMISSIONS ROUTES

    // USER ROUTES
    Route::middleware(['auth', 'roleOrPermission:admin,view_users'])->group(function () {
        // Route to get view for all users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    Route::middleware(['auth', 'roleOrPermission:admin,create_user'])->group(function () {
        // Route to get new user view
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        // Route to post or create new user
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });

    Route::middleware(['auth', 'roleOrPermission:admin,edit_user'])->group(function () {
        // Route to get edit user view
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        // Route to post or update user info
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    });

    Route::middleware(['auth', 'roleOrPermission:admin,delete_user'])->group(function () {
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    // USER ROUTES

    // PAGE ROUTES
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/{page}/version', [PageController::class, 'getVersion'])->name('pages.get-version');
    Route::delete('pages/{page}/versions', [PageController::class, 'deleteVersion'])->name('pages.deleteVersion');
    Route::get('/pages/{page}/next-versions', [PageController::class, 'getNextVersions'])->name('pages.nextVersions');
    Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page}', [PageController::class, 'show'])->name('pages.show');
    Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
    route::get('/page/{slug}', [PageController::class, 'display'])->name('pages.display');

    // Route::post('/upload-image', [PageController::class, 'uploadImage']);
    // Route::get('/get-images', [PageController::class, 'getImages']);
    Route::post('/upload-image', [PageController::class, 'uploadImage'])->name('upload-image');
    Route::get('/get-images', [PageController::class, 'getImages'])->name('get-images');

    Route::get('/check-storage', [PageController::class, 'checkStorageConfig']);

    // Grupo de rutas para navbars
    Route::prefix('navbars')->group(function () {
        // Rutas de listado y visualización
        Route::get('/', [NavbarController::class, 'index'])->name('navbars.index');
        Route::get('/load', [NavbarController::class, 'load'])->name('navbars.load');

        // Rutas de creación
        Route::get('/create', [NavbarController::class, 'create'])->name('navbars.create');
        Route::post('/', [NavbarController::class, 'store'])->name('navbars.store');

        // Rutas de edición y actualización
        Route::get('/{navbar}/edit', [NavbarController::class, 'edit'])->name('navbars.edit');
        Route::put('/{navbar}', [NavbarController::class, 'update'])->name('navbars.update');

        // Ruta para mostrar un navbar específico
        Route::get('/{navbar}', [NavbarController::class, 'show'])->name('navbars.show');

        // Ruta para eliminar
        Route::delete('/{navbar}', [NavbarController::class, 'destroy'])->name('navbars.destroy');

        // Ruta para activar/desactivar
        Route::post('/{navbar}/toggle-active', [NavbarController::class, 'toggleActive'])
            ->name('navbars.toggle-active');
    });

    // Grupo de rutas para footers
    Route::prefix('footers')->group(function () {
        // Rutas de listado y visualización
        Route::get('/', [FooterController::class, 'index'])->name('footers.index');
        Route::get('/load', [FooterController::class, 'load'])->name('footers.load');

        // Rutas de creación
        Route::get('/create', [FooterController::class, 'create'])->name('footers.create');
        Route::post('/', [FooterController::class, 'store'])->name('footers.store');

        // Rutas de edición y actualización
        Route::get('/{footer}/edit', [FooterController::class, 'edit'])->name('footers.edit');
        Route::put('/{footer}', [FooterController::class, 'update'])->name('footers.update');

        // Ruta para mostrar un footer específico
        Route::get('/{footer}', [FooterController::class, 'show'])->name('footers.show');

        // Ruta para eliminar
        Route::delete('/{footer}', [FooterController::class, 'destroy'])->name('footers.destroy');

        // Ruta para activar/desactivar
        Route::post('/{footer}/toggle-active', [FooterController::class, 'toggleActive'])
            ->name('footers.toggle-active');
    });

    Route::get('/get-css-styles', [TemplateController::class, 'getCssStyles'])->name('get.css.styles');
    Route::get('/get-css-style/{id}', [TemplateController::class, 'getCssStyle'])->name('get.css.style');
    // PAGE ROUTES

    // TEMPLATE ROUTES
    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/create', [TemplateController::class, 'create'])->name('templates.create');
    Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
    Route::get('/templates/{template}', [TemplateController::class, 'show'])->name('templates.show');
    Route::get('/templates/{template}/edit', [TemplateController::class, 'edit'])->name('templates.edit');
    Route::put('/templates/{template}', [TemplateController::class, 'update'])->name('templates.update');
    Route::delete('/templates/{template}', [TemplateController::class, 'destroy'])->name('templates.destroy');
    // Route::get('/templates/default-content', [TemplateController::class, 'getDefaultTemplateContent'])->name('templates.default-content');
    // TEMPLATE ROUTES

    // PLUGIN ROUTES
    Route::middleware(['auth'])->group(function () {
        Route::get('/plugin', [PluginController::class, 'index'])->name('plugin.index');
        Route::get('/plugins/create', [PluginController::class, 'create'])->name('plugins.create');
        Route::post('/plugin', [PluginController::class, 'store'])->name('plugin.store');
        Route::get('/plugins/{plugin}/preview/{viewName}', [PluginController::class, 'preview'])->name('plugins.preview');
        Route::get('/plugins/{plugin}/render', [PluginController::class, 'renderForEditor'])
            ->name('plugins.render.editor')
            ->middleware('auth');
        // Route::get('plugins/{plugin}/preview/{viewName}', [PluginController::class, 'renderView'])->name('plugins.preview');
        Route::get('/plugins/{plugin}', [PluginController::class, 'show'])->name('plugins.show');
        Route::get('/plugins/{plugin}/edit', [PluginController::class, 'edit'])->name('plugins.edit');
        Route::put('/plugins/{plugin}', [PluginController::class, 'update'])->name('plugins.update');
        Route::delete('/plugins/{plugin}', [PluginController::class, 'destroy'])->name('plugins.destroy');

        Route::post('/plugins/{plugin}/activate', [PluginController::class, 'activate'])->name('plugins.activate');
        Route::post('/plugins/{plugin}/deactivate', [PluginController::class, 'deactivate'])->name('plugins.deactivate');
        Route::get('/plugins/{plugin}/configure', [PluginController::class, 'configure'])->name('plugins.configure');

        Route::get('/plugins/assets/{plugin}/{path}', [PluginController::class, 'serveAsset'])
            ->where('path', '.*')
            ->name('plugins.asset');
    });
    // PLUGIN ROUTES

    // CONTENT ROUTES
    Route::get('contents/trash', [ContentController::class, 'trash'])->name('contents.trash');
    Route::post('contents/{id}/restore', [ContentController::class, 'restore'])->name('contents.restore');
    Route::delete('contents/{id}/force-delete', [ContentController::class, 'forceDelete'])->name('contents.force-delete');
    Route::post('contents/{content}/versions/{version}/restore', [ContentController::class, 'restoreVersion'])
        ->name('contents.restore-version');
    Route::post('contents/autosave', [ContentController::class, 'autosave'])->name('contents.autosave');
    Route::resource('contents', ContentController::class);
    // CONTENT ROUTES

    Route::prefix('api')->group(function () {
        Route::get('/tags', [TagController::class, 'index']);
        Route::post('/tags', [TagController::class, 'store']);
        Route::delete('/tags/{tag}', [TagController::class, 'destroy']);
        Route::get('/tags/search', [TagController::class, 'search']);
    });

    Route::resource('media', MediaController::class);
    Route::post('media/create-thumbnail', [MediaController::class, 'createThumbnail'])->name('media.create-thumbnail');;
});
