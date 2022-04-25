<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

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
        $configData = \Helper::applClasses();

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
        $message = __('action success', ['Action' => 'Đăng ký']);

        $data = [
            'user' => $user,
            'links' => [
                'redirectTo' => route('login.form')
            ]
        ];

        return \Jsend::sendSuccess($message, $data, 201);
    }

    /**
     * response when register failed with ajax request
     * @return [type] [description]
     */
    public function responseFailed()
    {
        $message = __('action fail', ['Action' => 'Đăng ký']);

        return \Jsend::sendError($message, [], 500);
    }
}
