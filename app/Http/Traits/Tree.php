<?php

namespace App\Http\Traits;

trait Tree
{
    /**
     * 生成树形菜单加整理数据
     *
     * @param [array] $menus
     * @param integer $pid
     * @return array
     */
    public function menuTree($menus, $pid = 0)
    {
        $resultMenu = [];
        foreach ($menus as $value) {
            if ($value['parent_id'] == $pid) {
                $tmp = $value;
                $tmp['name'] = $value['slug'];
                $tmp['meta']['title'] = $value['title'];
                $tmp['meta']['icon'] = $value['icon'];
                $tmp['meta']['uri'] = $value['uri'];
                $tmp['children'] = $this->menuTree($menus, $value['id']);
                if (empty($tmp['children'])) unset($tmp['children']);
                $resultMenu[] = $tmp;
            }
        }
        return $resultMenu;
    }
    /**
     * 生成树形权限
     *
     * @param [type] $permissions
     * @param integer $pid
     * @return void
     */
    public function permissionTree($permissions, $pid = 0)
    {
        $result = [];
        foreach ($permissions as $value) {
            if ($value['parent_id'] === $pid) {
                $tmp = $value;
                $tmp['title'] = $value['name'];
                $tmp['uri'] = $value['http_path'];
                $tmp['children'] = $this->permissionTree($permissions, $value['id']);
                if (empty($tmp['children'])) unset($tmp['children']);
                $result[] = $tmp;
            }
        }
        return $result;
    }

    /**
     * 查询某ID下的所有子集
     * @param array $id
     * @param array $data
     * @return array
     */
    public function getAllChildrenId($model, $id, $data = [])
    {
        if (!is_array($id)) {
            $id = [$id];
        }
        $pids = $model->whereIn('parent_id', $id)->pluck('id')->toArray();
        if (count($pids) > 0) {
            foreach ($pids as $v) {
                $data[] = $v;
                $data = $this->getAllChildrenId($model, $v, $data); //注意写$data 返回给上级
            }
        }
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }
    /**
     * 查询某ID下的所有父集ID
     * @param array $id
     * @param array $data
     * @return array
     */
    public function getAllParentId($model, $id, $data = [])
    {
        $pid = $model->where('id', $id)->value('parent_id');
        if ($pid != 0) {
            $data[] = $pid;
            $data = $this->getAllParentId($model, $pid, $data);
        }
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

}
