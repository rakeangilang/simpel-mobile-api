<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TabelRoleSI extends Model
{
    protected $table = "tbl_role_si";
    public $timestamps = false;
    protected $fillable = ['id','id_si','id_role'];

    public function sistemInformasi(){
    	return $this->belongsTo('App\SistemInformasi','id_si');
    }

    public function role(){
    	return $this->belongsTo('App\Role','id_role');
    }
}
