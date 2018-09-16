<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\kegiatan;
use App\Model\peneliti_psb;
use App\Model\peserta_kegiatan;
use App\User;
use Auth;

class KolaborasiController extends Controller
{
     public function getKolaborasi($id){
    	// $kolaborasi = kegiatan::find($id);
    	$kolaborasi = kegiatan::join('tipe_kegiatan', 'kegiatan.id_tipe_kegiatan', '=', 'tipe_kegiatan.id')
   					->join('berkas', 'berkas.id_kegiatan', '=', 'kegiatan.id')
   					->where('kegiatan.id', $id)
                    ->select('kegiatan.nama_kegiatan', 'kegiatan.tanggal_awal', 'tipe_kegiatan.nama_tipe_kegiatan', 'berkas.judul')
   					->first();
    	return $kolaborasi;

    }

     public function setujukolaborasi(Request $request){
    	$id_kegiatan = $request['kegiatan_id'];
    	$id_pegawai = auth::user()->id_pegawai;
        $peneliti_psb = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti_psb->peneliti->id;
    	peserta_kegiatan::where([['id_kegiatan',$id_kegiatan],['id_peneliti',$id_peneliti]])->update([
    		'status_konfirm'=>'setuju'
    	]);
    	$notification = array('title'=> 'Berhasil!','msg'=>'Anda telah menyetujui kolaborasi!','alert-type'=>'success');
		return redirect('/')->with($notification);
    }

    public function menolakkolaborasi(Request $request){
    	$id_kegiatan = $request['id_kegiatan'];
    	$id_pegawai = auth::user()->id_pegawai;
        $peneliti_psb = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti_psb->peneliti->id;
    	peserta_kegiatan::where([['id_kegiatan',$id_kegiatan],['id_peneliti',$id_peneliti]])->update([
    		'status_konfirm'=>'menolak'
    	]);
    	$notification = array('title'=> 'Berhasil!','msg'=>'Anda telah menolak kolaborasi!','alert-type'=>'success');
		return redirect('/')->with($notification);
    }
}
