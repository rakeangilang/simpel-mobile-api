<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class peneliti_nonPSB extends Model
{
    protected $table = 'peneliti_nonpsb';

    protected $fillable = [
        'id_peneliti', 'nama_peneliti', 'no_identitas', 'tipe_identitas'
    ];
    public $timestamps = false;

    public function peneliti(){
    	return $this->belongsTo('App\Model\peneliti', 'id_peneliti');
 	}
}
