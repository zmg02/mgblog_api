<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Tree;
use App\Model\Permission;
use App\Model\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    use Tree;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role)
    {
        $permissionM = new Permission();
        // 获取该角色下的权限ID
        $rolePermissionIds = $role->permissions()->select('id')->get()->toArray();
        $rpIds = array_column($rolePermissionIds, 'id');
        // slug * 的id                                                           
        $isAllId = $permissionM->where('slug', '*')->select('id')->first()->toArray();
        // 该角色是否拥有所有权限
        if (in_array($isAllId['id'], $rpIds)) {
            $roleAllPermissionIds = $permissionM->select('id')->get()->toArray();
            $rpIds = array_column($roleAllPermissionIds, 'id');
        }
        // 所有权限
        $permissions = $permissionM->get()->toArray();
        $allIds = array_column($permissions, 'id');
        // 判断权限是否删除，删除不可选择
        $delIds = $permissionM->where('status', 0)->select('id')->get()->toArray();
        $delIds = array_column($delIds, 'id');
        foreach ($permissions as $key => $item) {
            if (in_array($item['id'], $delIds)) {
                $permissions[$key]['disabled'] = true;
            }
        }

        // 树形权限
        $pTree = $this->permissionTree($permissions);
        // 返回数据整理
        $responseData = [
            'all_ids' => $allIds,
            'checked_ids' => $rpIds,
            'data' => $pTree
        ];

        return api_response($responseData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Role $role)
    {
        $permissionM = new Permission();
        $permissionIds = $request->input('permission_ids');
        $pIds = [];
        if (!empty($permissionIds)) {
            foreach ($permissionIds as $key => $id) {
                $tmp = $this->getAllParentId($permissionM, $id);
                if ($tmp) {
                    $pIds[] = $tmp;
                }
            }
        }
        // 二维数组转一维数组
        $pIdsArr = [];
        foreach ($pIds as $item) {
            array_map(function ($value) use (&$pIdsArr) {
                $pIdsArr[] = $value;
            }, $item);
        }
        // 合并
        $ids = array_merge($permissionIds, $pIdsArr);
        // 去重
        $ids = array_unique($ids);
        // 移除角色中的所有权限
        $role->permissions()->detach();
        // 添加提交过来的所有权限
        $result = $role->permissions()->attach($ids);
        return api_response($result);
    }
}
