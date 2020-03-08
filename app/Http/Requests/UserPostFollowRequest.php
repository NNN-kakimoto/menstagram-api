<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * フォロー
 *
 * Class UserPostFollowRequest
 * @package App\Http\Requests
 */
class UserPostFollowRequest extends FormRequest
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
            'target_user_id' => ['required', 'regex:/^[a-zA-Z0-9_]+$/', 'between:1,16', 'exists:users,user_id', ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'target_user_id.required' => config('errors.follow.target_user_id.required'),
            'target_user_id.regex'    => config('errors.follow.target_user_id.regex'),
            'target_user_id.between'  => config('errors.follow.target_user_id.between'),
            'target_user_id.exists'   => config('errors.follow.target_user_id.exists'),
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
