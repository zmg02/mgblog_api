<?php

use App\Model\Menu;
use App\Model\Permission;
use App\Model\Role;
use App\Model\User;
use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建角色
        Role::truncate();
        Role::create([
            'name' => '管理员',
            'slug' => 'admin'
        ]);
        // 关联用户
        User::first()->roles()->save(Role::first());
        // 创建权限
        Permission::truncate();
        Permission::insert([
            [
                'parent_id'     => 0,
                'name'          => 'All permission',
                'slug'          => '*',
                'http_method'   => '',
                'http_path'     => '*'
            ],

            [
                'parent_id'     => 0,
                'name'          => '主页',
                'slug'          => 'dashboard',
                'http_method'   => 'GET',
                'http_path'     => '/'
            ],

            [
                'parent_id'     => 0,
                'name'          => '成员管理',
                'slug'          => 'member',
                'http_method'   => 'GET',
                'http_path'     => '/member'
            ],

            [
                'parent_id'     => 3,
                'name'          => '用户',
                'slug'          => 'user',
                'http_method'   => 'GET',
                'http_path'     => '/member/user'
            ],

            [
                'parent_id'     => 3,
                'name'          => '作者',
                'slug'          => 'author',
                'http_method'   => 'GET',
                'http_path'     => '/member/author'
            ],

            [
                'parent_id'     => 3,
                'name'          => '管理员',
                'slug'          => 'admin',
                'http_method'   => 'GET',
                'http_path'     => '/member/admin'
            ],

            [
                'parent_id'     => 0,
                'name'          => '文章管理',
                'slug'          => 'article',
                'http_method'   => 'GET',
                'http_path'     => '/article'
            ],

            [
                'parent_id'     => 7,
                'name'          => '文章列表',
                'slug'          => 'article_list',
                'http_method'   => 'GET',
                'http_path'     => '/article/list'
            ],

            [
                'parent_id'     => 7,
                'name'          => '文章分类',
                'slug'          => 'category',
                'http_method'   => 'GET',
                'http_path'     => '/article/category'
            ],

        ]);
        // 关联角色
        Role::first()->permissions()->save(Permission::first());

        // 创建菜单
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'path'      => '/',
                'component' => 'layout',
                'slug'      => '',
                'title'     => '首页',
                'icon'      => '',
                'uri'       => '/dashboard',
                'order'     => 1,
            ],
            [
                'parent_id' => 1,
                'path'      => 'dashboard',
                'component' => 'dashboard',
                'slug'      => 'Dashboard',
                'title'     => '首页',
                'icon'      => 'dashboard',
                'uri'       => '/dashboard',
                'order'     => 1,
            ],
            [
                'parent_id' => 0,
                'path'      => '/member',
                'component' => 'layout',
                'slug'      => 'Member',
                'title'     => '成员管理',
                'icon'      => 'el-icon-user',
                'uri'       => '/member',
                'order'     => 2,
            ],
            [
                'parent_id' => 3,
                'path'      => 'user',
                'component' => 'user',
                'slug'      => 'User',
                'title'     => '用户',
                'icon'      => '',
                'uri'       => '/member/user',
                'order'     => 1,
            ],
            [
                'parent_id' => 3,
                'path'      => 'author',
                'component' => 'author',
                'slug'      => 'Author',
                'title'     => '作者',
                'icon'      => '',
                'uri'       => '/member/author',
                'order'     => 2,
            ],
            [
                'parent_id' => 3,
                'path'      => 'admin',
                'component' => 'admin',
                'slug'      => 'Admin',
                'title'     => '管理员',
                'icon'      => '',
                'uri'       => '/member/admin',
                'order'     => 3,
            ],
            [
                'parent_id' => 0,
                'path'      => '/article',
                'component' => 'layout',
                'slug'      => 'Article',
                'title'     => '文章管理',
                'icon'      => 'el-icon-postcard',
                'uri'       => '/article',
                'order'     => 3,
            ],
            [
                'parent_id' => 7,
                'path'      => 'list',
                'component' => 'article',
                'slug'      => 'List',
                'title'     => '文章列表',
                'icon'      => '',
                'uri'       => '/article/list',
                'order'     => 1,
            ],
            [
                'parent_id' => 7,
                'path'      => 'category',
                'component' => 'category',
                'slug'      => 'Category',
                'title'     => '文章分类',
                'icon'      => '',
                'uri'       => '/article/category',
                'order'     => 2,
            ],
        ]);
    }
}
