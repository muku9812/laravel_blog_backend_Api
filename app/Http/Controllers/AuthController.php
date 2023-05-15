<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\support\facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request){
        $attrs= $request->validate([
            'name'=> 'required|String',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|min:6'
            ]);
        $user = User::create([
            'name'=>$attrs['name'],
            'email'=>$attrs['email'],
            'password'=> bcrypt($attrs['password'])
        ]);
        return response([
            'user'=>$user,
            'token'=>$user->createToken('secret')->plainTextToken,
            'message'=>'Registration success'

        ],200);

    }
    public function login(Request $request){
        $attrs= $request->validate([

            'email'=> 'required|email',
            'password'=> 'required|min:6'
        ]);
        if(!Auth::attempt($attrs)){
            return  response([
                'message'=>'Invalid credentials.'
            ],403);
        }


        return response([
            'user'=>auth()->user(),
            'token'=>auth()->user()->createToken('secret')->plainTextToken,
            'message'=>'successfull'

        ],200);

    }
    //logout

    public  function logout(){
        \auth()->user()->tokens()->delete();
    return response([
        'message'=>'Logout success'
    ],200);
    }

//get user details
public  function  user(){
        return response([
            'user'=>\auth()->user()
            ],200);
}

public function update(Request $request){
        $attrs=$request->validate([
            'name'=> 'required|String'

        ]);
    $image=$this->saveImage($request->image,'posts');
    \auth()->user()->update([
        'name'=>$attrs['name'],
        'image'=>$image
    ]);
    return response([
        'message'=>'Profile updated',
        'user'=>\auth()->user()
    ],200);

}
}
