<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class berkas extends Model
{
    protected $table = 'berkas';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'id_tipe_berkas', 'nama_berkas', 'id_kegiatan','judul'
    ];
    public $timestamps = false;

    public function kegiatan(){
    	return $this->belongsTo('App\Model\kegiatan','id_kegiatan');
  	}
  	public function tipe_berkas(){
    	return $this->belongsTo('App\Model\tipe_berkas','id_tipe_berkas');
  	}
}
