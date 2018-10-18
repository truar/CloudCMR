<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Validator;

class CreateMemberRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {        
        return [
            'lastname' => ['required', 'string'],
            'firstname' => ['required', 'string'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'birthdate' => ['required', 'date_format:d/m/Y'],
            'email' => ['required', 'email']
        ];
    }

    /**
     * Adds a new Validator with a custom rule to validate the unicity of Lastname/Firstame/Birthdate
     * We want to do the last validation for the unicity only if the validation process passed
     * Can't be done elsewhere because :
     * - if the birthdate is given with a wrong format, carbon generates error
     * - if we use bail on the birthdate field, it is not working because Eloquent will compare the String birhtdate from the 
     *   request with the birthdate in the DB. they won't be the same as the type ad format are differents
     * 
     * TODO : Look for a prettier solution
     */
    public function withValidator($validator) {
        // We get the id from the member in the request, or we don't need an id
        $id = isset($this->member) ? $this->member->id : null;

        if($validator->passes()) {
            $validator->after(function ($validator) use ($id) {
                Validator::make($this->all(),
                    ['lastname' => Rule::unique('members')->where(
                        function ($query) {
                            return $query->where('lastname', $this->lastname)
                                ->where('firstname', $this->firstname)
                                ->whereDate('birthdate', Carbon::createFromFormat('d/m/Y', $this->birthdate));
                        })->ignore($id)], ['lastname.unique' => 'Le membre existe déjà en base'])->validate();
            });
        }
    }

    /**/
    public function messages() {
        return [
            'lastname.required' => 'Le nom est obligatoire',
            'lastname.string' => 'Le nom doit être composé uniquement de lettres',
            'firstname.required' => 'Le prénom est obligatoire',
            'firstname.string' => 'Le prénom doit être composé uniquement de lettres',
            'gender.required' => 'Le genre est obligatoire',
            'gender.in' => 'Le genre doit être Homme ou Femme uniquement',
            'birthdate.required' => 'La date de naissance est obligatoire',
            'birthdate.date_format' => 'Le format de la date de naissace est jj/mm/aaaa (ex: 25/05/1980 pour le 25 mai 1980)',
            'email.required' => 'L\'adresse email est obligatoire',
            'email.email' => 'Veuillez rentrer un format d\'email valide'
        ];
    }
    
}