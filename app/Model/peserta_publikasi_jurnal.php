<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class peserta_publikasi_jurnal extends Model
{
    protected $table = 'peserta_publikasi_jurnal';


    protected $fillable = [
        'id_peneliti', 'id_publikasi_jurnal', 'status_konfirm'
    ];
    public $timestamps = false;

    public function peneliti(){
    	return $this->belongsTo('App\Model\peneliti','id_peneliti');
  	}
  	public function publikasi_jurnal(){
    	return $this->belongsTo('App\Model\publikasi_jurnal','id_publikasi_jurnal');
  	}
}
