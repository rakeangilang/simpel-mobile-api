<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\kegiatan;
use App\Model\berkas;
use App\Model\tipe_kegiatan;
use App\Model\pegawai;
use App\Model\peneliti;
use App\Model\peneliti_nonpsb;
use App\Model\peneliti_psb;
use App\Model\peserta_kegiatan;
use App\Model\peran;
use App\User;
use Auth;
use File;

class BerkasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function findpsb(Request $request)
    {
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $peneliti_psb = pegawai::join('peneliti_psb', 'peneliti_psb.id_pegawai', '=', 'pegawai.id')
        ->where('pegawai.peran',1)->where('pegawai.nama','LIKE','%'.$term.'%')->where('pegawai.id','!=', auth::user()->id_pegawai)->select('peneliti_psb.id_peneliti', 'pegawai.nama')->get();
        $formatted_tags = [];
        foreach ($peneliti_psb as $peneliti) {
            $formatted_tags[] = ['id' => $peneliti->id_peneliti, 'text' => $peneliti->nama];
        }
        return \Response::json($formatted_tags);
    }

    public function findnonpsb(Request $request)
    {
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = peneliti_nonpsb::where('nama_peneliti','LIKE','%'.$term.'%')->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id_peneliti, 'text' => $tag->nama_peneliti];
        }
        return \Response::json($formatted_tags);
    }

    public function TambahNonPSB(Request $request){
		$nama = $request['nama'];
		$no_identitas = $request['nomor'];
		$tipe_identitas = $request['tipe_identitas'];

		$peneliti = peneliti::create([
		]);

		peneliti_nonpsb::create([
			'id_peneliti'=>$peneliti->id,
			'nama_peneliti'=>$nama,
			'no_identitas'=>$no_identitas,
			'tipe_identitas'=>$tipe_identitas
		]);
		$notification = array('title'=> 'Berhasil!','msg'=>'Peneliti Non PSB berhasil ditambahkan!','alert-type'=>'success');
		return redirect()->back()->with($notification);
	}

    public function viewberkas($id)
	{
		$id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
		$berkas = berkas::where('id_kegiatan', $id)->first();
		$kegiatan = kegiatan::find($id);
		$id_kegiatan = $kegiatan->id;
    	$tipekegiatan = $kegiatan->tipe_kegiatan;
    	$tipe_berkas = $tipekegiatan->tipe_berkas;
    	
    	
    	foreach ($tipe_berkas as $berkas_kegiatan) {
    		$berkas_kegiatans[] = berkas::where([['id_kegiatan', $id],['id_tipe_berkas', $berkas_kegiatan->id]])->first();
    	}
  		
  		$pesertas = peserta_kegiatan::with(['peneliti'=>function($q){
    			$q->with(['peneliti_psb'])->with(['peneliti_nonpsb']);
    		}])->where('id_kegiatan',$id_kegiatan)->where('id_peneliti','!=', $id_peneliti)->get();
  		$countpsb=0;
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

		$psb = peneliti_psb::join('pegawai', 'peneliti_psb.id_pegawai', '=', 'pegawai.id')->where('pegawai.peran',1)
		->where('pegawai.id','!=', auth::user()->id_pegawai)->select('peneliti_psb.id_peneliti', 'pegawai.nama')->get();

    	$nonpsb = peneliti_nonpsb::all();
		// dd($penelitinonpsb_terpilih);

    	return view('peneliti.berkas', ['tipekegiatan'=>$tipekegiatan, 'tipe_berkas'=>$tipe_berkas, 'berkas'=>$berkas, 'kegiatan'=>$kegiatan, 'berkas_kegiatans'=>$berkas_kegiatans, 'penelitipsb_terpilih'=>$penelitipsb_terpilih, 'penelitinonpsb_terpilih'=>$penelitinonpsb_terpilih, 'psb'=>$psb, 'nonpsb'=>$nonpsb]);
	}


	public function tambahBerkas(Request $request, $id)
	{
		$kegiatan = kegiatan::find($id);
		$id_kegiatan = $kegiatan->id;
    	$tipekegiatan = $kegiatan->tipe_kegiatan;
    	$tipe_berkas = $tipekegiatan->tipe_berkas;
		$id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
		$judul = $request['judul'];
		berkas::create([
			'id_kegiatan'=>$id_kegiatan,
			'judul'=>$judul
		]);
		foreach ($tipe_berkas as $berkas_kegiatan) {
			$reqberkas = $request->file('berkas'.$berkas_kegiatan->id);
			if(isset($reqberkas)){
				$path = $reqberkas->getClientOriginalName();
				$reqberkas->move($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/'.$berkas_kegiatan->nama_tipe_berkas, $reqberkas->getClientOriginalName());
				berkas::create([
					'nama_berkas'=>$path,
					'id_tipe_berkas'=>$berkas_kegiatan->id,
					'id_kegiatan'=>$id_kegiatan,
					'judul'=> $judul
				]);
			}
		}
		//ada req psb dan nonpsb
	    if($request->psb!=null && $request->nonpsb!=null){
		    foreach ($request->psb as $index => $psb) {
		    	$psb = (int)$psb;
		    	$peneliti = peneliti_psb::where('id_peneliti',$psb)->first();
		    	$id_peneliti = $peneliti->id_peneliti;
		    	peserta_kegiatan::create([
		    		'id_peneliti' => $id_peneliti,
		    		'id_kegiatan'=> $kegiatan->id,
		    		'status_konfirm'=> 'menunggu'
		    	]);
		    }

		    foreach ($request->nonpsb as $index => $nonpsb) {
		    	$nonpsb = (int)$nonpsb;
		    	peserta_kegiatan::create([
		    		'id_peneliti' => $nonpsb,
		    		'id_kegiatan'=> $kegiatan->id
		    	]);
		    }
		    $notification = array('title'=> 'Berhasil!','msg'=>'Berkas berhasil ditambahkan!','alert-type'=>'success');
		    return redirect('/')->with($notification);
		}
		//ada req nonpsb
		elseif ($request->psb==null && $request->nonpsb!=null) {
			foreach ($request->nonpsb as $index => $nonpsb) {
		    	$nonpsb = (int)$nonpsb;
		    	peserta_kegiatan::create([
		    		'id_peneliti' => $nonpsb,
		    		'id_kegiatan'=> $kegiatan->id
		    	]);
		    }
		    $notification = array('title'=> 'Berhasil!','msg'=>'Berkas berhasil ditambahkan!','alert-type'=>'success');
		    return redirect('/')->with($notification);
		}
		//ada req psb
		elseif ($request->psb!=null && $request->nonpsb==null) {
			foreach ($request->psb as $index => $psb) {
		    	$psb = (int)$psb;
		    	$peneliti = peneliti_psb::where('id_peneliti',$psb)->first();
		    	$id_peneliti = $peneliti->id_peneliti;
		    	peserta_kegiatan::create([
		    		'id_peneliti' => $id_peneliti,
		    		'id_kegiatan'=> $kegiatan->id,
		    		'status_konfirm'=> 'menunggu'
		    	]);
		    }
		    $notification = array('title'=> 'Berhasil!','msg'=>'Berkas berhasil ditambahkan!','alert-type'=>'success');
		    return redirect('/')->with($notification);
		}
		else{
			$notification = array('title'=> 'Berhasil!','msg'=>'Berkas berhasil ditambahkan!','alert-type'=>'success');
		    return redirect('/')->with($notification);
		}

	}

	public function editBerkas(Request $request, $id)
	{
		
		$kegiatan = kegiatan::find($id);
		$id_kegiatan = $kegiatan->id;
    	$tipekegiatan = $kegiatan->tipe_kegiatan;
    	$tipe_berkas = $tipekegiatan->tipe_berkas;
		$id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
		$judul = $request['judul'];
		$berkas = berkas::where('id_kegiatan',$id_kegiatan)->get();
		berkas::where('id_kegiatan',$id_kegiatan)->update([
			'judul' => $judul
		]);

		$pesertas = peserta_kegiatan::with(['peneliti'=>function($q){
    			$q->with(['peneliti_psb'])->with(['peneliti_nonpsb']);
    		}])->where('id_kegiatan',$id_kegiatan)->where('id_peneliti','!=', $id_peneliti)->get();

		foreach ($tipe_berkas as $berkas_kegiatan) {
			$reqberkas = $request->file('berkas'.$berkas_kegiatan->id);
			$vari = $berkas->where('id_tipe_berkas',null)->first();
			$varia =$berkas->where('id_tipe_berkas',$berkas_kegiatan->id)->first();
			if(isset($reqberkas)){
				//ada judul tapi ga ada berkas
				if($vari != null){
					if(\File::exists(public_path($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/'.$berkas_kegiatan->nama_tipe_berkas.'/'.$vari->nama_berkas))){
						\File::delete(public_path($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/'.$berkas_kegiatan->nama_tipe_berkas.'/'.$vari->nama_berkas));

						$path = $reqberkas->getClientOriginalName();
						$reqberkas->move($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/'.$berkas_kegiatan->nama_tipe_berkas, $reqberkas->getClientOriginalName());
					}
					else{
					    $path = $reqberkas->getClientOriginalName();
						$reqberkas->move($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/'.$berkas_kegiatan->nama_tipe_berkas, $reqberkas->getClientOriginalName());
					}
	
					berkas::where('id_kegiatan',$id_kegiatan)->update([
						'nama_berkas'=>$path,
						'id_tipe_berkas'=>$berkas_kegiatan->id,
						'judul'=> $judul
					]);
				}
				//ada judul dan udah ada berkas yang diupload
				elseif($varia!=null){
					if(\File::exists(public_path($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/'.$berkas_kegiatan->nama_tipe_berkas.'/'.$varia->nama_berkas))){
						\File::delete(public_path($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/'.$berkas_kegiatan->nama_tipe_berkas.'/'.$varia->nama_berkas));
						$path = $reqberkas->getClientOriginalName();
						$reqberkas->move($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/'.$berkas_kegiatan->nama_tipe_berkas, $reqberkas->getClientOriginalName());
					}
					else{
					    $path = $reqberkas->getClientOriginalName();
						$reqberkas->move($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/'.$berkas_kegiatan->nama_tipe_berkas, $reqberkas->getClientOriginalName());
					}
					berkas::where([['id_kegiatan',$id_kegiatan],['id_tipe_berkas',$berkas_kegiatan->id]])->update([
						'nama_berkas'=>$path,
						'id_tipe_berkas'=>$berkas_kegiatan->id,
						'judul'=> $judul
					]);
				}
				//buat row baru dari kegiatan yang udah ada
				else{
					$path = $reqberkas->getClientOriginalName();
					$reqberkas->move($tipekegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/'.$berkas_kegiatan->nama_tipe_berkas, $reqberkas->getClientOriginalName());
					berkas::create([
						'nama_berkas'=>$path,
						'id_tipe_berkas'=>$berkas_kegiatan->id,
						'id_kegiatan'=>$id_kegiatan,
						'judul'=> $judul
					]);
				}
			}
		}

		$countnonpsb = 0;
		$countpsb = 0;
		//ada req psb & nonpsb
		if($request->psb!=null && $request->nonpsb!=null){
			//delete psb lalu dimasukkan psb yang baru
		    foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_nonpsb==null){
					$hapusspsb[] = $peserta->peneliti->peneliti_psb;
					$countpsb+=1;
				}
			}
			if($countpsb>0){
				foreach ($hapusspsb as $hapuspsb) {
		    			peserta_kegiatan::where([['id_peneliti',$hapuspsb->id_peneliti],['id_kegiatan',$id_kegiatan]])->delete();
		    	}
				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_kegiatan::create([
			    		'id_peneliti' => $psb,
			    		'id_kegiatan'=>$id_kegiatan,
			    		'status_konfirm'=> 'menunggu'
			    	]);
			    }
			}
			else{
				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_kegiatan::create([
			    		'id_peneliti' => $psb,
			    		'id_kegiatan'=>$id_kegiatan,
			    		'status_konfirm'=> 'menunggu'
			    	]);
			    }
			}
			$countpsb = 0;
		    //delete nonpsb lalu dimasukkan nonpsb yang baru
		    foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_psb==null){
					$hapussnonpsb[] = $peserta->peneliti->peneliti_nonpsb;
					$countnonpsb +=1;
				}
			}
			if($countnonpsb>0){
				foreach ($hapussnonpsb as $hapusnonpsb) {
		    		peserta_kegiatan::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_kegiatan',$id_kegiatan]])->delete();
		    	}

		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_kegiatan::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_kegiatan'=>$id_kegiatan
			    	]);
		    	}
		    }
		    else{
		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_kegiatan::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_kegiatan'=>$id_kegiatan
			    	]);
		    	}
		    }
		    $countnonpsb=0;

		    $notification = array('title'=> 'Berhasil!','msg'=>'Berkas berhasil ditambahkan!','alert-type'=>'success');
		    return redirect('/')->with($notification);
		}

		//ada req nonpsb
		elseif ($request->psb==null && $request->nonpsb!=null) {
			//delete psb
			foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_nonpsb==null){
					$hapusspsb[] = $peserta->peneliti->peneliti_psb;
					$countpsb +=1;
				}
			}
			if($countpsb>0){
				foreach ($hapusspsb as $hapuspsb) {
		    		peserta_kegiatan::where([['id_peneliti',$hapuspsb->id_peneliti],['id_kegiatan',$id_kegiatan]])->delete();
		    	}
		    }
		    $countpsb=0;

		    //delete nonpsb lalu dimasukan nonpsb yang baru
			foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_psb==null){
					$hapussnonpsb[] = $peserta->peneliti->peneliti_nonpsb;
					$countnonpsb +=1;
				}
			}
			if($countnonpsb>0){
				foreach ($hapussnonpsb as $hapusnonpsb) {
		    		peserta_kegiatan::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_kegiatan',$id_kegiatan]])->delete();
		    	}

		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_kegiatan::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_kegiatan'=>$id_kegiatan
			    	]);
		    	}
		    }
		    else{
		    	foreach ($request->nonpsb as $index => $nonpsb) {
			    	$nonpsb = (int)$nonpsb;
			    	peserta_kegiatan::create([
			    		'id_peneliti' => $nonpsb,
			    		'id_kegiatan'=>$id_kegiatan
			    	]);
		    	}
		    }
		    $countnonpsb =0;

		    
		    $notification = array('title'=> 'Berhasil!','msg'=>'Berkas berhasil ditambahkan!','alert-type'=>'success');
		    return redirect('/')->with($notification);
		}
 
		//ada req psb
		elseif ($request->psb!=null && $request->nonpsb==null) {
			//delete nonpsb
			foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_psb==null){
					$hapussnonpsb[] = $peserta->peneliti->peneliti_nonpsb;
					$countnonpsb +=1;
				}
			}
			if($countnonpsb>0){
				foreach ($hapussnonpsb as $hapusnonpsb) {
		    		peserta_kegiatan::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_kegiatan',$id_kegiatan]])->delete();
		    	}
		    }
		    $countnonpsb=0;

		    //delete psb lalu dimasukkan psb yang baru
			foreach ($pesertas as $peserta) {
				if($peserta->peneliti->peneliti_nonpsb==null){
					$hapusspsb[] = $peserta->peneliti->peneliti_psb;
					$countpsb+=1;
				}
			}
			if($countpsb>0){
				foreach ($hapusspsb as $hapuspsb) {
		    		peserta_kegiatan::where([['id_peneliti',$hapuspsb->id_peneliti],['id_kegiatan',$id_kegiatan]])->delete();
		    	}
				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_kegiatan::create([
			    		'id_peneliti' => $psb,
			    		'id_kegiatan'=>$id_kegiatan,
			    		'status_konfirm'=> 'menunggu'
			    	]);
			    }
			}
			else{
				foreach ($request->psb as $index => $psb) {
			    	$psb = (int)$psb;
			    	peserta_kegiatan::create([
			    		'id_peneliti' => $psb,
			    		'id_kegiatan'=>$id_kegiatan,
			    		'status_konfirm'=> 'menunggu'
			    	]);
			    }
			}
			$countpsb = 0;

		    $notification = array('title'=> 'Berhasil!','msg'=>'Berkas berhasil ditambahkan!','alert-type'=>'success');
		    return redirect('/')->with($notification);
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
		    		peserta_kegiatan::where([['id_peneliti',$hapuspsb->id_peneliti],['id_kegiatan',$id_kegiatan]])->delete();
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
		    		peserta_kegaitan::where([['id_peneliti',$hapusnonpsb->id_peneliti],['id_kegiatan',$id_kegiatan]])->delete();
		    	}
		    }
		    $countnonpsb=0;

			$notification = array('title'=> 'Berhasil!','msg'=>'Berkas berhasil ditambahkan!','alert-type'=>'success');
		    return redirect('/')->with($notification);
		}
		
	}


}
