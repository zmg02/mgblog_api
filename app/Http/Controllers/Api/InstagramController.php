<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Instagram;
use Illuminate\Http\Request;

class InstagramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $instagramM = new Instagram();
        $perPage = $request->input('per_page', 9);
        $keywords = $request->query('keywords');
        $tag = intval($request->input('tag'));
        $list = $instagramM->where('status', '=', 1)
        ->when($keywords, function($query) use ($keywords) {
            $query->whereHas('user', function ($query) use ($keywords) {
                $query->where('name', 'like', "%{$keywords}%");
            })->orWhereHas('tag', function ($query) use ($keywords) {
                $query->where('name', 'like', "%{$keywords}%");
            });
        })
        ->when($tag, function($query) use ($tag) {
            $query->whereHas('tag', function ($query) use ($tag) {
                $query->where('tag_id', '=', $tag);
            });
        })
        ->with(['user:id,name,avatar', 'tag:id,name'])
        ->orderBy('create_time', 'desc')
        ->orderBy('order', 'desc')
        ->paginate($perPage);

        $srcs = [];
        foreach ($list as $value) {
            // $img = imagecreatefromjpeg($value['url']);
            // $value['width'] = imagesx($img);
            // $value['height'] = imagesy($img);
            // unset($value);
            $srcs[] = $value['url'];
        }
        
        $result['list'] = $list;
        $result['srcList'] = $srcs;

        return api_response($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
