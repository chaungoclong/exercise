<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'username' => [
                'required',
                'unique:users,username,' . $this->user->id
            ],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $this->user->id
            ],
            'address' => ['required'],
            'avatar' => ['image', 'max:600'],
            'phone' => [
                'required',
                'regex:/(84|0[3|5|7|8|9])+([0-9]{8})\b/',
                'unique:users,phone,' . $this->user->id
            ],
            'gender' => ['required'],
            'role_id' => ['required'],
            'birthday' => ['required']
        ];
    }
}
