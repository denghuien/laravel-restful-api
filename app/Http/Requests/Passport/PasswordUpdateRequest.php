<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/8
 * Time: 11:39
 */

namespace App\Http\Requests\Passport;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
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
            'password_original' => 'required',
            'password' => [
                'required',
                'regex:/^(?![^a-zA-Z]+$)(?!\D+$).{6,30}$/',
            ],
            'password_confirm' => 'required|same:password',
        ];
    }
}
