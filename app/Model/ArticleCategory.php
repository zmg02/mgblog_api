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
        'name.between' => '分类名称必须在2到100个字符之间'
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
                'unique:article_categories,' . $this->id,
                'between:2,100'
            ]
        ], $this->messages);
    }

    /**
     * 一对多
     * 一个分类多篇文章
     */
    public function article()
    {
        return $this->hasMany('App\Model\Article');
    }
}
