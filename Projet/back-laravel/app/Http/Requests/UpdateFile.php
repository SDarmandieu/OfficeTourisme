<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFile extends FormRequest
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
            'alt' => 'required|max:255',
            'imagetype' => 'nullable|numeric|min:1'
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
            'alt.required' => 'L\'alternative textuelle est requise',
            'alt.max' => 'L\'alternative textuelle doit comporter moins de 255 caractères',
            'imagetype.numeric' => 'Le type d\'image choisi n\'a pas un ID valide',
            'imagetype.min' => 'Le type d\'image choisi n\'a pas un ID valide',
        ];
    }
}
