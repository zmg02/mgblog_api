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

            [
                'parent_id'     => 7,
                'name'          => '标签',
                'slug'          => 'tag',
                'http_method'   => 'GET',
                'http_path'     => '/article/tag'
            ],

            [
                'parent_id'     => 0, //11
                'name'          => '设置',
                'slug'          => 'set',
                'http_method'   => 'GET',
                'http_path'     => '/set'
            ],
            [
                'parent_id'     => 11,
                'name'          => '角色',
                'slug'          => 'role',
                'http_method'   => 'GET',
                'http_path'     => '/set/role'
            ],
            [
                'parent_id'     => 11,
                'name'          => '权限',
                'slug'          => 'permission',
                'http_method'   => 'GET',
                'http_path'     => '/set/permission'
            ],
            [
                'parent_id'     => 11,
                'name'          => '菜单',
                'slug'          => 'menu',
                'http_method'   => 'GET',
                'http_path'     => '/set/menu'
            ],
            [
                'parent_id'     => 11,
                'name'          => '默认',
                'slug'          => 'default',
                'http_method'   => 'GET',
                'http_path'     => '/default'
            ],
            [
                'parent_id'     => 11, // 15
                'name'          => '前端设置',
                'slug'          => 'www',
                'http_method'   => 'GET',
                'http_path'     => '/set/www'
            ],
            [
                'parent_id'     => 16,
                'name'          => '轮播图',
                'slug'          => 'banner',
                'http_method'   => 'GET',
                'http_path'     => '/set/www/banner'
            ],
            [
                'parent_id'     => 16,
                'name'          => '照片墙',
                'slug'          => 'instagram',
                'http_method'   => 'GET',
                'http_path'     => '/set/www/instagram'
            ],
            [
                'parent_id'     => 7,
                'name'          => '文章评论',
                'slug'          => 'comment',
                'http_method'   => 'GET',
                'http_path'     => '/article/comment'
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
                'hidden'    => 0,
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
                'hidden'    => 0,
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
                'hidden'    => 0,
            ],
            [
                'parent_id' => 3,
                'path'      => 'user',
                'component' => 'user',
                'slug'      => 'User',
                'title'     => '用户',
                'icon'      => 'el-icon-user',
                'uri'       => '/member/user',
                'order'     => 1,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 3,
                'path'      => 'author',
                'component' => 'author',
                'slug'      => 'Author',
                'title'     => '作者',
                'icon'      => 'el-icon-user-solid',
                'uri'       => '/member/author',
                'order'     => 2,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 3,
                'path'      => 'admin',
                'component' => 'admin',
                'slug'      => 'Admin',
                'title'     => '管理员',
                'icon'      => 'el-icon-s-custom',
                'uri'       => '/member/admin',
                'order'     => 3,
                'hidden'    => 0,
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
                'hidden'    => 0,
            ],
            [
                'parent_id' => 7,
                'path'      => 'list',
                'component' => 'article',
                'slug'      => 'List',
                'title'     => '文章列表',
                'icon'      => 'el-icon-postcard',
                'uri'       => '/article/list',
                'order'     => 1,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 7,
                'path'      => 'category',
                'component' => 'category',
                'slug'      => 'Category',
                'title'     => '文章分类',
                'icon'      => 'el-icon-sort',
                'uri'       => '/article/category',
                'order'     => 2,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 7,
                'path'      => 'tag',
                'component' => 'tag',
                'slug'      => 'Tag',
                'title'     => '标签',
                'icon'      => 'el-icon-collection-tag',
                'uri'       => '/article/tag',
                'order'     => 3,
                'hidden'    => 0,
            ],

            [
                'parent_id' => 0,
                'path'      => '/set',
                'component' => 'layout',
                'slug'      => 'Set',
                'title'     => '设置',
                'icon'      => 'el-icon-setting',
                'uri'       => '/set',
                'order'     => 1,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 11,
                'path'      => 'role',
                'component' => 'role',
                'slug'      => 'Role',
                'title'     => '角色',
                'icon'      => 'el-icon-user',
                'uri'       => '/set/role',
                'order'     => 1,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 11,
                'path'      => 'permission',
                'component' => 'permission',
                'slug'      => 'Permission',
                'title'     => '权限',
                'icon'      => 'el-icon-check',
                'uri'       => '/set/permission',
                'order'     => 2,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 11,
                'path'      => 'menu',
                'component' => 'menu',
                'slug'      => 'Menu',
                'title'     => '菜单',
                'icon'      => 'el-icon-menu',
                'uri'       => '/set/menu',
                'order'     => 3,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 11,
                'path'      => '/default',
                'component' => 'default',
                'slug'      => 'Default',
                'title'     => '默认菜单',
                'icon'      => 'el-icon-help',
                'uri'       => '/default',
                'order'     => 4,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 11,
                'path'      => 'www',
                'component' => 'www',
                'slug'      => 'WWW',
                'title'     => '前端设置',
                'icon'      => 'el-icon-setting',
                'uri'       => '/set/www',
                'order'     => 5,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 16,
                'path'      => 'banner',
                'component' => 'banner',
                'slug'      => 'Banner',
                'title'     => '轮播图',
                'icon'      => 'el-icon-picture-outline',
                'uri'       => '/set/www/banner',
                'order'     => 1,
                'hidden'    => 0,
            ],
            [
                'parent_id' => 16,
                'path'      => 'instagram',
                'component' => 'instagram',
                'slug'      => 'Instagram',
                'title'     => '照片墙',
                'icon'      => 'el-icon-picture',
                'uri'       => '/set/www/instagram',
                'order'     => 2,
                'hidden'    => 0,
            ],

            [
                'parent_id' => 7,
                'path'      => 'comment',
                'component' => 'comment',
                'slug'      => 'Comment',
                'title'     => '文章评论',
                'icon'      => '',
                'uri'       => '/article/comment',
                'order'     => 0,
                'hidden'    => 1,
            ],
        ]);
    }
}
