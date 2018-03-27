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

Route::namespace('Admin')->group(function () {
    // "App\Http\Controllers\Admin"名前空間下のコントローラ
    Route::get('/admin/{showdate}', 'AdminController@dashboard');
    Route::get('/admin', 'AdminController@dashboard')->name('adminmain');
    Route::get('/event/add', function () {
        return redirect('/admin');
    });
    Route::get('/events/{ym}', function () {
        return redirect('/admin');
    });

});
