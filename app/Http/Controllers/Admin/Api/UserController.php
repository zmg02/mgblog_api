<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Tree;
use App\Model\Permission;
use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use Tree;
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
        $where = $orWhere = [];
        $keywords = $request->input('keywords');
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
        $isAuthor = $request->input('is_author');
        if ($isAuthor) {
            $where['is_author'] = $isAuthor;
        }
        $isAdmin = $request->input('is_admin');
        if ($isAdmin) {
            $where['is_admin'] = $isAdmin;
        }
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
        $userM = new User();
        $rule = [
            'name' => ['bail', 'required'],
            // 'avatar' => ['required'],
            'email' => [
                'required',
                'unique:users',
                'email:filter'
            ],
            'password' => [
                'required',
                'between:3,20'
            ]
        ];
        $validator = $userM->validate($request->all(), $rule);
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $data = $request->all();
        $result = $userM->create([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => Hash::make($data['password']),
            "avatar" => $data['avatar'],
            "desc" => $data['desc'],
            "phone" => $data['phone'],
            "is_admin" => $data['is_admin'],
            "is_author" => $data['is_author'],
            "status" => $data['status'],
        ]);
        return api_response($result);
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
        $rule = [
            'name' => ['bail', 'required'],
            'email' => [
                'required',
                'unique:users,email,' . $user->id,
                'email:filter'
            ],
            // 'avatar' => ['required']
        ];
        $validator = $user->validate($request->all(), $rule);
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }
        $result = $user->update($request->all());
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
        $userM = new User();
        $result = $userM->where('id', $id)->update(['status'=>0]);
        return api_response($result);
    }

    /**
     * 用户所有状态
     *
     * @return void
     */
    public function status()
    {
        return api_response(config('user.status'));
    }

    /**
     * 批量操作
     *
     * @param [type] $request
     * @param [type] $name
     * @return void
     */
    protected function operation($request, $name)
    {
        $userM = new User();
        $data = $request->input('data');
        $userIds = array_column($data, 'id');
        if (empty($userIds)) return api_response(null, 4004, '请求参数-用户ID，未找到');

        $result = $userM->$name($userIds);

        if ($result > 0) {
            return api_response($result);
        } else {
            return api_response($result, 4005, '未修改内容');
        }
    }
    /**
     * 批量验证用户
     *
     * @param Request $request
     * @return void
     */
    public function verify(Request $request)
    {
        return $this->operation($request, 'verify');
    }
    /**
     * 批量拉黑用户
     *
     * @param Request $request
     * @return void
     */
    public function blacklist(Request $request)
    {
        return $this->operation($request, 'blacklist');
    }
    /**
     * 批量删除用户
     *
     * @param Request $request
     * @return void
     */
    public function destroySelected(Request $request)
    {
        return $this->operation($request, 'destroySelected');
    }
    /**
     * 用户头像上传
     *
     * @param Request $request
     * @return void
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');

        // 文件是否上传成功
        if ($file->isValid()) {
            return upload_img($file, 'user');
        }
    }

    /**
     * 后台用户权限
     *
     * @param Request $request
     * @return void
     */
    public function permissions(Request $request)
    {
        $userM = new User();
        $user = $request->user();
        // 用户角色
        $rolesData = $user->roles()->where('status', 1)->get();
        if (!empty($rolesData)) {
            // 角色权限
            $rolePermissionsData = [];
            foreach ($rolesData as $role) {
                $rolePermissionsData[] = $role->permissions()->where('status', 1)->get()->toArray();
            }
        }
        // 用户权限
        $userPermissionsData = $user->permissions()->where('status', 1)->get()->toArray();
        // 用户权限整合
        $permissions = $userM->mergePermissions($rolePermissionsData, $userPermissionsData);
        // 树形权限
        $pTree = $this->permissionTree($permissions);

        return api_response($pTree);
    }
    
}
