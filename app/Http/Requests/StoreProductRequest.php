<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'price' =>'required|numeric|max:10',
            'photo_url' => 'nullable' //|mimes:jpeg,png
        ];
    }
}
