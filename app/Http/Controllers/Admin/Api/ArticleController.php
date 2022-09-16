<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Model\Article;
use App\Model\ArticleCategory;
use App\Model\ArticleTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $categoryId = intval($request->query('category_id'));
        $status = $request->query('status');
        $tag = intval($request->input('tag'));
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
        ->when($tag, function($query) use ($tag) {
            $query->whereHas('tag', function ($query) use ($tag) {
                $query->where('tag_id', '=', $tag);
            });
        })
        ->with(['user:id,name,avatar', 'category:id,name'])
        // ->with(['category:id,name'])
        ->with(['tag:id,name'])
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

        $requestData = $request->all();
        $articleInfo = [
            'title' => $requestData['title'],
            'user_id' => $requestData['user_id'],
            'default_img' => $requestData['default_img'],
            'content' => $requestData['content'],
            'category_id' => $requestData['category_id'],
            'status' => $requestData['status'],
            'order' => $requestData['order'],
        ];
        $tagIds = array_column($requestData['tag'], 'id');
        $insertData = [];
        // 开启事务
        DB::beginTransaction();
        if ($article = $articleM->create($articleInfo)) {
            $articleTagM = new ArticleTag();
            foreach ($tagIds as $tagId) {
                $insertData[] = [
                    'article_id' => $article['id'],
                    'tag_id' => $tagId,
                ];
            }

            if ($res = $articleTagM->insert($insertData)) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }
        $result['article'] = $res;
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
        $articleM = new Article();
        $articleCategoryM = new ArticleCategory();
        $articleTagM = new ArticleTag();
        $categoryId = $request->input('category_id');
        $validator = $article->validate($request->all());
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $requestData = $request->all();
        $articleInfo = [
            'title' => $requestData['title'],
            'default_img' => $requestData['default_img'],
            'content' => $requestData['content'],
            'category_id' => $requestData['category_id'],
            'status' => $requestData['status'],
            'order' => $requestData['order'],
            'praise_count' => $requestData['praise_count'],
        ];

        // dd($article['category_id'], $categoryId);
        $tagIds = array_column($requestData['tag'], 'id');
        $insertData = [];
        // 开启事务
        DB::beginTransaction();
        $articleRes = $categoryRes = $tagRes = false;
        if ($articleRes = $articleM->where('id', $requestData['id'])->update($articleInfo)) {
            if ($article['category_id'] != $categoryId) {
                $categoryRes['old'] = $articleCategoryM->where('id', $article['category_id'])->decrement('count');
                $categoryRes['new'] = $articleCategoryM->where('id', $categoryId)->increment('count');
            }
            foreach ($tagIds as $tagId) {
                $insertData[] = [
                    'article_id' => $requestData['id'],
                    'tag_id' => $tagId,
                ];
            }

            if ($articleTagM->where(['article_id' => $requestData['id']])->delete()) {
                if ($tagRes = $articleTagM->insert($insertData)) {
                    DB::commit();
                } else {
                    DB::rollBack();
                }
            }
        }
        $result['article'] = $articleRes;
        $result['article_category'] = $categoryRes;
        $result['article_tag'] = $tagRes;
        if (!$articleRes || !$tagRes) {
            return api_response($result, 4007, '文章修改失败');
        }

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

    public function banner()
    {
        $articleM = new Article();
        $list = $articleM->orderBy('order', 'desc')->take(30)->get()->toArray();
        
        return api_response($list);
    }
}
