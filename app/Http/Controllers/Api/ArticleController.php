<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articleM = new Article();
        $page = $request->input('page', 1);
        $pageSize = $request->input('page_size', 10);

        $list = $articleM->getPageList($page, $pageSize);
        return api_response($list);
        // return Article::all();
    }

    public function show(Article $article)
    {
        return api_response($article);
    }

    public function store(Request $request)
    {
        $article = Article::create($request->all());

        return api_response($article);
        // return response()->json($article, 201);
    }

    public function update(Request $request, Article $article)
    {
        $article->update($request->all());

        return api_response($article);
        // return response()->json($article, 200);
    }

    /**
     * 从存储中移除指定的资源。
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return api_response();
        // return response()->json(null, 204);
    }
}
