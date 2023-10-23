<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                    'message' => 'User created successfully',
                    'user' => $user,
                    'token'=> $this->token($user)
                ],
                201
            );
        } catch (\Exception $ve) {
            return response()->json(['message' => 'Error'], 500);
        }
    }
    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Enter email and password correctly',
                    'errors' => $validator->errors()], 422);
            }

            if(!Auth ::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'message' => 'Enter correct email and password',
                    'errors' => $validator->errors()], 422);
            }
            $user = User::where('email', $request->email)->first();
            return response()->json([
                    'message' => 'User logged in successfully',
                    'user' => $user,
                    'token'=> $this->token($user)
                ],
                201
            );
        } catch (\Exception $ve) {
            return response()->json(['message' => 'Error'], 500);
        }
    }

    private function token($user) {
        return $user->createToken("API TOKEN")->plainTextToken;
    }
}
