<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //

    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8',
            'nohp'       => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name'  => $request->name,
            'email'  => $request->email,
            'nohp'  => $request->nohp,
            'role'  => $request->role,
            'is_active'  => 0,
            'is_ready'  => 0,
            'password'  => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data'          => $user,
            'access_token'  => $token,
            'token_type'    => 'Baerer'
        ]);
    }

    function login(Request $request)
    {
        // die('masuk');
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message'   => 'Unautorized'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'           => 'Hi ' . $user->name . ', welcome to home',
            'role'              => $user->role,
            'acccess_token'     => $token,
            'token_type'        => 'Baerer'
        ]);
    }


    function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message'   => 'You have successfully logged put and token was successfully deleted'
        ];
    }
}
