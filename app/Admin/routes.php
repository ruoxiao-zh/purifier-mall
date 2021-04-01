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
    // 赚钱小技巧
    $router->resource('make-money-tips', MakeMoneyTipController::class);

    // 富文本编辑器图片上传
    $router->post('editor/upload', 'EditorUploadImageController@upload');

    // 商品
    $router->resource('products', ProductsController::class);

    // 订单
    $router->resource('orders', OrdersController::class);
    $router->post('orders/{order}/ship', 'OrdersController@ship')->name('admin.orders.ship');
});
