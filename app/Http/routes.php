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

use \Laravel\Lumen\Application;

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'registry/client/', 'namespace' => 'App\Http\Controllers'], function() use ($app) {
    $app->post('exists', 'ClientController@exists');
    $app->post('register', 'ClientController@register');
    $app->get('/', function() use ($app) { return $app->version(); });
    $app->post('test', 'ClientController@test');
});

$app->group(['prefix' => 'registry/server/'], function() use  ($app) {
    $app->post('register', 'ServerController@register');
    $app->post('beat', 'ServerController@beat');
    $app->post('list', 'ServerController@getList');
    $app->post('exists', 'ServerController@exists');
    $app->get('/', function() use ($app) { return $app->version(); });
    $app->post('test', 'ServerController@test');
});