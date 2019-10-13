<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\kegiatan;
use App\Model\peserta_kegiatan;
use App\Model\peneliti_psb;
use App\Model\transaksi;
use App\Model\transaksi_proyek;
use App\User;
use Auth;
use Log;
use Carbon\Carbon;
use Jenssegers\Date\Date;

class DanaController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    // public function getformdana(){
    // 	$id_pegawai = auth::user()->id_pegawai;
    //     $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
    //     $id_peneliti = $peneliti->id_peneliti;
    //     $pesertas = peserta_kegiatan::where([['id_peneliti', $id_peneliti],['status_konfirm','setuju']])->get();
    //     $today = strtotime(now());
    //     $countpeserta = $pesertas->count();
    //     if($countpeserta==0){
    //         $kegiatans = null;
    //     }
    //     else {
    //         foreach ($pesertas as $key => $peserta) {
    //             $tanggal[] = strtotime($peserta->kegiatan->tanggal_akhir);
    //             if($tanggal[$key] >= $today){
    //                 $kegiatans[] = $peserta->kegiatan;
    //             }
    //         }
    //     }
    //     return view('peneliti.dana', ['kegiatans' => $kegiatans]);

    // }

     public function getformdana($id){
        $id_pegawai = auth::user()->id_pegawai;
        $peneliti = peneliti_psb::where('id_pegawai',$id_pegawai)->first();
        $id_peneliti = $peneliti->id_peneliti;
        $kegiatan = kegiatan::find($id);
        return view('peneliti.pengajuandana', ['kegiatan' => $kegiatan]);

    }

    public function mengajukanDana(Request $request){
    	$id_kegiatan = $request['id_kegiatan'];
    	$tanggal = $request['tanggal']; 
        $id_pegawai = auth::user()->id_pegawai;

        foreach ($request->keterangan as $index => $ket) {
            $transaksi = transaksi::create([
                'id_pegawai' => $id_pegawai,
                'tanggal' => $tanggal,
                'nominal'=> $request->subtotal[$index],
                'status' => 3
            ]);

            transaksi_proyek::create([
                'id_kegiatan' => $id_kegiatan,
                'id_transaksi' => $transaksi->id,
                'jumlah' => $request->jumlah[$index],
                'perkiraan_biaya'=>$request->nominal[$index],
                'unit'=>$request->unit[$index],
                'keterangan' => $request->keterangan[$index]
            ]);
        }
        
    	

    	$notification = array('title'=> 'Berhasil!','msg'=>'Penggunaan dana berhasil diajukan!','alert-type'=>'success');
		return redirect('/getDana/'.$id_kegiatan)->with($notification);
    }

    public function getDana($id){
    	$trans_proyeks = transaksi_proyek::where('id_kegiatan',$id)->paginate(5);
        $kegiatan_tp = transaksi_proyek::where('id_kegiatan',$id)->first();
        if(count($trans_proyeks)==0){
    		$trans_proyeks = null;
    	}
        $id_kegiatan = $id;
        $kegiatan = kegiatan::where('id',$id)->first();
        $today = strtotime(now());
        $tgl_akhir = strtotime($kegiatan->tanggal_akhir);
        if($tgl_akhir>=$today){
            $status = "available";
        }
        else{
            $status = "nonavailable";
        }

    	return view('peneliti.editpengajuan', ['trans_proyeks' => $trans_proyeks, 'id_kegiatan'=>$id_kegiatan, 'kegiatan_tp'=>$kegiatan_tp, 'status'=>$status]);
    }

    public function singledana($id){
    	$transaksi= transaksi::join('transaksi_proyek', 'transaksi_proyek.id_transaksi','=','transaksi.id')
        ->where('transaksi_proyek.id_transaksi',$id)
        ->select('transaksi.tanggal', 'transaksi.nominal','transaksi_proyek.keterangan','transaksi_proyek.jumlah','transaksi_proyek.unit', 'transaksi_proyek.perkiraan_biaya')
        ->first();
    	return $transaksi;
    }

    public function editPengajuan(Request $request){
    	$id_transaksi = $request['id_transaksi'];
    	$tanggal = $request['tanggal'];
    	$keterangan = $request['keterangan'];
        $jumlah = $request['jumlah'];
        $nominal = $request['nominal'];
    	$subtotal = $request['subtotal'];
        $unit = $request['unit'];
    	transaksi::where('id',$id_transaksi)->update([
    		'tanggal' => $tanggal,
    		'nominal' => $subtotal,
    	]);

        transaksi_proyek::where('id_transaksi',$id_transaksi)->update([
            'jumlah' => $jumlah,
            'perkiraan_biaya'=>$nominal,
            'unit'=>$unit,
            'keterangan' => $keterangan
        ]);

    	$notification = array('title'=> 'Berhasil!','msg'=>'Penggunaan dana berhasil diedit!','alert-type'=>'success');
		return redirect()->back()->with($notification);
    }

    public function tambahPengajuan(Request $request){
        $id_kegiatan = $request['id_kegiatan'];
        $tanggal = $request['tanggal'];
        $jumlah = $request['jumlah'];
        $nominal = $request['nominal'];
        $subtotal = $request['subtotal'];
        $unit = $request['unit'];
        $keterangan = $request['keterangan'];   
        $id_pegawai = auth::user()->id_pegawai;

        $transaksi = transaksi::create([
            'id_pegawai' => $id_pegawai,
            'tanggal' => $tanggal,
            'nominal'=>$subtotal,
            'status' => 3
        ]);

        transaksi_proyek::create([
            'id_kegiatan' => $id_kegiatan,
            'id_transaksi' => $transaksi->id,
            'jumlah' => $jumlah,
            'perkiraan_biaya'=>$nominal,
            'unit'=>$unit,
            'keterangan' => $keterangan

        ]);

        $notification = array('title'=> 'Berhasil!','msg'=>'Penggunaan dana berhasil diajukan!','alert-type'=>'success');
        return redirect('/getDana/'.$id_kegiatan)->with($notification);
    }

    public function hapusPengajuan($id)
    {
        $trans = transaksi::find($id);
        transaksi::find($id)->delete();
        transaksi_proyek::where('id_transaksi',$id)->first()->delete();
        $notification = array('title'=> 'Berhasil!', 'msg'=>'Pengajuan dana berhasil dihapus!','alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}
