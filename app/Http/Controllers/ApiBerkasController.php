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

class ApiBerkasController extends Controller
{

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
  return response()->json([
      'success'=>true,
      'message'=>"Peneliti Non-PSB Berhasil Ditambahkan!"
    ]);
}

  public function viewberkas(User $user, $id, $id_k)
	{
        $id_pegawai = $user->find($id)->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
		$berkas = berkas::where('id_kegiatan', $id_k)->first();
		$kegiatan = kegiatan::find($id_k);
		$id_kegiatan = $kegiatan->id;
    	$tipekegiatan = $kegiatan->tipe_kegiatan;
    	$tipe_berkas = $tipekegiatan->tipe_berkas;


    	foreach ($tipe_berkas as $berkas_kegiatan) {
    		$berkas_kegiatans[] = berkas::where([['id_kegiatan', $id_k],['id_tipe_berkas', $berkas_kegiatan->id]])->first();
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

        return response()->json([
            'success'=>true,
            'message'=>"asdf",
            'tipekegiatan'=>$tipekegiatan,
            'tipe_berkas'=>$tipe_berkas,
            'berkas'=>$berkas,
            'kegiatan'=>$kegiatan,
            'berkas_kegiatans'=>$berkas_kegiatans,
            'penelitipsb_terpilih'=>$penelitipsb_terpilih,
            'penelitinonpsb_terpilih'=>$penelitinonpsb_terpilih,
            'psb'=>$psb,
            'nonpsb'=>$nonpsb
          ]);
    }

    public function findpsb(User $user, $id)
    {
		$id_pegawai = $user->find($id)->id_pegawai;
        $peneliti_psb = pegawai::join('peneliti_psb', 'peneliti_psb.id_pegawai', '=', 'pegawai.id')
		->where('pegawai.peran',1)->where('pegawai.id','!=', $id_pegawai)
		->select('peneliti_psb.id_peneliti', 'pegawai.nama')
		->orderBy('pegawai.nama','asc')
		->get();
		$peneliti_nonpsb= peneliti_nonpsb::select('peneliti_nonpsb.id_peneliti', 'peneliti_nonpsb.nama_peneliti')
		->orderBy('peneliti_nonpsb.nama_peneliti','asc')
		->get();
		return response()->json([
      'success'=>true,
      'message'=>"asdf",
			'peneliti_psb'=>$peneliti_psb,
			'peneliti_nonpsb'=>$peneliti_nonpsb,
          ]);
    }

}
