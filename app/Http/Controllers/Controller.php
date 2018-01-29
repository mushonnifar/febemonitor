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

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = []) {

        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $response = [
                'status' => 0,
                'errors' => $validator->errors()
            ];

            response()->json($response, 400, [], JSON_PRETTY_PRINT)->send();
            die();
        }

        return true;
    }
}
