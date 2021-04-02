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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

// Auth Client
$router->group(['middleware' => 'auth', 'namespace' => 'Client', 'prefix' => 'client'], function () use ($router) {
    $router->get('/', ['uses' => 'ClientController@getProfile']);
});

// Auth Depot
$router->group(['middleware' => 'auth', 'namespace' => 'Depot', 'prefix' => 'depot'], function () use ($router) {
    $router->get('/', ['uses' => 'DepotController@getProfile']);
});

// No Auth - Client
$router->group(['namespace' => 'Client', 'prefix' => 'client'], function () use ($router) {
    $router->post('/register', ['uses' => 'AuthController@register']);
});

// No Auth - Depot
$router->group(['namespace' => 'Depot', 'prefix' => 'depot'], function () use ($router) {
    $router->post('/register', ['uses' => 'AuthController@register']);
});
