<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/employees', 'EmployeeController@index')->name('employee-index');
Route::get('/employees/import', 'EmployeeController@importPage')->name('import-page');
Route::post('employees/import', 'EmployeeController@import')->name('import');

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');


Auth::routes();
