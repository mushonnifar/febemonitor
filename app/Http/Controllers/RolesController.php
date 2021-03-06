<?php

namespace App\Http\Controllers;

use App\Model\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller {

    public function __construct(Request $request) {
        $this->middleware('auth');
    }

    /**
     * @api {post} /role/ Create role
     * @apiVersion 0.1.0
     * @apiName Create Roles
     * @apiGroup Roles
     *
     * @apiHeader {String} x-access-token token autentikasi
     * 
     * @apiParam {String} name nama role
     * @apiParam {String} description deskripsi role
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {String} data.name nama role
     * @apiSuccess {String} data.description deskripsi role
     * @apiSuccess {Integer} data.created_by id user yang melakukan create
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     * @apiSuccess {Integer} data.id id role
     *
     */
    public function create(Request $request) {
        $this->validate($request, Roles::rules());

        $identity = $this->getIdentity($request);

        $attributes = $request->all();
        $attributes['created_by'] = $identity['user_id'];
        $model = Roles::create($attributes);

        $response = [
            'status' => 1,
            'data' => $model
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {post} /role/:id Get Role By ID
     * @apiVersion 0.1.0
     * @apiName Get Role By ID
     * @apiGroup Roles
     *
     * @apiHeader {String} x-access-token token autentikasi
     * 
     * @apiParam {Integer} id id role
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id role
     * @apiSuccess {String} data.name nama role
     * @apiSuccess {String} data.description deskripsi role
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
     * @api {put} /role/:id Update Role
     * @apiVersion 0.1.0
     * @apiName Update Role
     * @apiGroup Roles
     *
     * @apiHeader {String} x-access-token token autentikasi
     * 
     * @apiParam {Integer} id id user
     * @apiParam {String} name nama role
     * @apiParam {String} description deskripsi role
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id role
     * @apiSuccess {String} data.name nama role
     * @apiSuccess {String} data.description deskripsi role
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     *
     * @apiError {Integer} status 0 (fail)
     * @apiError {String} errors pesan eror
     */
    public function update(Request $request, $id) {
        $model = $this->findModel($id);
        
        $this->validate($request, Roles::rules($id));
        
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
     * @api {delete} /role/:id Delete Role
     * @apiVersion 0.1.0
     * @apiName Delete Role
     * @apiGroup Roles
     * 
     * @apiHeader {String} x-access-token token autentikasi
     *
     * @apiParam {Integer} id id role
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id role
     * @apiSuccess {String} data.name nama role
     * @apiSuccess {String} data.description deskripsi role
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
     * @api {get} /role/ Get All Data
     * @apiVersion 0.1.0
     * @apiName Get All Data
     * @apiGroup Roles
     * 
     * @apiHeader {String} x-access-token token autentikasi
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id role
     * @apiSuccess {String} data.name nama role
     * @apiSuccess {String} data.description deskripsi role
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     * @apiSuccess {Integer} page halaman
     * @apiSuccess {Integer} size banyak data per halaman
     * @apiSuccess {Integer} totalCount jumlah seluruh data
     */
    public function index(Request $request) {
        $models = Roles::search($request);

        $response = [
            'status' => 1,
            'data' => $models
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function findModel($id) {

        $model = Roles::find($id);
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
