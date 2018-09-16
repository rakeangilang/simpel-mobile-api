<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class peserta_kegiatan extends Model
{
    protected $table = 'peserta_kegiatan';


    protected $fillable = [
        'id_peneliti', 'id_kegiatan', 'status_konfirm', 'id_peran'
    ];
    public $timestamps = false;

    public function peneliti(){
        return $this-> belongsTo('App\Model\peneliti','id_peneliti');
    }

    public function kegiatan(){
        return $this-> belongsTo('App\Model\kegiatan','id_kegiatan');
    }
}
