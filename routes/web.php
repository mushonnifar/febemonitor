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
    $app->post('/', ['middleware' => 'otoritas:user,create', 'uses' => 'UserController@create']);
    $app->post('/auth', 'UserController@auth');
    $app->post('/gettoken', 'UserController@accesstoken');
    $app->post('/refresh', 'UserController@refresh');
    $app->post('/logout', 'UserController@logout');
//    $app->get('/me', 'UserController@me');
    $app->get('/{id}', ['middleware' => 'otoritas:user,read', 'uses' => 'UserController@view']);
    $app->get('/', ['middleware' => 'otoritas:user,read', 'uses' => 'UserController@index']);
    $app->put('/{id}', ['middleware' => 'otoritas:user,update', 'uses' => 'UserController@update']);
    $app->delete('/{id}', ['middleware' => 'otoritas:user,delete', 'uses' => 'UserController@deleteRecord']);
});

$router->group(['prefix' => 'action'], function($app) {
//    $app->post('/', 'ActionsController@create');
//    $app->put('/{id}', 'ActionsController@update');
//    $app->get('/{id}', 'ActionsController@view');
//    $app->delete('/{id}', 'ActionsController@deleteRecord');
    $app->get('/', ['middleware' => 'otoritas:action,read', 'uses' => 'ActionsController@index']);
});

$router->group(['prefix' => 'role'], function($app) {
    $app->post('/', ['middleware' => 'otoritas:role,create', 'uses' => 'RolesController@create']);
    $app->put('/{id}', ['middleware' => 'otoritas:role,update', 'uses' => 'RolesController@update']);
    $app->get('/{id}', ['middleware' => 'otoritas:role,read', 'uses' => 'RolesController@view']);
    $app->delete('/{id}', ['middleware' => 'otoritas:role,delete', 'uses' => 'RolesController@deleteRecord']);
    $app->get('/', ['middleware' => 'otoritas:role,read', 'uses' => 'RolesController@index']);
});

$router->group(['prefix' => 'userrole'], function($app) {
    $app->post('/', ['middleware' => 'otoritas:userrole,create', 'uses' => 'UserhasroleController@create']);
    $app->put('/{id}', ['middleware' => 'otoritas:userrole,update', 'uses' => 'UserhasroleController@update']);
    $app->get('/{id}', ['middleware' => 'otoritas:userrole,read', 'uses' => 'UserhasroleController@view']);
    $app->delete('/{id}', ['middleware' => 'otoritas:userrole,delete', 'uses' => 'UserhasroleController@deleteRecord']);
    $app->get('/', ['middleware' => 'otoritas:userrole,read', 'uses' => 'UserhasroleController@index']);
});

$router->group(['prefix' => 'menu'], function($app) {
    $app->post('/', ['middleware' => 'otoritas:menu,create', 'uses' => 'MenusController@create']);
    $app->put('/{id}', ['middleware' => 'otoritas:menu,update', 'uses' => 'MenusController@update']);
    $app->get('/id/{id}', ['middleware' => 'otoritas:menu,read', 'uses' => 'MenusController@view']);
    $app->delete('/{id}', ['middleware' => 'otoritas:menu,delete', 'uses' => 'MenusController@deleteRecord']);
    $app->get('/', ['middleware' => 'otoritas:menu,read', 'uses' => 'MenusController@index']);
    $app->get('/parent', ['middleware' => 'otoritas:menu,read', 'uses' => 'MenusController@getParent']);
    $app->get('/getmenu', ['middleware' => 'otoritas:menu,read', 'uses' => 'MenusController@getMenu']);
});

$router->group(['prefix' => 'rolemenu'], function($app) {
    $app->post('/', ['middleware' => 'otoritas:rolemenu,create', 'uses' => 'RolehasmenuController@create']);
    $app->put('/{id}', ['middleware' => 'otoritas:rolemenu,update', 'uses' => 'RolehasmenuController@update']);
    $app->get('/{id}', ['middleware' => 'otoritas:rolemenu,read', 'uses' => 'RolehasmenuController@view']);
    $app->delete('/{id}', ['middleware' => 'otoritas:rolemenu,delete', 'uses' => 'RolehasmenuController@deleteRecord']);
    $app->get('/', ['middleware' => 'otoritas:rolemenu,read', 'uses' => 'RolehasmenuController@index']);
});

$router->group(['prefix' => 'rolemenuaction'], function($app) {
    $app->post('/', ['middleware' => 'otoritas:rolemenuaction,create', 'uses' => 'RolemenuhasactionController@create']);
    $app->put('/{id}', ['middleware' => 'otoritas:rolemenuaction,update', 'uses' => 'RolemenuhasactionController@update']);
    $app->get('/{id}', ['middleware' => 'otoritas:rolemenuaction,read', 'uses' => 'RolemenuhasactionController@view']);
    $app->delete('/{id}', ['middleware' => 'otoritas:rolemenuaction,delete', 'uses' => 'RolemenuhasactionController@deleteRecord']);
    $app->get('/', ['middleware' => 'otoritas:rolemenuaction,read', 'uses' => 'RolemenuhasactionController@index']);
});

$router->group(['prefix' => 'route'], function($app) {
    $app->post('/', ['middleware' => 'otoritas:route,create', 'uses' => 'RoutesController@create']);
    $app->put('/{id}', ['middleware' => 'otoritas:route,update', 'uses' => 'RoutesController@update']);
    $app->get('/id/{id}', ['middleware' => 'otoritas:route,read', 'uses' => 'RoutesController@view']);
    $app->delete('/{id}', ['middleware' => 'otoritas:route,delete', 'uses' => 'RoutesController@deleteRecord']);
    $app->get('/', ['middleware' => 'otoritas:route,read', 'uses' => 'RoutesController@index']);
});

$router->group(['prefix' => 'roleroute'], function($app) {
    $app->post('/', ['middleware' => 'otoritas:roleroute,create', 'uses' => 'RolehasrouteController@create']);
    $app->put('/{id}', ['middleware' => 'otoritas:roleroute,update', 'uses' => 'RolehasrouteController@update']);
    $app->get('/{id}', ['middleware' => 'otoritas:roleroute,read', 'uses' => 'RolehasrouteController@view']);
    $app->delete('/{id}', ['middleware' => 'otoritas:roleroute,delete', 'uses' => 'RolehasrouteController@deleteRecord']);
    $app->get('/', ['middleware' => 'otoritas:roleroute,read', 'uses' => 'RolehasrouteController@index']);
});

$router->group(['prefix' => 'rolerouteaction'], function($app) {
    $app->post('/', ['middleware' => 'otoritas:rolerouteaction,create', 'uses' => 'RoleroutehasactionController@create']);
    $app->put('/{id}', ['middleware' => 'otoritas:rolerouteaction,update', 'uses' => 'RoleroutehasactionController@update']);
    $app->get('/{id}', ['middleware' => 'otoritas:rolerouteaction,read', 'uses' => 'RoleroutehasactionController@view']);
    $app->delete('/{id}', ['middleware' => 'otoritas:rolerouteaction,delete', 'uses' => 'RoleroutehasactionController@deleteRecord']);
    $app->get('/', ['middleware' => 'otoritas:rolerouteaction,read', 'uses' => 'RoleroutehasactionController@index']);
});