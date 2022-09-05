<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $bannerM = new Banner();
        $list = $bannerM->where('status', 1)->orderBy('order', 'desc')->get();
        return api_response($list);
    }
}
