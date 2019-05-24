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

// Authentication
Route::get('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');
