<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class riwayat_pendidikan extends Model
{
    protected $table = 'riwayat_pendidikan';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'id_pegawai', 'tingkat', 'nama_sekolah','spesialisasi','tahun_lulus'
    ];
    public $timestamps = false;
}
