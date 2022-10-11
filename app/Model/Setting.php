<?php

namespace App\Model;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Setting extends BaseModel
{
    protected $table = 'admin_settings';

    // 关闭自定义存储时间戳
    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'name', 'value', 'type'
    ];

    protected $messages = [
        'name.required' => '名称必须填写',
        'name.between' => '名称必须在1到255个字符之间',
        'name.unique' => '名称不能重复',
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
                'between:1,255',
                Rule::unique('admin_settings')->ignore($this->name)
                // Rule::unique('admin_settings')->ignore(request('name'))->where(function ($query) {
                //     $query->whereNotIn('status', [4]);
                // })
            ],
        ], $this->messages);
    }
}
