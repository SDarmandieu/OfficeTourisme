<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnswer extends FormRequest
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
        $valid = '0,1';
        return [
            'answer' => 'required|max:255',
            'valid' => 'required|numeric|in:' . $valid,
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
            'answer.required' => 'Le contenu de la question est requis',
            'valid.required' => 'La validité de la réponse est requise',
            'valid.numeric' => 'La véracité de la réponse n\'est pas valide',
            'file.numeric' => 'Le fichier choisi n\'a pas un ID valide',
            'file.min' => 'Le fichier choisi n\'a pas un ID valide',
            'answer.max' => 'Le contenu de la réponse doit comporter moins de 255 caractères',
            'valid.in' => 'La véracité de la réponse n\'est pas valide',
        ];
    }
}
