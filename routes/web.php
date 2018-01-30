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
    $app->get('/', 'UserController@index');
    $app->put('/{id}', 'UserController@update');
    $app->delete('/{id}', 'UserController@deleteRecord');
});

$router->group(['prefix' => 'action'], function($app) {
    $app->post('/', 'ActionsController@create');
    $app->put('/{id}', 'ActionsController@update');
    $app->get('/{id}', 'ActionsController@view');
    $app->delete('/{id}', 'ActionsController@deleteRecord');
    $app->get('/', 'ActionsController@index');
});

$router->group(['prefix' => 'role'], function($app) {
    $app->post('/', 'RolesController@create');
    $app->put('/{id}', 'RolesController@update');
    $app->get('/{id}', 'RolesController@view');
    $app->delete('/{id}', 'RolesController@deleteRecord');
    $app->get('/', 'RolesController@index');
});