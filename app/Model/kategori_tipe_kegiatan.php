<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class kategori_tipe_kegiatan extends Model
{
    protected $table = 'kategori_tipe_kegiatan';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'id_tipe_kegiatan', 'nama_kategori', 'keterangan'
    ];

    public function kegiatan(){
        return $this-> hasMany('App\Model\kegiatan','id_kategori_tipe_kegiatan');
    }
    public function tipe_kegiatan(){
        return $this-> belongsTo('App\Model\tipe_kegiatan', 'id_tipe_kegiatan');
    }
}
