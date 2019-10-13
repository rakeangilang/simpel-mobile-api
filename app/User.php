<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function pegawai(){
        return $this->belongsTo('App\Model\pegawai', 'id_pegawai');
    }

    public function tabel_user_si(){
        return $this->HasMany('App\Model\TabelUserSI','id_user');
    }
}
