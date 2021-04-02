<?php

use App\Enums\HttpCodeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->name('api.v1.')->middleware(['cors'])->group(function ($api) {

    Route::middleware('throttle:' . config('api.rate_limits.access'))->group(function ($api) {
        //******************** 无需登录可以访问的接口 ********************
        // 轮播图列表
        $api->get('slideshows', 'SlideshowController@index')->name('slideshows.index');
        // 公司详情
        $api->get('companies/{company}', 'CompanyController@show')->name('companies.index');
        // 省钱小技巧
        $api->get('make_money_tips', 'MakeMoneyTipController@index')->name('make_money_tips.index');
        $api->get('make_money_tips/{makeMoneyTip}', 'MakeMoneyTipController@show')->name('make_money_tips.show');

        // 商品列表
        $api->get('products', 'ProductController@index')->name('products.index');
        // 商品详情
        $api->get('products/{product}', 'ProductController@show')->name('products.show');


        // 用户注册
        // $api->post('users', 'UserController@store')->name('users.store');
        // 登录
        $api->post('authorizations', 'AuthorizationController@store')->name('api.authorizations.store');
        // 刷新token
        $api->put('authorizations/current', 'AuthorizationController@update')->name('authorizations.update');
        // 删除token
        $api->delete('authorizations/current', 'AuthorizationController@destroy')->name('authorizations.destroy');

        //******************** 登录之后才能访问的接口 ********************
        Route::middleware('auth:api')->group(function ($api) {
            // 用户地址
            $api->resource('user_addresses', 'UserAddressController');

            // 购物车列表
            $api->get('cart_items', 'CartController@index')->name('cart_items.index');
            // 添加商品到购物车
            $api->post('cart_items', 'CartController@store')->name('cart_items.store');
            // 删除购物车里边的商品
            $api->delete('cart_items/{productSku}', 'CartController@destroy')->name('cart_items.destroy');

            // 下单
            $api->post('orders', 'OrderController@store')->name('orders.store');
            // 订单列表
            $api->get('orders', 'OrderController@index')->name('orders.index');
            // 订单详情
            $api->get('orders/{order}', 'OrderController@show')->name('orders.show');
            // 删除订单
            $api->delete('orders', 'OrderController@destroy')->name('orders.destroy');
            // 订单确认收货
            $api->post('orders/{order}/received', 'OrderController@received')->name('orders.received');
            // 订单退款
            $api->post('orders/{order}/apply_refund', 'OrderController@applyRefund')->name('orders.apply_refund');

            // 支付
            $api->get('payment/{order}/wechat', 'PaymentController@payByWechat')->name('payment.wechat');
        });
    });

    Route::middleware(['throttle:' . config('api.rate_limits.sign')])->group(function ($api) {
        // 短信
        $api->post('message_codes', 'MessageCodeController@store')->name('message_codes.store');
    });
});

// 微信支付回调
Route::post('payment/wechat/notify', 'Api\PaymentController@wechatNotify')->name('payment.wechat.notify');
// 微信退款回调
Route::post('payment/wechat/refund_notify', 'Api\PaymentController@wechatRefundNotify')->name('payment.wechat.refund_notify');

Route::fallback(function () {
    return response()->json(['message' => '404 Not Found. Please check your request url!'], HttpCodeEnum::HTTP_CODE_404);
});
