<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGame extends FormRequest
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
        $ages = '7/9 ans,9/11 ans,11/13 ans';
        return [
            'name' => 'required|max:50',
            'desc' => 'required|max:255',
            'age' => 'required|in:' . $ages,
            'icon' => 'nullable|numeric|min:1'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Un nom est requis',
            'desc.required' => 'Une description est requise',
            'age.required' => 'Une tranche d\'âge est requise',
            'latitude.numeric' => 'La latitude doit être un nombre',
            'longitude.numeric' => 'La longitude doit être un nombre',
            'name.max' => 'Le nom doit comporter moins de 50 caractères',
            'desc.max' => 'La description doit comporter moins de 255 caractères',
            'age.in' => 'Cette tranche d\' âge n\'existe pas',
            'icon.numeric' => 'L\'icône choisi n\' a pas un ID valide',
            'icon.min' => 'L\'icône choisi n\' a pas un ID valide'
        ];
    }
}
