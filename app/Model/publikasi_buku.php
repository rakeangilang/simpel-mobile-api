<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class publikasi_buku extends Model
{
   protected $table = 'publikasi_buku';
   protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'judul_buku', 'judul_book_chapter', 'nama_penerbit', 'tahun_terbit', 'isbn'
    ];
    public $timestamps = false;

  public function peneliti(){
        return $this->belongsToMany('App\Model\peneliti','peserta_publikasi_buku','id_publikasi_buku','id_peneliti');
    }
 	
}
