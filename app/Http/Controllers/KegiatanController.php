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
use App\User;
use Auth;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class KegiatanController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
 

    }

	public function getKegiatan(Request $request)
    {
        $id_pegawai = auth::user()->id_pegawai;
        $peneliti_psb = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti_psb->id_peneliti;
        $pesertas = peserta_kegiatan::where([['status_konfirm', 'setuju'],['id_peneliti',$id_peneliti]])->get();
        $filter = "all";
        $countpeserta = $pesertas->count();
        if($countpeserta==0){
        	$entries = null;
        }
        else {
        	foreach ($pesertas as $peserta) {
        		$kegiatans[] = $peserta->kegiatan;
        		$tanggal[] = strtotime($peserta->kegiatan->tanggal_awal);
        	}
        	array_multisort($tanggal, SORT_DESC, $kegiatans);
	        	$currentPage = LengthAwarePaginator::resolveCurrentPage();
	        	$col = new Collection($kegiatans);
	        	$perPage = 5;
	        	$currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
	        	$entries = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage);
        }

        $tipekegiatans = tipe_kegiatan::all();
        return view('peneliti.dashboard', ['kegiatans'=>$entries, 'tipekegiatans'=>$tipekegiatans, 'filter'=>$filter]);  
    }

    public function detailKegiatan($id)
    {
    	$id_pegawai = auth::user()->id_pegawai;
        $peneliti_psb = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti_psb->id_peneliti;
        $berkas = berkas::where('id_kegiatan',$id)->first();

        $kegiatan = kegiatan::join('tipe_kegiatan', 'tipe_kegiatan.id', '=', 'kegiatan.id_tipe_kegiatan')
        ->where('kegiatan.id', $id)
        ->first();
          $penelitis=peserta_kegiatan::with(['peneliti'=>function($k){
            $k->with(['peneliti_psb'=>function($q){
              $q->with(['pegawai']);
            }])->with(['peneliti_nonpsb']);
          }])->where('id_kegiatan',$id)->get();

        return view('peneliti.detailKegiatan', ['kegiatan'=>$kegiatan, 'penelitis'=>$penelitis, 'berkas'=>$berkas]);
    }


    public function searchKegiatan(Request $request){
        $keywords=$request['search'];
        $id_pegawai = auth::user()->id_pegawai;
   		$peneliti_psb = peneliti_psb::where('id_pegawai', $id_pegawai)->first();
   		$id_peneliti = $peneliti_psb->id_peneliti;
   		$tipekegiatans = tipe_kegiatan::all();
        $kegiatans = kegiatan::join('peserta_kegiatan', 'peserta_kegiatan.id_kegiatan', '=', 'kegiatan.id')
        			->where('id_peneliti', $id_peneliti)
        			->where('nama_kegiatan','LIKE','%'.$keywords.'%')->paginate(5);

        return view('peneliti.dashboard', ['kegiatans' => $kegiatans,'tipekegiatans'=>$tipekegiatans]);
    }

    public function filterKegiatan(Request $request){
    	$tipekegiatans = tipe_kegiatan::all();
    	$filter = $request['tipekegiatan'];
    	$id_pegawai = auth::user()->id_pegawai;
   		$peneliti_psb = peneliti_psb::where('id_pegawai', $id_pegawai)->first();
   		$id_peneliti = $peneliti_psb->id_peneliti;
   		$pesertas = peserta_kegiatan::where([['status_konfirm', 'setuju'],['id_peneliti',$id_peneliti]])->get();
   		$countpeserta = $pesertas->count();
    	if($filter=="all"){
        	if($countpeserta==0){
        	$entries = null;
	        }
	        else {
	        	foreach ($pesertas as $peserta) {
	        		$kegiatans[] = $peserta->kegiatan;
	        		$tanggal[] = strtotime($peserta->kegiatan->tanggal_awal);
	        	}
	        	array_multisort($tanggal, SORT_DESC, $kegiatans);
		        	$currentPage = LengthAwarePaginator::resolveCurrentPage();
		        	$col = new Collection($kegiatans);
		        	$perPage = 5;
		        	$currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
		        	$entries = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage);
	        }
        	return view('peneliti.dashboard', ['kegiatans' => $entries, 'tipekegiatans'=>$tipekegiatans, 'filter'=>$filter]);
        }
        else{
        	if($countpeserta==0){
        		$entries = null;
	        }
	        else {
	        	foreach ($pesertas as $peserta) {
	        		if($peserta->kegiatan->id_tipe_kegiatan==$filter){
		        		$kegiatans[] = $peserta->kegiatan;
		        		$tanggal[] = strtotime($peserta->kegiatan->tanggal_awal);
	        		}
	        		
	        	}
	        	array_multisort($tanggal, SORT_DESC, $kegiatans);
		        	$currentPage = LengthAwarePaginator::resolveCurrentPage();
		        	$col = new Collection($kegiatans);
		        	$perPage = 5;
		        	$currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
		        	$entries = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage);
	        }
			// $kegiatans = kegiatan::join('peserta_kegiatan', 'peserta_kegiatan.id_kegiatan', '=', 'kegiatan.id')
			// ->where('id_peneliti', $id_peneliti)
			// ->where('id_tipe_kegiatan', $filter)->paginate(5);
        	return view('peneliti.dashboard', ['kegiatans' => $entries, 'tipekegiatans'=>$tipekegiatans, 'filter'=>$filter]);
        }

    }

    public function singleTipekegiatan($id){
   		$tipekegiatans = tipe_kegiatan::all();
        $kegiatans = kegiatan::all();
        $tk = tipe_kegiatan::where('id',$id)->first();
        $perans = $tk->peran;
        $kategoris = $tk->kategori_tipe_kegiatan;
   		return view('peneliti.kegiatan', ['tipekegiatans'=>$tipekegiatans, 'kategoris'=>$kategoris, 'id'=>$id, 'kegiatans'=>$kegiatans, 'perans'=>$perans]);
    }

    public function vieweditkegiatan($id){
    	$id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
    	$kegiatan = kegiatan::find($id);
    	$tipekegiatan = $kegiatan->tipe_kegiatan;
    	$tk = tipe_kegiatan::where('id',$kegiatan->tipe_kegiatan->id)->first();
        $perans = $tk->peran;
        $kategoris = $tk->kategori_tipe_kegiatan;
        $peran_terpilih = peserta_kegiatan::where([['id_kegiatan',$id],['id_peneliti', $id_peneliti]])->select('id_peran')->first();
        $kategori_terpilih = kegiatan::where('id', $id)->select('id_kategori_tipe_kegiatan')->first();
    	$berkas = berkas::where([['id_kegiatan', $id],['id_tipe_berkas', 5]])->first();
    	
        return view('peneliti.editkegiatan', ['kegiatan'=>$kegiatan, 'kategoris'=>$kategoris,'kategori_terpilih'=>$kategori_terpilih, 'tipekegiatan'=>$tipekegiatan, 'berkas'=>$berkas, 'peran_terpilih'=>$peran_terpilih, 'perans'=>$perans]);
    }

    public function tambahKegiatan(Request $request, $id)
    {
    	$id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
	    $tipe_kegiatan = tipe_kegiatan::find($id);
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
		    	'id_tipe_kegiatan' => $id,
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
		    return redirect('/')->with($notification);
		}

	    if($tipe_kegiatan->dokumentasi == 'ya')
	    {
			$kegiatan = kegiatan::create([
		    	'id_tipe_kegiatan' => $id,
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
			    $path = $foto->getClientOriginalName();
			    $foto->move($tipe_kegiatan->nama_tipe_kegiatan.'/'.$id_kegiatan.'/foto', $foto->getClientOriginalName());
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
		    return redirect('/')->with($notification);
	    }    
		else
		{
			$kegiatan = kegiatan::create([
		    	'id_tipe_kegiatan' => $id,
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
		    return redirect('/')->with($notification);
		} 
		
	}

	public function editKegiatan(Request $request, $id)
    {
    	$id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
    	$kegiatan = kegiatan::find($id);
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
		    return redirect('/')->with($notification);
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
		    return redirect('/')->with($notification);
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
		    return redirect('/')->with($notification);
		} 

    }

	
}


