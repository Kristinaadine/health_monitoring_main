@extends('layouts.app')

@push('title')
    Form - Nutrition & Health Monitoring and Progress
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">@t('Tambah Data Anak')</span>
@endsection

@section('content')
    <div id="edit_profile">
        <div class="p-3">
            <form id="stunting-form" action="{{ locale_route('nutrition-monitoring.children.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">@t('Nama Anak')</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">@t('Tanggal Lahir') @t('Anak')</label>
                            <input type="text" name="tanggal_lahir"
                                class="form-control tanggal_lahir @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">@t('Jenis Kelamin')</label>
                            <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                <option value="L" @selected(old('jenis_kelamin') == 'L')>@t('Laki-laki')</option>
                                <option value="P" @selected(old('jenis_kelamin') == 'P')>@t('Perempuan')</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card my-3">
                        <div class="card-header">@t('Target Nutrisi (Opsional)')</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">@t('Kalori') (kkal)</label>
                                    <input type="number" step="0.1" id="kalori" name="kalori"
                                        class="form-control @error('kalori') is-invalid @enderror"
                                        value="{{ old('kalori') }}" placeholder="@t('contoh'): 100.0">
                                    @error('kalori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">@t('Protein') (g)</label>
                                    <input type="number" step="0.1" id="protein" name="protein"
                                        class="form-control @error('protein') is-invalid @enderror"
                                        value="{{ old('protein') }}" placeholder="@t('contoh'): 100.0">
                                    @error('protein')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">@t('Lemak') (g)</label>
                                    <input type="number" step="0.1" id="lemak" name="lemak"
                                        class="form-control @error('lemak') is-invalid @enderror"
                                        value="{{ old('lemak') }}" placeholder="@t('contoh'): 100.0">
                                    @error('lemak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">@t('Karbo') (g)</label>
                                    <input type="number" step="0.1" id="karbo" name="karbo"
                                        class="form-control @error('karbo') is-invalid @enderror"
                                        value="{{ old('karbo') }}" placeholder="@t('contoh'): 100.0">
                                    @error('karbo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
        flatpickr(".tanggal_lahir", {
            dateFormat: "d F Y",
            allowInput: true,
            locale: "id",
            yearSelectorType: "dropdown",
            allowInput: true,
        });
    </script>
@endpush
