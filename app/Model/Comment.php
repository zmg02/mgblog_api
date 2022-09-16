<?php

namespace App\Model;

use Illuminate\Support\Facades\Validator;

class Comment extends BaseModel
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
    public function article()
    {
        return $this->belongsTo('App\Model\Article');
    }

    protected $messages = [
        'article_id.required' => '文章必须选择',
        'user_id.required' => '评论人必须选择',
        'content.required' => '评论内容必须填写',
        'content.between' => '评论内容在 1-255 个字符',
    ];

    protected $rules = [
        'article_id' => ['bail', 'required'],
        'user_id' => ['required'],
        'content' => ['required', 'between:1,255'],
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

        /**
     * 获取注册时间属性
     *
     * @param [type] $value
     * @return void
     */
    public function getCreateTimeAttribute()
    {
        return date('Y年m月d日 H:i', $this->attributes['create_time']);
    }
}
