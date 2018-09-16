<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\kegiatan;
use App\Model\berkas;
use App\Model\tipe_kegiatan;
use App\Model\kategori_tipe_kegiatan;
use App\Model\pegawai;
use App\Model\peneliti;
use App\Model\peneliti_nonpsb;
use App\Model\peneliti_psb;
use App\Model\peserta_kegiatan;
use App\User;

class ApiKegiatanController extends Controller
{
  public function tesGet(User $user, $id)
  {
      $user = $user->find($id)->id_pegawai;

      return response()->json([
        'success'=>true,
        'message'=>"asdf",
        'data'=>$user
      ]);
  }

  public function tesPostgambar(Request $request){
    if($request!=NULL){
      return response()->json([
        'success'=>true,
        'message'=>"berhasil cok"
      ]);
    }
    else {
      return response()->json([
        'success'=>true,
        'message'=>"mamam"
      ]);
    }
  }

  public function getKegiatan(User $user, $id)
    {
        $id_pegawai = $user->find($id)->id_pegawai;
        $peneliti_psb = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti_psb->id_peneliti;
        $pesertas = peserta_kegiatan::where([['status_konfirm', 'setuju'],['id_peneliti',$id_peneliti]])->get();
        $filter = "all";
        $collection = [];
        $countpeserta = $pesertas->count();
        if($countpeserta==0){
        	$kegiatans = null;
        }
        else {
        	foreach ($pesertas as $peserta) {
        		$kegiatans[] = $peserta->kegiatan;
        		$tanggal[] = strtotime($peserta->kegiatan->tanggal_awal);
        	}
          array_multisort($tanggal, SORT_DESC, $kegiatans);
        }
        $tipekegiatans = tipe_kegiatan::all();

        foreach($kegiatans as $kegiatan) {
          if($kegiatan->id_tipe_kegiatan==1) {
            $kegiatan->id_tipe_kegiatan = "Penelitian";
          }
          elseif ($kegiatan->id_tipe_kegiatan==2) {
            $kegiatan->id_tipe_kegiatan = "Kerjasama";
          }
          elseif ($kegiatan->id_tipe_kegiatan==3) {
            $kegiatan->id_tipe_kegiatan="Pengabdian";
          }
          elseif ($kegiatan->id_tipe_kegiatan==4) {
            $kegiatan->id_tipe_kegiatan="Seminar Ilmiah";
          }
        }

        return response()->json([
          'success'=>true,
          'message'=>"asdf",
          'tipekegiatans'=>$tipekegiatans,
          'kegiatans'=>$kegiatans,
          'filter'=>$filter
        ]);
    }


    public function detailKegiatan(User $user, $id, $id_k)
    {
      $id_pegawai = $user->find($id)->id_pegawai;
        $peneliti_psb = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti_psb->id_peneliti;
        $berkas = berkas::where('id_kegiatan',$id_k)->first();

        $kegiatan = kegiatan::join('tipe_kegiatan', 'tipe_kegiatan.id', '=', 'kegiatan.id_tipe_kegiatan')
        ->where('kegiatan.id', $id_k)
        ->first();
          $penelitis=peserta_kegiatan::with(['peneliti'=>function($k){
            $k->with(['peneliti_psb'=>function($q){
              $q->with(['pegawai']);
            }])->with(['peneliti_nonpsb']);
          }])->where('id_kegiatan',$id_k)->get();

          return response()->json([
            'success'=>true,
            'message'=>"asdf",
            'kegiatans'=>$kegiatan,
            'penelitis'=>$penelitis,
            'berkas'=>$berkas
          ]);
    }

