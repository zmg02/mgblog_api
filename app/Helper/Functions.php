<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('str_random')) {
    function str_random($length)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $len = strlen($str) - 1;
        $randstr = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $randstr .= $str[$num];
        }
        return $randstr;
    }
}

if (!function_exists('_print')) {
    function _print($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit('打印完成');
    }
}

if (!function_exists('api_response')) {
    function api_response($data = null, $code = 200, $message = 'success')
    {
        return response(['code' => $code, 'message' => $message, 'data' => $data], 200);
    }
}

if (!function_exists('upload_img')) {
    function upload_img($file, $dir)
    {
        // 原文件名
        $originalName = $file->getClientOriginalName();
        // 扩展名
        $ext = $file->getClientOriginalExtension();
        // mimeType
        $type = $file->getClientMimeType();
        // 临时绝对路径
        $realPath = $file->getRealPath();
        // 组装文件名称
        $fileName = $dir . '/' . md5(date('Y-m-d_H:i:s') . uniqid(). rand(1000, 9999)) . '.' . $ext;

        $bool = Storage::disk('public')->put($fileName, file_get_contents($realPath));

        if ($bool) {
            $data = asset("storage/$fileName");
            return api_response($data);
        }
    }
}