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


class ProfilController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getProfil(Request $request)
    {
    	$id_pegawai = auth::user()->id_pegawai;
      $id_user = auth::user()->id;
      $user = user::where('id', $id_user)->first();
    	$pegawai = pegawai::where('id', $id_pegawai)->first();
    	$peneliti_psb = peneliti_psb::where('id_pegawai', $id_pegawai)->first();
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

      $koneksi = $this->koneksi();
      //Storage::put('treeData.json',$this->koneksi());
    	return view('peneliti.profil', ['pegawai'=>$pegawai, 'user'=>$user, 'peneliti_psb'=>$peneliti_psb, 'penelitians'=>$penelitians,'seminars'=>$seminars, 'publikasijurnals'=>$publikasijurnals, 'publikasibukuu'=> $publikasibukuu, 'koneksi'=> $koneksi]);
    }

    public function getpubbuku($id){
      $publikasibuku = publikasi_buku::find($id);
      return $publikasibuku;
    }

    public function compareusername(Request $request){
      $username = $request['username']; //username hasil ketik
      $id_user = auth::user()->id;
      $users = User::find($id_user);
      $user = $users->username; //username yg lagi login
      $usersdb = User::where('username', $username)->get(); //username yang ada di db
      $status = "tidak ada";
      foreach ($usersdb as $userdb) {
        if($userdb->username == $username && $username!=$user){
          $status = "ada";
          return $status;
        }
        else{
          return $status;
        }
        
      }
    }

    public function editusername(Request $request)
    {
      $id_user = auth::user()->id;
      $username = $request['username'];
      user::where('id',$id_user)->update([
        'username'=> $username
      ]);
      $notification = array('tittle'=> 'Berhasil!','msg'=>'Username anda telah diganti.','alert-type'=>'success');
    return redirect()->back()->with($notification);
    }

    public function comparepass(Request $request){
      $passuser = $request['password'];
      $id_user = auth::user()->id;
      $user = user::find($id_user);
      $passdb = $user->password;
      // return response()->json(['pass'=>$passuser,'passdb'=>$passdb]);
      if(hash::check($passuser, $passdb)){
        return "bener";
      }
      else{
        return "salah";
      }
    }

    public function editpassword(Request $request){
      $id_user = auth::user()->id;
      $passbaru = $request['passbaru'];
      user::where('id',$id_user)->update([
        'password'=> bcrypt($passbaru)
      ]);
      $notification = array('tittle'=> 'Berhasil!','msg'=>'Password anda telah diganti.','alert-type'=>'success');
      return redirect()->back()->with($notification);
    }

    public function koneksi(){
      $id_pegawai = auth::user()->id_pegawai;
      $id_user = auth::user()->id;
      $user = user::where('id', $id_user)->first();
      $pegawai = pegawai::where('id', $id_pegawai)->first();
      $peneliti_psb = peneliti_psb::where('id_pegawai', $id_pegawai)->first();
      $id_peneliti = $peneliti_psb->id_peneliti;
      
      //kegiatan yg dimiliki peneliti yg login
      $kegiatans =  peserta_kegiatan::where('id_peneliti', $id_peneliti)->select("peserta_kegiatan.id_kegiatan")->get();
      foreach ($kegiatans as $key => $kegiatan) {
        $id_kegiatan[] = $kegiatan->id_kegiatan;
      }
      //daftar peneliti yang terdapat pada kegiatan yang dimiliki oleh peneliti yang sedang login
      foreach ($kegiatans as $key => $kegiatan) {
        $penelitiss[] = peserta_kegiatan::where('id_kegiatan', $id_kegiatan[$key])->select("peserta_kegiatan.id_peneliti")->get();
      }
      if(!isset($penelitiss)){
        $penelitiss=null;
      }

      //kondisi jika ada daftar penelitinya
      if($penelitiss!=null){
        //memasukan daftar id penelitinya ke dalam variabel
        foreach($penelitiss as  $penelitis){
          foreach($penelitis as  $peneliti){
            if($peneliti->id_peneliti != $id_peneliti){
              $id_penelitis[] = $peneliti->id_peneliti;
            }
          }
        }
        //distinct id penelitinya
        $idpeneliti_unique = array_unique($id_penelitis);
        //menghubungkan tabel untuk mendapatkan namanya
        $nama_penelitis = peneliti::with(['peneliti_psb'=>function($k){
          $k->with(['pegawai']);
        }])->with(['peneliti_nonpsb'])->whereIn('id', $id_penelitis)->get();
        
        //pembuatan json untuk tree
        $send = [
          'name' => $pegawai->nama,
          'parent' => null,
          "children" => []
        ];
        foreach ($nama_penelitis as $nama_peneliti) {
          if($nama_peneliti->peneliti_psb!=null){
            $children = [
              'name' => $nama_peneliti->peneliti_psb->pegawai->nama,
              'parent' => $pegawai->nama
            ];
          }
          else{
            $children = [
              'name' => $nama_peneliti->peneliti_nonpsb->nama_peneliti,
              'parent' => $pegawai->nama
            ];
          }
          array_push($send['children'], $children);
        }
        $json = json_encode($send);
        return $json;
      }

      else{
        $json = null;
        return $json;
      }

      
    }
    
}
