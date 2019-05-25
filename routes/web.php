<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Show front page
Route::get('/', 'FrontController@home');

// Update front message
Route::post('/frontmessage/update', 'FrontController@updateFrontMessage');

// Authentication
Route::get('/login', 'AuthController@testLogin');
Route::get('/logout', 'AuthController@logout');
