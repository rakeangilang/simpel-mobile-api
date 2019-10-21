<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Model\kegiatan;
use App\Model\pegawai;
use App\Model\peneliti;
use App\Model\peneliti_psb;
use App\Model\publikasi_jurnal;
use App\Model\publikasi_buku;
use App\Model\peserta_publikasi_jurnal;
use App\Model\peserta_kegiatan;
use App\User;
use Auth;
use PDF;
use Storage;
use Carbon\Carbon;
use Jenssegers\Date\Date;

class ApiProfilController extends Controller
{
  public function getProfil(User $user, $id)
  {
    $id_pegawai = $user->find($id)->id_pegawai;
    $id_user = $user->find($id)->id;
    $user = user::where('id', $id_user)->first();
    $pegawai = pegawai::where('id', $id_pegawai)->first();
    $peneliti_psb = peneliti_psb::with('departemen.fakultas')
                  ->where('id_pegawai', $id_pegawai)->first();

    $id_peneliti = $peneliti_psb->id_peneliti;

    $penelitians = kegiatan::join('berkas', 'berkas.id_kegiatan', '=', 'kegiatan.id')
          ->join('peserta_kegiatan', 'peserta_kegiatan.id_kegiatan', '=', 'kegiatan.id')
          ->where('peserta_kegiatan.id_peneliti', $id_peneliti)
          ->where('peserta_kegiatan.status_konfirm','setuju')
          ->where('berkas.judul','!=',null)
          ->where(function($k){
            $k->where('id_tipe_kegiatan',1)
              ->orWhere('id_tipe_kegiatan',2)
              ->orWhere('id_tipe_kegiatan',3);
            })
          ->select('kegiatan.id', 'berkas.judul')
          ->distinct('berkas.judul')
          ->get();

    $seminars = kegiatan::join('berkas', 'berkas.id_kegiatan', '=', 'kegiatan.id')
          ->join('peserta_kegiatan', 'peserta_kegiatan.id_kegiatan', '=', 'kegiatan.id')
          ->where('peserta_kegiatan.id_peneliti', $id_peneliti)
          ->where('peserta_kegiatan.status_konfirm','setuju')
          ->where('berkas.judul','!=',null)
          ->where('id_tipe_kegiatan',4)
          ->select('kegiatan.id','berkas.judul')
          ->distinct('berkas.judul')
          ->get();

    $publikasijurnals = publikasi_jurnal::join('peserta_publikasi_jurnal', 'peserta_publikasi_jurnal.id_publikasi_jurnal', '=', 'publikasi_jurnal.id')
          ->where([['id_peneliti', $id_peneliti],['peserta_publikasi_jurnal.status_konfirm','setuju']])
          ->select('publikasi_jurnal.id','publikasi_jurnal.judul_artikel')
          ->get();
    $publikasibukuu = publikasi_buku::join('peserta_publikasi_buku', 'peserta_publikasi_buku.id_publikasi_buku', '=', 'publikasi_buku.id')
          ->where([['id_peneliti', $id_peneliti],['peserta_publikasi_buku.status_konfirm','setuju']])
          ->select('publikasi_buku.id','publikasi_buku.judul_buku')
          ->get();

    //$koneksi = $this->koneksi();
    //Storage::put('treeData.json',$this->koneksi());

    return response()->json([
      'success'=>true,
      'message'=>"asdf",
      'pegawai'=>$pegawai,
      'user'=>$user,
      'peneliti_psb'=>$peneliti_psb,
      'penelitians'=>$penelitians,
      'seminars'=>$seminars,
      'publikasijurnals'=>$publikasijurnals,
      'publikasibukuu'=> $publikasibukuu
    ]);
  }

  public function editusername(Request $request, User $user, $id_u)
    {
      $id_user = $user->find($id_u)->id;
      $username = $request['username'];
      user::where('id',$id_user)->update([
        'username'=> $username
      ]);
      $notification = array('tittle'=> 'Berhasil!','msg'=>'Username anda telah diganti.','alert-type'=>'success');
      return response()->json([
        'success'=>true,
        'message'=>"Username berhasil diubah"
      ]);
    }

    public function editpassword(Request $request, User $user, $id_u){
      $id_user = $user->find($id_u)->id;
      $passbaru = $request['passbaru'];
      user::where('id',$id_user)->update([
        'password'=> bcrypt($passbaru)
      ]);
      $notification = array('tittle'=> 'Berhasil!','msg'=>'Password anda telah diganti.','alert-type'=>'success');
      return response()->json([
        'success'=>true,
        'message'=>"Password berhasil diganti"
      ]);
    }

}