    public function filterKegiatan(Request $request, User $user, $id, $id_filter){
    	$tipekegiatans = tipe_kegiatan::all();
    	$filter = $id_filter;
    	$id_pegawai = $user->find($id)->id_pegawai;
   		$peneliti_psb = peneliti_psb::where('id_pegawai', $id_pegawai)->first();
   		$id_peneliti = $peneliti_psb->id_peneliti;
   		$pesertas = peserta_kegiatan::where([['status_konfirm', 'setuju'],['id_peneliti',$id_peneliti]])->get();
   		$countpeserta = $pesertas->count();
    	if($filter==0){
        	if($countpeserta==0){
        	$kegiatans = null;
	        }
	        else {
	        	foreach ($pesertas as $peserta) {
	        		$kegiatans[] = $peserta->kegiatan;
	        		$tanggal[] = strtotime($peserta->kegiatan->tanggal_awal);
	        	}
	        	array_multisort($tanggal, SORT_DESC, $kegiatans);

            foreach($kegiatans as $kegiatan) {
              if($kegiatan->id_tipe_kegiatan==1) {
                $kegiatan->id_tipe_kegiatan = "Penelitian";
              }
              elseif ($kegiatan->id_tipe_kegiatan==2) {
                $kegiatan->id_tipe_kegiatan = "Kerjasama";
              }
              elseif ($kegiatan->id_tipe_kegiatan==3) {
                $kegiatan->id_tipe_kegiatan="Pengabdian";
              }
              elseif ($kegiatan->id_tipe_kegiatan==4) {
                $kegiatan->id_tipe_kegiatan="Seminar Ilmiah";
              }
            }
	        }
          return response()->json([
            'success'=>true,
            'message'=>"asdf",
            'kegiatans'=>$kegiatans,
            'tipekegiatans'=>$tipekegiatans,
            'filter'=>$filter
          ]);
        }
        else{
        	if($countpeserta==0){
        		$kegiatans = null;
	        }
	        else {
	        	foreach ($pesertas as $peserta) {
	        		if($peserta->kegiatan->id_tipe_kegiatan==$filter){
		        		$kegiatans[] = $peserta->kegiatan;
		        		$tanggal[] = strtotime($peserta->kegiatan->tanggal_awal);
	        		}

	        	}
	        	array_multisort($tanggal, SORT_DESC, $kegiatans);
            foreach($kegiatans as $kegiatan) {
              if($kegiatan->id_tipe_kegiatan==1) {
                $kegiatan->id_tipe_kegiatan = "Penelitian";
              }
              elseif ($kegiatan->id_tipe_kegiatan==2) {
                $kegiatan->id_tipe_kegiatan = "Kerjasama";
              }
              elseif ($kegiatan->id_tipe_kegiatan==3) {
                $kegiatan->id_tipe_kegiatan="Pengabdian";
              }
              elseif ($kegiatan->id_tipe_kegiatan==4) {
                $kegiatan->id_tipe_kegiatan="Seminar Ilmiah";
              }
            }
	        }
			// $kegiatans = kegiatan::join('peserta_kegiatan', 'peserta_kegiatan.id_kegiatan', '=', 'kegiatan.id')
			// ->where('id_peneliti', $id_peneliti)
			// ->where('id_tipe_kegiatan', $filter)->paginate(5);
      return response()->json([
        'success'=>true,
        'message'=>"asdf",
        'kegiatans'=>$kegiatans,
        'tipekegiatans'=>$tipekegiatans,
        'filter'=>$filter
      ]);
        }

    }

    public function singleTipekegiatan($id_kegiatan){
      $tk = tipe_kegiatan::where('id',$id_kegiatan)->first();
      $perans = $tk->peran;
      switch($id_kegiatan){
        case 1:
          $kategoris = kategori_tipe_kegiatan::where('id_tipe_kegiatan', $id_kegiatan)->select('keterangan', 'id')->get();
          return response()->json([
            'success'=>true,
            'message'=>"asdf",
            'perans'=>$perans,
            'kategoris'=>$kategoris
          ]);
        case 2:
          $kategoris = kategori_tipe_kegiatan::where('id_tipe_kegiatan', $id_kegiatan)->select('keterangan', 'id')->get();
          return response()->json([
            'success'=>true,
            'message'=>"asdf",
            'perans'=>$perans,
            'kategoris'=>$kategoris
          ]);
        case 3:
          $kategoris = kategori_tipe_kegiatan::where('id_tipe_kegiatan', $id_kegiatan)->select('keterangan', 'id')->get();
          return response()->json([
            'success'=>true,
            'message'=>"asdf",
            'perans'=>$perans,
            'kategoris'=>$kategoris
          ]);
        case 4:
          $kategoris = kategori_tipe_kegiatan::where('id_tipe_kegiatan', $id_kegiatan)->select('keterangan', 'id')->get();
          return response()->json([
            'success'=>true,
            'message'=>"asdf",
            'perans'=>$perans,
            'kategoris'=>$kategoris
          ]);
      }
    }

