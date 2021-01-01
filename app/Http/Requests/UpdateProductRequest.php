<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the product is authorized to make this request.
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
            'name' => 'required|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'type' => 'required|in:dessert,drink,hot dish,cold dish',
            'description' => 'required|min:3|max:500',
            'price' =>'required|numeric|max:1000',
            'photo_url' => 'image' //|mimes:jpeg,png
        ];
    }
}
