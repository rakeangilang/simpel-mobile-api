<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'id_unit', 'nama', 'nip', 'gelar_depan','gelar_belakang', 'no_ktp', 'tanggal_lahir','tempat_lahir', 'jabatan', 'jenis_kelamin', 'agama', 'status_kawin', 'email', 'nomor_hp', 'telepon', 'faks', 'alamat', 'gambar', 'peran'
    ];
    public $timestamps = false;

    public function peneliti_psb(){
    	return $this->hasOne('App\Model\peneliti_psb', 'id_pegawai');
 	}

 	public function user(){
    	return $this->hasOne('App\user', 'id_pegawai');
 	}

}
