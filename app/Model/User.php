<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
     *
     * @var array
     */
    protected $guarded = [
        'email_verified_time', 'last_login_time', 'create_time', 'update_time'
    ];
    /**
     * 数组中应该隐藏的属性。
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }
}
