<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class peserta_publikasi_buku extends Model
{
    protected $table = 'peserta_publikasi_buku';


    protected $fillable = [
        'id_peneliti', 'id_publikasi_buku', 'status_konfirm'
    ];
    public $timestamps = false;

    public function peneliti(){
    	return $this->belongsTo('App\Model\peneliti','id_peneliti');
  	}
  	public function publikasi_buku(){
    	return $this->belongsTo('App\Model\publikasi_buku','id_publikasi_buku');
  	}
}
