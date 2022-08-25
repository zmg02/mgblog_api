<?php

namespace App\Model;

use Illuminate\Support\Facades\Validator;

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

    protected $messages = [
        'name.required' => '分类名称必须填写',
        'name.unique' => '分类名称是唯一的',
        'name.min' => '分类名称不小于2个字符',
        'name.max' => '分类名称不大于100个字符',
    ];

    protected $rules = [
        'name' => ['bail', 'required', 'unique:article_categories', 'min:2', 'max:100']
    ];

    public function validate($data)
    {
        return Validator::make($data, $this->rules, $this->messages);
    }

    /**
     * 一对多
     * 一个分类多篇文章
     */
    public function article()
    {
        return $this->hasMany('App\Model\Article');
    }

    /**
     * 获取创建时间属性
     */
    public function getCreateTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['create_time']);
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
