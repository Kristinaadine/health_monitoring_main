@extends('layouts.app')

@push('title')
    Dashboard - Nutrition & Health Monitoring and Progress
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">@t('Dashboard - Nutrition & Health
        Monitoring
        and Progress')</span>
    {{-- <a class="toggle ms-auto" href="#"><i class="icofont-navigation-menu"></i></a> --}}
@endsection

@section('content')
    <div class="address p-3 bg-white">
        <h6 class="m-0 text-dark d-flex align-items-center">👶 @t('Anak Terdaftar') <span class="small ms-auto"><a
                    href="{{ locale_route('nutrition-monitoring.children.index') }}"
                    class="fw-bold text-decoration-none text-success"><i class="icofont-plus"></i>
                    @t('Kelola Data Anak')</a></span></h6>
    </div>
    <div class="p-3">
        <ul>
            @forelse($children as $c)
                <li>{{ $c->nama }} ({{ $c->jenis_kelamin }})</li>
            @empty
                <li>@t('Tidak ada anak terdaftar')</li>
            @endforelse
        </ul>
    </div>
    <div class="address p-3 bg-white">
        <h6 class="m-0 text-dark">⚠️ @t('Alert Terbaru')</h6>
    </div>
    <div class="p-3">
        <ul>
            @forelse($alerts as $a)
                <li><strong>{{ $a->tipe }}</strong>: {{ $a->pesan }}
                    ({{ $a->created_at->locale('id')->diffForHumans() }})
                </li>
            @empty
                <li>@t('Tidak ada alert')</li>
            @endforelse
        </ul>
    </div>
    <div class="accordion mb-4" id="accordionExample">
        <div class="address p-3 bg-white">
            <h6 class="m-0 text-dark"><a class="d-flex align-items-center text-decoration-none w-100" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                    aria-controls="collapseThree">
                    📈 @t('Grafik Pertumbuhan')
                    <i class="icofont-rounded-down ms-auto"></i>
                </a></h6>
        </div>
        <div id="collapseThree" class="collapse p-3 border-top" aria-labelledby="headingThree"
            data-bs-parent="#accordionExample">
            <div class="p-3">
                <div class="mb-3">
                    <div class="my-6">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>@t('Grafik Pertumbuhan Anak')</h5>
                                @if(!empty($growthPeriod))
                                    <p class="text-muted small mb-2">
                                        <i class="icofont-calendar"></i> Periode: {{ $growthPeriod }}
                                    </p>
                                @else
                                    <p class="text-muted small mb-2">
                                        <i class="icofont-info-circle"></i> Belum ada data pertumbuhan
                                    </p>
                                @endif
                                <canvas id="growthChart"></canvas>
                            </div>

                            <div class="col-md-6">
                                <h5>@t('Grafik Nutrisi Harian')</h5>
                                @if(!empty($foodPeriod))
                                    <p class="text-muted small mb-2">
                                        <i class="icofont-calendar"></i> Periode: {{ $foodPeriod }}
                                    </p>
                                @else
                                    <p class="text-muted small mb-2">
                                        <i class="icofont-info-circle"></i> Belum ada data nutrisi
                                    </p>
                                @endif
                                <canvas id="nutritionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion mb-4" id="accordion2">
        <div class="address p-3 bg-white">
            <h6 class="m-0 text-dark"><a class="d-flex align-items-center text-decoration-none w-100" type="button"
                    data-bs-toggle="collapse" data-bs-target="#accordions2" aria-expanded="false"
                    aria-controls="accordions2">
                    📈 @t('Progress Nutrisi Hari Ini')
                    <i class="icofont-rounded-down ms-auto"></i>
                </a></h6>
        </div>
        <div id="accordions2" class="collapse p-3 border-top" aria-labelledby="headingThree" data-bs-parent="#accordion2">
            <div class="p-3">
                <div class="mb-3">
                    <div class="my-6">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik Pertumbuhan
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: @json($growthDates), // array tanggal
                datasets: [{
                    label: "@t('Berat Badan') (kg)",
                    data: @json($weights), // array berat
                    borderColor: 'blue',
                    fill: false
                }, {
                    label: "@t('Tinggi Badan') (cm)',
                    data: @json($heights), // array tinggi
                    borderColor: 'green',
                    fill: false
                }]
            }
        });

        // Grafik Nutrisi Harian
        const nutritionCtx = document.getElementById('nutritionChart').getContext('2d');
        new Chart(nutritionCtx, {
            type: 'bar',
            data: {
                labels: @json($foodDates), // array tanggal
                datasets: [{
                    label: "@t('Kalori')",
                    data: @json($kalori),
                    backgroundColor: 'orange'
                }, {
                    label: "@t('Protein')",
                    data: @json($protein),
                    backgroundColor: 'blue'
                }, {
                    label: "@t('Karbo')",
                    data: @json($karbo),
                    backgroundColor: 'green'
                }, {
                    label: "@t('Lemak')",
                    data: @json($lemak),
                    backgroundColor: 'red'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        // Grafik Progress vs Target (Donut)
        new Chart(document.getElementById('progressChart'), {
            type: 'doughnut',
            data: {
                labels: ["@t('Kalori')", "@t('Protein')", "@t('Karbo')", "@t('Lemak')"],
                datasets: [{
                    data: [
                        {{ $progress['kalori'] ?? 0 }},
                        {{ $progress['protein'] ?? 0 }},
                        {{ $progress['karbo'] ?? 0 }},
                        {{ $progress['lemak'] ?? 0 }}
                    ],
                    backgroundColor: ['orange', 'blue', 'green', 'red']
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: "@t('Capaian Nutrisi Hari Ini vs Target')"
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let val = context.raw;
                                let label = context.label;
                                let target = {
                                    'Kalori': {{ $target['kalori'] ?? 0 }},
                                    'Protein': {{ $target['protein'] ?? 0 }},
                                    'Karbo': {{ $target['karbo'] ?? 0 }},
                                    'Lemak': {{ $target['lemak'] ?? 0 }}
                                };
                                return label + ': ' + val + ' / ' + target[label];
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
