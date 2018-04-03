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

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@login')->name('admin.logindo');
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/admin/login');
    });
    Route::group(['middleware' => 'auth.admin'], function () {
        Route::get('/', 'AdminController@dashboard')->name('admin.main');
        Route::get('/event/add', 'AdminController@dashboard');
        Route::get('/user/edit/{id}', 'UserController@edit');
        Route::post('/user/uploadprofile', 'UserController@updateprofile');
        Route::post('/user/update', 'UserController@update');
        Route::get('/typegroup/{id}', 'TypeGroupsController@show')->name('type.group');
        Route::get('/type/create/{id}', 'TypesController@create');
        Route::post('/type/create_do', 'TypesController@create_do');
        Route::post('/type/updatetypes', 'TypesController@updatetypes');
        Route::get('/user/edit', function () {
            return redirect('/user/edit/' . Auth::user()->id);
        });
        
    });
    // Route::post('logout', 'LoginController@logout');
});
