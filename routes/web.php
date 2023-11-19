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

$router->get('/ulas', 'UlasanController@index');
$router->get('/ulas/{id}', 'UlasanController@show');
$router->post('/ulas', 'UlasanController@store');
$router->put('/ulas/{id}', 'UlasanController@update');
$router->delete('/ulas/{id}', 'UlasanController@destroy');