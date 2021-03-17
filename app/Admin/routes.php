<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    // 轮播图
    $router->resource('slideshows', SlideshowController::class);
    // 公司
    $router->resource('companies', CompanyController::class);

    // 富文本编辑器图片上传
    $router->post('editor/upload', 'EditorUploadImageController@upload');
});
