<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Tree;
use App\Model\Article;
use App\Model\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    use Tree;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $commentM = new Comment();

        $list = $commentM->where('status', 1)->where('article_id', $id)
                    ->with(['user:id,name,avatar'])
                    ->orderBy('order', 'desc')
                    ->orderBy('create_time', 'desc')
                    ->get()->toArray();
        $tree = $this->commentTree($list);
        
        return api_response($tree);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $commentM = new Comment();
        $articleM = new Article();
        $validator = $commentM->validate($request->all());
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        // 开启事务
        DB::beginTransaction();
        if ($comment = $commentM->create($request->all())) {
            if ($article = $articleM->where('id', $id)->increment('comment_count', 1)) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }
        $result['comment'] = $comment;
        $result['article'] = $article;
        return api_response($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commentM = new Comment();

        $childrenIds = $this->getAllChildrenId($commentM, $id, [], 'pid');

        array_push($childrenIds, intval($id));

        $list = $commentM->where('status', 1)->whereIn('id', $childrenIds)
                    ->with(['user:id,name,avatar'])
                    ->get()->toArray();
                    
        $tree = $this->commentTree($list);
        
        return api_response($tree);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $validator = $comment->validate($request->all());
        
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $result = $comment->update($request->all());
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
        $commentM = new Comment();
        $result = $commentM->where('id', $id)->update(['status'=>0]);
        return api_response($result);
    }
}
