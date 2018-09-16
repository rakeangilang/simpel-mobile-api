<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class departemen extends Model
{
    protected $table = 'departemen';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nama_departemen', 'id_fakultas'
    ];
    public $timestamps = false;

    public function peneliti_psb(){
    	return $this->hasMany('App\Model\penliti_psb', 'id_peneliti');
 	}
 	public function fakultas(){
    	return $this->belongsTo('App\Model\fakultas', 'id_fakultas');
 	}
}
