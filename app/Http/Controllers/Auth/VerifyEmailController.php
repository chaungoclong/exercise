<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailVerify;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Show form verify email
     *
     * @return void
     */
    public function showVerifyEmailPage()
    {
        return view(
            'pages.auth.verify-email',
            [
                'email' => auth()->user()->email
            ]
        );
    }

    /**
     * Verify Email
     *
     * @param EmailVerificationRequest $request automatically take care of
     * validating the request's id and hash parameters
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        // Mark email as verified (read doc)
        $request->fulfill();

        // Redirect to intended URL after verified email
        return redirect()->intended(route('home'));
    }

    /**
     * Send email verification notification
     *
     * @param Request $request
     */
    public function sendEmailVerificationNotification(Request $request)
    {
        SendEmailVerify::dispatch($request->user())
            ->onConnection('database')
            ->onQueue('test')
            ->afterCommit();

        toast()->success(__('Verification link sent!'))
            ->width('350px')
            ->position('top-end')
            ->timerProgressBar();

        return back();
    }
}
