<?php

namespace App\Http\Requests\Project;

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
            'name' => ['required'],
            'start_date' => ['required', 'date_format:d-m-Y'],
            'due_date' => ['date_format:d-m-Y', 'after:start_date'],
            'duration' => ['required'],
            'revenue' => ['required'],
            'detail' => ['required'],
            'users' => ['array'],
            'positions' => ['array']
        ];
    }
}
