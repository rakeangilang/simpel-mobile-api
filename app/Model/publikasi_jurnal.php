<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class publikasi_jurnal extends Model
{
   protected $table = 'publikasi_jurnal';
   protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'judul_artikel', 'status_akreditasi', 'nama_berkala', 'volume_halaman', 'url', 'tahun_terbit'
    ];
    public $timestamps = false;

   public function peneliti(){
        return $this->belongsToMany('App\Model\peneliti','peserta_publikasi_jurnal','id_publikasi_jurnal','id_peneliti');
    }
 
}
