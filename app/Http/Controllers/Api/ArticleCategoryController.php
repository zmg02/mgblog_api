<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\ArticleCategory;
use Illuminate\Http\Request;

class ArticleCategoryController extends Controller
{
    /**
     * 显示资源的列表。
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ArticleCategory::all();
    }


    /**
     * 将新创建的资源存储到存储中。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $articleCategory = ArticleCategory::create($request->all());

        return response()->json($articleCategory, 201);
    }

    /**
     * 显示指定的资源。
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($articleCategory)
    {
        return $articleCategory;
    }

    /**
     * 更新存储中的指定资源。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $articleCategory)
    {
        $articleCategory->update($request->all());

        return response()->json($articleCategory, 200);
    }

    /**
     * 从存储中移除指定的资源。
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($articleCategory)
    {
        $articleCategory->delete();

        return response()->json(null, 204);
    }
}
