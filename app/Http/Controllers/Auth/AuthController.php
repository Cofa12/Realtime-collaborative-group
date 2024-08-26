<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    //
    use HasApiTokens;
    public function login(Request $request){
        try{
            $user = Auth::attempt($request->only(['email','password']));
            if(!$user){
                return response()->json([
                    'status'=>false,
                    'message'=>'Email or Password are not correct'
                ],401);
            }
            $user = User::where('email',$request->email)->first();

            $token = $user->createToken('api_token')->plainTextToken;


            return response()->json([
                'status'=>true,
                'message'=>'user logged in successfully',
                'data'=> [
                    'user'=>$user,
                    'token'=>$token
                ]
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>'server error',
                'error'=>$e->getMessage(),
            ],500);
        }
    }

    public function signUp(Request $request){
        try{
            // validations
            $validator = validator::make($request->all(),[
                'email'=>'email|string|required',
                'password'=>'string|required|min:8',
                'name'=>'string|required|'
            ]);
            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>$validator->errors(),
                ],422 );
            }

            $user = User::create([
                'email'=>$request->email,
                'name'=>$request->name,
                'password'=>Hash::make($request->password)
            ]);

            if(!$user){
                return response()->json([
                    'status'=>false,
                    'message'=>'Can\'t return a user data'
                ],401);
            }

            return response()->json([
                'status'=>true,
                'message'=>'User has been created successfully'
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>'server error',
                'error'=>$e->getMessage(),
            ],500);
        }
    }
}
