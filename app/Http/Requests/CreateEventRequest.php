<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CreateEventRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'name' => ['required', 'string'],
            'startDate' => ['required', 'date_format:Y-m-d h:i:s'],
            'type' => ['required', Rule::in(['SORTIE', 'SOIREE'])],
            'price' => ['required', 'numeric', 'min:0']
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Le nom est obligatoire'
        ];
    }
}
