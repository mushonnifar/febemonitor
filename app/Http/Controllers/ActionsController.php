<?php

namespace App\Http\Controllers;

use App\Model\Actions;
use Illuminate\Http\Request;

class ActionsController extends Controller {

    public function __construct(Request $request) {
        $this->middleware('auth');
    }

    public function create(Request $request) {
        $this->validate($request, Actions::rules());

        $identity = $this->getIdentity($request);

        $attributes = $request->all();
        $attributes['created_by'] = $identity['user_id'];
        $model = Actions::create($attributes);

        $response = [
            'status' => 'success',
            'data' => $model
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function view($id) {
        $model = $this->findModel($id);
        $response = [
            'status' => 'success',
            'data' => $model
        ];
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function update(Request $request, $id) {
        $model = $this->findModel($id);
        
        $this->validate($request, Actions::rules($id));
        
        $identity = $this->getIdentity($request);
        
        $attributes = $request->all();
        $attributes['updated_by'] = $identity['user_id'];
        $model->update($attributes);

        $response = [
            'status' => 'success',
            'data' => $model
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function deleteRecord($id) {
        $model = $this->findModel($id);
        $model->delete();

        $response = [
            'status' => 'success',
            'data' => $model,
            'message' => 'Removed successfully.'
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function index(Request $request) {
        $models = Actions::search($request);

        $response = [
            'status' => 'success',
            'data' => $models
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function findModel($id) {

        $model = Actions::find($id);
        if (!$model) {
            $response = [
                'status' => "fail",
                'errors' => "Invalid Record"
            ];

            response()->json($response, 400, [], JSON_PRETTY_PRINT)->send();
            die;
        }
        return $model;
    }

    

}
