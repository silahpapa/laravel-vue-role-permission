<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthControllers extends Controller
{
    public function login(){
        $data = request()->all();
        $rules = [
            'email'=>'required',
            'password'=>'required'
        ];
        $valid = Validator::make($data,$rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ], 422);
        }
        $email = request('email');
        $password = request('password');
        if(Auth::attempt(['email'=>$email,'password'=>$password])){
            $token = request()->user()->createToken('api_token_at_'.now()->toDateTimeString());
            return response([
                'status'=>'success',
                'access_token'=>$token->plainTextToken,
                'user'=>request()->user()
            ]);
        }
        return response([
            'status'=>'failed',
            'errors'=>['email'=>['Invalid email or password']]
        ],422);
    }

    public function register(){
        $rules = [
            'email'=>'required|unique:users',
            'name'=>'required',
            'password'=>'required|confirmed',
        ];
        $data = request()->all();
        $valid = Validator::make($data,$rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ], 422);
        }
        $password = \request('password');
        User::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'password'=>Hash::make($password),
            'role_id' => \request('role_id'),
            'department_id' => \request('department_id')
        ]);
        if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
            $token = request()->user()->createToken('api_token_at_'.now()->toDateTimeString());
            return response([
                'status'=>'success',
                'access_token'=>$token->plainTextToken,
                'user'=>request()->user()
            ]);
        }
    }

    public function getUser() {
        $user = Auth::getUser();
        return response([
            'status' => 'success',
            'user' => $user,
            'role' => $user->role
        ]);
    }

}
