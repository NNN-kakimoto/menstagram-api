<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * フォロー一覧
 *
 * Class UserGetFollowRequest
 * @package App\Http\Requests
 */
class UserGetFollowRequest extends FormRequest
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
            'user_id'   => ['regex:/^[a-zA-Z0-9_]+$/', 'between:1,16', 'exists:users,user_id', ],
            'follow_id' => ['integer', 'exists:follows,id', ],
            'type'      => ['in:old,new', ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'user_id.regex'     => config('errors.user.user_id.regex'),
            'user_id.between'   => config('errors.user.user_id.between'),
            'user_id.exists'    => config('errors.user.user_id.exists'),

            'follow_id.integer' => config('errors.follow.id.integer'),
            'follow_id.exists'  => config('errors.follow.id.exists'),

            'type.in'           => config('errors.general.type.in'),
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
