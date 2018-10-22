<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchMembersRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'searchText' => ['required', 'string']
        ];
    }

    public function messages() {
        return [
            'searchText.required' => 'Le champ recherche est obligatoire'
        ];
    }
}
