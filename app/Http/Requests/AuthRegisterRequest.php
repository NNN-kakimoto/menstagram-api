<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * ユーザーの登録
 *
 * Class AuthRegisterRequest
 * @package App\Http\Requests
 */
class AuthRegisterRequest extends FormRequest
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
            'user_id'   => ['required', 'regex:/^[a-zA-Z0-9_]+$/', 'between:1,16', 'unique:users', ],
            'user_name' => ['required', 'string', 'between:1,16', ],
            'email'     => ['required', 'email', 'unique:users', ],
            'password'  => ['required', 'string', 'min:8', ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'user_id.required'   => config('errors.user.user_id.required'),
            'user_id.regex'      => config('errors.user.user_id.regex'),
            'user_id.between'    => config('errors.user.user_id.between'),
            'user_id.unique'     => config('errors.user.user_id.unique'),

            'user_name.required' => config('errors.user.user_name.required'),
            'user_name.string'   => config('errors.user.user_name.string'),
            'user_name.between'  => config('errors.user.user_name.between'),

            'email.required'     => config('errors.user.email.required'),
            'email.email'        => config('errors.user.email.email'),
            'email.unique'       => config('errors.user.email.unique'),

            'password.required' => config('errors.user.password.required'),
            'password.string'   => config('errors.user.password.string'),
            'password.min'      => config('errors.user.password.min'),
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        err_response($validator->errors()->toArray(), 400);
    }
}
