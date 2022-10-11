<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Model\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settingM = new Setting();

        $list = $settingM->get();
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
        $settingM = new Setting();
        $validator = $settingM->validate($request->all());
        
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }
        
        $result = $settingM->create($request->all());
        return api_response($result);
    }

    /**
     * 批量更新系统设置
     *
     * @param Request $request
     * @return void
     */
    public function form(Request $request)
    {
        $data = $request->all();
        $result = app(Setting::class)->updateBatch($data);
        
        if (!$result) {
            return api_response($result, 4008, '批量更新失败');
        }
        return api_response($result);
    }

    /**
     * 后台图片上传
     *
     * @param Request $request
     * @return void
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');

        // 文件是否上传成功
        if ($file->isValid()) {
            return upload_img($file, 'admin');
        }
    }

    public function getSetting(Request $request)
    {
        $settingM = new Setting();
        $name = $request->input('name');
        $info = $settingM->where('name', $name)->first();
        return api_response($info);
    }
}
