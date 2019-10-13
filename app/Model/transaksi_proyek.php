<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class transaksi_proyek extends Model
{
   	protected $table = 'transaksi_proyek';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'id_transaksi', 'id_kegiatan', 'keterangan', 'jumlah', 'unit', 'perkiraan_biaya'
    ];
    public $timestamps = false;

    public function kegiatan(){
        return $this-> belongsTo('App\Model\kegiatan','id_kegiatan');
    }

    public function transaksi(){
        return $this-> belongsTo('App\Model\transaksi','id_transaksi');
    }
}
