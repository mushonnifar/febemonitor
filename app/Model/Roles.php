<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model {

    protected $fillable = ['name','description','created_by','updated_by','deleted_by'];

    static public function rules() {
        return [
            'name' => 'required'
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

        $query = Roles::select(['id', 'name', 'description','created_by','deleted_by','updated_by', 'deleted_at', 'created_at', 'updated_at'])
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

}
