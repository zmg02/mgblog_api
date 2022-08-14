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
        ->when($status, function($query) use ($status) {
            $query->where('status', $status);
        })
        ->with(['user:id,name'])
        ->with(['category:id,name'])
        ->orderBy('order', 'desc')
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
        $categoryId = $request->input('category_id');
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
        // _print($article->id);
        return api_response($article->where('id', $article->id)->with(['user:id,name'])->with(['category:id,name'])->first());
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
        $article->update($request->all());
        return api_response($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->update(['status'=>0]);
        return api_response($article);
    }
}
