<?php

use Illuminate\Http\Request;

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
    // 轮播图列表
    $api->get('slideshows', 'SlideshowController@index')->name('slideshows.index');
    // 公司详情
    $api->get('companies/{company}', 'CompanyController@show')->name('companies.index');
    // 省钱小技巧
    $api->get('make-money-tips', 'MakeMoneyTipController@index')->name('make-money-tips.index');
    $api->get('make-money-tips/{makeMoneyTip}', 'MakeMoneyTipController@show')->name('make-money-tips.show');

    Route::middleware(['throttle:' . config('api.rate_limits.sign')])->group(function ($api) {
        // 短信
        $api->post('message-codes', 'MessageCodeController@store')->name('message-codes.store');
    });
});

Route::fallback(function () {
    return response()->json(['message' => '404 Not Found. Please check your request url!'], 404);
});
