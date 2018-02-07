<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rolemenuhasaction extends Model {

    protected $table = 'rolemenuhasaction';
    
    protected $fillable = ['rolemenu_id','action_id','created_by','updated_by'];

    static public function rules() {
        return [
            'rolemenu_id' => 'required',
            'action_id' => 'required'
        ];
    }

    static public function search($request) {

        $page = $request->input('page');
        $limit = $request->input('limit');
        $order = $request->input('order');

        $search = $request->input('search');

        if (isset($search)) {
            $params = $search;
        }

        $limit = isset($limit) ? $limit : 10;
        $page = isset($page) ? $page : 1;


        $offset = ($page - 1) * $limit;

        $query =  app('db')->table('rolemenuhasaction')
                ->join('rolehasmenu','rolehasmenu.id','=','rolemenuhasaction.rolemenu_id')
                ->join('menus','menus.id','=','rolehasmenu.menu_id')
                ->join('actions','actions.id','=','rolemenuhasaction.action_id')
                ->select('rolemenuhasaction.id','rolehasmenu.menu_id','menus.name as nama_menu','rolemenuhasaction.action_id','actions.name as nama_action')
                ->limit($limit)
                ->offset($offset);

        if (isset($params['id'])) {
            $query->where(['id' => $params['id']]);
        }
        if (isset($params['name'])) {
            $query->where('name', 'like', $params['name']);
        }

        if (isset($order)) {
            $query->orderBy($order);
        }

        $data = $query->get();


        return [
            'status' => 1,
            'data' => $data,
            'page' => (int) $page,
            'size' => $limit,
            'totalCount' => (int) $data->count()
        ];
    }

    static public function getById($id){
        $data = app('db')->table('rolemenuhasaction')
                ->join('rolehasmenu','rolehasmenu.id','=','rolemenuhasaction.rolemenu_id')
                ->join('menus','menus.id','=','rolehasmenu.menu_id')
                ->join('actions','actions.id','=','rolemenuhasaction.action_id')
                ->select('rolemenuhasaction.id','rolehasmenu.menu_id','menus.name as nama_menu','rolemenuhasaction.action_id','actions.name as nama_action')
                ->where('rolemenuhasaction.id',$id)
                ->get();
        
        return $data;
    }
    
    static public function updateData($data,$id){
        $update = app('db')->table('rolemenuhasaction')
                ->where('id',$id)
                ->update($data);
        
        return $update;
    }
}
