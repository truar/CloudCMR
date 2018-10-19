<?php

namespace App\Http\Requests;

class UpdatePhoneRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'number' => ['required', 'string']
        ];
    }

    public function messages() {
        return [
            'number.required' => 'Le numéro de téléphone est obligatoire'
        ];
    }
}
