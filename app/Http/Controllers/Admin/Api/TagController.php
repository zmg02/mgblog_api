<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Model\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tagM = new Tag();
        // $tags = $tagM->get();
        // foreach ($tags as $tag) {
        //     $articles = $tag->article()->get();
        //     dd($articles);
        // }

        $list = $tagM->withCount('article')->get();
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
        $tagM = new Tag();
        $validator = $tagM->validate($request->all());
        
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }
        
        $result = $tagM->create($request->all());
        return api_response($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $validator = $tag->validate($request->all());
        
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $result = $tag->update($request->all());
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
        $tagM = new Tag();
        $result = $tagM->where('id', $id)->update(['status'=>0]);
        return api_response($result);
    }
}
