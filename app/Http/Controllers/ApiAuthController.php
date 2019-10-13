<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Transformers\UserTransformer;
use Auth;

class ApiAuthController extends Controller
{
  public function login(Request $request, User $user)
  {
      if(!Auth::attempt(['username' => $request->username, 'password' => $request->password]))
      {
          return response()->json(['error' => 'Username atau password salah'], 401);
      }
      $user = $user->find(Auth::user()->id);
      if($user->api_token==NULL){
        $user->api_token = bcrypt($request->username);
        $user->save();
      }
      $data_user = $user;

      return response()->json([
        'success'=>true,
        'message'=>"Berhasil login",
        'data_user'=>$data_user
      ]);
      //$user = $user->find(Auth::user()->id);
      //return fractal()
      //    ->item($user)
      //    ->transformWith(new UserTransformer)
      //    ->addMeta([
      //        'token' => $user->api_token,
      //    ])
      //    ->toArray();
  }
}
