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
Route::auth();
Route::get('/', [ 'middleware' => 'auth', 'uses' => 'HomeController@index' ]);
Route::get('/home', [ 'middleware' => 'auth', 'uses' => 'HomeController@index' ])->name('home');
Route::get('/import_file', 'ImportController@getImport')->name('import');
Route::get('/import_status', 'ImportController@statusImport')->name('import_status');
Route::post('/import_parse', 'ImportController@parseImport')->name('import_parse');
Route::post('/import_process', 'ImportController@processImport')->name('import_process');

