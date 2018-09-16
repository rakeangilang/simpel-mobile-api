<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\pegawai;
use App\Transformers\UserTransformer;

class UserController extends Controller
{
    public function pekerja(pegawai $pegawai)
    {
        $pegawais = $pegawai -> all();

        return fractal() 
            -> collection($pegawais)
            -> transformWith(new UserTransformer)
            -> toArray();
    }
}
