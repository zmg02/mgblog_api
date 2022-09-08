<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Model\OperationLog;
use Illuminate\Http\Request;

class OperationLogController extends Controller
{
    public function index(Request $request)
    {
        $oprationLogM = new OperationLog();
        $perPage = $request->input('per_page', 10);
        $keywords = $request->input('keywords');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $dateTime = [];
        if ($startTime && $endTime) {
            $dateTime = [
                $request->input('start_time'),
                $request->input('end_time')
            ];
        }
        $list = $oprationLogM->when($keywords, function($query) use ($keywords) {
            $query->where('input', 'like', "%{$keywords}%")
            ->orWhereHas('user', function($query) use ($keywords) {
                $query->where('name', 'like', "%{$keywords}%");
            });
        })
        ->when($dateTime, function($query) use ($dateTime) {
            $query->whereBetween('create_time', $dateTime);
        })
        ->with(['user:id,name,avatar'])
        ->orderBy('id', 'desc')
        // ->toSql();
        ->paginate($perPage);

        return api_response($list);
    }
}
