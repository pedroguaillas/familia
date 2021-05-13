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
Route::view('/', 'auth/login');
// Route::get('/', function () {
//     return view('auth/login');
//     // return view('welcome');
// });

Auth::routes();

//HOME
Route::get('home', 'HomeController@index')->name('inicio');
Route::get('home/report', 'HomeController@report')->name('home.report');
Route::get('home/reportcurrent/{person_id}', 'HomeController@reportcurrent')->name('home.reportcurrent');

//CONTRIBUTIONS
Route::resource('contributions', 'ContributionController');
Route::post('aportes/registro-masivo', 'ContributionController@create2')->name('contributions.create2');
Route::post('aportes/almacenamiento-masivo', 'ContributionController@storeMasive')->name('contributions.storemasive');
Route::get('aporte/{person_id}/historial', 'ContributionController@history')->name('aporte.historial');
Route::get('aporte/{person_id}/solicitud', 'ContributionController@solicitude')->name('aporte.solicitude');
Route::get('aporte/{person_id}/historial-reporte', 'ContributionController@historypdf')->name('aporte.historial-reporte');
Route::get('aportes/reporte', 'ContributionController@report')->name('aportes.reporte');

//LONANS
Route::resource('loans', 'LoanController');
Route::post('loans/renovation/{loan}', 'LoanController@renovation');
Route::get('prestamos/solicitud/{loan}', 'LoanController@solicitude')->name('prestamos.solicitud');
Route::get('prestamos/pdf', 'LoanController@pdf')->name('loans.pdf');

//LONAN_RENEWALS
Route::get('prestamo/{loan}/renovaciones', 'LoanRenewalController@index2')->name('prestamo.renovaciones');

//PAYMENT 
Route::resource('payments', 'PaymentController');
Route::get('prestamo/{id}/pagos', 'PaymentController@index2')->name('prestamo.pagos');
Route::get('prestamo/{loan}/pagos/reporte', 'PaymentController@report')->name('prestamo.pagos.reporte');
Route::get('prestamo/pago/{payment}/comprobante', 'PaymentController@voucher')->name('prestamo.pago.comprobante');
Route::get('payments/interestCalculate/{loan_id}', 'PaymentController@interestCalculate');

//PERSON
Route::resource('people', 'PersonController');
Route::get('people/index/json', 'PersonController@indexJson')->name('people.index.json');
Route::post('/people/delete/{id}', 'PersonController@delete');
Route::get('personas/reporte/{type}', 'PersonController@report')->name('personas.reporte');
Route::post('people/purchaseactions', 'PersonController@purchaseactions')->name('people.purchaseactions');

//DIRECTIVE
Route::resource('directives', 'DirectiveController');

//SPEND
Route::resource('spends', 'SpendController');
