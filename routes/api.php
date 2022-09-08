<?php

use Api\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function PHPSTORM_META\map;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| GET（SELECT）：从服务器取出资源（一项或多项）。
| POST（CREATE）：在服务器新建一个资源。
| PUT（UPDATE）：在服务器更新资源（客户端提供改变后的完整资源）。
| PATCH（UPDATE）：在服务器更新资源（客户端提供改变的属性）。
| DELETE（DELETE）：从服务器删除资源。
|
*/

// 后台api
Route::group([
    'prefix' => 'admin/v1',
    'namespace' => 'Admin\Api'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('articles/upload', 'ArticleController@upload');
    Route::post('banners/upload', 'BannerController@upload');
    Route::post('users/upload', 'UserController@upload');
});

// 后台api,登录
Route::group([
    'prefix' => 'admin/v1',
    'namespace' => 'Admin\Api',
    'middleware' => 'auth:admin'
], function () {
    // 运行日志
    Route::get('operation_logs', 'OperationLogController@index');
    
    // 用户相关数据及操作
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::get('users/permissions', 'UserController@permissions');
    Route::get('users/status', 'UserController@status');
    Route::patch('users/verify', 'UserController@verify');
    Route::patch('users/blacklist', 'UserController@blacklist');
    Route::patch('users/destroy_selected', 'UserController@destroySelected');
    // 角色未添加的用户
    Route::get('roles/{role}/admin', 'RoleUserController@admin');

    // 菜单
    Route::apiResource('menus', 'MenuController')->except(['show']);
    // 权限
    Route::apiResource('permissions', 'PermissionController')->except(['show']);
    // 角色
    Route::apiResource('roles', 'RoleController')->except(['show']);
    // 角色权限
    Route::apiResource('roles.permissions', 'RolePermissionController')->except(['show', 'update', 'destroy']);
    // 角色用户
    Route::apiResource('roles.users', 'RoleUserController')->except(['show', 'update']);
    // 用户权限
    Route::apiResource('users.permissions', 'UserPermissionController')->except(['show', 'update', 'destroy']);

    /**
     * 控制器
     * 用户;文章;文章分类;轮播图
     */
    Route::apiResources([
        'users' => 'UserController',
        'articles' => 'ArticleController',
        'article_categories' => 'ArticleCategoryController',
        'banners' => 'BannerController',
    ]);

});




// 前台api
Route::namespace('Api')->prefix('v1')->group(function () {
    Route::get('tests', function () {
        $data = config('article');
        return api_response($data);
    });
    // 不用登录的api
    Route::get('articles', 'ArticleController@index');
    Route::get('articles/{article}', 'ArticleController@show');
    Route::get('articles/prev_article/{article}', 'ArticleController@getPrevArticle');
    Route::get('articles/next_article/{article}', 'ArticleController@getNextArticle');
    Route::get('articleCategories', 'ArticleCategoryController@index');
    Route::get('articleCategories/{articleCategory}', 'ArticleCategoryController@show');
    Route::get('banners', 'BannerController@index');
    // 需要登录的api
    Route::middleware('auth:api')->group(function () {
        Route::post('articles', 'ArticleController@store');
        Route::put('articles/{article}', 'ArticleController@update');
        Route::delete('articles/{article}', 'ArticleController@destroy');

        Route::post('articleCategories', 'ArticleCategoryController@store');
        Route::put('articleCategories/{articleCategory}', 'ArticleCategoryController@update');
        Route::delete('articleCategories/{articleCategory}', 'ArticleCategoryController@destroy');
    });
});
Route::namespace('Auth')->prefix('v1')->group(function () {
    Route::post('register', 'RegisterController@register');
    Route::post('login', 'LoginController@login');
});
Route::namespace('Auth')->prefix('v1')->middleware('auth:api')->group(function () {
    Route::post('logout', 'LoginController@logout');
});

// test
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
    // Auth::guard('api')->user(); // 登录用户实例
    // Auth::guard('api')->check(); // 如果用户通过了认证
    // Auth::guard('api')->id(); // 通过认证的用户id
    Route::apiResources([
        'test' => TestController::class,
    ]);
});
