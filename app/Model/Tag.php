<?php

namespace App\Model;

class Tag extends BaseModel
{
    protected $guarded = [
        'create_time', 'update_time'
    ];
    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'status' => 1,
    ];

    /**
     * 多对多（反向）
     * 一个标签多篇文章，一篇文章多个标签
     */
    public function article()
    {
        return $this->belongsToMany('App\Model\Article');
    }

    /**
     * 多对多（反向）
     * 一个标签多张照片，一张照片多个标签
     */
    public function instagram()
    {
        return $this->belongsToMany('App\Model\Instagram');
    }
}
