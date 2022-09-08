<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Validator;

class Permission extends BaseModel
{
    protected $table = 'admin_permissions';

    /**
     * 不可批量分配的属性。黑名单
     * 属性内的字段$fillable可以使用 Eloquent 的create()和update()方法批量分配。
     * 您也可以使用该$guarded属性，以允许除少数属性之外的所有属性。
     * @var array
     */
    protected $fillable = ['parent_id', 'name', 'slug', 'http_method', 'http_path', 'status'];

    public function roles(): BelongsToMany
    {
        $pivotTable = 'admin_role_permissions';
        $relatedModel = Role::class;

        return $this->belongsToMany($relatedModel, $pivotTable, 'permission_id', 'role_id');
    }

    public function getAllList()
    {
        return $this->where('status', 1)->get();
    }

    protected $messages = [
        'name.required' => '名称必须填写',
        'slug.required' => '别名必须填写',
        'slug.unique' => '别名不能重复',
        'http_path.required' => '路径必须填写',
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
            'name' => ['bail', 'required'],
            'slug' => [
                'required',
                'unique:admin_permissions,slug,' . $this->id,
            ],
            'http_path' => ['required'],
        ], $this->messages);
    }
}
