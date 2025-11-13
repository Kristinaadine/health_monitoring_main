@extends('layouts.app')

@push('title')
    Diet - Growth Detection & Risk Prediction
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">Diet (Dewasa/Umum)</span>
    {{-- <a class="toggle ms-auto" href="#"><i class="icofont-navigation-menu"></i></a> --}}
@endsection

@section('content')
    {{-- <div class="container"> --}}
    <form action="#" method="POST">
        {{-- <form action="{{ locale_route('diet.store') }}" method="POST"> --}}
        @csrf

        <div class="address p-3 bg-white">
            <h4>1. Identitas Pengguna</h4>
            <div class="mb-3">
                <label>Nama:</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Usia:</label>
                <input type="number" name="usia" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Jenis Kelamin:</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Berat Badan (kg):</label>
                <input type="number" step="0.1" name="berat_badan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Tinggi Badan (cm):</label>
                <input type="number" step="0.1" name="tinggi_badan" class="form-control" required>
            </div>


            <h4>2. Riwayat Penyakit</h4>
            @php
                $penyakitList = ['Diabetes', 'Hipertensi', 'Gangguan Tiroid', 'Alergi Makanan'];
            @endphp
            @foreach ($penyakitList as $penyakit)
                <div class="form-check">
                    <input type="checkbox" name="penyakit[]" value="{{ $penyakit }}" class="form-check-input">
                    <label class="form-check-label">{{ $penyakit }}</label>
                </div>
            @endforeach
            <div class="mb-3 mt-2">
                <label>Lainnya:</label>
                <input type="text" name="penyakit_lainnya" class="form-control">
            </div>
            <div class="mb-3">
                <label>Menggunakan Obat atau Suplemen?</label>
                <select name="menggunakan_obat" class="form-control">
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Detail Obat/Suplemen (jika ada):</label>
                <textarea name="detail_obat" class="form-control"></textarea>
            </div>

            <h4>3. Kebiasaan Nutrisi (Skala 1-5)</h4>
            <div class="mb-3">
                <label>Frekuensi makan sayur/buah:</label>
                <input type="number" name="sayur_buah" min="1" max="5" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Konsumsi protein (telur/daging/ikan):</label>
                <input type="number" name="protein" min="1" max="5" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Konsumsi karbohidrat kompleks:</label>
                <input type="number" name="karbohidrat" min="1" max="5" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Konsumsi gula tambahan:</label>
                <input type="number" name="gula_tambahan" min="1" max="5" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Vegetarian / Vegan?</label>
                <select name="vegetarian" class="form-control">
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Frekuensi jajan di luar (dalam seminggu):</label>
                <input type="number" name="frekuensi_jajan" class="form-control" required>
            </div>

            <h4>4. Target Pengguna</h4>
            <div class="mb-3">
                <select name="target" class="form-control" required>
                    <option value="">-- Pilih Target --</option>
                    <option value="Menurunkan berat badan">Menurunkan berat badan</option>
                    <option value="Mengontrol gula darah">Mengontrol gula darah</option>
                    <option value="Meningkatkan massa otot">Meningkatkan massa otot</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit Data</button>
        </div>
    </form>
@endsection
