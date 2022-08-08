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

    // 重写分页
    public function getPageList($page, $pageSize)
    {
        return $this->paginate($pageSize, ['*'], $page, 'page');
    }

    /**
     * 一对多
     * 一个作者多篇文章
     */
    public function article()
    {
        return $this->hasMany('App\Model\Article');
    }
}
