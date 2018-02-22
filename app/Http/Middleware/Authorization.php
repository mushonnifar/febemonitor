<?php

namespace App\Http\Middleware;

use Closure;
use App\AccessTokens;
use App\Model\User;

class Authorization {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $route, $action) {
        $headers = $request->headers->all();
        $id = "";
        if (!empty($headers['x-access-token'][0])) {
            $id = $this->findIdentityByAccessToken($headers['x-access-token'][0]);
        } else if ($request->input('access_token')) {
            $id = $this->findIdentityByAccessToken($request->input('api_token'));
        }
        if (!$this->cekAkses($id, $route, $action) > 0) {
            $response = [
                'status' => "errors",
                'message' => "Unauthorized"
            ];
            response()->json($response, 400, [], JSON_PRETTY_PRINT)->send();
            die;
        }
        return $next($request);
    }

    private function findIdentityByAccessToken($token) {
        $access_token = AccessTokens::where(['token' => $token])->first();

        if ($access_token) {
            if ($access_token->expires_at < time()) {

                $response = [
                    'status' => 0,
                    'error' => "Access token expired"
                ];
                response()->json($response, 400, [], JSON_PRETTY_PRINT)->send();
                die;
            }

            $data = User::where(['id' => $access_token->user_id])->first();

            return $data->id;
        } else {
            $response = [
                'status' => 0,
                'error' => "Access token not found"
            ];
            response()->json($response, 400, [], JSON_PRETTY_PRINT)->send();
            die;
        }
    }

    private function cekAkses($id, $route, $action) {
        settype($action, "string");
        $query = app('db')->table('users')
                ->join('userhasrole', 'userhasrole.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'userhasrole.role_id')
                ->join('rolehasroute', 'rolehasroute.role_id', '=', 'roles.id')
                ->join('routes', 'routes.id', '=', 'rolehasroute.route_id')
                ->join('roleroutehasaction', 'roleroutehasaction.roleroute_id', '=', 'rolehasroute.id')
                ->join('actions', 'actions.id', '=', 'roleroutehasaction.action_id')
                ->select('roleroutehasaction.id', 'users.id AS user_id', 'users.name AS nama_user', 'userhasrole.role_id', 'roles.name AS nama_role', 'rolehasroute.route_id', 'routes.name AS nama_route', 'roleroutehasaction.action_id', 'actions.name AS nama_action')
                ->where('users.isactive', 'y')
                ->where('users.id', $id)
                ->where('routes.route', $route)
                ->where('actions.name', $action)
                ->count();
        return $query;
    }

}
