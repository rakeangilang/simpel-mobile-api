<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class peneliti extends Model
{
    protected $table = 'peneliti';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id'
    ];
    public $timestamps = false;

    public function peneliti_psb(){
    	return $this->hasOne('App\Model\peneliti_psb', 'id_peneliti');
 	}

 	public function peneliti_nonpsb(){
    	return $this->hasOne('App\Model\peneliti_nonpsb', 'id_peneliti');
 	}
    public function peserta_kegiatan(){
        return $this->hasMany('App\Model\peserta_kegiatan','id_peneliti');
    }
 	public function peserta_publikasi_jurnal(){
    	return $this->hasMany('App\Model\peserta_publikasi_jurnal','id_peneliti');
 	}
 	public function peserta_publikasi_buku(){
    	return $this->hasMany('App\Model\peserta_publikasi_buku', 'id_peneliti');
 	}

    public function kegiatan(){
        return $this->belongsToMany('App\Model\kegiatan','peserta_kegiatan','id_peneliti','id_kegiatan');
    }
    public function publikasi_jurnal(){
        return $this->belongsToMany('App\Model\publikasi_jurnal','peserta_publikasi_jurnal','id_peneliti','id_publikasi_jurnal');
    }
    public function publikasi_buku(){
        return $this->belongsToMany('App\Model\publikasi_buku','peserta_publikasi_buku','id_peneliti','id_publikasi_buku');
    }
}
