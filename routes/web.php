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

Route::get('/', function () {
    return view('auth/login');
    // return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('contributions', 'ContributionController');

Route::resource('loans', 'LoanController');

//PERSON ROUTES
Route::resource('people', 'PersonController');
Route::post('/people.delete/{id}', 'PersonController@delete');