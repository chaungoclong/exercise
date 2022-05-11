<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'username' => ['required', 'unique:users,username'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'min:8', 'same:password'],
            'address' => ['required'],
            'avatar' => ['image', 'max:600'],
            'phone' => [
                'required',
                'regex:/(84|0[3|5|7|8|9])+([0-9]{8})\b/',
                'unique:users,phone'
            ],
            'gender' => ['required'],
            'role_id' => ['required'],
            'birthday' => ['required']
        ];
    }
}
