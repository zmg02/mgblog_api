<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class BaseModel extends Model
{
    /**
     * 指示是否自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * 模型日期列的存储格式。
     *
     * @var string
     */
    protected $dateFormat = 'U';

    // 自定义存储时间戳的字段名
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**重写分页方法 */
    public function paginate($perPage = null, $columns = ['*'], $page = null, $pageName = 'page')
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->model->getPerPage();

        $results = ($total = $this->toBase()->getCountForPagination())
            ? $this->forPage($page, $perPage)->get($columns)
            : $this->model->newCollection();

        $pages = ceil($total / $perPage);

        $result = [
            'total'         => $total,
            'current_page'  => $page,
            'page_size'     => $perPage,
            'pages'         => $pages,
            'list'          => $results
        ];
        return $result;
    }

    // 分页
    public function getPageList($page, $pageSize)
    {
        return $this->paginate($pageSize, ['*'], $page, 'page');
    }
}
