<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transformers\UserTransformer;
use App\User;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request, User $user)
    {
        if(!Auth::attempt(['username' => $request->username, 'password' => $request->password]))
        {
            return response()->json(['error' => 'username atau password salah'], 401);
        }
        $user = $user->find(Auth::user()->id);
        $response = fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->addMeta([
                'token' => bcrypt($request->email),
            ])
            ->toArray();
        return response() -> json($response, 200);
    }
}
