<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Model\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bannerM = new Banner();
        $perPage = $request->input('per_page', 10);
        $keywords = $request->input('keywords');
        $list = $bannerM->when($keywords, function($query) use ($keywords) {
            $query->whereHas('article', function ($query) use ($keywords) {
                $query->where('title', 'like', "%{$keywords}%")
                ->orWhereHas('category', function ($query) use ($keywords) {
                    $query->where('name', 'like', "%{$keywords}%");
                });
            });
        })->with(['article:*', 'article.category'])->paginate($perPage);
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
        $bannerM = new Banner();
        $validator = $bannerM->validate($request->all());
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $banner = Banner::create($request->all());
        return api_response($banner);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $validator = $banner->validate($request->all());
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $result = $banner->update($request->all());
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
        $bannerM = new Banner();
        $result = $bannerM->where('id', $id)->update(['status'=>0]);
        return api_response($result);
    }

    /**
     * 上传图片
     *
     * @param Request $request
     * @return void
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        // 文件是否上传成功
        if ($file->isValid()) {
            return upload_img($file, 'banner');
        }
    }
}
