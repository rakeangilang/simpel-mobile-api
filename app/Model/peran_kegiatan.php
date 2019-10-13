<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class peran_kegiatan extends Model
{
    protected $table = 'peran_kegiatan';


    protected $fillable = [
        'id_tipe_kegiatan', 'id_peran'
    ];
    public $timestamps = false;
