<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * ユーザーの編集
 *
 * Class UserEditRequest
 * @package App\Http\Requests
 */
class UserEditRequest extends FormRequest
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
            'user_name' => ['string', 'between:1,16', ],
            'biography' => ['max:128', ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'user_name.string'  => config('errors.user.user_name.string'),
            'user_name.between' => config('errors.user.user_name.between'),

            'biography.max'     => config('errors.user.biography.max'),
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
