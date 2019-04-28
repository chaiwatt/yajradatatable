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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('api/contact', 'HomeController@apiContact')->name('api.contact');

Route::post('create', 'HomeController@Create')->name('contact.create');
Route::post('edit', 'HomeController@Edit')->name('contact.edit');
Route::post('editsave', 'HomeController@EditSave')->name('contact.editsave');
Route::post('delete', 'HomeController@Delete')->name('contact.delete');
