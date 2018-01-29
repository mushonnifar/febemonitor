<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\AccessTokens;

class Controller extends BaseController {

    //
    protected function getIdentity(Request $request) {
        $headers = $request->headers->all();
        $token = $headers['x-access-token'];
        $access_token = AccessTokens::where(['token' => $token])->first();
        return $access_token;
    }

}
