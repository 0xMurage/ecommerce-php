<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['namespace' => '\App\Http\Controllers\Auth', 'prefix' => 'api/v1'], function () use ($router) {

    $router->post('/register', 'RegisterController@store');
    $router->post('/login', 'LoginController@login');
});

$router->group(['prefix' => 'api/v1/users', 'middleware' => ['auth']], function () use ($router) {
    $router->get('/show', 'UserAccountController@index');
    $router->post('/new', 'UserAccountController@store');
    $router->put('/update/{id}', 'UserAccountController@update');
    $router->delete('/delete/{id}', 'UserAccountController@destroy');
});

$router->group(['prefix' => 'api/v1/roles', 'middleware' => ['auth']], function () use ($router) {
    $router->get('/show', 'RoleController@index');
    $router->post('/new', 'RoleController@store');
    $router->put('/update/{id}', 'RoleController@update');

});
