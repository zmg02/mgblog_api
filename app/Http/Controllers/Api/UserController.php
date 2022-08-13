<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userM = new User();
        // $page = $request->input('page', 1);
        $pageSize = $request->input('page_size', 10);
        $where = [];
        $keywords = $request->input('keywords');
        $orWhere = [];
        if ($keywords) {
            $orWhere[] = ['name', 'like', "%$keywords%", 'OR'];
            $orWhere[] = ['phone', 'like', "%$keywords%", 'OR'];
            $orWhere[] = ['email', 'like', "%$keywords%", 'OR'];
        }
        $time = $request->input('time');
        if ($time) {
            switch ($time) {
                case 'quarterly':
                    $uTime = strtotime("-3 month");
                    break;
                default:
                    $uTime = strtotime("last $time");
            }
            $where[] = ['create_time', '>', $uTime];
        }
        $status = $request->input('status');
        // status 0
        if ($status != null) {
            $where['status'] = $status;
        }

        // $list = $userM->where($where)->where($orWhere)->toSql();
        $list = $userM->where($where)->where($orWhere)->paginate($pageSize);
        return api_response($list);
    }

    /**
     * 将新创建的资源存储到存储中。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        return api_response($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return api_response($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return api_response($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return api_response();
    }

    public function adminInfo($token)
    {
        $userM = new User();
        $info = $userM->adminUserInfoToToken($token);
        if ($info == null || empty($info->toArray())) {
            return api_response($info, 402, 'token过期');
        }
        return api_response($info);
    }
}
