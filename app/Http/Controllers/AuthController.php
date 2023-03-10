<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct()
    {
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(
            [
                'user' => auth()->user(),
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]
        );
    }

    public function register(RegisterRequest $request)
    {

        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]
        );

        $user->assignRole('user');

        $token = Auth::login($user);

        return response()->json(
            [
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ],201
        );
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(
            [
                'status' => 'success',
            ]
        );
    }

    public function refresh()
    {
        return response()->json(
            [
                'user' => Auth::user(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ]
        );
    }
}
