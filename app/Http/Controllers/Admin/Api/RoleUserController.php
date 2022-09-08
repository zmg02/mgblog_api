<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role)
    {
        $users = $role->administrators()->get()->toArray();
        
        return api_response($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Role $role)
    {
        $userIds = $request->input('user_ids');
        $result = $role->administrators()->attach($userIds);
        return api_response($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role, $userId)
    {
        $result = $role->administrators()->detach($userId);

        return api_response($result);
    }

    /**
     * 角色还未添加的管理员
     *
     * @param [type] $id
     * @return void
     */
    public function admin(Request $request, Role $role)
    {
        $userM = new User();
        $perPage = $request->input('per_page', 10);
        $keywords = $request->input('keywords');
        // 获取该角色下的用户ID
        $roleUserIds = $role->administrators()->select('id')->get()->toArray();
        $uIds = array_column($roleUserIds, 'id');
        // 获取该角色未拥有的用户信息
        $userData = $userM->when($keywords, function($query) use ($keywords) {
            $query->where('name', 'like', "%{$keywords}%")
            ->orWhere('phone', 'like', "%{$keywords}%")
            ->orWhere('email', 'like', "%{$keywords}%");
        })->whereNotIn('id', $uIds)->where('is_admin', 1)->paginate($perPage);

        return api_response($userData);
    }
}
