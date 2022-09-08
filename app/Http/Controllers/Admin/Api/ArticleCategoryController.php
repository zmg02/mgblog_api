<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Model\ArticleCategory;
use Illuminate\Http\Request;

class ArticleCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articleCategoryM = new ArticleCategory();
        $list = $articleCategoryM->get();
        return api_response($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $articleCategoryM = new ArticleCategory();
        $validator = $articleCategoryM->validate($request->all());
        
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }
        
        $result = $articleCategoryM->create($request->all());
        return api_response($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ArticleCategory $articleCategory)
    {
        return api_response($articleCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ArticleCategory $articleCategory)
    {
        $validator = $articleCategory->validate($request->all());
        
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $result = $articleCategory->update($request->all());
        return api_response($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $articleCategoryM = new ArticleCategory();
        $result = $articleCategoryM->where('id', $id)->update(['status'=>0]);
        return api_response($result);
    }
}
