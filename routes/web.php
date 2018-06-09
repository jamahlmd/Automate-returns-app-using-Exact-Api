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


//Log in
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


//Api
Route::get('/home', 'ApiController@login')->name('home');
Route::get('/dorivit_retouren_index', 'ApiController@getTokens');
Route::post('/dorivit_retouren_index', 'ApiController@Verify');
Route::get('/retouren', 'ApiController@index');
Route::get('/searchID', 'ApiController@SearchID');
Route::get('/refresh', 'ApiController@refresh');

//DataControl
Route::post('/insert', 'DataController@insert');
Route::post('/export', 'DataController@export');
Route::get('/export', 'DataController@sheet');
Route::post('/server', 'DataController@server');
Route::get('/record/{invoice_id}', 'DataController@record');



//Excel
Route::get('/import', 'DataController@fileimport');
Route::post('/import', 'DataController@import');
Route::get('/retourgegevensdownload', 'DataController@downloadinterface');
Route::post('/retourgegevensdownload', 'DataController@download');

//User
Route::get('/rechten', 'UserController@index');
Route::post('/rechten', 'UserController@change');

//Menu
Route::get('/menu', function (){
    return view('menu');
});

//Statistiek
Route::get('/statistiek', 'StatisticsController@index');



//Account
Route::get('/account', 'AccountController@index');
Route::get('/account/{invoice_id}', 'AccountController@invoice');






