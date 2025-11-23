<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * API Routes
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 * 
 * @see https://laravel.com/docs/10.x/routing#main-content
 * 
 * 
 * @see https://laravel.com/docs/10.x/controllers#resource-controllers
 * 
 * @see https://laravel.com/docs/10.x/eloquent#route-model-binding
 * 
 * @see https://laravel.com/docs/10.x/routing#route-parameters
 * 
 * @see https://laravel.com/docs/10.x/routing#route-groups
 * 
 * @see https://laravel.com/docs/10.x/routing#registering-routes
 * 
 * @see https://laravel.com/docs/10.x/routing#the-api-router
 * 
 * @see https://laravel.com/docs/10.x/routing#route-caching
 * 
 * @see https://laravel.com/docs/10.x/routing#route-names
 * 
 * @see https://laravel.com/docs/10.x/routing#route-closures
 * 
 * @see https://laravel.com/docs/10.x/routing#route-controllers
 * 
 * @see https://laravel.com/docs/10.x/routing#route-middleware
 * 
 * @see https://laravel.com/docs/10.x/routing#route-prefixes
 */
Route::get('/public/post/getUser/{id}', [UserController::class, 'getUser']);
