<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Tree;
use App\Model\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use Tree;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $menuM = new Menu();
        $status = $request->input('status');
        $map = [];
        if ($status == 1) {
            $map['status'] = 1;
        }
        $menus = $menuM->where($map)->orderBy('order', 'asc')->get()->toArray();

        $menuTrees = $this->menuTree($menus);
        
        return api_response($menuTrees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $menuM = new Menu();
        $validator = $menuM->validate($request->all());
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $result = $menuM->create($request->all());
        return api_response($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $validator = $menu->validate($request->all());
        if ($validator->fails()) {
            return api_response($validator->errors(), 4006, $validator->errors()->first());
        }

        $result = $menu->update($request->all());
        return api_response($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menuM = new Menu();
        $ids = $this->getAllChildrenId($menuM, $id);
        if ($ids) {
            array_push($ids, intval($id));
        } else {
            $ids = [intval($id)];
        }
        $result = $menuM->whereIn('id', $ids)->update(['status'=>0]);
        return api_response($result);
    }
}
