<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\AuthorizationCodes;
use App\AccessTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    public function __construct(Request $request) {
        /*
         * midleware auth berikut bertujuan untuk memberi batasan akses user
         * elemen di 'except' merupakan method/function yang tidak diberikan autentikasi
         */
        $this->middleware(
                'auth', ['except' => ['create', 'accesstoken', 'auth']]
        );
    }

    /**
     * @api {post} /user/ Create User
     * @apiVersion 0.1.0
     * @apiName Create User
     * @apiGroup Users
     *
     * @apiParam {String} name nama user
     * @apiParam {String} username username user
     * @apiParam {String} password password user
     * @apiParam {String} email email user
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {String} data.name nama user
     * @apiSuccess {String} data.username username user
     * @apiSuccess {String} data.email email user
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     * @apiSuccess {Integer} data.id id user
     *
     */
    public function create(Request $request) {
        $this->validate($request, User::rules());

        $attributes = $request->all();

        $attributes['password'] = Hash::make($attributes['password']);

        $model = User::create($attributes);


        $response = [
            'status' => 1,
            'data' => $model
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {get} /user/me Get My Identity
     * @apiVersion 0.1.0
     * @apiName Get My Identity
     * @apiGroup Users
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id role
     * @apiSuccess {String} data.name nama user
     * @apiSuccess {String} data.username username user
     * @apiSuccess {String} data.email email user
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     *
     */
    public function me() {
        $data = Auth::user()->getAttributes();

        unset($data['password']);
        unset($data['password_reset_token']);

        $response = [
            'status' => 1,
            'data' => $data
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {post} /user/gettoken Get Token
     * @apiVersion 0.1.0
     * @apiName Get Token
     * @apiGroup Users
     *
     * @apiDescription Get Token digunakan untuk mendapatkan token, token ini nantinya dipakai untuk mengakses API.
     * 
     * @apiParam {String} authorization_code kode otorisasi
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {String} data.access_token kode token
     * @apiSuccess {Integer} data.expires_at expired token
     * 
     * @apiError {Integer} status 0 (fail)
     * @apiError {String} error pesan eror
     */
    public function accesstoken(Request $request) {
        $this->validate($request, User::accessTokenRules());

        $attributes = $request->all();

        $auth_code = AuthorizationCodes::isValid($attributes['authorization_code']);

        if (!$auth_code) {
            $response = [
                'status' => 0,
                'error' => "Invalid Authorization Code"
            ];
            return response()->json($response, 400, [], JSON_PRETTY_PRINT);
        }

        $model = $this->createAccesstoken($attributes['authorization_code']);

        $data = [];
        $data['access_token'] = $model->token;
        $data['expires_at'] = $model->expires_at;

        $response = [
            'status' => 1,
            'data' => $data
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {post} /user/refresh Refresh Token
     * @apiVersion 0.1.0
     * @apiName Refresh Token
     * @apiGroup Users
     *
     * @apiDescription Refresh Token digunakan untuk mendapatkan token baru setelah token sudah kadaluarsa.
     * 
     * @apiHeader {String} x-access-token token autentikasi
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {String} data.access_token kode token
     * @apiSuccess {Integer} data.expires_at expired token
     * 
     * @apiError {Integer} status 0 (fail)
     * @apiError {String} error pesan eror
     */
    public function refresh(Request $request) {

        $headers = $request->headers->all();

        if (!$access_token = $this->refreshAccesstoken($headers['x-access-token'])) {

            $response = [
                'status' => 0,
                'error' => "Invalid Access token"
            ];
            return response()->json($response, 400, [], JSON_PRETTY_PRINT);
        }


        $data = [];
        $data['access_token'] = $access_token->token;
        $data['expires_at'] = $access_token->expires_at;
        $response = [
            'status' => 1,
            'data' => $data
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {post} /user/auth Login User
     * @apiVersion 0.1.0
     * @apiName Login User
     * @apiGroup Users
     *
     * @apiDescription Login user untuk mendapatkan kode otorisasi
     * 
     * @apiParam {String} username username
     * @apiParam {String} password password
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {String} data.authorization_code kode otorisasi
     * @apiSuccess {Integer} data.expires_at expired token
     * 
     * @apiError {Integer} status 0 (fail)
     * @apiError {String} error pesan eror
     */
    public function auth(Request $request) {
        $this->validate($request, User::authorizeRules());

        if ($model = User::authorize($request->all())) {

            $auth_code = $this->createAuthorizationCode($model->id);

            $data = [];
            $data['authorization_code'] = $auth_code->code;
            $data['expires_at'] = $auth_code->expires_at;

            $response = [
                'status' => 1,
                'data' => $data
            ];

            return response()->json($response, 200, [], JSON_PRETTY_PRINT);
        } else {

            $response = [
                'status' => 0,
                'error' => "Username or Password is wrong"
            ];

            return response()->json($response, 400, [], JSON_PRETTY_PRINT);
        }
    }

    /**
     * @api {post} /user/logout Logout
     * @apiVersion 0.1.0
     * @apiName Logout User
     * @apiGroup Users
     * 
     * @apiHeader {String} x-access-token token autentikasi
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {String} data pesan sukses
     * 
     */
    public function logout(Request $request) {

        $token = $this->getAccessToken($request);

        $model = AccessTokens::where(['token' => $token])->first();


        if ($model->delete()) {

            $response = [
                'status' => 1,
                'message' => "Logged Out Successfully"
            ];
            return response()->json($response, 200, [], JSON_PRETTY_PRINT);
        } else {
            $response = [
                'status' => 0,
                'message' => "Invalid request"
            ];
            return response()->json($response, 400, [], JSON_PRETTY_PRINT);
        }
    }

    /**
     * @api {post} /user/:id Get User By ID
     * @apiVersion 0.1.0
     * @apiName Get User
     * @apiGroup Users
     *
     * @apiParam {Integer} id id user
     *
     * @apiSuccess {Integer} id id user
     * @apiSuccess {String} name nama user
     * @apiSuccess {String} username username user
     * @apiSuccess {String} email email user
     * @apiSuccess {Timestamp} updated_at waktu update data
     * @apiSuccess {Timestamp} created_at waktu create data
     *
     */
    public function view($id) {
        $model = $this->findModel($id);
        return response()->json($model, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {put} /user/:id Update User
     * @apiVersion 0.1.0
     * @apiName Update User
     * @apiGroup Users
     *
     * @apiParam {Integer} id id user
     * @apiParam {String} name nama user
     * @apiParam {String} username username user
     * @apiParam {String} password password user
     * @apiParam {String} email email user
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id user
     * @apiSuccess {String} data.name nama user
     * @apiSuccess {String} data.username username user
     * @apiSuccess {String} data.email email user
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     *
     * @apiError {Integer} status 0 (fail)
     * @apiError {String} errors pesan eror
     */
    public function update(Request $request, $id) {

        $model = $this->findModel($id);
        $this->validate($request, User::updateRules($id));

        $model->username = $request->input('username');
        $new_password = $request->input('password');

        $response = [
            'status' => 1,
        ];


        if (!empty($new_password)) {
            $model->password = Hash::make($new_password);

            $token = $this->getAccessToken($request);

            if ($new_token = $this->refreshAccesstoken($token)) {

                $response['new_access_token'] = $new_token->token;
            }
        }
        $email = $request->input('email');

        if (!empty($email))
            $model->email = $email;

        $model->save();

        $response['data'] = $model;

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @api {delete} /user/:id Delete User
     * @apiVersion 0.1.0
     * @apiName Delete User
     * @apiGroup Users
     *
     * @apiParam {Integer} id id user
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id user
     * @apiSuccess {String} data.name nama user
     * @apiSuccess {String} data.username username user
     * @apiSuccess {String} data.email email user
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
     * @api {get} /user/ Get All Data
     * @apiVersion 0.1.0
     * @apiName Get All Data
     * @apiGroup Users
     *
     * @apiSuccess {Integer} status 1 (success)
     * @apiSuccess {Array[]} data array data
     * @apiSuccess {Integer} data.id id user
     * @apiSuccess {String} data.name nama user
     * @apiSuccess {String} data.username username user
     * @apiSuccess {String} data.email email user
     * @apiSuccess {Timestamp} data.updated_at waktu update data
     * @apiSuccess {Timestamp} data.created_at waktu create data
     * @apiSuccess {Integer} page halaman
     * @apiSuccess {Integer} size banyak data per halaman
     * @apiSuccess {Integer} totalCount jumlah seluruh data
     */
    public function index(Request $request) {
        $response = User::search($request);
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function findModel($id) {

        $model = User::find($id);
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

    public function createAuthorizationCode($user_id) {
        $model = new AuthorizationCodes;

        $model->code = md5(uniqid());

        $model->expires_at = time() + (60 * 5);

        $model->user_id = $user_id;

        if (isset($_SERVER['HTTP_X_APPLICATION_ID'])) {
            $app_id = $_SERVER['HTTP_X_APPLICATION_ID'];
        } else {
            $app_id = null;
        }

        $model->app_id = $app_id;

        $model->created_at = time();

        $model->updated_at = time();

        $model->save();

        return ($model);
    }

    public function createAccesstoken($authorization_code) {

        $auth_code = AuthorizationCodes::where(['code' => $authorization_code])->first();

        $model = new AccessTokens();

        $model->token = md5(uniqid());

        $model->auth_code = $auth_code->code;

        $model->expires_at = time() + (60 * 60 * 24 * 60); // 60 days
        // $model->expires_at=time()+(60 * 2);// 2 minutes

        $model->user_id = $auth_code->user_id;

        $model->created_at = time();

        $model->updated_at = time();

        $model->save();

        return ($model);
    }

    public function refreshAccesstoken($token) {
        $access_token = AccessTokens::where(['token' => $token])->first();
        if ($access_token) {

            $access_token->delete();
            $new_access_token = $this->createAccesstoken($access_token->auth_code);
            return ($new_access_token);
        } else {

            return false;
        }
    }

    public function getAccessToken($request) {

        $headers = $request->headers->all();

        $token = false;

        if (!empty($headers['x-access-token'][0])) {

            $token = $headers['x-access-token'][0];
        } else if ($request->input('access_token')) {
            $token = $request->input('access_token');
        }

        return $token;
    }

}