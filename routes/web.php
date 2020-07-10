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

// Route::get('/forum', 'ForumController@index'); // menampilkan semua
Route::get('/forum', function () {
    return view('forum.index');
});

Route::get('/forum/create', function () {
    return view('forum.create');
});

// Route::group(['middleware' => 'auth'], function(){

//     Route::get('/forum/create', 'ForumController@create'); // menampilkan halaman form
//     Route::post('/forum', 'ForumController@store'); // menyimpan data    
//     Route::get('/forum/{id}', 'ForumController@show'); // menampilkan detail item dengan id 
//     Route::get('/forum/{id}/edit', 'ForumController@edit'); // menampilkan form untuk edit item
//     Route::put('/forum/{id}', 'ForumController@update'); // menyimpan perubahan dari form edit
//     Route::delete('/forum/{id}', 'ForumController@destroy'); // menghapus data dengan id

// });

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');