    public function tambahKegiatan(Request $request, User $user, $id, $id_k)
    {
      $id_pegawai = $user->find($id)->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
	    $tipe_kegiatan = tipe_kegiatan::find($id_k);
	    $nama = $request['nama'];
		$keterangan = $request['keterangan'];
		$lokasi = $request['lokasi'];
		$tgl_awal = $request['tglawal'];
		$tgl_akhir = $request['tglakhir'];
		$peran = $request['peran'];
		$kategori = $request['kategori'];
		$instansi = $request['instansi'];

		if($tipe_kegiatan->id==2){
			$kegiatan = kegiatan::create([
		    	'id_tipe_kegiatan' => $id_k,
		    	'nama_kegiatan' => $nama,
		    	'tanggal_awal' => $tgl_awal,
		    	'tanggal_akhir' => $tgl_akhir,
		    	'instansi' => $instansi,
		    	'id_kategori_tipe_kegiatan'=>$kategori
		    ]);
		    $id_kegiatan = $kegiatan->id;
		    peserta_kegiatan::create([
		    	'id_peneliti' => $id_peneliti,
		    	'id_kegiatan'=> $id_kegiatan,
		    	'status_konfirm'=> 'setuju',
		    	'id_peran'=> $peran
		    ]);
		    $notification = array('title'=> 'Berhasil!','msg'=>$nama.' berhasil ditambahkan!','alert-type'=>'success');
        return response()->json([
          'success'=>true,
          'message'=>"Kegiatan berhasil ditambahkan"
        ]);
		}

	    if($tipe_kegiatan->dokumentasi == 'ya')
	    {
			$kegiatan = kegiatan::create([
		    	'id_tipe_kegiatan' => $id_k,
		    	'nama_kegiatan' => $nama,
		    	'tanggal_awal' => $tgl_awal,
		    	'tanggal_akhir' => $tgl_akhir,
		    	'keterangan' => $keterangan,
		    	'lokasi' => $lokasi,
		    	'id_kategori_tipe_kegiatan'=>$kategori
		    ]);
		    $id_kegiatan = $kegiatan->id;
		    if($request->foto!=null){
			    $foto = $request->file('foto');
			    $path = "fotoku";
			    $foto->move($tipe_kegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/foto', "fotoku");
			    berkas::create([
			        'id_tipe_berkas' => 5,
			        'nama_berkas' => $path,
			        'id_kegiatan' => $id_kegiatan,

			    ]);
			}
		    peserta_kegiatan::create([
		    	'id_peneliti' => $id_peneliti,
		    	'id_kegiatan'=> $id_kegiatan,
		    	'status_konfirm'=> 'setuju',
		    	'id_peran'=> $peran
		    ]);
		    $notification = array('title'=> 'Berhasil!','msg'=>'Kegiatan berhasil ditambahkan!','alert-type'=>'success');
        return response()->json([
          'success'=>true,
          'message'=>"Kegiatan berhasil ditambahkan"
        ]);
	    }
		else
		{
			$kegiatan = kegiatan::create([
		    	'id_tipe_kegiatan' => $id_k,
		    	'nama_kegiatan' => $nama,
		    	'tanggal_awal' => $tgl_awal,
		    	'tanggal_akhir' => $tgl_akhir,
		    	'id_kategori_tipe_kegiatan'=>$kategori
		    ]);
		    $id_kegiatan = $kegiatan->id;
		    peserta_kegiatan::create([
		    	'id_peneliti' => $id_peneliti,
		    	'id_kegiatan'=> $id_kegiatan,
		    	'status_konfirm'=> 'setuju',
		    	'id_peran'=> $peran
		    ]);
		    $notification = array('title'=> 'Berhasil!','msg'=>$nama.' berhasil ditambahkan!','alert-type'=>'success');
        return response()->json([
          'success'=>true,
          'message'=>"Kegiatan berhasil ditambahkan"
        ]);
		}
    }
    public function vieweditkegiatan(User $user, $id, $id_kegiatan){
      $id_pegawai = $user->find($id)->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
      $kegiatan = kegiatan::find($id_kegiatan);
      $tipekegiatan = $kegiatan->tipe_kegiatan;
      $tk = tipe_kegiatan::where('id',$kegiatan->tipe_kegiatan->id)->first();
        $perans = $tk->peran;
        $kategoris = $tk->kategori_tipe_kegiatan;
        $peran_terpilih = peserta_kegiatan::where([['id_kegiatan',$id_kegiatan],['id_peneliti', $id_peneliti]])->select('id_peran')->first();
        $kategori_terpilih = kegiatan::where('id', $id_kegiatan)->select('id_kategori_tipe_kegiatan')->first();
      $berkas = berkas::where([['id_kegiatan', $id_kegiatan],['id_tipe_berkas', 5]])->first();

      return response()->json([
        'success'=>true,
        'message'=>"asdf",
        'kegiatan'=>$kegiatan,
        'kategoris'=>$kategoris,
        'kategori_terpilih'=>$kategori_terpilih,
        'tipekegiatan'=>$tipekegiatan,
        'berkas'=>$berkas,
        'peran_terpilih'=>$peran_terpilih,
        'perans'=>$perans
      ]);

    }

    public function editKegiatan(Request $request, User $user, $id_u, $id_k)
      {
      	$id_pegawai = $user->find($id_u)->id_pegawai;
          $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
          $id_peneliti = $peneliti->id_peneliti;
      	$kegiatan = kegiatan::find($id_k);
      	$id_kegiatan = $kegiatan->id;
      	$berkas = berkas::where('id_kegiatan',$id_kegiatan)->first();
      	$tipekegiatan = $kegiatan->tipe_kegiatan;
      	$tipeberkas = $tipekegiatan->tipe_berkas;
      	$nama = $request['nama'];
  		$keterangan = $request['keterangan'];
  		$lokasi = $request['lokasi'];
  		$tgl_awal = $request['tglawal'];
  		$tgl_akhir = $request['tglakhir'];
  		$peran = $request['peran'];
  		$kategori = $request['kategori'];
  		$instansi = $request['instansi'];

  		if($kegiatan->id_tipe_kegiatan==2){
  			kegiatan::where('id',$id_kegiatan)->update([
  		    	'nama_kegiatan' => $nama,
  		    	'tanggal_awal' => $tgl_awal,
  		    	'tanggal_akhir' => $tgl_akhir,
  		    	'instansi' => $instansi,
  		    	'id_kategori_tipe_kegiatan'=>$kategori
  		    ]);
  		    peserta_kegiatan::where([['id_kegiatan',$id_kegiatan],['id_peneliti', $id_peneliti]])->update([
  		    	'id_peran'=> $peran
  		    ]);
  		    $notification = array('title'=> 'Berhasil!', 'msg'=>'data'. $nama.' berhasil diubah!','alert-type'=>'success');
          return response()->json([
            'success'=>true,
            'message'=>"Kegiatan berhasil diedit"
          ]);
  		}

      	if($tipekegiatan->dokumentasi == 'ya')
  	    {
  			kegiatan::where('id',$id_kegiatan)->update([
  		    	'nama_kegiatan' => $nama,
  		    	'tanggal_awal' => $tgl_awal,
  		    	'tanggal_akhir' => $tgl_akhir,
  		    	'keterangan' => $keterangan,
  		    	'lokasi' => $lokasi,
  		    	'id_kategori_tipe_kegiatan'=>$kategori
  		    ]);
  		    peserta_kegiatan::where([['id_kegiatan',$id_kegiatan],['id_peneliti', $id_peneliti]])->update([
  		    	'id_peran'=> $peran
  		    ]);
  		    if($request->foto!=null && $berkas==null){
  			    $foto = $request->file('foto');
  			    $path = $foto->getClientOriginalName();
  			    $foto->move($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/foto', $foto->getClientOriginalName());
  			    berkas::create([
  			        'id_tipe_berkas' => 5,
  			        'nama_berkas' => $path,
  			        'id_kegiatan' => $id_kegiatan

  			    ]);

  			}
  			elseif($request->foto!=null && $berkas!=null) {
  				$foto = $request->file('foto');
  			    $path = $foto->getClientOriginalName();
  			    $foto->move($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/foto', $foto->getClientOriginalName());
  			    berkas::where('id_kegiatan',$id_kegiatan)->update([
  			        'id_tipe_berkas' => 5,
  			        'nama_berkas' => $path
  			    ]);
  			}

  		    $notification = array('title'=> 'Berhasil!','msg'=>'Kegiatan berhasil diedit!','alert-type'=>'success');
          return response()->json([
            'success'=>true,
            'message'=>"Kegiatan berhasil diedit"
          ]);
  	    }
  		else
  		{
  			kegiatan::where('id',$id_kegiatan)->update([
  		    	'nama_kegiatan' => $nama,
  		    	'tanggal_awal' => $tgl_awal,
  		    	'tanggal_akhir' => $tgl_akhir,
  		    	'id_kategori_tipe_kegiatan'=>$kategori
  		    ]);
  		    peserta_kegiatan::where([['id_kegiatan',$id_kegiatan],['id_peneliti', $id_peneliti]])->update([
  		    	'id_peran'=> $peran
  		    ]);
  		    $notification = array('title'=> 'Berhasil!','msg'=>'Kegiatan berhasil diedit!','alert-type'=>'success');
          return response()->json([
            'success'=>true,
            'message'=>"Kegiatan berhasil diedit"
          ]);
  		}

      }



}
