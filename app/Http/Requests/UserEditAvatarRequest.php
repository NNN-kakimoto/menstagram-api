<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserEditAvatarRequest extends FormRequest
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
            'avatar' => [
                'bail',
                'image',
                'max:5120'
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'avatar.image' => config('errors.user.avatar.image'),
            'avatar.max'   => config('errors.user.avatar.max'),
        ];
    }

     /**
     * @param Validator $validator
     */
    public function failedValidation(Validator $validator)
    {
        err_response($validator->errors()->toArray(), 400);
    }
}
