<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNutritionRequest extends FormRequest
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
            'calorie_target' => 'required|numeric|min:500|max:5000',
            'nutrient_ration' => 'required|exists:nutrient_ratio,id',
        ];
    }

    public function messages(): array
    {
        return [
            'calorie_target.required' => 'Target kalori wajib diisi.',
            'calorie_target.numeric' => 'Target kalori harus berupa angka.',
            'calorie_target.min' => 'Target kalori minimal adalah 500 kcal.',
            'calorie_target.max' => 'Target kalori maksimal adalah 5000 kcal.',
            
            'nutrient_ration.required' => 'Rasio nutrisi wajib dipilih.',
            'nutrient_ration.exists' => 'Rasio nutrisi yang dipilih tidak valid.',
        ];
    }
}
