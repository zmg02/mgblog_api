<?php

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
        var_dump($data);
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
