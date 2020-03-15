<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;


/**
 * スラープを削除する
 *
 * Class SlurpDeleteRequest
 * @package App\Http\Requests
 */
class SlurpDeleteRequest extends FormRequest
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
            'slurp_id' => ['required', 'integer', 'exists:slurps,id', ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'slurp_id.required' => config('errors.slurp.id.required'),
            'slurp_id.integer'  => config('errors.slurp.id.integer'),
            'slurp_id.exists'   => config('errors.slurp.id.exists'),
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
