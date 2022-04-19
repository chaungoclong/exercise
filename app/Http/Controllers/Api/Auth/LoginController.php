<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends ApiController
{
    public function login(LoginRequest $request)
    {
        if (!\Auth::attempt($request->validated())) {
            return \Jsend::sendFail('login fail', [], 401);
        }
        
        $token = auth()->user()->createToken('auth_token')->plainTextToken;

        return \Jsend::sendSuccess(
            'login success',
            [
                'access_token' => $token,
                'links' => ['redirect_to' => route('home')]
            ],
        );
    }
}
