<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class fakultas extends Model
{
    protected $table = 'fakultas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nama_fakultas'
    ];
    public $timestamps = false;

 	public function departemen(){
    	return $this->hasMany('App\Model\departemen', 'id_fakultas');
 	}
}
