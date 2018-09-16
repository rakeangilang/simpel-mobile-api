<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class peran extends Model
{
    protected $table = 'peran';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'nama_peran'
    ];
    public $timestamps = false;

    public function tipe_kegiatan(){
        return $this->belongsToMany('App\Model\tipe_kegiatan','peran_kegiatan','id_peran','id_tipe_kegiatan');
    }
}
