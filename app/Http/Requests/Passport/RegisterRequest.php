<?php

namespace App\Http\Requests\Passport;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get validation rules that apply to request
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('users', 'email'),
            ],
            'password' => [
                'required',
                'regex:/^(?![^a-zA-Z]+$)(?!\D+$).{6,30}$/',
            ],
            'password_confirm' => 'required|same:password',
        ];
    }
}
