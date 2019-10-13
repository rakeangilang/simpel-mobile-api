<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'id_tipe_kegiatan', 'nama_kegiatan', 'tanggal_awal','tanggal_akhir','keterangan','lokasi','saldo', 'id_kategori_tipe_kegiatan', 'instansi'
    ];
    public $timestamps = false;

    public function peserta_kegiatan(){
        return $this-> hasMany('App\Model\peserta_kegiatan','id_kegiatan');
    }
    public function tipe_kegiatan(){
        return $this-> belongsTo('App\Model\tipe_kegiatan', 'id_tipe_kegiatan');
    }
    public function berkas(){
        return $this-> hasMany('App\Model\berkas','id_kegiatan');
    }
    public function peneliti(){
        return $this->belongsToMany('App\Model\peneliti','peserta_kegiatan','id_kegiatan','id_peneliti');
    }
    public function transaksi_proyek(){
        return $this-> hasMany('App\Model\transaksi_proyek','id_kegiatan');
    }
    public function kategori_tipe_kegiatan(){
        return $this-> belongsTo('App\Model\kategori_tipe_kegiatan', 'id_kategori_tipe_kegiatan');
    }
}
