<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $user->update(['status'=>0]);
        // $user->delete();
        return api_response();
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

    public function upload(Request $request)
    {
        $file = $request->file('file');

        // 文件是否上传成功
        if ($file->isValid()) {
            // 原文件名
            $originalName = $file->getClientOriginalName();
            // 扩展名
            $ext = $file->getClientOriginalExtension();
            // mimeType
            $type = $file->getClientMimeType();
            // 临时绝对路径
            $realPath = $file->getRealPath();
            // 组装文件名称
            $fileName = md5(date('Y-m-d_H:i:s') . uniqid(). rand(1000, 9999)) . '.' . $ext;

            $bool = Storage::disk('public')->put($fileName, file_get_contents($realPath));

            if ($bool) {
                $data = asset("storage/$fileName");
                return api_response($data);
            }
        }
    }
}
