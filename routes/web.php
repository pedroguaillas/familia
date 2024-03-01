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
Route::get('préstamo/{loan}/renovación', 'RenovationController@show')->name('prestamo.renovacion');
Route::put('loan/{loan}/renew', 'RenovationController@update')->name('loan.renew');
Route::get('prestamo/{loan}/renovaciones', 'LoanRenewalController@index2')->name('prestamo.renovaciones');

//PAYMENT 
Route::resource('payments', 'PaymentController');
Route::get('prestamo/{loan}/pagos', 'PaymentController@index')->name('prestamo.pagos');
Route::get('prestamo/{loan}/pagos/reporte', 'PaymentController@report')->name('prestamo.pagos.reporte');
Route::get('prestamo/{loan}/tabla-de-amortización', 'PaymentController@amortizationTable')->name('prestamo.tablaamortizacion');
Route::get('prestamo/pago/{payment}/comprobante', 'PaymentController@voucher')->name('prestamo.pago.comprobante');
Route::get('payments/interestCalculate/{loan}', 'PaymentController@interestCalculate');
Route::get('payments/liquidacionCalculate/{loan}', 'PaymentController@liquidacionCalculate');

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

//MANUAL
Route::get('manual', 'HomeController@manual');

// Cambiar contraseña
Route::get('cambiar-contraseña', 'Auth\SetPasswordController@reset');
Route::post('setpass', 'Auth\SetPasswordController@set')->name('setpass');

// REPORTES
Route::get('reporte-anual', 'ChashClosingController@year')->name('reporte-anual');
Route::get('{year}/reporte-mensual', 'ChashClosingController@month')->name('reporte-mensual');
