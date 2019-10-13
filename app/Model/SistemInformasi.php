<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SistemInformasi extends Model
{
    protected $table = "sistem_informasi";
    public $timestamps = false;
    protected $fillable = ['id','nama_sistem'];

    public function tabelUserSI(){
    	return $this->hasMany('App\TabelUserSI','id_si');
    }

    public function tabelRoleSI(){
    	return $this->hasMany('App\TabelRoleSI','id_si');
    }

    public function role(){
    	return $this->belongsToMany('App\Role','tbl_role_si','id_si','id_role');
    }
}
