<?php

namespace App\Http\Requests\Growth;

use Illuminate\Foundation\Http\FormRequest;

class StuntingUserRequest extends FormRequest
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
            'nama' => 'required|string|max:120',
            'usia' => 'required|integer|min:0|max:60', // bulan
            'jenis_kelamin' => 'required|in:L,P',
            'berat_badan' => 'required|numeric|min:1|max:40',
            'tinggi_badan' => 'required|numeric|min:40|max:130',
            'lingkar_lengan' => 'nullable|numeric|min:5|max:25',

            // Kesehatan
            'riwayat_penyakit' => 'nullable|array',
            'riwayat_penyakit.*' => 'string',
            'menggunakan_obat' => 'nullable|boolean',
            'detail_obat' => 'nullable|string',
            'pola_pertumbuhan' => 'nullable|array',
            'pola_pertumbuhan.*.bulan' => 'required_with:pola_pertumbuhan|string',
            'pola_pertumbuhan.*.bb' => 'required_with:pola_pertumbuhan|numeric',
            'pola_pertumbuhan.*.tb' => 'required_with:pola_pertumbuhan|numeric',
            'frekuensi_sakit_6_bulan' => 'nullable|integer|min:0|max:24',

            // Nutrisi
            'sayur_buah' => 'nullable|integer|min:1|max:5',
            'protein' => 'nullable|integer|min:1|max:5',
            'karbohidrat' => 'nullable|integer|min:1|max:5',
            'gula' => 'nullable|integer|min:1|max:5',
            'vegetarian' => 'nullable|boolean',
            'frekuensi_jajan' => 'nullable|integer|min:1|max:5',

            // Lingkungan
            'akses_pangan' => 'nullable|array',
            'akses_pangan.*' => 'string',

            // Target & monitoring
            'target_tinggi' => 'nullable|boolean',
            'target_berat' => 'nullable|boolean',
            'target_gizi' => 'nullable|boolean',
            'izinkan_monitoring' => 'nullable|boolean',
            'frekuensi_update' => 'nullable|in:mingguan,bulanan',
        ];
    }

    public function messages(): array
    {
        return [
            // Data dasar
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama tidak boleh lebih dari 120 karakter.',

            'usia.required' => 'Usia wajib diisi.',
            'usia.integer' => 'Usia harus berupa angka.',
            'usia.min' => 'Usia tidak boleh kurang dari 0 bulan.',
            'usia.max' => 'Usia tidak boleh lebih dari 60 bulan.',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',

            'berat_badan.required' => 'Berat badan wajib diisi.',
            'berat_badan.numeric' => 'Berat badan harus berupa angka.',
            'berat_badan.min' => 'Berat badan minimal adalah 1 kg.',
            'berat_badan.max' => 'Berat badan maksimal adalah 40 kg.',

            'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
            'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
            'tinggi_badan.min' => 'Tinggi badan minimal adalah 40 cm.',
            'tinggi_badan.max' => 'Tinggi badan maksimal adalah 130 cm.',

            'lingkar_lengan.numeric' => 'Lingkar lengan harus berupa angka.',
            'lingkar_lengan.min' => 'Lingkar lengan minimal adalah 5 cm.',
            'lingkar_lengan.max' => 'Lingkar lengan maksimal adalah 25 cm.',

            // Kesehatan
            'riwayat_penyakit.array' => 'Riwayat penyakit harus berupa daftar.',
            'riwayat_penyakit.*.string' => 'Setiap riwayat penyakit harus berupa teks.',

            'menggunakan_obat.boolean' => 'Format penggunaan obat tidak valid.',
            'detail_obat.string' => 'Detail obat harus berupa teks.',

            'pola_pertumbuhan.array' => 'Pola pertumbuhan harus berupa daftar.',
            'pola_pertumbuhan.*.bulan.required_with' => 'Bulan pada pola pertumbuhan wajib diisi.',
            'pola_pertumbuhan.*.bulan.string' => 'Bulan pada pola pertumbuhan harus berupa teks.',
            'pola_pertumbuhan.*.bb.required_with' => 'Berat badan pada pola pertumbuhan wajib diisi.',
            'pola_pertumbuhan.*.bb.numeric' => 'Berat badan pada pola pertumbuhan harus berupa angka.',
            'pola_pertumbuhan.*.tb.required_with' => 'Tinggi badan pada pola pertumbuhan wajib diisi.',
            'pola_pertumbuhan.*.tb.numeric' => 'Tinggi badan pada pola pertumbuhan harus berupa angka.',

            'frekuensi_sakit_6_bulan.integer' => 'Frekuensi sakit harus berupa angka.',
            'frekuensi_sakit_6_bulan.min' => 'Frekuensi sakit minimal adalah 0 kali.',
            'frekuensi_sakit_6_bulan.max' => 'Frekuensi sakit maksimal adalah 24 kali.',

            // Nutrisi
            'sayur_buah.integer' => 'Frekuensi konsumsi sayur dan buah harus berupa angka.',
            'sayur_buah.min' => 'Minimal konsumsi sayur dan buah adalah 1.',
            'sayur_buah.max' => 'Maksimal konsumsi sayur dan buah adalah 5.',

            'protein.integer' => 'Frekuensi konsumsi protein harus berupa angka.',
            'karbohidrat.integer' => 'Frekuensi konsumsi karbohidrat harus berupa angka.',
            'gula.integer' => 'Frekuensi konsumsi gula harus berupa angka.',
            'vegetarian.boolean' => 'Format vegetarian tidak valid.',
            'frekuensi_jajan.integer' => 'Frekuensi jajan harus berupa angka.',

            // Lingkungan
            'akses_pangan.array' => 'Akses pangan harus berupa daftar.',
            'akses_pangan.*.string' => 'Setiap akses pangan harus berupa teks.',

            // Target & monitoring
            'target_tinggi.boolean' => 'Format target tinggi tidak valid.',
            'target_berat.boolean' => 'Format target berat tidak valid.',
            'target_gizi.boolean' => 'Format target gizi tidak valid.',
            'izinkan_monitoring.boolean' => 'Format izin monitoring tidak valid.',
            'frekuensi_update.in' => 'Frekuensi update harus berupa "mingguan" atau "bulanan".',
        ];
    }
}
