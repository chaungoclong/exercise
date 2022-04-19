<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAccountDetailRequest;
use App\Http\Requests\RegisterPersonalInfoRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showFormRegister()
    {
        return view('pages.auth.register');
    }

    public function registerAccountDetails(RegisterAccountDetailRequest $request)
    {
        return \Jsend::sendSuccess();
    }

    //
    public function registerPersonalInfo(RegisterPersonalInfoRequest $request)
    {
        try {
             $user = $this->authService->registerUser($request->all());
             $redirectTo = route('home');
        } catch (\Exception $e) {
        }
    }

    /**
     * [redirectIfSuccess description]
     * @return [type] [description]
     */
    public function redirectIfSuccess()
    {
        alert()->success('Register successfully');

        return redirect()->route('home');
    }

    /**
     * [redirectIfFail description]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function redirectIfFail($value = '')
    {
        alert()->error('Register failed');

        return redirect()->back()->withInput();
    }
}
