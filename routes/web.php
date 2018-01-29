<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It is a breeze. Simply tell Lumen the URIs it should respond to
  | and give it the Closure to call when that URI is requested.
  |
 */


$router->get('/', function () use ($router) {
    //return $router->app->version();
    $response = [
        'status' => 1,
        'data' => "Laravel 5.5.* Lumen 5.5.0 RESTful API with OAuth2 created by FE/BE Apps"
    ];

    return response()->json($response, 200, [], JSON_PRETTY_PRINT);
});


$router->group(['prefix' => 'user'], function($app) {
    $app->post('/', 'UserController@create');
    $app->post('/auth', 'UserController@auth');
    $app->post('/gettoken', 'UserController@accesstoken');
    $app->post('/refresh', 'UserController@refresh');
    $app->post('/logout', 'UserController@logout');
    $app->get('/me', 'UserController@me');
    $app->get('/{id}', 'UserController@view');
    $app->get('/users', 'UserController@index');
    $app->put('/{id}', 'UserController@update');
    $app->delete('/{id}', 'UserController@deleteRecord');
});


$router->group(['prefix' => 'employee'], function($app) {
    $app->post('/', 'EmployeesController@create');
    $app->put('/{id}', 'EmployeesController@update');
    $app->get('/{id}', 'EmployeesController@view');
    $app->delete('/{id}', 'EmployeesController@deleteRecord');
    $app->get('/', 'EmployeesController@index');
});
