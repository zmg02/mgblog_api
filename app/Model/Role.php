<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Validator;

class Role extends BaseModel
{
    protected $table = 'admin_roles';

    /**
     * 不可批量分配的属性。黑名单
     * 属性内的字段$fillable可以使用 Eloquent 的create()和update()方法批量分配。
     * 您也可以使用该$guarded属性，以允许除少数属性之外的所有属性。
     * @var array
     */
    protected $fillable = ['name', 'slug', 'status'];

    public function administrators(): BelongsToMany
    {
        $pivotTable = 'admin_role_users';
        $relatedModel = User::class;

        return $this->belongsToMany($relatedModel, $pivotTable, 'role_id', 'user_id');
    }

    public function permissions(): BelongsToMany
    {
        $pivotTable = 'admin_role_permissions';
        $relatedModel = Permission::class;

        return $this->belongsToMany($relatedModel, $pivotTable, 'role_id', 'permission_id');
    }

    protected $messages = [
        'name.required' => '名称必须填写',
        'slug.required' => '别名必须填写',
        'slug.unique' => '别名不能重复',
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
                'unique:admin_roles,slug,' . $this->id
            ],
        ], $this->messages);
    }
}
