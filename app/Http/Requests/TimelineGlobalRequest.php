<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * グローバルタイムライン
 *
 * Class TimelineGlobalRequest
 * @package App\Http\Requests
 */
class TimelineGlobalRequest extends FormRequest
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
            'slurp_id' => ['integer', 'exists:slurps,id', ],
            'type'     => ['in:old,new', ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'slurp_id.integer' => config('errors.slurp.id.integer'),
            'slurp_id.exists'  => config('errors.slurp.id.exists'),

            'type.in'          => config('errors.general.type.in'),
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
