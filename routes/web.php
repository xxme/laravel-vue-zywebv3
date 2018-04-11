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
        // resource
        Route::resource('event', 'EventController');
        // get
        Route::get('/', 'AdminController@dashboard')->name('admin.main');
        Route::get('/event/create/{date}', 'EventController@create');
        Route::get('/user/index/{id}', 'UserController@index');
        Route::get('/user/create/{id}', 'UserController@create');
        Route::get('/user/edit/{id}', 'UserController@edit');
        Route::get('/type/index/{id}', 'TypesController@index')->name('type.index');
        Route::get('/type/create/{id}', 'TypesController@create');
        Route::get('/user/edit', function () {
            return redirect('/user/edit/' . Auth::user()->id);
        });
        // post
        Route::post('/user/uploadprofile', 'UserController@updateprofile');
        Route::post('/user/update', 'UserController@update');
        Route::post('/type/create_do', 'TypesController@create_do');
        Route::post('/type/updatetypes', 'TypesController@updatetypes');
        Route::post('/uploadimg', 'AdminController@updateImage');
    });
    // Route::post('logout', 'LoginController@logout');
});
