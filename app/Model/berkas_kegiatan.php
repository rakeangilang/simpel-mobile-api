<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class berkas_kegiatan extends Model
{
    protected $table = 'berkas_kegiatan';

    protected $fillable = [
        'id_tipe_kegiatan', 'id_tipe_berkas'
    ];
    public $timestamps = false;

    public function tipe_kegiatan(){
    	return $this->belongsTo('App\Model\tipe_kegiatan','id');
  	}
  	public function tipe_berkas(){
    	return $this->belongsTo('App\Model\tipe_berkas','id');
  	}
}
