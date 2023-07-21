<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => 'required|min:3',
            "email" => "required|email|unique:users",
            "password" => "required|min:8|confirmed",
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        // return $user;

        return response()->json([
            "message" => "register successfully",
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:8"
        ]);


        if (!Auth::attempt($request->only('email', "password"))) {
            return response()->json([
                "message" => "email or password wrong"
            ]);
        }

        return $request->user()->createToken("android")->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "logout successfully",
        ]);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            "message" => "logout from all devices successfully",
        ]);
    }

    public function devices(Request $request)
    {
        return $request->user()->tokens;
    }
}
