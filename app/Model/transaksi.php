<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'id_pegawai', 'keterangan', 'nominal','tanggal', 'status'
    ];
    public $timestamps = false;
    
    public function transaksi_proyek(){
        return $this-> HasOne('App\Model\transaksi_proyek','id_transaksi');
    }
}
