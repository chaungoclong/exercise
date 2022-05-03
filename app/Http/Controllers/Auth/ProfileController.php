<?php

namespace App\Http\Controllers\Auth;

use PDOException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Facades\Support\Jsend;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepository;
use App\Http\Requests\Auth\UpdateAvatarRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfileController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Show the current user's profile
     *
     * @return void
     */
    public function show()
    {
        return view(
            'pages.auth.profile',
            [
                'profile' => auth()->user(),
                'configData' => \App\Helpers\Helper::applClasses(),
                'breadcrumbs' => [
                    ['link' => "profile", 'name' => "Profile"]
                ]
            ]
        );
    }


    /**
     * Update the current user's profile
     *
     * @param Request $request
     * @param [type] $id
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            $user->update($request->all());

            return Jsend::sendSuccess(
                __('Profile update successful'),
                ['profile' => $user->append(['fullName'])],
                200
            );
        } catch (\Exception $e) {
            Log::error($e);

            return Jsend::sendError(
                __('Profile update failed'),
                [],
                500
            );
        }
    }

    /**
     * Update current user avatar
     *
     * @return void
     */
    public function updateAvatar(UpdateAvatarRequest $request)
    {
        try {
            // Check file is exist
            if (!$request->hasFile('avatar')) {
                return Jsend::sendError(__('No Data'), [], 400);
            }

            // Upload file and update user'path avatar
            $file = $request->file('avatar');
            $path = $this->userRepository->uploadAvatar($file);

            auth()->user()->update(['avatar' => $path]);

            return Jsend::sendSuccess(
                __('Upload avatar success'),
                ['path' => $path]
            );
        } catch (\Exception $e) {
            return Jsend::sendError(__('Upload avatar failed'), [], 500);
        }
    }
}
