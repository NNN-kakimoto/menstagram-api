<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * スラープ(テキスト)
 *
 * Class SlurpTextRequest
 * @package App\Http\Requests
 */
class SlurpTextRequest extends FormRequest
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
            'text'     => ['required', 'string', 'between:1,256', ],
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

            'text.required'     => config('errors.slurp.text.required'),
            'text.string'       => config('errors.slurp.text.string'),
            'text.between'      => config('errors.slurp.text.between'),
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
