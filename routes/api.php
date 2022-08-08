<?php

use Api\TestController;
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
// Auth::guard('api')->user(); // 登录用户实例
// Auth::guard('api')->check(); // 如果用户通过了认证
// Auth::guard('api')->id(); // 通过认证的用户id
});

// 需要登录的api
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('articles', 'Api\ArticleController@store');
    Route::put('articles/{article}', 'Api\ArticleController@update');
    Route::delete('articles/{article}', 'Api\ArticleController@delete');
    Route::post('articleCategories', 'Api\ArticleCategoryController@store');
    Route::put('articleCategories/{articleCategory}', 'Api\ArticleCategoryController@update');
    Route::delete('articleCategories/{articleCategory}', 'Api\ArticleCategoryController@delete');

    Route::post('logout', 'Auth\LoginController@logout');

    Route::apiResources([
        'test' => TestController::class,
    ]);
});
// 不需要登录的api
Route::get('articles', 'Api\ArticleController@index');
Route::get('articles/{article}', 'Api\ArticleController@show');

Route::get('articleCategories', 'Api\ArticleCategoryController@index');
Route::get('articleCategories/{articleCategory}', 'Api\ArticleCategoryController@show');

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');

// 测试路由
// Route::apiResources([
//     'test' => TestController::class,
// ]);