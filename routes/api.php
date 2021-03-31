<?php

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
        $api->get('make-money-tips', 'MakeMoneyTipController@index')->name('make-money-tips.index');
        $api->get('make-money-tips/{makeMoneyTip}', 'MakeMoneyTipController@show')->name('make-money-tips.show');

        // 商品列表
        $api->get('products', 'ProductController@index')->name('products.index');
        // 商品详情
        $api->get('products/{product}', 'ProductController@show')->name('products.show');


        // 用户注册
        $api->post('users', 'UsersController@store')->name('users.store');
        // 登录
        $api->post('authorizations', 'AuthorizationsController@store')->name('api.authorizations.store');
        // 刷新token
        $api->put('authorizations/current', 'AuthorizationsController@update')->name('authorizations.update');
        // 删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')->name('authorizations.destroy');

        //******************** 登录之后才能访问的接口 ********************
        // Route::middleware('auth:api')->group(function ($api) {
            // 用户地址
            $api->resource('user-addresses', 'UserAddressController');
        // });
    });

    Route::middleware(['throttle:' . config('api.rate_limits.sign')])->group(function ($api) {
        // 短信
        $api->post('message-codes', 'MessageCodeController@store')->name('message-codes.store');
    });
});

Route::fallback(function () {
    return response()->json(['message' => '404 Not Found. Please check your request url!'], 404);
});
