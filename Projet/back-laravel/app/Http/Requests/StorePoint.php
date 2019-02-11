<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePoint extends FormRequest
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
            'desc' => 'required',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180'
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
            'desc.required' => 'Une description est requise',
            'latitude.required' => 'Une latitude est requise',
            'longitude.required' => 'Une longitude est requise',
            'latitude.numeric' => 'La latitude doit être un nombre',
            'longitude.numeric' => 'La longitude doit être un nombre',
            'latitude.min' => 'La latitude doit être plus grande que -90',
            'latitude.max' => 'La latitude doit être plus petite que 90',
            'longitude.min' => 'La longitude doit être plus grande que -180',
            'longitude.max' => 'La longitude doit être plus petite que 180',
        ];
    }
}
