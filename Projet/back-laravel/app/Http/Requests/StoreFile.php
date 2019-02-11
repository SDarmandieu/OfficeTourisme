<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFile extends FormRequest
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
        $image_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $audio_ext = ['mp3', 'ogg', 'mpga'];
        $video_ext = ['mp4', 'mpeg'];
        $all_ext = implode(',', array_merge($image_ext, $audio_ext, $video_ext));
        return [
            'file' => 'required|file|mimes:' . $all_ext . '|max:15000',
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
            'file.required' => 'Le fichier est requis',
            'file.mime' => 'Le fichier choisi doit être d\'un des extensions suivantes : jpg jpeg png gif mp3 mp4 ogg mpga mp4 mpeg',
            'file.max' => 'La taille du fichier ne doit pas excéder 15Mo',
            'alt.required' => 'L\'alternative textuelle est requise',
            'alt.max' => 'L\'alternative textuelle doit comporter moins de 255 caractères',
            'imagetype.numeric' => 'Le type d\'image choisi n\'a pas un ID valide',
            'imagetype.min' => 'Le type d\'image choisi n\'a pas un ID valide',
        ];
    }
}
