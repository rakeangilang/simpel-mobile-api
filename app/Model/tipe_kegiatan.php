<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\berkas_kegiatan;

class tipe_kegiatan extends Model
{
    protected $table = 'tipe_kegiatan';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'nama_tipe_kegiatan', 'dokumentasi'
    ];
    public $timestamps = false;

    public function kegiatan(){
    	return $this->hasMany('App\Model\kegiatan', 'id_tipe_kegiatan');
 	}
 	public function berkas_kegiatan(){
    	return $this->hasMany('App\Model\berkas_kegiatan','id_tipe_kegiatan');
 	}
 	public function tipe_berkas(){
 		return $this->belongsToMany('App\Model\tipe_berkas','berkas_kegiatan','id_tipe_kegiatan','id_tipe_berkas'); 
 	}
 	public function peran(){
        return $this->belongsToMany('App\Model\peran','peran_kegiatan','id_tipe_kegiatan','id_peran');
    }
    public function kategori_tipe_kegiatan(){
        return $this->hasMany('App\Model\kategori_tipe_kegiatan','id_tipe_kegiatan');
    }
}
