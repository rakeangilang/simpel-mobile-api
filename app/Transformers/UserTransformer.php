<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'nama' => $user -> username,
            'email'=> $user -> email,
            // 'status' => 200,
        ];
    }
}