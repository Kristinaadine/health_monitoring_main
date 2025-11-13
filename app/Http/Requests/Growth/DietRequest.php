<?php

namespace App\Http\Requests\Growth;

use Illuminate\Foundation\Http\FormRequest;

class DietRequest extends FormRequest
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
            'nama' => 'required|string',
            'usia' => 'required|integer|min:1',
            'jenis_kelamin' => 'required|in:L,P',
            'berat_badan' => 'required|numeric|min:1',
            'tinggi_badan' => 'required|numeric|min:1',
            'frekuensi_sayur' => 'required|integer|min:1|max:5',
            'konsumsi_protein' => 'required|integer|min:1|max:5',
            'konsumsi_karbo' => 'required|integer|min:1|max:5',
            'konsumsi_gula' => 'required|integer|min:1|max:5',
            'vegetarian' => 'nullable|boolean',
            'frekuensi_jajan' => 'required|integer|min:0',
            'target' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',

            'usia.required' => 'Usia wajib diisi.',
            'usia.integer' => 'Usia harus berupa angka.',
            'usia.min' => 'Usia minimal adalah 1 tahun.',

            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',

            'berat_badan.required' => 'Berat badan wajib diisi.',
            'berat_badan.numeric' => 'Berat badan harus berupa angka.',
            'berat_badan.min' => 'Berat badan minimal adalah 1 kg.',

            'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
            'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
            'tinggi_badan.min' => 'Tinggi badan minimal adalah 1 cm.',

            'frekuensi_sayur.required' => 'Frekuensi konsumsi sayur wajib diisi.',
            'frekuensi_sayur.integer' => 'Frekuensi konsumsi sayur harus berupa angka.',
            'frekuensi_sayur.min' => 'Frekuensi konsumsi sayur minimal adalah 1.',
            'frekuensi_sayur.max' => 'Frekuensi konsumsi sayur maksimal adalah 5.',

            'konsumsi_protein.required' => 'Frekuensi konsumsi protein wajib diisi.',
            'konsumsi_protein.integer' => 'Frekuensi konsumsi protein harus berupa angka.',
            'konsumsi_protein.min' => 'Frekuensi konsumsi protein minimal adalah 1.',
            'konsumsi_protein.max' => 'Frekuensi konsumsi protein maksimal adalah 5.',

            'konsumsi_karbo.required' => 'Frekuensi konsumsi karbohidrat wajib diisi.',
            'konsumsi_karbo.integer' => 'Frekuensi konsumsi karbohidrat harus berupa angka.',
            'konsumsi_karbo.min' => 'Frekuensi konsumsi karbohidrat minimal adalah 1.',
            'konsumsi_karbo.max' => 'Frekuensi konsumsi karbohidrat maksimal adalah 5.',

            'konsumsi_gula.required' => 'Frekuensi konsumsi gula wajib diisi.',
            'konsumsi_gula.integer' => 'Frekuensi konsumsi gula harus berupa angka.',
            'konsumsi_gula.min' => 'Frekuensi konsumsi gula minimal adalah 1.',
            'konsumsi_gula.max' => 'Frekuensi konsumsi gula maksimal adalah 5.',

            'vegetarian.boolean' => 'Nilai vegetarian harus berupa true atau false.',

            'frekuensi_jajan.required' => 'Frekuensi jajan wajib diisi.',
            'frekuensi_jajan.integer' => 'Frekuensi jajan harus berupa angka.',
            'frekuensi_jajan.min' => 'Frekuensi jajan minimal adalah 0.',

            'target.string' => 'Target harus berupa teks.',
        ];
    }
}
