<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->post('upload', 'UploadController@index')->name('admin.upload');
    $router->resource('users', UserController::class);
    $router->resource('banners', BannerController::class);
    $router->resource('articles', ArticleController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('departments', DepartmentController::class);
    $router->resource('messages', MessageController::class);

});