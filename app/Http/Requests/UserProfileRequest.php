<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * ユーザーのプロフィール
 *
 * Class UserProfileRequest
 * @package App\Http\Requests
 */
class UserProfileRequest extends FormRequest
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
            'user_id' => ['regex:/^[a-zA-Z0-9_]+$/', 'between:1,16', 'exists:users,user_id', ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'user_id.regex'   => config('errors.user.user_id.regex'),
            'user_id.between' => config('errors.user.user_id.between'),
            'user_id.exists'  => config('errors.user.user_id.exists'),
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        if (array_key_exists('Exists', $validator->failed()['user_id'])) {
            err_response($validator->errors()->toArray(), 404);
        } else {
            err_response($validator->errors()->toArray(), 400);
        }
    }
}
