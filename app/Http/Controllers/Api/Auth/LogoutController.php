<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return \Jsend::sendSuccess(
            'logout success',
            ['links' => ['next' => route('api.login')]]
        );
    }
}
