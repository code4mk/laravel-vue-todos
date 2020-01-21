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
    return view('todo');
});

Route::get('todo/add', 'TodoController@add')->name('todo.add');
Route::get('todo/get', 'TodoController@get')->name('todo.get');
Route::get('todo/update', 'TodoController@update')->name('todo.update');
Route::get('todo/action', 'TodoController@todoAction')->name('todo.action');
Route::get('todo/delete', 'TodoController@delete')->name('todo.delete');
