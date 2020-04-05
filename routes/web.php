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
Auth::routes();

Route::get('/', function () {
    // Only authenticated users may enter...
    return view('depan');
})->middleware('auth');

/*
Route::get('/', function () {
    return view('depan');
});
*/
Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'MasterController@index')->name('dashboard.index');
    Route::get('/pegawai/list', 'PegawaiController@index')->name('pegawai.list');
    Route::post('/pegawai/sync', 'PegawaiController@syncData')->name('pegawai.sync');
    Route::post('/cek/community', 'PegawaiController@cekCommunity')->name('cek.community');
});

//Route::get('logout', 'Auth\LoginController@logout');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
