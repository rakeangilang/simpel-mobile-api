<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\publikasi_buku;
use App\Model\peserta_publikasi_buku;
use App\Model\peneliti_psb;
use App\Model\peneliti_nonpsb;
use App\Model\pegawai;
use App\User;
use Auth;


class PubbukuController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function getPubbuku($id){
		$pubbuku = publikasi_buku::find($id);
		$id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
        $psb = peneliti_psb::join('pegawai', 'peneliti_psb.id_pegawai', '=', 'pegawai.id')->where('pegawai.peran',1)
		->where('pegawai.id','!=', auth::user()->id_pegawai)->select('peneliti_psb.id_peneliti', 'pegawai.nama')->get();
    	$nonpsb = peneliti_nonpsb::all();
		$pesertas = peserta_publikasi_buku::with(['peneliti'=>function($q){
    			$q->with(['peneliti_psb'])->with(['peneliti_nonpsb']);
    		}])->where('id_publikasi_buku',$id)->where('id_peneliti','!=', $id_peneliti)->get();
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
		return view('peneliti.editpubbuku', ['pubbuku'=>$pubbuku,'penelitipsb_terpilih'=>$penelitipsb_terpilih, 'penelitinonpsb_terpilih'=>$penelitinonpsb_terpilih, 'psb'=>$psb, 'nonpsb'=>$nonpsb]);
	}

	public function detailPubbuku($id)
    {
        $pubbuku = publikasi_buku::where('id', $id)->first();

	      $penelitis=peserta_publikasi_buku::with(['peneliti'=>function($k){
	        $k->with(['peneliti_psb'=>function($q){
	          $q->with(['pegawai']);
	        }])->with(['peneliti_nonpsb']);
	      }])->where('id_publikasi_buku',$id)->get();

        return view('peneliti.detailpubbuku', ['pubbuku'=>$pubbuku, 'penelitis'=>$penelitis]);
    }

	public function tambahPubbuku(Request $request)
	{
		$id_pegawai = auth::user()->id_pegawai;
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
		     $notification = array('title'=> 'Berhasil!', 'msg'=>'Publikasi buku berhasil ditambahkan!','alert-type'=>'success');
			return redirect('/profil')->with($notification);
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
		     $notification = array('title'=> 'Berhasil!', 'msg'=>'Publikasi buku berhasil ditambahkan!','alert-type'=>'success');
			return redirect('/profil')->with($notification);
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
		     $notification = array('title'=> 'Berhasil!', 'msg'=>'Publikasi buku berhasil ditambahkan!','alert-type'=>'success');
			return redirect('/profil')->with($notification);
		}
		else{
			 $notification = array('title'=> 'Berhasil!', 'msg'=>'Publikasi buku berhasil ditambahkan!','alert-type'=>'success');
			return redirect('/profil')->with($notification);
		}
	}

	public function editPubbuku(Request $request)
	{
		$id_pegawai = auth::user()->id_pegawai;
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
		    return redirect('/profil')->with($notification);
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
		    return redirect('/profil')->with($notification);
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
		    return redirect('/profil')->with($notification);
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
		    return redirect('/profil')->with($notification);
		}

	}

	public function hapusPubbuku($id)
	{
		$judulbuku=publikasi_buku::find($id)->judul_buku;
		publikasi_buku::find($id)->delete();
		$notification = array('title'=> 'Berhasil!', 'msg'=>$judulbuku.' berhasil dihapus!','alert-type'=>'success');
		return redirect('/profil')->with($notification);
	}

}
