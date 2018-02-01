<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rolehasmenu extends Model {

    protected $table = 'rolehasmenu';
    
    protected $fillable = ['menu_id','role_id','created_by','updated_by'];

    static public function rules() {
        return [
            'menu_id' => 'required',
            'role_id' => 'required'
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

        $query =  app('db')->table('userhasrole')
                ->join('users','users.id','=','userhasrole.user_id')
                ->join('roles','roles.id','=','userhasrole.role_id')
                ->select('userhasrole.id','userhasrole.user_id','users.name as nama_user','userhasrole.role_id','roles.name as nama_role')
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
        $data = app('db')->table('rolehasmenu')
                ->join('menus','menus.id','=','rolehasmenu.menu_id')
                ->join('roles','roles.id','=','rolehasmenu.role_id')
                ->select('rolehasmenu.id','rolehasmenu.menu_id','menus.name as nama_menu','rolehasmenu.role_id','roles.name as nama_role')
                ->where('rolehasmenu.id',$id)
                ->get();
        
        return $data;
    }
    
    static public function updateData($data,$id){
        $update = app('db')->table('rolehasmenu')
                ->where('id',$id)
                ->update($data);
        
        return $update;
    }
}
