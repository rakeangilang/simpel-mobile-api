<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class peneliti_psb extends Model
{
    protected $table = 'peneliti_psb';

    protected $fillable = [
        'id_peneliti', 'id_pegawai', 'id_departemen', 'cv'
    ];
    public $timestamps = false;

    public function peneliti(){
    	return $this->belongsTo('App\Model\peneliti', 'id_peneliti');
 	}
 	public function departemen(){
    	return $this->belongsTo('App\Model\departemen', 'id_departemen');
 	}
 	public function pegawai(){
    	return $this->belongsTo('App\Model\pegawai', 'id_pegawai');
 	}

}
