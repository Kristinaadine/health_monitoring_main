@extends('layouts.app')

@push('title')
    Pre-Stunting - Risk Detection
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a>
    <span class="fw-bold ms-3 h6 mb-0">@t('Input Data Ibu Hamil (Pre-Stunting Risk)')</span>
@endsection

@section('content')
    @php($data = $data ?? null)
    <div id="edit_profile">
        <div class="p-3">
        <form method="POST" action="{{ isset($data->id)
    ? route('growth-detection.pre-stunting.update', ['locale' => app()->getLocale(), 'id' => $data->id])
    : route('growth-detection.pre-stunting.calculate', ['locale' => app()->getLocale()]) }}">
                @csrf
                @if(isset($data->id))
                    @method('PUT')
                @endif
                <div class="mb-3">
                    <label>@t('Nama Ibu')</label>
                    <input type="text" name="nama" class="form-control" required value="{{ old('nama', $data->nama ?? '') }}">
                </div>
                <div class="mb-3">
                    <label>@t('Usia') (@t('tahun'))</label>
                    <input type="number" name="age" class="form-control" required value="{{ old('age', $data->usia ?? '') }}">
                </div>
                <div class="mb-3">
                    <label>@t('Tinggi Badan cm')</label>
                    <input type="number" step="0.1" name="height" class="form-control" required value="{{ old('height', $data->tinggi_badan ?? '') }}">
                </div>
                <div class="mb-3">
                    <label>@t('Berat Badan Pra Hamil kg')</label>
                    <input type="number" step="0.1" name="pre_pregnancy_weight" class="form-control" value="{{ old('pre_pregnancy_weight', $data->berat_badan_pra_hamil ?? '') }}">
                </div>
                <div class="mb-3 bg-light p-2">
                    <label>@t('BMI Pra-Hamil')</label>
                    <input type="number" step="0.1" name="pre_pregnancy_bmi" class="form-control" id="bmi" readonly value="{{ old('pre_pregnancy_bmi', $data->bmi_pra_hamil ?? '') }}">
                </div>

                <script>
                    const weightInput = document.querySelector('input[name="pre_pregnancy_weight"]');
                    const heightInput = document.querySelector('input[name="height"]');
                    const bmiInput = document.getElementById('bmi');

                    function calculateBMI() {
                        const weight = parseFloat(weightInput.value);
                        const height = parseFloat(heightInput.value) / 100; // convert cm to m
                        if(weight > 0 && height > 0){
                            const bmi = weight / (height * height);
                            bmiInput.value = bmi.toFixed(1);
                        } else {
                            bmiInput.value = '';
                        }
                    }

                    weightInput.addEventListener('input', calculateBMI);
                    heightInput.addEventListener('input', calculateBMI);
                </script>

                <div class="mb-3">
                    <label>@t('Berat Badan pada Minggu Ke')-12 (kg)</label>
                    <input type="number" step="0.1" name="weight_at_g12" class="form-control" value="{{ old('weight_at_g12', $data->weight_at_g12 ?? '') }}" placeholder="@t('berat_badan_pada_trimester') 1 (@t('minggu ke')-12)">
                </div>
                <div class="mb-3">
                    <label>@t('Berat Badan pada Minggu Ke')-36 (kg)</label>
                    <input type="number" step="0.1" name="weight_at_g36" class="form-control" value="{{ old('weight_at_g36', $data->weight_at_g36 ?? '') }}" placeholder="@t('berat_badan_pada_trimester') 3 (@t('minggu ke')-36)">
                </div>
                <div class="mb-3">
                    <label>@t('Kenaikan BB Trisemester') (kg)</label>
                    <input type="number" step="0.1" name="weight_gain_trimester" class="form-control" value="{{ old('weight_gain_trimester', $data->kenaikan_bb_trimester ?? '') }}" readonly>
                </div>
                <div class="mb-3">
                    <label>@t('Lingkar Lengan Atas') (MUAC) cm</label>
                    <input type="number" step="0.1" name="muac" class="form-control" required value="{{ old('muac', $data->muac ?? '') }}">
                </div>
                <div class="mb-3">
                    <label>@t('Jarak Kelahiran') (@t('bulan'))</label>
                    <input type="number" name="birth_interval" class="form-control" value="{{ old('birth_interval', $data->jarak_kelahiran ?? '') }}">
                </div>
                <div class="mb-3">
                    <label>@t('Jumlah Kunjungan ANC')</label>
                    <select name="anc_visits" class="form-control" required>
                        @for ($i = 0; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('anc_visits', $data->anc_visits ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="d-flex gap-4 mb-3 bg-light p-2">
                    <div class="flex-1">
                        <label>@t('Trimester')</label>
                        <select name="trimester" class="form-control" id="trimester" required>
                            <option value="1" {{ old('trimester', $data->trimester ?? '') == '1' ? 'selected' : '' }}>Trimester 1</option>
                            <option value="2" {{ old('trimester', $data->trimester ?? '') == '2' ? 'selected' : '' }}>Trimester 2</option>
                            <option value="3" {{ old('trimester', $data->trimester ?? '') == '3' ? 'selected' : '' }}>Trimester 3</option>
                        </select>
                    </div>
                    <div class="flex-grow-1">
                        <label>Hb (g/dL)</label>
                        <input type="number" step="0.1" name="hb" class="form-control" id="hb" required value="{{ old('hb', $data->hb ?? '') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label>@t('Kepatuhan TTD')</label>
                    <select name="ttd_compliance" class="form-control">
                        <option value="1" {{ old('ttd_compliance', $data->ttd_compliance ?? '') == '1' ? 'selected' : '' }}>@t('Patuh')</option>
                        <option value="0" {{ old('ttd_compliance', $data->ttd_compliance ?? '') == '0' ? 'selected' : '' }}>@t('Tidak Patuh')</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>@t('Infeksi/Komplikasi')</label>
                    <select name="has_infection" class="form-control">
                        <option value="0" {{ old('has_infection', $data->has_infection ?? '') == '0' ? 'selected' : '' }}>@t('Tidak Ada')</option>
                        <option value="1" {{ old('has_infection', $data->has_infection ?? '') == '1' ? 'selected' : '' }}>@t('Ada')</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>EFW/SGA</label>
                    <select name="efw_sga" class="form-control">
                        <option value="0" {{ old('efw_sga', $data->efw_sga ?? '') == '0' ? 'selected' : '' }}>@t('Tidak')</option>
                        <option value="1" {{ old('efw_sga', $data->efw_sga ?? '') == '1' ? 'selected' : '' }}>@t('Ya')</option>
                    </select>
                </div>
                <div class="text-center d-grid mt-3">
                    <button type="submit" class="btn btn-success btn-lg">{{ isset($data->id) ? __('general.hitung_ulang') : __('general.hitung_risiko') }}</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    const trimesterSelect = document.getElementById('trimester');
    const hbInput = document.getElementById('hb');

    function updateHbMin() {
        let min = 0;
        let placeholderText = '';
        switch(trimesterSelect.value) {
            case '1':
                min = 11;
                placeholderText = '11 g/dL {{__('general.atau_lebih') }}';
                break;
            case '2':
                min = 10.5;
                placeholderText = '10.5 g/dL {{__('general.atau_lebih') }}';
                break;
            case '3':
                min = 11;
                placeholderText = '11 g/dL {{__('general.atau_lebih') }}';
                break;
        }
        hbInput.min = min;
        hbInput.placeholder = placeholderText;
    }

    trimesterSelect.addEventListener('change', updateHbMin);
    updateHbMin();

    // Script to calculate weight gain trimester automatically
    document.addEventListener('DOMContentLoaded', function () {
        const weightAtG12Input = document.querySelector('input[name="weight_at_g12"]');
        const weightAtG36Input = document.querySelector('input[name="weight_at_g36"]');
        const weightGainTrimesterInput = document.querySelector('input[name="weight_gain_trimester"]');

        function calculateWeightGainTrimester() {
            const weightAtG12 = parseFloat(weightAtG12Input.value);
            const weightAtG36 = parseFloat(weightAtG36Input.value);
            if (!isNaN(weightAtG12) && !isNaN(weightAtG36)) {
                const gain = weightAtG36 - weightAtG12;
                weightGainTrimesterInput.value = gain.toFixed(1);
            } else {
                weightGainTrimesterInput.value = '';
            }
        }

        weightAtG12Input.addEventListener('input', calculateWeightGainTrimester);
        weightAtG36Input.addEventListener('input', calculateWeightGainTrimester);
        calculateWeightGainTrimester();
    });
    </script>

    <!-- Toast container -->
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
            @if(session()->has('success'))
                <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" id="toast-success">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @elseif(session()->has('error'))
                <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" id="toast-error">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @elseif($errors->any())
                <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true" id="toast-errors">
                    <div class="d-flex">
                        <div class="toast-body">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastSuccessEl = document.getElementById('toast-success');
            if (toastSuccessEl) {
                var toastSuccess = new bootstrap.Toast(toastSuccessEl);
                toastSuccess.show();
            }
            var toastErrorEl = document.getElementById('toast-error');
            if (toastErrorEl) {
                var toastError = new bootstrap.Toast(toastErrorEl);
                toastError.show();
            }
            var toastErrorsEl = document.getElementById('toast-errors');
            if (toastErrorsEl) {
                var toastErrors = new bootstrap.Toast(toastErrorsEl);
                toastErrors.show();
            }
        });
    </script>
@endsection
