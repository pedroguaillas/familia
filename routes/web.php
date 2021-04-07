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
Route::get('/home/report', 'HomeController@report')->name('home.report');
Route::get('/home/reportcurrent/{person_id}', 'HomeController@reportcurrent')->name('home.reportcurrent');

Route::resource('contributions', 'ContributionController');
Route::post('aportes/registro-masivo', 'ContributionController@create2')->name('contributions.create2');
Route::post('aportes/almacenamiento-masivo', 'ContributionController@storeMasive')->name('contributions.storemasive');
Route::get('aportes/historial/{person_id}', 'ContributionController@history')->name('aportes.historial');
Route::get('aportes/historialpdf/{person_id}', 'ContributionController@historypdf')->name('aportes.historialpdf');
Route::get('aportes/reporte', 'ContributionController@report')->name('aportes.reporte');

Route::resource('loans', 'LoanController');
Route::get('prestamo/imprimir', 'LoanController@showPdf')->name('prestamo.imprimir');
Route::get('prestamos/pdf', 'LoanController@pdf')->name('loans.pdf');

//PAYMENT ROUTES 
Route::resource('payments', 'PaymentController');
Route::get('prestamos/pagos/{id}', 'PaymentController@index2')->name('prestamos.pagos');
Route::get('prestamos/pagos/pdf/{id}', 'PaymentController@pdf')->name('prestamos.pagos.pdf');
Route::get('payments/interestCalculate/{loan_id}', 'PaymentController@interestCalculate');

//PERSON ROUTES
Route::resource('people', 'PersonController');
Route::get('people/index/json', 'PersonController@indexJson')->name('people.index.json');
Route::post('/people/delete/{id}', 'PersonController@delete');
Route::get('personas/reporte/{type}', 'PersonController@report')->name('personas.reporte');
Route::post('people/purchaseactions', 'PersonController@purchaseactions')->name('people.purchaseactions');
