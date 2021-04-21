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
// TODO Client middleware
$router->group(['middleware' => ['auth'], 'namespace' => 'Client', 'prefix' => 'client'], function () use ($router) {
    $router->get('/', ['uses' => 'ClientController@getProfile']);
    $router->patch('/', ['uses' => 'ClientController@updateProfile']);

    // Transaction
    $router->group(['prefix' => 'transaction'], function () use ($router) {
        $router->get('/', ['uses' => 'TransactionController@getTransaction']);
        $router->get('/{id}', ['uses' => 'TransactionController@getDetailTransaction']);
        $router->post('/', ['uses' => 'TransactionController@addTransaction']);
    });

    // Depot
    $router->group(['prefix' => 'depot'], function () use ($router) {
        $router->get('/', ['uses' => 'DepotController@getDepot']);
    });
});

// Auth Depot
// TODO Depot middleware
$router->group(['middleware' => ['auth'], 'namespace' => 'Depot', 'prefix' => 'depot'], function () use ($router) {
    $router->get('/', ['uses' => 'DepotController@getProfile']);
    $router->patch('/open', ['uses' => 'DepotController@openDepot']);
    $router->patch('/close', ['uses' => 'DepotController@closeDepot']);

    // Transaction
    $router->group(['prefix' => 'transaction'], function () use ($router) {
        $router->get('/', ['uses' => 'TransactionController@getTransaction']);
        $router->get('/{id}', ['uses' => 'TransactionController@getDetailTransaction']);
        $router->patch('/{id}/take-status', ['uses' => 'TransactionController@takeGallonStatus']);
        $router->patch('/{id}/send-status', ['uses' => 'TransactionController@sendGallonStatus']);
        $router->patch('/{id}/complete-status', ['uses' => 'TransactionController@completeStatus']);
        $router->patch('/{id}/deny-status', ['uses' => 'TransactionController@denyStatus']);
    });
});

// No Auth - Client
$router->group(['namespace' => 'Client', 'prefix' => 'client'], function () use ($router) {
    $router->post('/check-user', ['uses' => 'AuthController@checkUser']);
    $router->post('/register', ['uses' => 'AuthController@register']);
});

// No Auth - Depot
$router->group(['namespace' => 'Depot', 'prefix' => 'depot'], function () use ($router) {
    $router->post('/check-user', ['uses' => 'AuthController@checkUser']);
    $router->post('/register', ['uses' => 'AuthController@register']);
});

// No Auth - Public
$router->get('/img/{type}/{fileName}', ['uses' => 'ImageController@getImage']);
