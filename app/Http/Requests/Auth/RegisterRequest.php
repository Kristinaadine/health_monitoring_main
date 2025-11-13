<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                          => 'required|min:3|max:50',
            'email'                         => 'required|unique:users|email',
            'password'                      => 'required|same:password2|min:6',
            'password2'                     => 'required',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'name.required'                 => 'Name is required.',
    //         'name.min'                      => 'Name min 3 Character.',
    //         'name.max'                      => 'Name max 50 Character.',
    //         'phone.required'                => 'Phone Number is required.',
    //         'email.unique'                  => 'Email already taken.',
    //         'password.required'             => 'Password is required.',
    //         'password.min'                  => 'Password min 6 Character.',
    //         'password.confirmed'            => 'Password does not match.',
    //         'password2.required'            => 'Confirmation Password is required.',
    //     ];
    // }
}
