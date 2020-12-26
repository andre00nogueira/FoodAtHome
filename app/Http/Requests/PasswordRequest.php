<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use App\Rules\MatchOldPassword;

class PasswordRequest extends FormRequest
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
    return [//'currentPassword' => ['required', 'string','min:3'/*, new MatchOldPassword*/], 
                'password' => ['required', 'string', 'min:3', 'confirmed']  
          ];
    }
}
