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

    public function view($id) {
        $model = $this->findModel($id);
        $response = [
            'status' => 1,
            'data' => $model
        ];
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

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
