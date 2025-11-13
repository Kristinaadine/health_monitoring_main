@extends('layouts.app')

@push('title')
    @t('Data Anak - Nutrition & Health Monitoring and Progress')
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('nutrition-monitoring.children.index') }}">
        <i class="icofont-rounded-left back-page"></i>
    </a>
    <span class="fw-bold ms-3 h6 mb-0">@t('Data Anak - Nutrition & Health Monitoring and Progress')</span>
@endsection

@section('content')
    <div class="address p-3 bg-white">
        <h6 class="m-0 text-dark d-flex align-items-center">👶 @t('Detail Anak')</h6>
    </div>

    <div class="p-3">
        <table style="width: 100%; font-size: 16px; line-height: 1.5;">
            <tr>
                <td style="width: 30%">@t('Nama Anak')</td>
                <td style="width: 2%">:</td>
                <td>{{ $childModel->nama }}</td>
            </tr>
            <tr>
                <td style="width: 30%">@t('Tanggal Lahir')</td>
                <td style="width: 2%">:</td>
                <td>{{ \Carbon\Carbon::parse($childModel->tanggal_lahir)->locale(app()->getLocale())->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td style="width: 30%">@t('Jenis Kelamin')</td>
                <td style="width: 2%">:</td>
                <td>
                    {{ $childModel->jenis_kelamin == 'L' ? '♂️' : '♀️' }}
                    {{ $childModel->jenis_kelamin == 'L' ? __('general.laki_laki') : __('general.perempuan') }}
                </td>
            </tr>
        </table>

        <div class="card my-3">
            <div class="card-header">💪 @t('Target Nutrisi')</div>
            <div class="card-body">
                <div class="row" style="font-size: 16px">
                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="font-size: 16px">@t('Kalori') (kkal)</label>
                        {{ $nutrition->kalori }} kkal
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="font-size: 16px">@t('Protein') (g)</label>
                        {{ $nutrition->protein }} g
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="font-size: 16px">@t('Lemak') (g)</label>
                        {{ $nutrition->lemak }} g
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label" style="font-size: 16px">@t('Karbo') (g)</label>
                        {{ $nutrition->karbo }} g
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-6">
                <a href="{{ locale_route('nutrition-monitoring.children.growth.index', encrypt($childModel->id)) }}"
                    class="btn btn-primary w-100">@t('Log Pertumbuhan')</a>
            </div>
            <div class="col-6">
                <a href="{{ locale_route('nutrition-monitoring.children.food.index', encrypt($childModel->id)) }}"
                    class="btn btn-success w-100">@t('Log Makanan')</a>
            </div>
        </div>

        <div class="mt-3">
            <div class="card my-3">
                <div class="card-header">📈 @t('Grafik Pertumbuhan')</div>
                <div class="card-body">
                    <div class="row">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card my-3">
                <div class="card-header">📈 @t('Grafik Nutrisi vs Target')</div>
                <div class="card-body">
                    <div class="row">
                        <canvas id="nutritionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Growth Chart
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($growthData->pluck('tanggal')) !!},
                datasets: [{
                        label: '@t("Berat") (kg)',
                        data: {!! json_encode($growthData->pluck('berat')) !!},
                        borderColor: 'blue',
                        fill: false
                    },
                    {
                        label: '@t("Tinggi") (cm)',
                        data: {!! json_encode($growthData->pluck('tinggi')) !!},
                        borderColor: 'green',
                        fill: false
                    }
                ]
            }
        });

        // Nutrition Chart
        const nutritionCtx = document.getElementById('nutritionChart').getContext('2d');
        new Chart(nutritionCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($foodData->pluck('tanggal')) !!},
                datasets: [{
                        label: "@t('Kalori')",
                        data: {!! json_encode($foodData->pluck('kalori')) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.5)'
                    },
                    {
                        label: '@t("Target Kalori")',
                        data: Array({{ count($foodData) }}).fill({{ $target->kalori ?? 0 }}),
                        type: 'line',
                        borderColor: 'red',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: '@t("Protein")',
                        data: {!! json_encode($foodData->pluck('protein')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.5)'
                    },
                    {
                        label: '@t("Target Protein")',
                        data: Array({{ count($foodData) }}).fill({{ $target->protein ?? 0 }}),
                        type: 'line',
                        borderColor: 'blue',
                        borderWidth: 2,
                        fill: false
                    }
                ]
            }
        });
    </script>
@endpush