<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| API Routes
|-------------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'Auth'], function () {

    Route::post('auth/login', 'AuthController@login');

    /*
    |---------------------------------------------------------------------------
    | Rotas protegidas por token [JWT]
    |---------------------------------------------------------------------------
    | Rotas que exigem um token para liberar o acesso e invalidam o token
    | após o uso, exigindo que o sistema atualize o token e o devolva no
    | cabeçalho de resposta da API.
    |
     */
    Route::group(['middleware' => ['api.jwt', 'jwt.refresh']], function () {

        Route::get('rota-protegida', function () {
            return response('ola mundo')
            ->header('Content-Type', 'json')
            ->header('X-Header-One', 'Header Value 1');           
        });
    });
});
