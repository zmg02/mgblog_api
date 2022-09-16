<?php

namespace App\Model;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Menu extends BaseModel
{
    protected $table = 'admin_menu';

    /**
     * 不可批量分配的属性。黑名单
     * 属性内的字段$fillable可以使用 Eloquent 的create()和update()方法批量分配。
     * 您也可以使用该$guarded属性，以允许除少数属性之外的所有属性。
     * @var array
     */
    protected $fillable = [
        'parent_id', 'path', 'slug', 'component', 'title', 'icon', 'uri', 'order', 'status', 'hidden'
    ];

    protected $messages = [
        'path.required' => '路径必须填写',
        'slug.required' => '别名必须填写',
        'slug.unique' => '别名不能重复',
        'component.required' => '组件名必须填写',
        'title.required' => '标题必须填写',
        'uri.required' => 'uri必须填写',
    ];

    /**
     * 表单验证
     *
     * @param [type] $data
     * @return void
     */
    public function validate($data)
    {
        return Validator::make(
            $data,
            [
                'path' => ['bail', 'required'],
                'slug' => [
                    'required',
                    'unique:admin_menu,slug,' . $this->id,
                ],
                'component' => ['required'],
                'title' => ['required'],
                'uri' => ['required'],
            ],
            $this->messages
        );
    }
}
