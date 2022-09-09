<?php

namespace App\Model;

use Illuminate\Support\Facades\Validator;

class Tag extends BaseModel
{
    protected $table = 'tags';

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
        return $this->belongsToMany('App\Model\Article', 'article_tags');
    }

    /**
     * 多对多（反向）
     * 一个标签多张照片，一张照片多个标签
     */
    public function instagram()
    {
        return $this->belongsToMany('App\Model\Instagram');
    }

    protected $messages = [
        'name.required' => '标签名称必须填写',
        'name.unique' => '标签名称是唯一的',
        'name.between' => '标签名称必须在2到100个字符之间'
    ];

    /**
     * 表单验证
     *
     * @param [type] $data
     * @return void
     */
    public function validate($data)
    {
        return Validator::make($data, [
            'name' => [
                'bail',
                'required',
                'unique:tags,name,' . $this->id,
                'between:2,100'
            ]
        ], $this->messages);
    }

}
