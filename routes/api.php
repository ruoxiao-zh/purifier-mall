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
//    Route::get('version', function() {
//        // abort(403, 'test');
//
//        return 'this is version v1';
//    })->name('version');

    // 轮播图列表
    $api->get('slideshows', 'SlideshowController@index')->name('slideshows.index');
    // 公司详情
    $api->get('companies/{company}', 'CompanyController@show')->name('companies.index');
    // 省钱小技巧
    $api->get('make-money-tips', 'MakeMoneyTipController@index')->name('make-money-tips.index');
    $api->get('make-money-tips/{makeMoneyTip}', 'MakeMoneyTipController@show')->name('make-money-tips.show');

    // 短信
    $api->post('message-codes', 'MessageCodeController@store')->name('message-codes.store');
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found. Please check your request url!'], 404);
});
