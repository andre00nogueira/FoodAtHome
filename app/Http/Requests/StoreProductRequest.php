<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
            'name' => ['required','regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/', Rule::unique('products')->ignore($this->product)],
            'type' => 'required|in:dessert,drink,hot dish,cold dish',
            'description' => 'required|min:3|max:500',
            'price' =>'required|numeric|min:0.01|max:1000',
            'photo_url' => 'required|image'
        ];
    }
}
