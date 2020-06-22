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

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::resource('posts', 'PostController');

Route::get('/home', function(){
    return redirect('posts');
})->name('home');

Route::get('/like/{post}', 'PostController@like')->name('like');

Route::get('/users/{user}', 'UserController@edit')->name('users.edit');
Route::put('/users/{user}', 'UserController@update')->name('users.update');

Route::get('myposts', 'UserController@posts')->name('myposts');