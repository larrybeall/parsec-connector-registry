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

$app->group(['prefix' => 'registry/client/'], function (Application $app) {
    $app->post('exists', 'ClientController@exists');
    $app->post('register', 'ClientController@register');
});

$app->group(['prefix' => 'registry/server/'], function (Application $app) {
    $app->post('register', 'ServerController@register');
    $app->post('beat', 'ServerController@beat');
    $app->post('list', 'ServerController@getList');
    $app->post('exists', 'ServerController@exists');
});