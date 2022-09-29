<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Model\Instagram;
use App\Model\InstagramTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $perPage = $request->input('per_page', 50);
        $keywords = $request->query('keywords');
        $status = $request->query('status');
        $tag = intval($request->input('tag'));
        $list = $instagramM->where('status','<>',0)
        ->when($keywords, function($query) use ($keywords) {
            $query->whereHas('user', function ($query) use ($keywords) {
                $query->where('name', 'like', "%{$keywords}%");
            })->orWhereHas('tag', function ($query) use ($keywords) {
                $query->where('name', 'like', "%{$keywords}%");
            });
        })
        ->when(($status != null), function($query) use ($status) {
            $query->where('status', '=', $status);
        })
        ->when($tag, function($query) use ($tag) {
            $query->whereHas('tag', function ($query) use ($tag) {
                $query->where('tag_id', '=', $tag);
            });
        })
        // ->select('url as src')
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
    public function store($user, $data = [], $tagIds = [])
    {
        $instagramM = new Instagram();
        $insert = [
            'url' => $data['data'],
            'user_id' => $user['id'],
            'order' => 1
        ];
        $validator = $instagramM->validate($insert);
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        // 开启事务
        DB::beginTransaction();
        if ($instagram = $instagramM->create($insert)) {
            $instagramTagM = new InstagramTag();
            foreach ($tagIds as $tagId) {
                $insertData[] = [
                    'instagram_id' => $instagram['id'],
                    'tag_id' => $tagId,
                ];
            }
            if ($instagramTagM->insert($insertData)) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }

        $data = $instagramM->with(['user:id,name,avatar', 'tag:id,name'])->find($instagram['id']);
        return api_response($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instagram $instagram)
    {
        $instagramM = new Instagram();
        $instagramTagM = new InstagramTag();
        $validator = $instagram->validate($request->all());
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $requestData = $request->all();
        $instagramInfo = [
            'user_id' => $requestData['user_id'],
            'status' => $requestData['status'],
            'order' => $requestData['order'],
        ];

        $tagIds = array_column($requestData['tag'], 'id');
        $insertData = [];
        // 开启事务
        DB::beginTransaction();
        $instagramRes = $tagRes = false;
        if ($instagramRes = $instagramM->where('id', $requestData['id'])->update($instagramInfo)) {
            foreach ($tagIds as $tagId) {
                $insertData[] = [
                    'instagram_id' => $requestData['id'],
                    'tag_id' => $tagId,
                ];
            }

            if ($instagramTagM->where(['instagram_id' => $requestData['id']])->delete()) {
                if ($tagRes = $instagramTagM->insert($insertData)) {
                    DB::commit();
                } else {
                    DB::rollBack();
                }
            }
        }
        $result['instagram'] = $instagramRes;
        $result['instagram_tag'] = $tagRes;
        if (!$instagramRes || !$tagRes) {
            return api_response($result, 4007, '文章修改失败');
        }

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
        $user = $request->user();
        $file = $request->file('file');
        $tags = $request->input('tags');
        $tags = json_decode($tags, true);
        $tagIds = array_column($tags, 'id');

        // 文件是否上传成功
        if ($file->isValid()) {
            $result = upload_img($file, 'instagram');
            $data = json_decode(json_encode($result), true);
            // 上传成功，保存数据
            if (isset($data['original']['code']) && $data['original']['code'] == 200) {
                return $this->store($user, $data['original'], $tagIds);
            }
        }
    }

    /**
     * 图片所有状态
     *
     * @return void
     */
    public function status()
    {
        return api_response(config('instagram.status'));
    }
}
