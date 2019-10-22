<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Login
Route::post('/login', 'ApiAuthController@login');
Route::get('/tesget/{id}', 'ApiKegiatanController@tesGet')->middleware('auth:api');

// Home
Route::get('/getkegiatan/{id}', 'ApiKegiatanController@getKegiatan')->middleware('auth:api');
Route::get('/detailkegiatan/{id}/{id_k}', 'ApiKegiatanController@detailKegiatan')->middleware('auth:api');

// Kegiatan
Route::get('/singletipekegiatan/{id_kegiatan}', 'ApiKegiatanController@singleTipekegiatan')->middleware('auth:api');
Route::post('/tambahkegiatan/{id_user}/{id_kegiatan}', 'ApiKegiatanController@tambahKegiatan')->middleware('auth:api');
Route::post('/tespostgambar', 'ApiKegiatanController@tesPostgambar')->middleware('auth:api');
Route::get('/filterkegiatan/{id}/{id_filter}', 'ApiKegiatanController@filterKegiatan')->middleware('auth:api');

Route::put('/editkegiatan/{id_u}/{id_k}', 'ApiKegiatanController@editKegiatan')->middleware('auth:api');

// Berkas
Route::get('/getberkas/{id}/{id_k}', 'ApiBerkasController@viewberkas')->middleware('auth:api');
Route::get('/penulispsb/{id}', 'ApiBerkasController@findpsb')->middleware('auth:api');
Route::post('/tambahnonpsb', 'ApiBerkasController@TambahNonPSB')->middleware('auth:api');

// Pub jurnal
Route::post('/tambahpubjurnal/{id}', 'ApiPubJurnalController@tambahPubjurnal')->middleware('auth:api');
Route::get('/getpubjurnal/{id_u}/{id_j}', 'ApiPubJurnalController@getPubjurnal')->middleware('auth:api');
Route::put('/editpubjurnal/{id}', 'ApiPubJurnalController@editPubjurnal')->middleware('auth:api');

// Pub buku
Route::post('/tambahpubbuku/{id}', 'ApiPubBukuController@tambahPubbuku')->middleware('auth:api');
Route::get('/getpubbuku/{id_u}/{id_b}', 'ApiPubBukuController@getPubbuku')->middleware('auth:api');
Route::put('/editpubbuku/{id}', 'ApiPubBukuController@editPubbuku')->middleware('auth:api');


// Edit
Route::get('/vieweditkegiatan/{id}/{id_k}', 'ApiKegiatanController@vieweditkegiatan')->middleware('auth:api');

// Profil
Route::get('/profil/{id}', 'ApiProfilController@getProfil')->middleware('auth:api');
Route::put('/editusername/{id_u}','ApiProfilController@editusername');
Route::put('/editpassword/{id_u}','ProfilController@editpassword');

// PDF
Route::get('/getPDF/{id}', 'ApiPDFController@getPDF');