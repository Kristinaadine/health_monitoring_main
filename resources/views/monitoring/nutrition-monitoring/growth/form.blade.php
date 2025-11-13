@extends('layouts.app')

@push('title')
    Form - Nutrition & Health Monitoring and Progress
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">@t('Tambah Log Pertumbuhan') -
        {{ $child->nama }}</span>
@endsection

@section('content')
    <div id="edit_profile">
        <div class="p-3">
            <form id="stunting-form" action="{{ locale_route('nutrition-monitoring.children.growth.store', encrypt($child->id)) }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">@t('Tanggal')</label>
                            <input type="text" name="tanggal"
                                class="form-control tanggal @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal') }}">
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">@t('Berat') (kg)</label>
                            <input type="number" step="0.1" name="berat" class="form-control @error('berat') is-invalid @enderror"
                                value="{{ old('berat') }}">
                            @error('berat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">@t('Tinggi') (cm)</label>
                            <input type="number" step="0.1" name="tinggi" class="form-control @error('tinggi') is-invalid @enderror"
                                value="{{ old('tinggi') }}">
                            @error('tinggi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-center d-grid mt-3">
                        <button type="submit" class="btn btn-success btn-lg">@t('Simpan')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        flatpickr(".tanggal", {
            dateFormat: "d F Y",
            allowInput: true,
            locale: "id",
            yearSelectorType: "dropdown",
            allowInput: true,
        });
    </script>
@endpush
