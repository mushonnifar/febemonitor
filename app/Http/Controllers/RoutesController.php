<?php

namespace App\Http\Controllers;

use App\Model\Routes;
use Illuminate\Http\Request;

class RoutesController extends Controller {

    public function __construct(Request $request) {
        $this->middleware('auth');
    }

    /**
     * @api {post} /route/ Create route
     * @apiVersion 0.1.0
     * @apiName Create Routes
     * @apiGroup Routes
     *
     * @apiHeader {String} x-access-token token autentikasi
     * 
     * @apiParam {String} name nama route
     * @apiParam {String} description deskripsi route
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {String} data.name nama route
     * @apiSuccess {String} data.description deskripsi route
     * @apiSuccess {Integer} data.created_by id user yang melakukan create
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     * @apiSuccess {Integer} data.id id route
     *
     */
    public function create(Request $request) {
        $this->validate($request, Routes::rules());

        $identity = $this->getIdentity($request);

        $attributes = $request->all();
        $attributes['created_by'] = $identity['user_id'];
        $model = Routes::create($attributes);

        $response = [
            'status' => 1,
            'data' => $model
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {post} /route/:id Get Role By ID
     * @apiVersion 0.1.0
     * @apiName Get Role By ID
     * @apiGroup Routes
     *
     * @apiHeader {String} x-access-token token autentikasi
     * 
     * @apiParam {Integer} id id route
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id route
     * @apiSuccess {String} data.name nama route
     * @apiSuccess {String} data.description deskripsi route
     * @apiSuccess {Integer} data.updated_by id user yang melakukan update data
     * @apiSuccess {Integer} data.created_by id user yang melakukan create data
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     *
     * @apiError {Integer} status 0 (fail)
     * @apiError {String} errors pesan eror
     */
    public function view($id) {
        $model = $this->findModel($id);
        $response = [
            'status' => 1,
            'data' => $model
        ];
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {put} /route/:id Update Role
     * @apiVersion 0.1.0
     * @apiName Update Role
     * @apiGroup Routes
     *
     * @apiHeader {String} x-access-token token autentikasi
     * 
     * @apiParam {Integer} id id user
     * @apiParam {String} name nama route
     * @apiParam {String} description deskripsi route
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id route
     * @apiSuccess {String} data.name nama route
     * @apiSuccess {String} data.description deskripsi route
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     *
     * @apiError {Integer} status 0 (fail)
     * @apiError {String} errors pesan eror
     */
    public function update(Request $request, $id) {
        $model = $this->findModel($id);

        $this->validate($request, Routes::rules($id));

        $identity = $this->getIdentity($request);

        $attributes = $request->all();
        $attributes['updated_by'] = $identity['user_id'];
        $model->update($attributes);

        $response = [
            'status' => 1,
            'data' => $model
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {delete} /route/:id Delete Role
     * @apiVersion 0.1.0
     * @apiName Delete Role
     * @apiGroup Routes
     * 
     * @apiHeader {String} x-access-token token autentikasi
     *
     * @apiParam {Integer} id id route
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id route
     * @apiSuccess {String} data.name nama route
     * @apiSuccess {String} data.description deskripsi route
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     * @apiSuccess {String} message Removed successfully
     *
     * @apiError {Integer} status 0 (fail)
     * @apiError {String} errors pesan eror
     */
    public function deleteRecord($id) {
        $model = $this->findModel($id);
        $model->delete();

        $response = [
            'status' => 1,
            'data' => $model,
            'message' => 'Removed successfully.'
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {get} /route/ Get All Data
     * @apiVersion 0.1.0
     * @apiName Get All Data
     * @apiGroup Routes
     * 
     * @apiHeader {String} x-access-token token autentikasi
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id route
     * @apiSuccess {String} data.name nama route
     * @apiSuccess {String} data.description deskripsi route
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     * @apiSuccess {Integer} page halaman
     * @apiSuccess {Integer} size banyak data per halaman
     * @apiSuccess {Integer} totalCount jumlah seluruh data
     */
    public function index(Request $request) {
        $models = Routes::search($request);

        $response = [
            'status' => 1,
            'data' => $models
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function getParent() {
        $models = Routes::where('parent', 0)->get();

        $response = [
            'status' => 1,
            'data' => $models
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function getRoute() {
        $parent = Routes::where('parent', 0)->get();
        $child = Routes::where('parent', '!=', 0)->get();

        $route = array();

        foreach ($parent as $value) {
            array_push($route, array(
                "id" => $value->id,
                "name" => $value->description,
                "link" => $value->link,
                "child" => array()
            ));            
        }
        foreach($route as $key=>$value){
            foreach($child as $valuechild){
                if($value['id'] == $valuechild->parent){
                    array_push($route[$key]['child'], array(
                        "id" => $valuechild->id,
                        "name" => $valuechild->description,
                        "link" => $valuechild->link,
                    ));
                }
            }
        }
        
        $response = [
            'status' => 1,
            'data' => $route
        ];
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function findModel($id) {

        $model = Routes::find($id);
        if (!$model) {
            $response = [
                'status' => 0,
                'errors' => "Invalid Record"
            ];

            response()->json($response, 400, [], JSON_PRETTY_PRINT)->send();
            die;
        }
        return $model;
    }

}
