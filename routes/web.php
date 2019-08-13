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
    return view('index');
});
Route::get('/admintest', function () {
    return redirect('/admin');
});
Route::get('/offer', 'PublicController@public')->name('offer');
Route::get('/offer/{zipcode}', 'PublicController@public');
Route::post('/offer', 'PublicController@createOffer');

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
        Route::resource('comment', 'CommentController');
        Route::resource('productlist', 'ProductListController');
        Route::resource('estimate', 'EstimateController');
        // vue
        Route::get('/', 'AdminController@vue')->name('admin.main');
        Route::get('/event/create', 'AdminController@vue');
        Route::get('/event/create/{date}', 'AdminController@vue');
        Route::get('/event/createbyproductlist/{listid}', 'AdminController@vue');
        Route::get('/event/createbyorder/{orderid}', 'AdminController@vue');
        Route::get('/event/contract/{id}', 'AdminController@vue');
        Route::get('/finances', 'AdminController@vue');

        // get
        Route::get('/events/{ym}', 'EventController@index');
        Route::get('/events/getsales/{id}', 'EventController@getsales');
        Route::get('/events/finances/{type}', 'EventController@finances');
        Route::get('/event/showbycontract/{id}', 'EventController@showbycontract');
        Route::get('/event/item/{id}', 'EventController@item');
        Route::get('/user/index/{id}', 'UserController@index')->name('admin.userindex');
        Route::get('/user/create/{id}', 'UserController@create');
        Route::get('/user/edit/{id}', 'UserController@edit')->name('admin.useredit');
        Route::get('/type/index/{id}', 'TypesController@index')->name('type.index');
        Route::get('/type/create/{id}', 'TypesController@create');
        Route::get('/user/edit', function () {
            return redirect('/user/edit/' . Auth::user()->id);
        });
        Route::get('/ajaxlogs/{ymd}', 'AdminController@getLogByYmd');
        Route::get('/pdlist/getlists/{type}', 'ProductListController@getlists');
        Route::get('/estimates/{type}', 'EstimateController@getlists');
        Route::get('/logs', 'AdminController@logsindex');
        Route::get('/logs/{ym}', 'AdminController@logsindex');
        Route::get('/logs/{ym}/{action}', 'AdminController@logsindex');
        Route::get('/exps', 'EventController@exps');
        // post
        Route::post('/user/uploadprofile', 'UserController@updateprofile');
        Route::post('/user/update', 'UserController@update');
        Route::post('/user/create', 'UserController@store');
        Route::post('/type/create_do', 'TypesController@create_do');
        Route::post('/type/updatetypes', 'TypesController@updatetypes');
        Route::post('/type/updatestatus', 'TypesController@updatestatus');
        Route::post('/uploadimg', 'AdminController@updateImage');
        Route::post('/event/complete', 'EventController@complete');
        Route::post('/event/received', 'EventController@setReceived');
        Route::post('/event/batchattendance', 'EventController@batchAttendance');
        Route::post('/event/search', 'EventController@search');
    });
    // Route::post('logout', 'LoginController@logout');
});
