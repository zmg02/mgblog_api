<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Pagination\Paginator;

// class User extends BaseModel
class User extends Authenticatable
{
    use Notifiable;
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
    protected $guarded = [
        'last_login_time', 'create_time', 'update_time'
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
     * 管理员信息
     *
     * @param [type] $token
     * @return void
     */
    public function adminUserInfoToToken($token)
    {
        return $this->where(['api_token' => $token, 'is_admin' => 1])->first();
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
}
