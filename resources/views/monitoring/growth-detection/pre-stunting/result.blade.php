@extends('layouts.app')

@push('title')
    Pre-Stunting - Risk Detection
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('growth-detection.pre-stunting.index') }}">
        <i class="icofont-rounded-left back-page"></i>
    </a>
    <span class="fw-bold ms-3 h6 mb-0">{{__('general.deteksi_risiko_pre_stunting')}}</span>
@endsection

@section('content')
    @php
        $data = isset($data) ? (is_iterable($data) ? collect($data) : collect([$data])) : collect();
    @endphp
    @php
        $low = $data->where('level_risiko', 'Risiko rendah')->count();
        $mid = $data->where('level_risiko', 'Risiko sedang')->count();
        $high = $data->where('level_risiko', 'Risiko tinggi')->count();
    @endphp

    <div class="p-3">
        <div class="mb-3 p-3 bg-white rounded shadow-sm">
            <h5 class="mb-3">@t('Data Risiko Pre-Stunting')</h5>
            @if($data->isNotEmpty())
                @php $record = $data->first(); @endphp
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th>@t('Level Risiko')</th>
                                        <td><span class="badge bg-{{ $record->level_risiko === 'Risiko tinggi' ? 'danger' : ($record->level_risiko === 'Risiko sedang' ? 'warning' : 'success') }}">{{ $record->level_risiko }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>@t('Tanggal Analisis')</th>
                                        <td>: {{ $record->created_at->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Nama')</th>
                                        <td>: {{ $record->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Usia')</th>
                                        <td>: {{ $record->usia }} tahun</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Tinggi Badan')</th>
                                        <td>: {{ $record->tinggi_badan }} cm</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Berat Badan Pra-Hamil')</th>
                                        <td>: {{ $record->berat_badan_pra_hamil }} kg</td>
                                    </tr>
                                    <tr>
                                        <th>@t('BB Hamil Minggu ke') 12</th>
                                        <td>: {{ $record->weight_at_g12 }} kg</td>
                                    </tr>
                                    <tr>
                                        <th>@t('BB pada Minggu Ke')-36</th>
                                        <td>: {{ $record->weight_at_g36 }} kg</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Kenaikan BB Trimester')</th>
                                        <td>: {{ $record->weight_gain_trimester }} kg</td>
                                    </tr>
                                    <tr>
                                        <th>@t('BMI Pra-Hamil')</th>
                                        <td>: {{ $record->bmi_pra_hamil }}</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Kenaikan BB Trimester')</th>
                                        <td>: {{ $record->kenaikan_bb_trimester }} kg</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Lingkar Lengan Atas') MUAC</th>
                                        <td>: {{ $record->muac }} cm</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Jarak Kelahiran')</th>
                                        <td>: {{ $record->jarak_kelahiran }} bulan</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Kunjungan') ANC</th>
                                        <td>: {{ $record->anc_visits }} Kali</td>
                                    </tr>
                                    <tr>
                                        <th>Hb</th>
                                        <td>: {{ $record->hb }} g/dL</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Kepatuhan TTD')</th>
                                        <td>: {{ $record->kepatuhan_ttd ? 'Patuh' : 'Tidak Patuh' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@t('Infeksi/Komplikasi')</th>
                                        <td>: {{ $record->ada_infeksi ? 'Ada' : 'Tidak Ada' }}</td>
                                    </tr>
                                    <tr>
                                        <th>EFW/SGA</th>
                                        <td>: {{ $record->efw_sga ? 'Ya' : 'Tidak' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-center">@t('Tidak ada data')</p>
            @endif
        </div>
    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('riskChart').getContext('2d');
            const riskChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Risiko rendah', 'Risiko sedang', 'Risiko tinggi'],
                    datasets: [{
                        data: [{{ $low }}, {{ $mid }}, {{ $high }}],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Risiko Pre-Stunting'
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
