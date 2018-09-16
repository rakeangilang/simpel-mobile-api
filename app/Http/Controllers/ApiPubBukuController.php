<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\publikasi_buku;
use App\Model\peserta_publikasi_buku;
use App\Model\peneliti_psb;
use App\Model\peneliti_nonpsb;
use App\Model\pegawai;
use App\User;

class ApiPubBukuController extends Controller
{

  public function getPubbuku(User $user, $id_u, $id_b){
  $pubbuku = publikasi_buku::find($id_b);
  $id_pegawai = $user->find($id_u)->id_pegawai;
      $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
      $id_peneliti = $peneliti->id_peneliti;
      $psb = peneliti_psb::join('pegawai', 'peneliti_psb.id_pegawai', '=', 'pegawai.id')->where('pegawai.peran',1)
  ->where('pegawai.id','!=', $id_pegawai)->select('peneliti_psb.id_peneliti', 'pegawai.nama')->get();
    $nonpsb = peneliti_nonpsb::all();
  $pesertas = peserta_publikasi_buku::with(['peneliti'=>function($q){
        $q->with(['peneliti_psb'])->with(['peneliti_nonpsb']);
      }])->where('id_publikasi_buku',$id_b)->where('id_peneliti','!=', $id_peneliti)->get();
    $countpsb =0;
    $countnonpsb =0;
    //cek isi peserta terpilih
    foreach ($pesertas as $peserta) {
    if($peserta->peneliti->peneliti_psb!=null){
      $peneliti_psb[] = $peserta->peneliti->peneliti_psb;
      $countpsb+=1;
    }
    elseif ($peserta->peneliti->peneliti_nonpsb!=null) {
      $peneliti_nonpsb[] = $peserta->peneliti->peneliti_nonpsb;
      $countnonpsb +=1;
    }
  }

  //psb terpilih
  if($countpsb>0){
    foreach ($peneliti_psb as $penelitipsb) {
      $penelitipsb_terpilih[] = $penelitipsb;
    }
  }
  else{
    $penelitipsb_terpilih = null;
  }
  $countpsb=0;

  //nonpsb terpilih
  if($countnonpsb>0){
    foreach ($peneliti_nonpsb as $penelitinonpsb) {
      $penelitinonpsb_terpilih[] = $penelitinonpsb;
    }
  }
  else{
    $penelitinonpsb_terpilih = null;

  }
  $countnonpsb=0;
  return response()->json([
    'success'=>true,
    'pubbuku'=>$pubbuku,
    'penelitipsb_terpilih'=>$penelitipsb_terpilih,
    'penelitinonpsb_terpilih'=>$penelitinonpsb_terpilih,
    'psb'=>$psb,
    'nonpsb'=>$nonpsb
  ]);
}

  public function tambahPubbuku(Request $request, User $user, $id)
  {
    $id_pegawai = $user->find($id)->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
    $judulbuku = $request['judulbuku'];
    $judulchapter = $request['judulchapter'];
    $tahun = $request['tahunterbit'];
    $namapenerbit = $request['namapenerbit'];
    $isbn = $request['isbn'];
    $pubbuku =  publikasi_buku::create([
        'judul_buku' => $judulbuku,
        'judul_book_chapter' => $judulchapter,
        'tahun_terbit' => $tahun,
        'nama_penerbit' => $namapenerbit,
        'isbn' => $isbn
      ]);
      $idpubbuku = $pubbuku->id;

      peserta_publikasi_buku::create([
        'id_peneliti' => $id_peneliti,
        'id_publikasi_buku'=> $idpubbuku,
        'status_konfirm'=> 'setuju'
      ]);
      //ada req psb dan nonpsb
      if($request->psb!=null && $request->nonpsb!=null){
        foreach ($request->psb as $index => $psb) {
          $psb = (int)$psb;
          $peneliti = peneliti_psb::where('id_peneliti',$psb)->first();
          $id_peneliti = $peneliti->id_peneliti;
          peserta_publikasi_buku::create([
            'id_peneliti' => $id_peneliti,
            'id_publikasi_buku'=> $idpubbuku,
            'status_konfirm'=> 'menunggu'
          ]);
        }

        foreach ($request->nonpsb as $index => $nonpsb) {
          $nonpsb = (int)$nonpsb;
          peserta_publikasi_buku::create([
            'id_peneliti' => $nonpsb,
            'id_publikasi_buku'=> $idpubbuku
          ]);
        }
        return response()->json([
          'success'=>true,
          'message'=>"Publikasi buku berhasil ditambahkan"
        ]);
    }
    //ada req nonpsb
    elseif ($request->psb==null && $request->nonpsb!=null) {
      foreach ($request->nonpsb as $index => $nonpsb) {
          $nonpsb = (int)$nonpsb;
          peserta_publikasi_buku::create([
            'id_peneliti' => $nonpsb,
            'id_publikasi_buku'=> $idpubbuku
          ]);
        }
        return response()->json([
          'success'=>true,
          'message'=>"Publikasi buku berhasil ditambahkan"
        ]);
    }
    //ada req psb
    elseif ($request->psb!=null && $request->nonpsb==null) {
      foreach ($request->psb as $index => $psb) {
          $psb = (int)$psb;
          $peneliti = peneliti_psb::where('id_peneliti',$psb)->first();
          $id_peneliti = $peneliti->id_peneliti;
          peserta_publikasi_buku::create([
            'id_peneliti' => $id_peneliti,
            'id_publikasi_buku'=> $idpubbuku,
            'status_konfirm'=> 'menunggu'
          ]);
        }
        return response()->json([
          'success'=>true,
          'message'=>"Publikasi buku berhasil ditambahkan"
        ]);
    }
    else{
      return response()->json([
        'success'=>true,
        'message'=>"Publikasi buku berhasil ditambahkan"
      ]);
    }
  }

