<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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


Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('/pertanyaan', 'PertanyaanController@index');
Route::get('/pertanyaan/{id}', 'PertanyaanController@show');

Route::get('/pertanyaan', 'PertanyaanController@index');
Route::group(['middleware' => 'auth'], function() {
    
    Route::get('/pertanyaan/create', 'PertanyaanController@create');
    Route::get('/pertanyaan/{id}/edit', 'PertanyaanController@edit');
    Route::post('/pertanyaan', 'PertanyaanController@store');
    Route::put('/pertanyaan/{id}', 'PertanyaanController@update');
    Route::delete('/pertanyaan/{id}', 'PertanyaanController@delete');

    Route::get('/jawaban/{pertanyaan_id}', 'JawabanController@index');
    Route::post('/jawaban/{pertanyaan_id}', 'JawabanController@store');

    Route::post('/vote', 'PertanyaanController@vote')->name('vote');
    Route::post('/vote-jawaban', 'PertanyaanController@vote_jawaban')->name('vote-jawaban');
});
