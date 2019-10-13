<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\publikasi_jurnal;
use App\Model\peserta_publikasi_jurnal;
use App\Model\peneliti_psb;
use App\Model\peneliti_nonpsb;
use App\Model\pegawai;
use App\User;
use Auth;


class PubjurnalController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function getPubjurnal($id){
		$pubjurnal = publikasi_jurnal::find($id);
		$id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
		$psb = peneliti_psb::join('pegawai', 'peneliti_psb.id_pegawai', '=', 'pegawai.id')->where('pegawai.peran',1)
		->where('pegawai.id','!=', auth::user()->id_pegawai)->select('peneliti_psb.id_peneliti', 'pegawai.nama')->get();
    	$nonpsb = peneliti_nonpsb::all();

  		$pesertas = peserta_publikasi_jurnal::with(['peneliti'=>function($q){
    			$q->with(['peneliti_psb'])->with(['peneliti_nonpsb']);
    		}])->where('id_publikasi_jurnal',$id)->where('id_peneliti','!=', $id_peneliti)->get();
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

		return view('peneliti.editpubjurnal', ['pubjurnal'=>$pubjurnal, 'penelitipsb_terpilih'=>$penelitipsb_terpilih, 'penelitinonpsb_terpilih'=>$penelitinonpsb_terpilih, 'psb'=>$psb, 'nonpsb'=>$nonpsb]);
	}

	public function detailPubjurnal($id)
    {
        $pubjurnal = publikasi_jurnal::where('id', $id)->first();

	      $penelitis=peserta_publikasi_jurnal::with(['peneliti'=>function($k){
	        $k->with(['peneliti_psb'=>function($q){
	          $q->with(['pegawai']);
	        }])->with(['peneliti_nonpsb']);
	      }])->where('id_publikasi_jurnal',$id)->get();

        return view('peneliti.detailpubjurnal', ['pubjurnal'=>$pubjurnal, 'penelitis'=>$penelitis]);
    }

	public function tambahPubjurnal(Request $request)
	{

		$id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
		$judul = $request['judul'];
		$status_akreditasi = $request['status_akreditasi'];
		$namaberkala = $request['namaberkala'];
		$volume = $request['volume'];
		$url = $request['url'];
		$tahunterbit =$request['tahun'];
		$pubjurnal =  publikasi_jurnal::create([
	    	'judul_artikel' => $judul,
	    	'status_akreditasi' => $status_akreditasi,
	    	'tahun_terbit' => $tahunterbit,
	    	'nama_berkala' => $namaberkala,
	    	'volume_halaman' => $volume,
	    	'url' => $url,
	    	'tahun' => $tahunterbit 
	    ]);

	    $idpubjurnal = $pubjurnal->id;
	    peserta_publikasi_jurnal::create([
	    	'id_peneliti' => $id_peneliti,
	    	'id_publikasi_jurnal'=> $idpubjurnal,
	    	'status_konfirm'=>'setuju'
	    ]);
	    //ada req psb dan nonpsb
	    if($request->psb!=null && $request->nonpsb!=null){
		    foreach ($request->psb as $index => $psb) {
		    	$psb = (int)$psb;
		    	$peneliti = peneliti_psb::where('id_peneliti',$psb)->first();
		    	$id_peneliti = $peneliti->id_peneliti;
		    	peserta_publikasi_jurnal::create([
		    		'id_peneliti' => $id_peneliti,
		    		'id_publikasi_jurnal'=> $idpubjurnal,
		    		'status_konfirm'=>'menunggu'
		    	]);
		    }

		    foreach ($request->nonpsb as $index => $nonpsb) {
		    	$nonpsb = (int)$nonpsb;
		    	peserta_publikasi_jurnal::create([
		    		'id_peneliti' => $nonpsb,
		    		'id_publikasi_jurnal'=> $idpubjurnal
		    	]);
		    }
		    $notification = array('title'=> 'Berhasil!', 'msg'=>'Publikasi jurnal berhasil ditambahkan!','alert-type'=>'success');
			return redirect('/profil')->with($notification);
		}

		//ada req nonpsb
		elseif ($request->psb==null && $request->nonpsb!=null) {
			foreach ($request->nonpsb as $index => $nonpsb) {
		    	$nonpsb = (int)$nonpsb;
		    	peserta_publikasi_jurnal::create([
		    		'id_peneliti' => $nonpsb,
		    		'id_publikasi_jurnal'=> $idpubjurnal
		    	]);
		    }
		    $notification = array('title'=> 'Berhasil!', 'msg'=>'Publikasi jurnal berhasil ditambahkan!','alert-type'=>'success');
			return redirect('/profil')->with($notification);
		}
		//ada req psb
		elseif ($request->psb!=null && $request->nonpsb==null) {
			foreach ($request->psb as $index => $psb) {
		    	$psb = (int)$psb;
		    	$peneliti = peneliti_psb::where('id_peneliti',$psb)->first();
		    	$id_peneliti = $peneliti->id_peneliti;
		    	peserta_publikasi_jurnal::create([
		    		'id_peneliti' => $id_peneliti,
		    		'id_publikasi_jurnal'=> $idpubjurnal,
		    		'status_konfirm'=>'menunggu'
		    	]);
		    }
		    $notification = array('title'=> 'Berhasil!', 'msg'=>'Publikasi jurnal berhasil ditambahkan!','alert-type'=>'success');
			return redirect('/profil')->with($notification);
		}
		else{
			 $notification = array('title'=> 'Berhasil!', 'msg'=>'Publikasi jurnal berhasil ditambahkan!','alert-type'=>'success');
			return redirect('/')->with($notification);
		}
	   
	}

	public function editPubjurnal(Request $request)
	{
		$id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
		$id_pubjurnal = $request['id_pubjurnal'];
        $judul = $request['judul'];
		$status_akreditasi = $request['status_akreditasi'];
		$namaberkala = $request['namaberkala'];
		$volume = $request['volume'];
		$url = $request['url'];
		$tahunterbit =$request['tahun'];
		$pesertas = peserta_publikasi_jurnal::with(['peneliti'=>function($q){
    			$q->with(['peneliti_psb'])->with(['peneliti_nonpsb']);
    		}])->where('id_publikasi_jurnal',$id_pubjurnal)->where('id_peneliti','!=', $id_peneliti)->get();


		publikasi_jurnal::where('id', $id_pubjurnal)->update([
	    	'judul_artikel' => $judul,
	    	'status_akreditasi' => $status_akreditasi,
	    	'tahun_terbit' => $tahunterbit,
	    	'nama_berkala' => $namaberkala,
	    	'volume_halaman' => $volume,
	    	'url' => $url,
	    	'tahun_terbit' => $tahunterbit 
	    ]);

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
		    		peserta_publikasi_jurnal::where([['id_peneliti',$hapuspsb->id_peneliti],['id_publikasi_jurnal',$id_pubjurnal]])->delete();
		    	}

				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_publikasi_jurnal::create([
			    		'id_peneliti' => $psb,
			    		'id_publikasi_jurnal'=>$id_pubjurnal,
			    		'status_konfirm'=>'menunggu'
			    	]);
			    }
			}
			else{
				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_publikasi_jurnal::create([
			    		'id_peneliti' => $psb,
			    		'id_publikasi_jurnal'=>$id_pubjurnal,
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
		    		peserta_publikasi_jurnal::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_publikasi_jurnal',$id_pubjurnal]])->delete();
		    	}

		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_publikasi_jurnal::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_publikasi_jurnal'=>$id_pubjurnal
			    	]);
		    	}
		    }
		    else{
		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_publikasi_jurnal::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_publikasi_jurnal'=>$id_pubjurnal
			    	]);
		    	}
		    }
		    $countnonpsb=0;

		    $notification = array('title'=> 'Berhasil!','msg'=>'Publikasi jurnal berhasil diedit!','alert-type'=>'success');
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
		    		peserta_publikasi_jurnal::where([['id_peneliti',$hapuspsb->id_peneliti],['id_publikasi_jurnal',$id_pubjurnal]])->delete();
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
		    		peserta_publikasi_jurnal::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_publikasi_jurnal',$id_pubjurnal]])->delete();
		    	}

		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_publikasi_jurnal::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_publikasi_jurnal'=>$id_pubjurnal
			    	]);
		    	}
		    }
		    else{
		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_publikasi_jurnal::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_publikasi_jurnal'=>$id_pubjurnal
			    	]);
		    	}
		    }
		    $countnonpsb =0;

		    
		    $notification = array('title'=> 'Berhasil!','msg'=>'Publikasi jurnal berhasil diedit!','alert-type'=>'success');
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
		    		peserta_publikasi_jurnal::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_publikasi_jurnal',$id_pubjurnal]])->delete();
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
		    		peserta_publikasi_jurnal::where([['id_peneliti',$hapuspsb->id_peneliti],['id_publikasi_jurnal',$id_pubjurnal]])->delete();
		    	}

				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_publikasi_jurnal::create([
			    		'id_peneliti' => $psb,
			    		'id_publikasi_jurnal'=>$id_pubjurnal,
			    		'status_konfirm'=>'menunggu'
			    	]);
			    }
			}
			else{
				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_publikasi_jurnal::create([
			    		'id_peneliti' => $psb,
			    		'id_publikasi_jurnal'=>$id_pubjurnal,
			    		'status_konfirm'=>'menunggu'
			    	]);
			    }
			}
			$countpsb = 0;

		    $notification = array('title'=> 'Berhasil!','msg'=>'Publikasi jurnal berhasil diedit!','alert-type'=>'success');
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
		    		peserta_publikasi_jurnal::where([['id_peneliti',$hapuspsb->id_peneliti],['id_publikasi_jurnal',$id_pubjurnal]])->delete();
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
		    		peserta_publikasi_jurnal::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_publikasi_jurnal',$id_pubjurnal]])->delete();
		    	}
		    }
		    $countnonpsb=0;

			$notification = array('title'=> 'Berhasil!','msg'=>'Publikasi jurnal berhasil diedit!','alert-type'=>'success');
		    return redirect('/profil')->with($notification);
		}
	}

	

	public function hapusPubjurnal($id)
	{
		publikasi_jurnal::find($id)->delete();
		peserta_publikasi_jurnal::where('id_publikasi_jurnal',$id)->delete();
		$notification = array('title'=> 'Berhasil!', 'msg'=>'Publikasi jurnal berhasil dihapus!','alert-type'=>'success');
		return redirect('/profil')->with($notification);
	}
}
