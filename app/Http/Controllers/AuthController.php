<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{

    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }
    public function login(LoginRequest $request)
    {
        $isLogged = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($isLogged) {
            $user = User::where('email', $request->email)->first();

            $tokens = $user->tokens();
            if ($tokens->count() > 0) {
                $tokens->delete();
            }


            return $this->successResponse([
                'token' => $user->createToken('auth_token')->plainTextToken,
                'user' => new UserResource($user)
            ]);
        } else {
            return $this->errorResponse('Email or password does not match!');
        }


    }
}