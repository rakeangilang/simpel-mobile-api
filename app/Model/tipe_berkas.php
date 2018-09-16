<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class tipe_berkas extends Model
{
   protected $table = 'tipe_berkas';
   protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'nama_tipe_berkas'
    ];
    public $timestamps = false;

    public function berkas(){
    	return $this->hasMany('App\Model\berkas');
 	}
 	public function tipe_kegiatan(){
 		return $this->hasMany('App\Model\tipe_kegiatan','berkas_kegiatan','id_tipe_berkas','id_tipe_kegiatan');
 	}

}
