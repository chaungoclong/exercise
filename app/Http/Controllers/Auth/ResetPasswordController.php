<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Show forgot password form
     *
     */
    public function showForgotPasswordForm()
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Send link reset passworf
     *
     * @param Request $request
     */
    public function sendEmailResetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            toast()
                ->success(__($status))
                ->width('350px')
                ->position('top-end')
                ->timerProgressBar();
        } else {
            toast()
                ->error(__($status))
                ->width('350px')
                ->position('top-end')
                ->timerProgressBar();
        }

        return back();
    }

    /**
     * Show reset password form
     *
     * @param string $token
     */
    public function showResetPasswordForm(string $token)
    {
        return view(
            'pages.auth.reset-password',
            [
                'token' => $token
            ]
        );
    }

    /**
     * Reset password
     *
     * @param Request $request
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:password'
        ]);

        $status = Password::reset(
            $request
                ->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            toast()
                ->success(__($status))
                ->width('350px')
                ->position('top-end')
                ->timerProgressBar();

            return redirect()->guest(route('login.form'));
        } else {
            toast()
                ->error(__($status))
                ->width('350px')
                ->position('top-end')
                ->timerProgressBar();

            return back();
        }
    }
}
