<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\NoPermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showFormLogin()
    {
        return view('pages.auth.login');
    }

    /**
     * [login description]
     * @param  LoginRequest $request [description]
     * @return [type]                [description]
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $remember    = $request->has('remember');

        if (!\Auth::attempt($credentials, $remember)) {
            return $this->loginFailed();
        }

        // If user is inactive then logout and throw NoPermissionException.
        // Exception is handled in Class App\Exceptions\Handler.php
        $user = auth()->user();

        if (!$user->isActive()) {
            auth()->logout();

            throw new NoPermissionException();
        }

        return $this->loginSuccess();
    }

    /**
     * [loginSuccess description]
     * @return [type] [description]
     */
    public function loginSuccess()
    {
        session()->regenerate();

        toast()->success(__('action success', ['Action' => 'Đăng nhập']))
            ->width('350px')
            ->position('top-end')
            ->timerProgressBar();

        return redirect()->intended(route('home'));
    }

    /**
     * [loginFailed description]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function loginFailed()
    {
        toast()->error(__('auth.failed'))
            ->position('top-end')
            ->width('350px')
            ->timerProgressBar();

        return redirect()->back()->withInput();
    }
}
