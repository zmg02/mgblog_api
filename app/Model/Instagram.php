<?php

namespace App\Model;

use Illuminate\Support\Facades\Validator;

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
        return $this->belongsToMany('App\Model\Tag', 'instagram_tags');
    }

    protected $messages = [
        'url.required' => '图片必须上传成功',
        'user_id.required' => '作者未获取到',
    ];

    protected $rules = [
        'url' => ['bail', 'required'],
        'user_id' => ['required'],
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
