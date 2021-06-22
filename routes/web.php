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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('me', 'AuthController@me');
        $router->post('logout', 'AuthController@logout');

        $router->group(['prefix' => 'warehouse'], function () use ($router) {
            $router->get('show', 'WarehouseController@show');

            $router->post('store', 'WarehouseController@store');
            $router->post('{id}/update', 'WarehouseController@update');
            $router->post('{id}/destroy', 'WarehouseController@destroy');
        });

        $router->group(['prefix' => 'vehicle'], function () use ($router) {
            $router->get('show', 'VehicleController@show');
            $router->get('{id}/detail', 'VehicleController@detail');

            $router->post('store', 'VehicleController@store');
            $router->post('{id}/update', 'VehicleController@update');
            $router->post('{id}/destroy', 'VehicleController@destroy');
        });

        $router->group(['prefix' => 'vehicle-usage'], function () use ($router) {
            $router->get('show', 'VehicleUsageController@show');

            $router->post('store', 'VehicleUsageController@store');
            $router->post('{id}/update', 'VehicleUsageController@update');
            $router->post('{id}/destroy', 'VehicleUsageController@destroy');
        });

    });
});
