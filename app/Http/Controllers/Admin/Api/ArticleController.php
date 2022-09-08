<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Model\Article;
use App\Model\ArticleCategory;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articleM = new Article();
        $pageSize = $request->input('page_size', 10);
        $keywords = $request->query('keywords');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');

        $list = $articleM->when($keywords, function($query) use ($keywords) {
            $query->where('title', 'like', "%{$keywords}%")
            ->orWhere('content', 'like', "%{$keywords}%")
            ->orWhereHas('user', function ($query) use ($keywords) {
                $query->where('name', 'like', "%{$keywords}%");
            });
        })
        ->when($categoryId, function($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->when(($status != null), function($query) use ($status) {
            $query->where('status', $status);
        })
        ->with(['user:id,name,avatar'])
        ->with(['category:id,name'])
        // ->orderBy('order', 'desc')
        ->orderBy('id', 'desc')
        // ->toSql();
        ->paginate($pageSize);
       
        // $list = $articleM->where($where)->whereHas()->paginate($pageSize);
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
        $articleM = new Article();
        $categoryId = $request->input('category_id');
        $validator = $articleM->validate($request->all());
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $result['article'] = Article::create($request->all());
        $result['article_category'] = ArticleCategory::where('id', $categoryId)->increment('count', 1);
        return api_response($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return api_response($article->where('id', $article->id)->with(['user:id,name,avatar'])->with(['category:id,name'])->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $validator = $article->validate($request->all());
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $result = $article->update($request->all());
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
        $articleM = new Article();
        $result = $articleM->where('id', $id)->update(['status'=>0]);
        return api_response($result);
    }
    /**
     * 上传主图
     *
     * @param Request $request
     * @return void
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        // 文件是否上传成功
        if ($file->isValid()) {
            return upload_img($file, 'article');
        }
    }
}
