<?php

namespace App\Model;

use Illuminate\Support\Facades\Validator;

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

    protected $messages = [
        'title.required' => '标题必须填写',
        'title.between' => '标题必须在2到100个字符之间',
        'user_id.required' => '作者必须填写',
        'default_img.required' => '主图必须上传',
        'content.required' => '内容必须填写',
        'content.min' => '标题不小于10个字符',
        'category_id.required' => '分类必须选择',
        'status.required' => '状态必须选择',
    ];

    protected $rules = [
        'title' => ['bail', 'required', 'between:2,100'],
        'user_id' => ['bail', 'required'],
        'default_img' => ['bail', 'required'],
        'content' => ['bail', 'required', 'min:10'],
        'category_id' => ['bail', 'required'],
        'status' => ['bail', 'required'],
    ];
    /**
     * 表单验证
     *
     * @param [type] $data
     * @return void
     */
    public function validate($data)
    {
        return Validator::make($data, $this->rules, $this->messages);
    }
}