  public function editPubbuku(Request $request, User $user, $id)
	{
		$id_pegawai = $user->find($id)->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
		$id_pubbuku = $request['id_pubbuku'];
        $judulbuku = $request['judulbuku'];
		$judulchapter = $request['judulchapter'];
		$tahun = $request['tahunterbit'];
		$namapenerbit = $request['namapenerbit'];
		$isbn = $request['isbn'];
		$pesertas = peserta_publikasi_buku::with(['peneliti'=>function($q){
    			$q->with(['peneliti_psb'])->with(['peneliti_nonpsb']);
    		}])->where('id_publikasi_buku',$id_pubbuku)->where('id_peneliti','!=', $id_peneliti)->get();


		publikasi_buku::where('id', $id_pubbuku)->update([
	    	'judul_buku' => $judulbuku,
	    	'judul_book_chapter' => $judulchapter,
	    	'tahun_terbit' => $tahun,
	    	'nama_penerbit' => $namapenerbit,
	    	'isbn' => $isbn
	    ]);

		$countnonpsb = 0;

		$countnonpsb = 0;
		$countpsb = 0;
		//ada req psb & nonpsb
		if($request->psb!=null && $request->nonpsb!=null){
			//hapus psb lalu dimasukkan psb yang baru
		    foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_nonpsb==null){
					$hapusspsb[] = $peserta->peneliti->peneliti_psb;
					$countpsb+=1;
				}
			}
			if($countpsb>0){
				foreach ($hapusspsb as $hapuspsb) {
		    		peserta_publikasi_buku::where([['id_peneliti',$hapuspsb->id_peneliti],['id_publikasi_buku',$id_pubbuku]])->delete();
		    	}

				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_publikasi_buku::create([
			    		'id_peneliti' => $psb,
			    		'id_publikasi_buku'=>$id_pubbuku,
			    		'status_konfirm'=>'menunggu'
			    	]);
			    }
			}
			else{
				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_publikasi_buku::create([
			    		'id_peneliti' => $psb,
			    		'id_publikasi_buku'=>$id_pubbuku,
			    		'status_konfirm'=>'menunggu'
			    	]);
			    }
			}
			$countpsb = 0;

		    //hapus nonpsb lalu dimasukkan nonpsb yang baru
		    foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_psb==null){
					$hapussnonpsb[] = $peserta->peneliti->peneliti_nonpsb;
					$countnonpsb +=1;
				}
			}
			if($countnonpsb>0){
				foreach ($hapussnonpsb as $hapusnonpsb) {
		    		peserta_publikasi_buku::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_publikasi_buku',$id_pubbuku]])->delete();
		    	}

		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_publikasi_buku::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_publikasi_buku'=>$id_pubbuku
			    	]);
		    	}
		    }
		    else{
		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_publikasi_buku::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_publikasi_buku'=>$id_pubbuku
			    	]);
		    	}
		    }
		    $countnonpsb=0;

		    $notification = array('title'=> 'Berhasil!','msg'=>'Publikasi buku berhasil diedit!','alert-type'=>'success');
        return response()->json([
          'success'=>true,
          'message'=>"Publikasi buku berhasil diedit"
        ]);
		}
		//ada req nonpsb
		elseif ($request->psb==null && $request->nonpsb!=null) {
			//hapus psb
			foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_nonpsb==null){
					$hapusspsb[] = $peserta->peneliti->peneliti_psb;
					$countpsb +=1;
				}
			}
			if($countpsb>0){
				foreach ($hapusspsb as $hapuspsb) {
		    		peserta_publikasi_buku::where([['id_peneliti',$hapuspsb->id_peneliti],['id_publikasi_buku',$id_pubbuku]])->delete();
		    	}
		    }
		    $countpsb=0;

		    //hapus nonpsb lalu dimasukkan nonpsb yang baru
			foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_psb==null){
					$hapussnonpsb[] = $peserta->peneliti->peneliti_nonpsb;
					$countnonpsb +=1;
				}
			}
			if($countnonpsb>0){
				foreach ($hapussnonpsb as $hapusnonpsb) {
		    		peserta_publikasi_buku::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_publikasi_buku',$id_pubbuku]])->delete();
		    	}

		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_publikasi_buku::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_publikasi_buku'=>$id_pubbuku
			    	]);
		    	}
		    }
		    else{
		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_publikasi_buku::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_publikasi_buku'=>$id_pubbuku
			    	]);
		    	}
		    }
		    $countnonpsb =0;


		    $notification = array('title'=> 'Berhasil!','msg'=>'Publikasi buku berhasil diedit!','alert-type'=>'success');
        return response()->json([
          'success'=>true,
          'message'=>"Publikasi buku berhasil diedit"
        ]);
		}
		//ada req psb
		elseif ($request->psb!=null && $request->nonpsb==null) {
			//hapus nonpsb
			foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_psb==null){
					$hapussnonpsb[] = $peserta->peneliti->peneliti_nonpsb;
					$countnonpsb +=1;
				}
			}
			if($countnonpsb>0){
				foreach ($hapussnonpsb as $hapusnonpsb) {
		    		peserta_publikasi_buku::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_publikasi_buku',$id_pubbuku]])->delete();
		    	}
		    }
		    $countnonpsb=0;

		    //hapus psb lalu dimasukkan psb yang baru
			foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_nonpsb==null){
					$hapusspsb[] = $peserta->peneliti->peneliti_psb;
					$countpsb+=1;
				}
			}
			if($countpsb>0){
				foreach ($hapusspsb as $hapuspsb) {
		    		peserta_publikasi_buku::where([['id_peneliti',$hapuspsb->id_peneliti],['id_publikasi_buku',$id_pubbuku]])->delete();
		    	}

				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_publikasi_buku::create([
			    		'id_peneliti' => $psb,
			    		'id_publikasi_buku'=>$id_pubbuku,
			    		'status_konfirm'=>'menunggu'
			    	]);
			    }
			}
			else{
				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_publikasi_buku::create([
			    		'id_peneliti' => $psb,
			    		'id_publikasi_buku'=>$id_pubbuku,
			    		'status_konfirm'=>'menunggu'
			    	]);
			    }
			}
			$countpsb = 0;

		    $notification = array('title'=> 'Berhasil!','msg'=>'Publikasi buku berhasil diedit!','alert-type'=>'success');
        return response()->json([
          'success'=>true,
          'message'=>"Publikasi buku berhasil diedit"
        ]);
		}
		else
		{
			//hapus psb
			foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_nonpsb==null){
					$hapusspsb[] = $peserta->peneliti->peneliti_psb;
					$countpsb +=1;
				}
			}
			if($countpsb>0){
				foreach ($hapusspsb as $hapuspsb) {
		    		peserta_publikasi_buku::where([['id_peneliti',$hapuspsb->id_peneliti],['id_publikasi_buku',$id_pubbuku]])->delete();
		    	}
		    }
		    $countpsb=0;

		    //hapus nonpsb
		    foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_psb==null){
					$hapussnonpsb[] = $peserta->peneliti->peneliti_nonpsb;
					$countnonpsb +=1;
				}
			}
			if($countnonpsb>0){
				foreach ($hapussnonpsb as $hapusnonpsb) {
		    		peserta_publikasi_buku::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_publikasi_buku',$id_pubbuku]])->delete();
		    	}
		    }
		    $countnonpsb=0;

			$notification = array('title'=> 'Berhasil!','msg'=>'Publikasi buku berhasil diedit!','alert-type'=>'success');
      return response()->json([
        'success'=>true,
        'message'=>"Publikasi buku berhasil diedit"
      ]);
		}

	}

}
