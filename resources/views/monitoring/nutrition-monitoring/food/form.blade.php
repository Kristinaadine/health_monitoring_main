@extends('layouts.app')

@push('title')
    Form - Nutrition & Health Monitoring and Progress
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">@t('Tambah Log Makanan Harian') -
        {{ $child->nama }}</span>
@endsection

@section('content')
    <div id="edit_profile">
        <div class="p-3">
            <form id="stunting-form" id="formLog" action="{{ locale_route('nutrition-monitoring.children.food.store', encrypt($child->id)) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">@t('Tanggal') <span class="text-danger">*</span></label>
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
                            <label class="form-label">@t('Nama Makanan') <span class="text-danger">*</span></label>
                            <input type="text" name="nama_makanan" class="form-control @error('nama_makanan') is-invalid @enderror"
                                value="{{ old('nama_makanan') }}" placeholder="@t('contoh'): Nasi Goreng">
                            @error('nama_makanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">@t('Porsi')</label>
                            <input type="text" name="porsi" class="form-control @error('porsi') is-invalid @enderror"
                                value="{{ old('porsi') }}" placeholder="@t('contoh'): 1 @t('piring') / 1 @t('mangkuk') / 1 @t('sendok')">
                            @error('porsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">@t('Foto') (@t('opsional'))</label>
                            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*" capture="environment">
                            <small class="text-muted">@t('Jika Mengupload foto, maka data kalori, protein, dan lemak akan di get otomatis')</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3" id="nutrition-info">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">@t('Kalori') (kkal)</label>
                            <input type="number" step="0.1" id="kalori" name="kalori" class="form-control @error('kalori') is-invalid @enderror"
                                value="{{ old('kalori') }}" placeholder="@t('contoh'): 100.0">
                            @error('kalori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">@t('Protein') (g)</label>
                            <input type="number" step="0.1" id="protein" name="protein" class="form-control @error('protein') is-invalid @enderror"
                                value="{{ old('protein') }}" placeholder="@t('contoh'): 100.0">
                            @error('protein')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">@t('Lemak') (g)</label>
                            <input type="number" step="0.1" id="lemak" name="lemak" class="form-control @error('lemak') is-invalid @enderror"
                                value="{{ old('lemak') }}" placeholder="@t('contoh'): 100.0">
                            @error('lemak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">@t('Karbo') (g)</label>
                            <input type="number" step="0.1" id="karbo" name="karbo" class="form-control @error('karbo') is-invalid @enderror"
                                value="{{ old('karbo') }}" placeholder="@t('contoh'): 100.0">
                            @error('karbo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-3" id="review-img" style="display:none;">
                    </div>
                    <div class="text-center d-grid mt-3">
                        <button type="submit" class="btn btn-success btn-lg mb-3">@t('Simpan')</button>
                        <button type="reset" id="btnReset" class="btn btn-secondary btn-lg">@t('Reset')</button>
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

        $("#foto").change(function() {
            $("#review-img").empty();
            $("#nutrition-info").hide();
            $("#kalori").val('');
            $("#protein").val('');
            $("#lemak").val('');
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#review-img").html('<img src="' + e.target.result + '" class="img-fluid" style="max-width: 200px; max-height: 200px;">');
                    $("#review-img").show();
                }
                reader.readAsDataURL(file);
            } else {
                $("#review-img").hide();
            }
        });

        $("#btnReset").click(function() {
            $("#review-img").empty();
            $("#review-img").hide();
            $("#nutrition-info").show();
            $("#formLog")[0].reset();
        });
    </script>
@endpush
