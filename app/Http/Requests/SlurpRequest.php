<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * スラープ
 *
 * Class SlurpRequest
 * @package App\Http\Requests
 */
class SlurpRequest extends FormRequest
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
            'image1' => ['image', 'max:5120', ],
            'image2' => ['image', 'max:5120', ],
            'image3' => ['image', 'max:5120', ],
            'image4' => ['image', 'max:5120', ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'image1.image' => config('errors.slurp.image.image'),
            'image1.max'   => config('errors.slurp.image.max'),

            'image2.image' => config('errors.slurp.image.image'),
            'image2.max'   => config('errors.slurp.image.max'),

            'image3.image' => config('errors.slurp.image.image'),
            'image3.max'   => config('errors.slurp.image.max'),

            'image4.image' => config('errors.slurp.image.image'),
            'image4.max'   => config('errors.slurp.image.max'),
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
