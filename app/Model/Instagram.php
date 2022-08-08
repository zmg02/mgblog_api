<?php

namespace App\Model;

class Instagram extends BaseModel
{
    protected $guarded = [
        'create_time', 'update_time'
    ];
    
    /**
     * 一对多（反向）
     * 一个作者多篇文章
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
    
    /**
     * 多对多
     * 一张照片多个标签，一个标签多张照片
     */
    public function tag()
    {
        return $this->belongsToMany('App\Model\Tag');
    }
}
