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
    $router->resource('staffs', StaffController::class);
    $router->resource('duties', DutyController::class);
    $router->resource('rewards', RewardController::class);
    $router->resource('banners', BannerController::class);
    $router->resource('articles', ArticleController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('departments', DepartmentController::class);
    $router->resource('messages', MessageController::class);
    $router->get('score/show/{staff_id}', 'ScoreController@show')->name('score.show');

});