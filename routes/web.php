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

// View bookings
Route::get('/bookings', 'FrontController@viewBookings');

// Manage your own booking
Route::get('/manage-booking', 'FrontController@manageYourBooking');

// Book a flight
Route::get('/book/{id}', 'FrontController@addBooking');

// Authentication
Route::get('/login', 'AuthController@testLogin');
Route::get('/logout', 'AuthController@logout');
