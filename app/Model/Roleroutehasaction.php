<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Roleroutehasaction extends Model {

    protected $table = 'roleroutehasaction';
    
    protected $fillable = ['roleroute_id','action_id','created_by','updated_by'];

    static public function rules() {
        return [
            'roleroute_id' => 'required',
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

        $query =  app('db')->table('roleroutehasaction')
                ->join('rolehasroute','rolehasroute.id','=','roleroutehasaction.roleroute_id')
                ->join('routes','routes.id','=','rolehasroute.route_id')
                ->join('actions','actions.id','=','roleroutehasaction.action_id')
                ->select('roleroutehasaction.id','rolehasroute.route_id','routes.name as nama_route','roleroutehasaction.action_id','actions.name as nama_action')
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
        $data = app('db')->table('roleroutehasaction')
                ->join('rolehasroute','rolehasroute.id','=','roleroutehasaction.roleroute_id')
                ->join('routes','routes.id','=','rolehasroute.route_id')
                ->join('actions','actions.id','=','roleroutehasaction.action_id')
                ->select('roleroutehasaction.id','rolehasroute.route_id','routes.name as nama_route','roleroutehasaction.action_id','actions.name as nama_action')
                ->where('roleroutehasaction.id',$id)
                ->get();
        
        return $data;
    }
    
    static public function updateData($data,$id){
        $update = app('db')->table('roleroutehasaction')
                ->where('id',$id)
                ->update($data);
        
        return $update;
    }
}
