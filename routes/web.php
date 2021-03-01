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
Route::get('prestamo/imprimir', 'LoanController@showPdf')->name('prestamo.imprimir');

//PAYMENT ROUTES 
Route::resource('payments', 'PaymentController');
Route::get('prestamos/pagos/{id}', 'PaymentController@index2')->name('prestamos.pagos');

//PERSON ROUTES
Route::resource('people', 'PersonController');
Route::get('people/index/json', 'PersonController@indexJson')->name('people.index.json');
Route::post('/people.delete/{id}', 'PersonController@delete');
Route::get('personas/reporte/{type}', 'PersonController@report')->name('personas.reporte');
