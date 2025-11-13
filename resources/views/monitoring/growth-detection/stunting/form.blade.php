@extends('layouts.app')

@push('title')
    Stunting - Growth Detection & Risk Prediction
@endpush

@push('css')
<style>
    #validation-alert-sticky {
        position: sticky;
        top: 60px;
        z-index: 1050;
        margin-bottom: 1rem;
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .field-error-inline {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
        font-weight: 500;
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
        border-width: 2px;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .form-control.is-valid {
        border-color: #198754;
        border-width: 2px;
    }
</style>
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0"> (@t('stunting_ai_who_risk'))</span>
@endsection

@section('content')
    <div id="edit_profile">
        <div class="p-3">
            {{-- Sticky Validation Alert Box - Always visible when scrolling --}}
            <div id="validation-alert-sticky" class="alert alert-danger alert-dismissible d-none" role="alert">
                <strong><i class="icofont-warning"></i> ⚠️ Perhatian! Mohon perbaiki kesalahan berikut:</strong>
                <ul id="validation-errors-sticky" class="mb-0 mt-2" style="font-size: 0.95rem;"></ul>
                <button type="button" class="btn-close" onclick="$('#validation-alert-sticky').addClass('d-none')"></button>
            </div>
            
            {{-- Alert Messages --}}
            @include('components.alert')
            
            <form id="stunting-form" action="{{ locale_route('growth-detection.stunting.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    {{-- Foto & Medical ID Section --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>📋 Identitas Anak</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                {{-- Foto Anak --}}
                                <div class="col-md-12">
                                    <label class="form-label">📸 Foto Anak (Opsional)</label>
                                    <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                                    <small class="text-muted">Format: JPG, PNG (Maksimal 2MB)</small>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    {{-- Photo Preview --}}
                                    <div id="photo-preview" class="mt-3" style="display: none;">
                                        <div class="d-flex align-items-center">
                                            <img id="preview-image" src="" class="rounded" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                            <button type="button" class="btn btn-sm btn-danger ms-3" id="remove-photo">
                                                <i class="icofont-trash"></i> Hapus Foto
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Medical ID --}}
                                <div class="col-md-6">
                                    <label class="form-label">🏥 ID Rekam Medis (Opsional)</label>
                                    <input type="text" name="medical_id" id="medical_id" class="form-control @error('medical_id') is-invalid @enderror" value="{{ old('medical_id') }}" placeholder="Contoh: RM-2025-001">
                                    <small class="text-muted">ID untuk sistem internal (opsional)</small>
                                    @error('medical_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tanggal Lahir --}}
                                <div class="col-md-6">
                                    <label class="form-label">🎂 Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}" required>
                                    <small class="text-muted">Usia akan dihitung otomatis</small>
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">@t('Nama anak') <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" required>
                            <small class="text-danger d-none" id="error-nama">Nama anak wajib diisi (minimal 2 karakter)</small>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">@t('Usia') (@t('bulan')) <span class="text-danger">*</span></label>
                            <input type="number" name="usia" id="usia" class="form-control @error('usia') is-invalid @enderror"
                                min="0" max="60" value="{{ old('usia') }}" required>
                            <small class="text-danger d-none" id="error-usia">Usia harus antara 0-60 bulan</small>
                            @error('usia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">@t('Jenis Kelamin')</label>
                            <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                <option value="L" @selected(old('jenis_kelamin') == 'L')>@t('Laki-laki')</option>
                                <option value="P" @selected(old('jenis_kelamin') == 'P')>@t('Perempuan')</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">@t('Berat Badan') (kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="berat_badan" id="berat_badan"
                                class="form-control @error('berat_badan') is-invalid @enderror"
                                min="2" max="50" value="{{ old('berat_badan') }}" required>
                            <small class="text-danger d-none" id="error-berat_badan">Berat badan harus antara 2-50 kg</small>
                            @error('berat_badan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">@t('Tinggi Badan') (cm) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan"
                                class="form-control @error('tinggi_badan') is-invalid @enderror"
                                min="40" max="130" value="{{ old('tinggi_badan') }}" required>
                            <small class="text-danger d-none" id="error-tinggi_badan">Tinggi badan harus antara 40-130 cm</small>
                            @error('tinggi_badan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">@t('Lingkar Lengan Atas') (MUAC, cm) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="lingkar_lengan" id="lingkar_lengan"
                                class="form-control @error('lingkar_lengan') is-invalid @enderror"
                                min="10" max="30" value="{{ old('lingkar_lengan') }}" required>
                            <small class="text-danger d-none" id="error-lingkar_lengan">Lingkar lengan harus antara 10-30 cm</small>
                            @error('lingkar_lengan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card my-3">
                        <div class="card-header">@t('Data Kesehatan')</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label d-block">@t('Riwayat Penyakit') (@t('checklist'))</label>
                                    @php
                                        $opts = [
                                            __('general.diare_kronis'),
                                            __('general.tb'),
                                            __('general.hiv'),
                                            __('general.penyakit_jantung_bawaan'),
                                            __('general.alergi_makanan'),
                                            __('general.gangguan_tiroid'),
                                            __('general.lainnya'),
                                        ];
                                        $oldP = old('riwayat_penyakit', []);
                                    @endphp
                                    <div class="row">
                                        @foreach ($opts as $o)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input @error('riwayat_penyakit') is-invalid @enderror"
                                                        type="checkbox" name="riwayat_penyakit[]"
                                                        value="{{ $o }}" id="rp_{{ $loop->index }}"
                                                        @checked(in_array($o, $oldP))>
                                                    <label class="form-check-label"
                                                        for="rp_{{ $loop->index }}">{{ $o }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('riwayat_penyakit')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">@t('Menggunakan Obat')?</label>
                                    <select name="menggunakan_obat"
                                        class="form-select @error('menggunakan_obat') is-invalid @enderror"
                                        id="menggunakan_obat">
                                        <option value="0" @selected(old('menggunakan_obat') === '0')>@t('Tidak')</option>
                                        <option value="1" @selected(old('menggunakan_obat') === '1')>@t('Ya')</option>
                                    </select>
                                    @error('menggunakan_obat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-9" id="detail_obat_wrap" style="display:none;">
                                    <label class="form-label">@t('Detail Obat') (@t('nama'), @t('dosis'), @t('durasi'))</label>
                                    <input type="text" name="detail_obat"
                                        class="form-control @error('detail_obat') is-invalid @enderror"
                                        value="{{ old('detail_obat') }}">
                                    @error('detail_obat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card my-3">
                        <div class="card-header">@t('Data Pertumbuhan')</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label">@t('Pola Pertumbuhan') (@t('bulanan'))</label>
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle" id="growth-table">
                                            <thead>
                                                <tr>
                                                    <th>@t('Tahun - Bulan') (YYYY-MM)</th>
                                                    <th>@t('BB') (kg)</th>
                                                    <th>@t('TB') (cm)</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="add-growth">+
                                        @t('Tambah')
                                        @t('Baris')</button>
                                    <input type="hidden" name="pola_pertumbuhan_json" id="pola_pertumbuhan_json">
                                    @error('pola_pertumbuhan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @error('pola_pertumbuhan.*.bulan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @error('pola_pertumbuhan.*.bb')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @error('pola_pertumbuhan.*.tb')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    {{-- handler di bawah akan serialize ke array "pola_pertumbuhan" --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">@t('Frekuensi Sakit') (6 @t('bln'))</label>
                            <input type="number" name="frekuensi_sakit_6_bulan"
                                class="form-control @error('frekuensi_sakit_6_bulan') is-invalid @enderror" min="0"
                                max="24" value="{{ old('frekuensi_sakit_6_bulan') }}">
                            @error('frekuensi_sakit_6_bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nutrisi (1-5) --}}
                        @php $scales = ['sayur_buah'=>'Sayur/Buah','protein'=>'Protein','karbohidrat'=>'Karbohidrat Kompleks','gula'=>'Gula Tambahan']; @endphp
                        @foreach ($scales as $name => $label)
                            <div class="col-md-3">
                                <label class="form-label">{{ $label }} (1–5)</label>
                                <input type="number" name="{{ $name }}"
                                    class="form-control @error($name) is-invalid @enderror" min="1"
                                    max="5" value="{{ old($name) }}">
                                @error($name)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        <div class="col-md-3">
                            <label class="form-label">@t('Vegetarian')/Vegan?</label>
                            <select name="vegetarian" class="form-select @error('vegetarian') is-invalid @enderror">
                                <option value="0" @selected(old('vegetarian') === '0')>@t('Tidak')</option>
                                <option value="1" @selected(old('vegetarian') === '1')>@t('Ya')</option>
                            </select>
                            @error('vegetarian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">@t('Frekuensi Jajan diLuar') (1–5)</label>
                            <input type="number" name="frekuensi_jajan"
                                class="form-control @error('frekuensi_jajan') is-invalid @enderror" min="0"
                                max="5" value="{{ old('frekuensi_jajan') }}">
                            @error('frekuensi_jajan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label d-block">@t('Akses Pangan') (checklist)</label>
                            @php
                                $aksesOpts = [
                                    __('general.kesulitan_akses_sayur/buah_segar'),
                                    __('general.ketergantungan_makanan_instan'),
                                    __('general.air_bersih_terbatas'),
                                ];
                                $oldA = old('akses_pangan', []);
                            @endphp
                            <div class="row">
                                @foreach ($aksesOpts as $o)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input @error('akses_pangan') is-invalid @enderror"
                                                type="checkbox" name="akses_pangan[]" value="{{ $o }}"
                                                id="ap_{{ $loop->index }}" @checked(in_array($o, $oldA))>
                                            <label class="form-check-label"
                                                for="ap_{{ $loop->index }}">{{ $o }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('akses_pangan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Target & monitoring --}}
                        <div class="col-12">
                            <label class="form-label d-block">@t('Target')</label>
                            @php
                                $targets = [
                                    'target_tinggi' => __('general.perbaikan_tinggi_badan'),
                                    'target_berat'  => __('general.peningkatan_berat_badan'),
                                    'target_gizi'   => __('general.perbaikan_asupan_gizi'),
                                ];
                                @endphp
                            <div class="row">
                                @foreach ($targets as $nm => $lbl)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input @error($nm) is-invalid @enderror"
                                                type="checkbox" name="{{ $nm }}" value="1"
                                                id="{{ $nm }}" @checked(old($nm) === '1')>
                                            <label class="form-check-label"
                                                for="{{ $nm }}">{{ $lbl }}</label>
                                        </div>
                                        @error($nm)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">@t('Izinkan Monitoring AI')?</label>
                            <select name="izinkan_monitoring"
                                class="form-select @error('izinkan_monitoring') is-invalid @enderror"
                                id="izin_monitoring">
                                <option value="0" @selected(old('izinkan_monitoring') === '0')>@t('tidak')</option>
                                <option value="1" @selected(old('izinkan_monitoring') === '1')>@t('Ya')</option>
                            </select>
                            @error('izinkan_monitoring')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3" id="freq_wrap" style="display:none;">
                            <label class="form-label">Frekuensi Update</label>
                            <select name="frekuensi_update"
                                class="form-select @error('frekuensi_update') is-invalid @enderror">
                                <option value="mingguan" @selected(old('frekuensi_update') === 'mingguan')>@t('Mingguan')</option>
                                <option value="bulanan" @selected(old('frekuensi_update') === 'bulanan')>@t('Bulanan')</option>
                            </select>
                            @error('frekuensi_update')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-center d-grid mt-3">
                        <button type="submit" class="btn btn-success btn-lg">@t('Simpan & Analisis')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Photo Preview
            $('#photo').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Check file size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('⚠️ Ukuran file maksimal 2MB');
                        $(this).val('');
                        $('#photo-preview').hide();
                        return;
                    }
                    
                    // Check file type
                    if (!file.type.match('image/(jpeg|png|jpg)')) {
                        alert('⚠️ Format file harus JPG atau PNG');
                        $(this).val('');
                        $('#photo-preview').hide();
                        return;
                    }
                    
                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-image').attr('src', e.target.result);
                        $('#photo-preview').fadeIn();
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Remove photo
            $('#remove-photo').on('click', function() {
                $('#photo').val('');
                $('#photo-preview').fadeOut();
            });
            
            // Auto calculate age from birth date
            $('#tanggal_lahir').on('change', function() {
                const birthDate = new Date($(this).val());
                const today = new Date();
                
                // Calculate months
                let months = (today.getFullYear() - birthDate.getFullYear()) * 12;
                months -= birthDate.getMonth();
                months += today.getMonth();
                
                // Adjust if day hasn't occurred yet this month
                if (today.getDate() < birthDate.getDate()) {
                    months--;
                }
                
                // Validate age range (0-60 months)
                if (months < 0) {
                    alert('⚠️ Tanggal lahir tidak boleh di masa depan');
                    $(this).val('');
                    $('#usia').val('');
                } else if (months > 60) {
                    alert('⚠️ Usia maksimal 60 bulan (5 tahun)');
                    $(this).val('');
                    $('#usia').val('');
                } else {
                    $('#usia').val(months);
                    $('#usia').removeClass('is-invalid').addClass('is-valid');
                    $('#error-usia').addClass('d-none');
                }
            });
            
            $("#add-growth").trigger('click');
            
            // Real-time validation function with instant alert
            function validateField(fieldId, min, max, errorMsg) {
                const field = $('#' + fieldId);
                const errorEl = $('#error-' + fieldId);
                
                field.on('input blur', function() {
                    const val = parseFloat($(this).val());
                    let hasError = false;
                    let message = '';
                    
                    if (!$(this).val() || $(this).val().trim() === '') {
                        hasError = true;
                        message = 'Field ini wajib diisi';
                    } else if (fieldId === 'nama') {
                        if ($(this).val().trim().length < 2) {
                            hasError = true;
                            message = errorMsg || 'Nama minimal 2 karakter';
                        }
                    } else {
                        if (isNaN(val) || val < min || val > max) {
                            hasError = true;
                            message = errorMsg;
                        }
                    }
                    
                    if (hasError) {
                        field.removeClass('is-valid').addClass('is-invalid');
                        errorEl.removeClass('d-none').text(message);
                        
                        // Show instant alert on blur
                        if ($(this).is(':focus') === false) {
                            showInstantAlert(message);
                        }
                    } else {
                        field.removeClass('is-invalid').addClass('is-valid');
                        errorEl.addClass('d-none');
                    }
                    
                    return !hasError;
                });
            }
            
            // Show instant alert for real-time validation
            function showInstantAlert(message) {
                const alertBox = $('#validation-alert-sticky');
                const errorList = $('#validation-errors-sticky');
                
                errorList.html('<li><strong>' + message + '</strong></li>');
                alertBox.removeClass('d-none');
                
                // Auto hide after 8 seconds
                setTimeout(function() {
                    alertBox.addClass('d-none');
                }, 8000);
            }
            
            // Apply validation to required fields
            validateField('nama', 0, 0, '');
            validateField('usia', 0, 60, 'Usia harus antara 0-60 bulan');
            validateField('berat_badan', 2, 50, 'Berat badan harus antara 2-50 kg');
            validateField('tinggi_badan', 40, 130, 'Tinggi badan harus antara 40-130 cm');
            validateField('lingkar_lengan', 10, 30, 'Lingkar lengan harus antara 10-30 cm');
            
            // Validate before submit
            $('#stunting-form').on('submit', function(e) {
                let errors = [];
                let isValid = true;
                
                // Validate nama
                const nama = $('#nama').val();
                if (!nama || nama.trim().length < 2) {
                    errors.push('Nama anak wajib diisi (minimal 2 karakter)');
                    $('#nama').addClass('is-invalid');
                    $('#error-nama').removeClass('d-none');
                    isValid = false;
                }
                
                // Validate usia
                const usia = parseFloat($('#usia').val());
                if (isNaN(usia) || usia < 0 || usia > 60) {
                    errors.push('Usia harus antara 0-60 bulan');
                    $('#usia').addClass('is-invalid');
                    $('#error-usia').removeClass('d-none');
                    isValid = false;
                }
                
                // Validate berat_badan
                const berat = parseFloat($('#berat_badan').val());
                if (isNaN(berat) || berat < 2 || berat > 50) {
                    errors.push('Berat badan harus antara 2-50 kg');
                    $('#berat_badan').addClass('is-invalid');
                    $('#error-berat_badan').removeClass('d-none');
                    isValid = false;
                }
                
                // Validate tinggi_badan
                const tinggi = parseFloat($('#tinggi_badan').val());
                if (isNaN(tinggi) || tinggi < 40 || tinggi > 130) {
                    errors.push('Tinggi badan harus antara 40-130 cm');
                    $('#tinggi_badan').addClass('is-invalid');
                    $('#error-tinggi_badan').removeClass('d-none');
                    isValid = false;
                }
                
                // Validate lingkar_lengan
                const lingkar = parseFloat($('#lingkar_lengan').val());
                if (isNaN(lingkar) || lingkar < 10 || lingkar > 30) {
                    errors.push('Lingkar lengan atas harus antara 10-30 cm');
                    $('#lingkar_lengan').addClass('is-invalid');
                    $('#error-lingkar_lengan').removeClass('d-none');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Show sticky alert box with all errors
                    const alertBox = $('#validation-alert-sticky');
                    const errorList = $('#validation-errors-sticky');
                    errorList.empty();
                    
                    errors.forEach(function(error) {
                        errorList.append('<li><strong>' + error + '</strong></li>');
                    });
                    
                    alertBox.removeClass('d-none');
                    
                    // Scroll to alert
                    $('html, body').animate({
                        scrollTop: 0
                    }, 300);
                    
                    return false;
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const obat = document.getElementById('menggunakan_obat');
            const detailWrap = document.getElementById('detail_obat_wrap');
            const izin = document.getElementById('izin_monitoring');
            const freqWrap = document.getElementById('freq_wrap');

            function toggleObat() {
                detailWrap.style.display = obat.value === '1' ? '' : 'none';
            }

            function toggleIzin() {
                freqWrap.style.display = izin.value === '1' ? '' : 'none';
            }
            toggleObat();
            toggleIzin();
            obat.addEventListener('change', toggleObat);
            izin.addEventListener('change', toggleIzin);

            const table = document.querySelector('#growth-table tbody');
            const addBtn = document.getElementById('add-growth');
            const hiddenJson = document.getElementById('pola_pertumbuhan_json');

            function row(bulan = '', bb = '', tb = '') {
                const tr = document.createElement('tr');
                tr.innerHTML = `
      <td><input type="month" class="form-control form-control-sm bulan" value="${bulan}"></td>
      <td><input type="number" step="0.1" class="form-control form-control-sm bb" value="${bb}"></td>
      <td><input type="number" step="0.1" class="form-control form-control-sm tb" value="${tb}"></td>
      <td><button type="button" class="btn btn-sm btn-outline-danger rm">@t('Hapus')</button></td>`;
                tr.querySelector('.rm').addEventListener('click', () => {
                    tr.remove();
                });
                return tr;
            }
            addBtn.addEventListener('click', () => table.appendChild(row()));

            // serialize sebelum submit → kirim sebagai array `pola_pertumbuhan[]`
            document.getElementById('stunting-form').addEventListener('submit', function(e) {
                const arr = [];
                table.querySelectorAll('tr').forEach(tr => {
                    arr.push({
                        bulan: tr.querySelector('.bulan').value,
                        bb: parseFloat(tr.querySelector('.bb').value),
                        tb: parseFloat(tr.querySelector('.tb').value),
                    });
                });
                hiddenJson.value = JSON.stringify(arr.filter(x => x.bulan));
                // inject ke form sebagai fields array
                // hapus field lama kalau ada
                document.querySelectorAll('input[name^="pola_pertumbuhan["]').forEach(el => el.remove());
                arr.forEach((x, i) => {
                    ['bulan', 'bb', 'tb'].forEach(k => {
                        const inp = document.createElement('input');
                        inp.type = 'hidden';
                        inp.name = `pola_pertumbuhan[${i}][${k}]`;
                        inp.value = x[k];
                        e.target.appendChild(inp);
                    })
                });
            });
        });
    </script>
@endpush
