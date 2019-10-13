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

// menu
	
Route::get('/login', function () {
		// $gambar = base64_encode(realpath('C:\Users\Asus\Pictures\alifka.png'));
		// return view('auth.login',['gambar'=>$gambar]);
		return view('auth.login');
	});

Auth::routes();

Route::group(['middleware' => ['auth','revalidate']],function(){
	
	Route::get('/', 'KegiatanController@getKegiatan');
	Route::get('/penulispsb/find', 'BerkasController@findpsb');
	Route::get('/penulisnonpsb/find', 'BerkasController@findnonpsb');
	Route::post('/tambahNonPSB', 'BerkasController@tambahNonPSB');

	//kegiatan
	Route::get('/kegiatan/{id}', 'KegiatanController@singleTipekegiatan');
	Route::get('/vieweditkegiatan/{id}', 'KegiatanController@vieweditkegiatan');
	Route::post('/tambahKegiatan/{id}', 'KegiatanController@tambahKegiatan');
	Route::put('/editKegiatan/{id}', 'KegiatanController@editKegiatan');
	Route::get('/searchKegiatan', 'KegiatanController@searchKegiatan');
	Route::get('/filterKegiatan', 'KegiatanController@filterKegiatan');
	Route::get('/detailKegiatan/{id}', 'KegiatanController@detailKegiatan');

	//berkas
	Route::get('/berkas/{id}', 'BerkasController@viewberkas');
	Route::post('/tambahBerkas/{id}', 'BerkasController@tambahBerkas');
	Route::put('/editBerkas/{id}', 'BerkasController@editBerkas');


	//pubjurnal
	Route::get('/publikasijurnal', function () { return view('peneliti.publikasi_jurnal'); });
	Route::post('/tambahpubjurnal', 'PubjurnalController@tambahPubjurnal');
	Route::put('/editPubjurnal', 'PubjurnalController@editPubjurnal');
	Route::get('/getPubjurnal/{id}', 'PubjurnalController@getPubjurnal');
	Route::delete('/hapusPubjurnal/{id}', 'PubjurnalController@hapusPubjurnal');
	Route::get('/detailPubjurnal/{id}', 'PubjurnalController@detailPubjurnal');

	//pub_buku
	Route::get('/publikasibuku', function () { return view('peneliti.publikasi_buku'); });
	Route::post('/tambahpubbuku', 'PubbukuController@tambahPubbuku');
	Route::put('/editPubbuku', 'PubbukuController@editPubbuku');
	Route::get('/getPubbuku/{id}', 'PubbukuController@getPubbuku');
	Route::delete('/hapusPubbuku/{id}', 'PubbukuController@hapusPubbuku');
	Route::get('/detailPubbuku/{id}', 'PubbukuController@detailPubbuku');

	//kolaborasi
	Route::get('/getKolaborasi/{id}', 'KolaborasiController@getKolaborasi');
	Route::put('/setujukolaborasi', 'KolaborasiController@setujukolaborasi');
	Route::put('/menolakkolaborasi', 'KolaborasiController@menolakkolaborasi');

	//profil
	Route::get('/profil', 'ProfilController@getProfil');
	Route::get('/getpubbuku/{id}', 'ProfilController@getpubbuku');
	Route::post('/compareusername','ProfilController@compareusername');
	Route::put('/editusername','ProfilController@editusername');
	Route::post('/comparepass', 'ProfilController@comparepass');
	Route::put('/editpassword','ProfilController@editpassword');
	Route::get('/koneksi', 'ProfilController@koneksi');

	//penggunaan dana
	Route::get('/formDana/{id}', 'DanaController@getformdana');
	Route::post('/mengajukanDana', 'DanaController@mengajukanDana');
	Route::get('/getDana/{id}', 'DanaController@getDana');
	Route::get('/geteditdana/{id}', 'DanaController@singledana');
	Route::put('/editpengajuan', 'DanaController@editPengajuan');
	// Route::post('/tambahPengajuan', 'DanaController@tambahPengajuan');
	Route::delete('/hapusPengajuan/{id}', 'DanaController@hapusPengajuan');
	
	//CV
	Route::get('/getPDF', 'PDFController@getPDF');


});
// Route::get('/gambar', function(){
// 		$gambar = base64_encode(file_get_contents(realpath('C:\Users\Asus\Pictures\alifka.png')));
// 		return view('welcome',['gambar'=>$gambar]);
// 	});

