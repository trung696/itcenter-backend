<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ActiveUserController extends Controller
{
    public function active(User $user, $token){
        $tokens =  rtrim($token, '}');
         if($user->tokenActive === $tokens){
             $user->update(['status'=>1,
                            'tokenActive' => null]);
                            
         }else{
             // return redirect()->route('')->with('NO','chưa xác thực đưuọc tài khoản');
         }
     }
}
