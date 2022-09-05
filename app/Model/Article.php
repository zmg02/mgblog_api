<?php

namespace App\Model;

class Article extends BaseModel
{
    /**
     * 不可批量分配的属性。黑名单
     * 属性内的字段$fillable可以使用 Eloquent 的create()和update()方法批量分配。
     * 您也可以使用该$guarded属性，以允许除少数属性之外的所有属性。
     * @var array
     */
    protected $guarded = [
        'create_time', 'update_time'
    ];
    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'comment_count' => 0,
        'praise_count' => 0,
    ];

    /**
     * 一对多（反向）
     * 一个作者多篇文章
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }

    /**
     * 一对多（反向）
     * 一个文章分类多篇文章
     */
    public function category()
    {
        return $this->belongsTo('App\Model\ArticleCategory');
    }

    /**
     * 多对多
     * 一篇文章多个标签，一个标签多个文章
     */
    public function tag()
    {
        return $this->belongsToMany('App\Model\Tag');
    }

    /**
     * 获取创建时间属性
     */
    public function getCreateTimeAttribute()
    {
        return date('Y 年 m 月 d 日', $this->attributes['create_time']);
    }
    /**
     * 设置创建时间属性
     */
    public function setCreateTimeAttribute($value)
    {
        $this->attributes['create_time'] = is_int($value) ? $value : strtotime($value);
    }
    /**
     * 获取修改时间属性
     */
    public function getUpdateTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['update_time']);
    }
    /**
     * 设置修改时间属性
     */
    public function setUpdateTimeAttribute($value)
    {
        $this->attributes['update_time'] = is_int($value) ? $value : strtotime($value);
    }
}
