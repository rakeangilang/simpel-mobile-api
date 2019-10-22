<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Model\kegiatan;
use App\Model\pegawai;
use App\Model\peneliti_psb;
use App\Model\publikasi_jurnal;
use App\Model\publikasi_buku;
use App\Model\peserta_publikasi_jurnal;
use App\Model\peserta_kegiatan;
use App\Model\riwayat_pendidikan;
use App\User;
use Auth;
use PDF;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Storage;

class ApiPDFController extends Controller
{
    public function getPDF(User $user, $id){
      $id_pegawai = $user->find($id)->id_pegawai;
      $peneliti_psb = peneliti_psb::where('id_pegawai', $id_pegawai)->first();
      $id_peneliti = $peneliti_psb->id_peneliti;

      $biodata = pegawai::where('id',$id_pegawai)->first();
      $pendidikans = riwayat_pendidikan::where('id_pegawai',$id_pegawai)->orderBy('tahun_lulus','asc')->get();
      if($pendidikans->count()==0){
      	$pendidikans = null;
      }

      $penelitians = kegiatan::join('berkas', 'berkas.id_kegiatan', '=', 'kegiatan.id')
            ->join('peserta_kegiatan', 'peserta_kegiatan.id_kegiatan', '=', 'kegiatan.id')
            ->where('id_peneliti', $id_peneliti)
            ->where('peserta_kegiatan.status_konfirm','setuju')
            ->where('berkas.judul','!=',null)
            ->where(function($k){
              $k->where('id_tipe_kegiatan',1)
                ->orWhere('id_tipe_kegiatan',2)
                ->orWhere('id_tipe_kegiatan',3);
              })
            ->select('berkas.judul', 'kegiatan.tanggal_awal')          
            ->distinct('berkas.judul')
            ->get();
      $countpenelitian = $penelitians->count();
      if($countpenelitian==0){
        $penelitians=null;
      }

      $seminars = kegiatan::join('berkas', 'berkas.id_kegiatan', '=', 'kegiatan.id')
            ->join('peserta_kegiatan', 'peserta_kegiatan.id_kegiatan', '=', 'kegiatan.id')
            
            ->where('id_peneliti', $id_peneliti)
            ->where('peserta_kegiatan.status_konfirm','setuju')
            ->where('berkas.judul','!=',null)
            ->where('id_tipe_kegiatan',4)
            ->select('berkas.judul', 'kegiatan.tanggal_awal','kegiatan.nama_kegiatan', 'kegiatan.lokasi')
            ->distinct('berkas.judul')
            ->get();

      $countseminar = $seminars->count();
      if($countseminar==0){
        $seminars=null;
      }

      $publikasi_jurnals = peserta_publikasi_jurnal::where([['id_peneliti', $id_peneliti],['status_konfirm','setuju']])->get();
      foreach ($publikasi_jurnals as $key => $jurnal) {
          $peserta_pubjurnals[$key]=peserta_publikasi_jurnal::with(['peneliti'=>function($k){
            $k->with(['peneliti_psb'=>function($q){
              $q->with(['pegawai']);
            }])->with(['peneliti_nonpsb']);
          }])->with(['publikasi_jurnal'])->where('id_publikasi_jurnal', $jurnal->id_publikasi_jurnal)->get(); 
      }

      if(!isset($peserta_pubjurnals)){
        $peserta_pubjurnals=null;
      }


      $publikasi_bukuu = publikasi_buku::join('peserta_publikasi_buku', 'peserta_publikasi_buku.id_publikasi_buku', '=', 'publikasi_buku.id')
            ->where([['id_peneliti', $id_peneliti],['peserta_publikasi_buku.status_konfirm','setuju']])
            ->select('publikasi_buku.judul_buku', 'publikasi_buku.judul_book_chapter', 'publikasi_buku.nama_penerbit','publikasi_buku.tahun_terbit','publikasi_buku.isbn')
            ->get();
      $countbuku = $publikasi_bukuu->count();
      if($countbuku==0){
        $publikasi_bukuu=null;
      }

      $path = storage_path('cv/cv.pdf');

      $pdf=PDF::loadVIew('peneliti.cv',['penelitians'=>$penelitians, 'seminars'=>$seminars, 'peserta_pubjurnals'=>$peserta_pubjurnals, 'biodata'=>$biodata, 'publikasi_bukuu'=>$publikasi_bukuu, 'pendidikans'=>$pendidikans]);
      $pdf->save($path);

      $headers = ['Content-Type'=>'application/pdf', 'Content-Disposition'=> 'attachment; filename="CV.pdf"'];
      

      return response()->file($path, $headers);
    }
}
