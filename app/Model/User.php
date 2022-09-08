<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\JWTSubject;

// class User extends BaseModel
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    /**
     * 获取将存储在JWT主题声明中的标识符。
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 返回一个键值数组，其中包含要添加到JWT的任何自定义声明。
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 指示是否自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * 模型日期列的存储格式。
     *
     * @var string
     */
    protected $dateFormat = 'U';

    // 自定义存储时间戳的字段名
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    /**
     * 不可批量分配的属性。黑名单
     * 'email_verified_time', 
     * @var array
     */
    // protected $guarded = [];
    protected $fillable = [
        'name', 'avatar', 'desc', 'phone', 'email', 'password', 'status', 'is_admin', 'is_author'
    ];

    /**
     * 数组中应该隐藏的属性。
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];
    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'is_admin' => 0,
    ];
    /**
     * 生成token并保存
     *
     * @return void
     */
    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }

    /**
     * 一对多
     * 一个作者多篇文章
     */
    public function article()
    {
        return $this->hasMany('App\Model\Article');
    }
    /**
     * 用户角色
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        $pivotTable = 'admin_role_users';
        $relateModel = Role::class;

        return $this->belongsToMany($relateModel, $pivotTable, 'user_id', 'role_id');
    }
    /**
     * 用户权限
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        $pivotTable = 'admin_user_permissions';
        $relateModel = Permission::class;

        return $this->belongsToMany($relateModel, $pivotTable, 'user_id', 'permission_id');
    }

    /**
     * 用户权限整合，角色权限与用户权限拼接
     *
     * @param [type] $rolePermission 角色权限,三维数组
     * @param [type] $userPermission 用户权限,二维数组
     * @return void
     */
    public function mergePermissions($rolePermission = [], $userPermission = [])
    {
        $permissionM = new Permission();
        // 三维数组处理
        $result = $newRolePermission = $tmp = $tmp2 = [];
        foreach ($rolePermission as $item) {
            foreach ($item as $permission) {
                // slug为* 返回slug不为*的所有权限
                if ($permission['slug'] == '*') {
                    $map = [
                        ['slug', '<>', '*'],
                        ['status', '=', 1]
                    ];
                    $result = $permissionM->where($map)->get()->toArray();
                    return $result;
                }
                unset($permission['pivot']);
                $hash = md5(json_encode($permission));
                if (!in_array($hash, $tmp)) {
                    $tmp[] = $hash;
                    $newRolePermission[] = $permission;
                }
            }
        }

        foreach ($userPermission as $key => $item) {
            unset($userPermission[$key]['pivot']);
        }
        $mergePermission = array_merge($newRolePermission, $userPermission);

        foreach ($mergePermission as $val) {
            $hash1 = md5(json_encode($val));
            if (!in_array($hash1, $tmp2)) {
                $tmp2[] = $hash1;
                $result[] = $val;
            }
        }

        return $result;
    }

    /**
     * 设置最后一次登录时间属性
     *
     * @param [type] $value
     * @return void
     */
    public function setLastLoginTimeAttribute($value)
    {
        $this->attributes['last_login_time'] = is_int($value) ? $value : strtotime($value);
    }
    /**
     * 获取最后一次登录时间属性
     *
     * @param [type] $value
     * @return void
     */
    public function getLastLoginTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['last_login_time']);
    }
    /**
     * 设置邮箱验证时间属性
     *
     * @param [type] $value
     * @return void
     */
    public function setEmailVerifiedTimeAttribute($value)
    {
        $this->attributes['email_verified_time'] = is_int($value) ? $value : strtotime($value);
    }
    /**
     * 获取邮箱验证时间属性
     *
     * @param [type] $value
     * @return void
     */
    public function getEmailVerifiedTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['email_verified_time']);
    }
    /**
     * 设置注册时间属性
     *
     * @param [type] $value
     * @return void
     */
    public function setCreateTimeAttribute($value)
    {
        $this->attributes['create_time'] = is_int($value) ? $value : strtotime($value);
    }
    /**
     * 获取注册时间属性
     *
     * @param [type] $value
     * @return void
     */
    public function getCreateTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['create_time']);
    }
    /**
     * 设置更新时间属性
     *
     * @param [type] $value
     * @return void
     */
    public function setUpdateTimeAttribute($value)
    {
        $this->attributes['update_time'] = is_int($value) ? $value : strtotime($value);
    }
    /**
     * 获取更新时间属性
     *
     * @param [type] $value
     * @return void
     */
    public function getUpdateTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['update_time']);
    }

    public function verify($userIds)
    {
        return $this->whereIn('id', $userIds)->update(['email_verified_time' => time()]);
    }

    public function blacklist($userIds)
    {
        return $this->whereIn('id', $userIds)->update(['status' => 2]);
    }

    public function destroySelected($userIds)
    {
        return $this->whereIn('id', $userIds)->update(['status' => 0]);
    }

    protected $messages = [
        'name.required' => '名称必须填写',
        // 'avatar.required' => '头像必须上传',
        'email.required' => '邮箱必须填写',
        'email.unique' => '邮箱已注册',
        'email.email' => '邮箱格式错误',
        'password.required' => '密码必须填写',
        'password.between' => '密码必须在3到20个字符之间'
    ];

    /**
     * rfc: RFCValidation
     * strict: NoRFCWarningsValidation
     * dns: DNSCheckValidation
     * spoof: SpoofCheckValidation
     * filter: FilterEmailValidation
     * 当下版本 filter 验证规则使用 PHP 的 filter_var 方法进行验证，
     * 在 5.8 版本接入 Laravel 。 dns 和 spoof 验证器需要 PHP 的 intl 扩展。
     * @var array
     */
    /**
     * 表单验证
     *
     * @param [type] $data
     * @return void
     */
    public function validate($data, $rule)
    {
        return Validator::make($data, $rule, $this->messages);
    }
}
