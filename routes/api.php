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
    Route::apiResources([
        'test' => TestController::class,
    ]);
});

// 需要登录的api
Route::namespace('Api')->prefix('v1')->middleware('auth:api')->group(function () {
    Route::post('articles', 'ArticleController@store');
    Route::put('articles/{article}', 'ArticleController@update');
    Route::delete('articles/{article}', 'ArticleController@destroy');

    Route::post('articleCategories', 'ArticleCategoryController@store');
    Route::put('articleCategories/{articleCategory}', 'ArticleCategoryController@update');
    Route::delete('articleCategories/{articleCategory}', 'ArticleCategoryController@destroy');

    Route::post('users', 'UserController@store');
    Route::put('users/{user}', 'UserController@update');
    Route::delete('users/{user}', 'UserController@destroy');
    Route::get('users/info/{token}', 'UserController@adminInfo');
    Route::get('users/status', function() {
        return api_response(config('user.status'));
    });
});
// 不需要登录的api
Route::namespace('Api')->prefix('v1')->group(function () {
    Route::get('articles', 'ArticleController@index');
    Route::get('articles/{article}', 'ArticleController@show');

    Route::get('articleCategories', 'ArticleCategoryController@index');
    Route::get('articleCategories/{articleCategory}', 'ArticleCategoryController@show');

    Route::get('users', 'UserController@index');
    Route::get('users/{user}', 'UserController@show');
});
Route::namespace('Auth')->prefix('v1')->group(function () {
    Route::post('register', 'RegisterController@register');
    Route::post('login', 'LoginController@login');
});

Route::namespace('Auth')->prefix('v1')->middleware('auth:api')->group(function () {
    Route::post('logout', 'LoginController@logout');
});
