<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestion extends FormRequest
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
        $expe = '16,32,64';
        return [
            'question' => 'required|max:255',
            'expe' => 'required|numeric|in:' . $expe,
            'file' => 'nullable|numeric|min:1'
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
            'question.required' => 'Le contenu de la question est requis',
            'expe.required' => 'L\'expérience est requise',
            'expe.numeric' => 'L\expérience doit être un nombre',
            'file.numeric' => 'Le fichier choisi n\'a pas un ID valide',
            'file.min' => 'Le fichier choisi n\'a pas un ID valide',
            'content.max' => 'Le contenu de la question doit comporter moins de 255 caractères',
            'expe.in' => 'Cette valeur d\' expérience n\'existe pas',
        ];
    }
}
