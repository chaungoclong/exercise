<?php

namespace App\Http\Controllers\Auth;

use App\Facades\Support\Jsend;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Auth\Events\Registered;

/**
 * Overview:
 * this Controler use for Register new user
 */
class RegisterController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function showFormRegister()
    {
        $configData = \App\Helpers\Helper::applClasses();

        return view('pages.auth.register', ['configData' => $configData]);
    }

    /**
     * [register description]
     * @param  RegisterRequest $request [description]
     * @return [type]                   [description]
     */
    public function register(RegisterRequest $request)
    {
        $payload = $request->validated();

        try {
            // upload file if exist
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $payload['avatar'] = $this->userRepository->uploadAvatar($file);
            }

            // encode password
            $payload['password'] = Hash::make($payload['password']);

            $user = $this->userRepository->registerUser($payload);

            event(new Registered($user));

            return $this->responseSuccess($user);
        } catch (\Exception $e) {
            Log::error($e);

            return $this->responseFailed();
        }
    }

    /**
     * response when register success with ajax request
     * @return [type] [description]
     */
    public function responseSuccess($user)
    {
        $message = __('Register success');

        $data = [
            'user' => $user,
            'links' => [
                'redirectTo' => route('login.form')
            ]
        ];

        return Jsend::sendSuccess($message, $data, 201);
    }

    /**
     * response when register failed with ajax request
     * @return [type] [description]
     */
    public function responseFailed()
    {
        $message = __('Register failed');

        return Jsend::sendError($message, [], 500);
    }
}
