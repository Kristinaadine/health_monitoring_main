@extends('layouts.app')

@push('title')
    Stunting - Growth Detection & Risk Prediction
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('growth-detection.stunting') }}"><i
            class="icofont-rounded-left back-page"></i></a>
    <span class="fw-bold ms-3 h6 mb-0">Hasil Analisis Stunting AI – WHO + Risk</span>
@endsection

@section('content')
    <div class="address p-3 bg-white">
        <h6 class="m-0 text-dark d-flex align-items-center">👶 Identitas Anak</h6>
    </div>
    <div class="p-3">
        <div class="mb-3">
            <table style="width: 100%">
                <tr>
                    <th style="width: 40%">Nama</th>
                    <td style="width: 2:">:</td>
                    <td>{{ $data->nama }}</td>
                </tr>
                <tr>
                    <th>Usia</th>
                    <td style="width: 2:">:</td>
                    <td>{{ $data->usia }} Bulan</td>
                </tr>
                <tr>
                    <th>Jenis kelamin</th>
                    <td style="width: 2:">:</td>
                    <td>
                        @php
                            $jk = 'Laki - Laki';
                            $color = 'primary';
                            if ($data->jenis_kelamin == 'P') {
                                $jk = 'Perempuan';
                                $color = 'danger';
                            }
                        @endphp
                        <span class="px-2 py-1 rounded text-white bg-{{ $color }}">{{ $jk }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="accordion mb-4" id="accordionExample">
        <div class="address p-3 bg-white">
            <h6 class="m-0 text-dark"><a class="d-flex align-items-center text-decoration-none w-100" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                    aria-controls="collapseThree">
                    📈 Grafik Pertumbuhan
                    <i class="icofont-rounded-down ms-auto"></i>
                </a></h6>
        </div>
        <div id="collapseThree" class="collapse p-3 border-top" aria-labelledby="headingThree"
            data-bs-parent="#accordionExample">
            <div class="p-3">
                <div class="mb-3">
                    <div class="my-6">
                        <div class="row">
                            <div class="col-6">
                                <canvas id="growthChart"></canvas>
                            </div>
                            <div class="col-6">
                                <canvas id="growthChart2"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="address p-3 bg-white">
        <h6 class="m-0 text-dark">📐 Hasil WHO Z-Score</h6>
    </div>
    <div class="p-3">
        <div class="mb-3">
            <table style="width: 100%">
                <tr>
                    <th style="width: 40%">HAZ (Tinggi menurut Usia)</th>
                    <td style="width: 2:">:</td>
                    <td>{{ number_format($data->haz, 2) }}</td>
                </tr>
                <tr>
                    <th>WHZ (Berat menurut Usia)</th>
                    <td style="width: 2:">:</td>
                    <td>{{ number_format($data->whz, 2) }}</td>
                </tr>
                <tr>
                    <th>Status Pertumbuhan</th>
                    <td style="width: 2:">:</td>
                    <td>
                        <span
                            class="px-2 py-1 rounded text-white
                    @if ($data->status_pertumbuhan === 'Normal') bg-success
                    @elseif(in_array($data->status_pertumbuhan, ['Stunted', 'Wasting'])) bg-warning
                    @else bg-danger @endif">
                            {{ $data->status_pertumbuhan }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="address p-3 bg-white">
        <h6 class="m-0 text-dark">🛡️ Analisis Risiko Tambahan</h6>
    </div>
    <div class="p-3">
        <div class="mb-3">
            <table style="width: 100%">
                <tr>
                    <th style="width: 40%">Level Risiko</th>
                    <td style="width: 2:">:</td>
                    <td><span
                            class="px-2 py-1 rounded text-white
                        @if ($data->level_risiko === 'Rendah') bg-success
                        @elseif($data->level_risiko === 'Sedang') bg-warning
                        @else bg-danger @endif">
                            {{ $data->level_risiko }}
                        </span></td>
                </tr>
                <tr>
                    <th>Faktor Utama</th>
                    <td style="width: 2:">:</td>
                    <td>{{ $data->faktor_utama ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="address p-3 bg-white">
        <h6 class="m-0 text-dark">💡 Rekomendasi</h6>
    </div>
    <div class="p-3">
        {{ $data->rekomendasi }}
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        const growthCtx = document.getElementById('growthChart');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                        label: 'Berat Badan (kg)',
                        data: {!! json_encode($weights) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: '#3b82f6',
                        tension: 0.3,
                        pointRadius: 5,
                        pointBackgroundColor: '#3b82f6',
                    },
                    {
                        label: 'Standar WHO (Median)',
                        data: {!! json_encode($whow) !!},
                        borderColor: '#22c55e',
                        backgroundColor: '#22c55e',
                        borderDash: [5, 5],
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#22c55e',
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return "{{ $childName }}: " + context.parsed.y + " kg";
                            }
                        }
                    },
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        color: '#000',
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value) => value + " kg" // langsung tampilkan angkanya
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Berat (kg)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        const growthCtx2 = document.getElementById('growthChart2');
        new Chart(growthCtx2, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                        label: 'Tinggi Badan Anak (cm)',
                        data: {!! json_encode($heights) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: '#3b82f6',
                        tension: 0.3,
                        pointRadius: 5,
                        pointBackgroundColor: '#3b82f6',
                    },
                    {
                        label: 'Standar WHO (Median)',
                        data: {!! json_encode($hwho) !!},
                        borderColor: '#22c55e',
                        backgroundColor: '#22c55e',
                        borderDash: [5, 5],
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#22c55e',
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return "{{ $childName }}: " + context.parsed.y + " kg";
                            }
                        }
                    },
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        color: '#000',
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value) => value + " cm" // langsung tampilkan angkanya
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Tinggi (cm)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
@endpush
