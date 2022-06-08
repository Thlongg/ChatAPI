<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public $successStatus = 200;
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user();
            $user['rememberToken'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json([
                'success' => true,
                'token'=>  $user['rememberToken'],
                'user_info' => [
                    'name'=> $user->name,
                    'email'=>$user->email,
                    'id'=> $user->id
                ]
            ], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['success'=>'false', 'message'=>'can not login'], 401); 
        } 
    }
    
    public function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email|unique:users', 
            'password' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 401);            
        }
        $dataCreate = $request->all();
        $dataCreate['password'] = Hash::make($request->password); 
        $user = User::create($dataCreate); 
        $user['rememberToken'] =  $user->createToken('MyApp')-> accessToken; 
        return response()->json([
            'success'=>true,
            'user_name'=>$user->name,
            'user_email'=>$user->email,
            'message'=> 'login success'
        ], $this-> successStatus); 
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out',
            'status'=>true
        ]);
    }

    public function user(Request $request)
    {
        try{
            return response()->json($request->user());
        }
        catch(Exception $e) {
            report($e);
     
            return response()->json(['message' =>'Can not found user'], Response::HTTP_UNAUTHORIZED); 
            // return false;
        }
    }
}
