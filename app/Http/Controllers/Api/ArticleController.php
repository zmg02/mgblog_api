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
        $pageSize = $request->input('page_size', 20);
        $categoryName = '私密';

        $list = $articleM->where('status', 1)
            ->whereHas('category', function ($query) use ($categoryName) {
                $query->where('name', '<>', "$categoryName");
            })
            ->with(['user:id,name,avatar'])
            ->with(['category:id,name'])
            ->select('*', 'default_img as src')->paginate($pageSize);
        return api_response($list);
    }

    public function show($id)
    {
        $articleM = new Article();
        $info = $articleM->with(['user:*'])->with(['category:id,name'])->find($id)->toArray();
        return api_response($info);
    }

    public function getPrevArticle($id)
    {
        $articleM = new Article();
        $preId = $articleM->where('id', '<', $id)->max('id');
        if (!$preId) {
            $preId = $articleM->max('id');
        }
        $info = $articleM->with(['user:*'])->with(['category:id,name'])->find($preId);
        return api_response($info);
    }
    public function getNextArticle($id)
    {
        $articleM = new Article();
        $nextId = $articleM->where('id', '>', $id)->min('id');
        if (!$nextId) {
            $nextId = 1;
        }
        $info = $articleM->with(['user:*'])->with(['category:id,name'])->find($nextId);
        return api_response($info);
    }

    public function getNewArticle()
    {
        $articleM = new Article();
        $info = $articleM
        ->with(['user:*'])->with(['category:id,name'])
        ->orderBy('create_time', 'desc')
        ->orderBy('order', 'desc')
        ->take(4)
        ->get();
        return api_response($info);
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
    }

    /**
     * 最新帖子
     */
    public function last()
    {
        $articleM = new Article();

        $list = $articleM->where('status', 1)->orderBy('create_time', 'desc')->with(['user:id,name,avatar'])
        ->with(['category:id,name'])->select('*', 'default_img as src')->take(5)->get();

        return api_response($list);
    }

    /**
     * 最新帖子
     */
    public function hottest()
    {
        $articleM = new Article();

        $list = $articleM->where('status', 1)->orderBy('praise_count', 'desc')->with(['user:id,name,avatar'])
        ->with(['category:id,name'])->select('*', 'default_img as src')->take(5)->get();

        return api_response($list);
    }
}
