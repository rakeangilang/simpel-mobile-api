<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TabelUserSI extends Model
{
    protected $table = "tbl_user_si";
    public $timestamps = false;
    protected $fillable = ['id','id_user','id_si','id_role'];

    public function user(){
    	return $this->belongsTo('App\User','id_user');
    }

    public function role(){
    	return $this->belongsTo('App\Role','id_role');
    }

    public function sistemInformasi(){
    	return $this->belongsTo('App\SistemInformasi','id_si');
    }
}
