<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        return [
            'username' => ['unique:users,username'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required', 'unique:users,phone'],
            'gender' => ['required'],
            'address' => ['required'],
            'birthday' => ['required'],
            'avatar' => ['image', 'max:600'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ];
    }
}
