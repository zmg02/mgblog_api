<?php

namespace App\Model;

use Illuminate\Support\Facades\Validator;

class Banner extends BaseModel
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
        'url.required' => '图片必须上传',
        'status.required' => '状态必须选择',
    ];

    protected $rules = [
        'url' => ['bail', 'required'],
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
