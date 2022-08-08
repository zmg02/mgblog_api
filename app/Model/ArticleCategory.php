<?php

namespace App\Model;

class ArticleCategory extends BaseModel
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
     * 一对多
     * 一个分类多篇文章
     */
    public function article()
    {
        return $this->hasMany('App\Model\Article');
    }
}
