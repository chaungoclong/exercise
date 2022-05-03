<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currentUserId = auth()->id();

        return [
            'username' => [
                'required',
                Rule::unique('users')->ignore($currentUserId),
            ],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => [
                'required',
                Rule::unique('users')->ignore($currentUserId)
            ],
            'gender' => ['required'],
            'address' => ['required'],
            'birthday' => ['required'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($currentUserId),
            ],
        ];
    }
}
