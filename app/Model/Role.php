<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "role";
    public $timestamps = false;
    protected $fillable = ['id','nama_role','keterangan'];

    public function tabelRoleSI(){
    	return $this->hasMany('App\TabelRoleSI','id_role');
    }

    public function tabelUserSI(){
    	return $this->hasMany('App\TabelUserSI','id_tole');
    }

    public function sistemInformasi(){
    	return $this->belongsToMany('App\SistemInformasi','tbl_role_si','id_role','id_si');
    }
}
