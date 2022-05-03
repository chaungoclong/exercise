<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomException;
use App\Facades\Support\Jsend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{
    public function __invoke(ChangePasswordRequest $request)
    {
        try {
            $user = auth()->user();

            // Check current password same user's password
            if (!Hash::check($request->current_password, $user->password)) {
                return Jsend::sendError(
                    __('Current password is incorrect'),
                    [],
                    400
                );
            }

            // Update password
            $user->update(['password' => Hash::make($request->new_password)]);
        } catch (\Exception $e) {
            Log::error($e);

            return Jsend::sendError(
                __('Change password failed'),
                [],
                500
            );
        }

        // Response success
        return Jsend::sendSuccess(__('Change password success'));
    }
}
