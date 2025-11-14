@extends('layouts.app')

@push('title')
    Home
@endpush

@section('leading')
    <a href="home" class="text-decoration-none text-dark d-flex align-items-center">
        <img class="osahan-logo me-2"
            src="{{ asset('') }}assets/img/logo/{{ $setting->where('key', 'website_logo')->first()->value }}">
        <h4 class="font-weight-bold text-success m-0">{{ $setting->where('key', 'website_name')->first()->value }}</h4>
    </a>
@endsection

@section('content')
    <div class="p-3 osahan-categories">
        <h6 class="mb-2">{!! __('home.whatDoYouLookingFor') !!}</h6>
        <div class="row m-0">
            <div class="col ps-0 pe-1 py-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('growth-monitoring.index') }}">
                        <img src="{{ asset('') }}assets/img/categorie/1.svg" class="img-fluid px-2">
                        <p class="m-0 pt-2 text-muted text-center">{!! __('home.growthMonitoring') !!}</p>
                    </a>
                </div>
            </div>
            <div class="col p-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('growth-detection.index') }}">
                        <img src="{{ asset('') }}assets/img/categorie/2.svg" class="img-fluid px-2">
                        <p class="m-0 pt-2 text-muted text-center"> {!! __('home.growthDetection') !!}</p>
                    </a>
                </div>
            </div>
            <div class="col p-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('nutrition-monitoring.index') }}">
                        <img src="{{ asset('') }}assets/img/categorie/3.svg" class="img-fluid px-2">
                        <p class="m-0 pt-2 text-muted text-center">{!! __('home.nutritionHealthMonitoring') !!}</p>
                    </a>
                </div>
            </div>
            <div class="col ps-0 pe-1 py-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('caloric') }}">
                        <img src="{{ asset('') }}assets/img/categorie/4.svg" class="img-fluid px-2">
                        <p class="m-0 pt-2 text-muted text-center">{!! __('home.Calories') !!}</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="row m-0">
            <div class="col ps-0 pe-1 py-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('bmi') }}">
                        <img width="48" height="48" src="https://img.icons8.com/color/96/bmi.png" alt="bmi" />
                        <p class="m-0 pt-2 text-muted text-center">{!! __('home.bmiCalculater') !!}</p>
                    </a>
                </div>
            </div>
            <div class="col p-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('meal-planner') }}">
                        <img src="{{ asset('') }}assets/img/categorie/6.svg" class="img-fluid px-2">
                        <p class="m-0 pt-2 text-muted text-center">{!! __('home.mealPlan') !!}</p>
                    </a>
                </div>
            </div>
            <div class="col p-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('food-guide') }}">
                        <img src="{{ asset('') }}assets/img/categorie/7.svg" class="img-fluid px-2">
                        <p class="m-0 pt-2 text-muted text-center">{!! __('home.foodGuides') !!}</p>
                    </a>
                </div>
            </div>
            <div class="col ps-0 pe-1 py-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('stunting') }}">
                        <img src="{{ asset('') }}assets/img/categorie/8.svg" class="img-fluid px-2">
                        <p class="m-0 pt-2 text-muted text-center">{!! __('home.stuntingInfo') !!}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-3 bg-white osahan-promos shadow-sm">
        <div class="d-flex align-items-center px-3 mb-2">
            <h6 class="m-0">{!! __('home.growthChartMonitoring') !!}</h6>
            <a href="{{ locale_route('growth-monitoring.index') }}" class="ms-auto text-success">{!! __('home.seeMore') !!}</a>
        </div>
        
        {{-- Penjelasan Z-Score --}}
        <div class="px-3 mb-2">
            <div class="alert alert-info border-0 py-2 mb-2" style="font-size: 0.85rem;">
                <strong><i class="icofont-bulb"></i> Apa itu Z-Score?</strong>
                <p class="mb-0 mt-1 small">
                    <strong>Z-score</strong> menunjukkan posisi tinggi/berat anak dibanding standar WHO. 
                    <br>• <strong>0</strong> = rata-rata (normal)
                    <br>• <strong>Negatif</strong> (contoh: -1.5) = di bawah rata-rata
                    <br>• <strong>Positif</strong> (contoh: +1.5) = di atas rata-rata
                </p>
            </div>
        </div>
        
        {{-- Legenda Sederhana --}}
        <div class="px-3 mb-2">
            <div class="card border-0 bg-light mb-0">
                <div class="card-body p-2">
                    <div class="row small">
                        <div class="col-6">
                            <p class="mb-1"><span class="badge bg-success">🟢</span> Tinggi Badan (TB/U)</p>
                            <p class="mb-0"><span class="badge bg-primary">🔵</span> Berat Badan (BB/U)</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><span class="badge bg-success">✅</span> Normal (-2 s/d +2)</p>
                            <p class="mb-1"><span class="badge bg-warning">⚠️</span> Waspada (-3 s/d -2 atau +2 s/d +3)</p>
                            <p class="mb-0"><span class="badge bg-danger">⚠️</span> Perlu Perhatian (< -3 atau > +3)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <figure class="highcharts-figure">
            <div id="growthMonitoring"></div>
        </figure>
        
        {{-- Interpretasi Singkat --}}
        @if(isset($graph['height']) && count($graph['height']) > 0)
        <div class="px-3 mt-2">
            <small class="text-muted">
                <strong>Catatan:</strong> 
                @php
                    $lastHeight = end($graph['height']);
                    $lastWeight = end($graph['weight']);
                @endphp
                @if($lastHeight < -2 || $lastWeight < -2)
                    ⚠️ Ada indikasi yang perlu perhatian. Klik "Lihat Lebih" untuk detail lengkap.
                @elseif($lastHeight > 2 || $lastWeight > 2)
                    ⚠️ Pertumbuhan di atas rata-rata. Konsultasi untuk memastikan kesehatan optimal.
                @else
                    ✅ Pertumbuhan dalam rentang normal. Pertahankan pola makan sehat!
                @endif
            </small>
        </div>
        @endif
    </div>
@endsection

@push('js')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.chart('growthMonitoring', {
            chart: {
                type: 'line',
                backgroundColor: '#FAFAFA'
            },
            title: {
                text: 'Grafik Perkembangan Pertumbuhan Anak',
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            },
            subtitle: {
                text: 'Berdasarkan Standar WHO Z-Score',
                style: {
                    fontSize: '11px',
                    color: '#666'
                }
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: {!! json_encode($graph['xAxis']) !!},
                title: {
                    text: 'Usia',
                    style: {
                        fontWeight: 'bold',
                        fontSize: '11px'
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Z-Score',
                    style: {
                        fontWeight: 'bold',
                        fontSize: '11px'
                    }
                },
                // Zona warna background
                plotBands: [{
                    from: -2,
                    to: 2,
                    color: 'rgba(85, 191, 59, 0.1)', // Hijau muda - Normal
                    label: {
                        text: 'Normal',
                        style: {
                            color: '#55BF3B',
                            fontWeight: 'bold',
                            fontSize: '10px'
                        }
                    }
                }, {
                    from: -3,
                    to: -2,
                    color: 'rgba(255, 193, 7, 0.1)', // Kuning muda - Waspada
                    label: {
                        text: 'Waspada',
                        style: {
                            color: '#FFC107',
                            fontSize: '9px'
                        }
                    }
                }, {
                    from: 2,
                    to: 3,
                    color: 'rgba(255, 193, 7, 0.1)', // Kuning muda - Waspada
                    label: {
                        text: 'Waspada',
                        style: {
                            color: '#FFC107',
                            fontSize: '9px'
                        }
                    }
                }, {
                    from: -5,
                    to: -3,
                    color: 'rgba(244, 67, 54, 0.1)', // Merah muda - Perlu Perhatian
                    label: {
                        text: 'Perlu Perhatian',
                        style: {
                            color: '#F44336',
                            fontSize: '9px'
                        }
                    }
                }, {
                    from: 3,
                    to: 5,
                    color: 'rgba(244, 67, 54, 0.1)', // Merah muda - Perlu Perhatian
                    label: {
                        text: 'Perlu Perhatian',
                        style: {
                            color: '#F44336',
                            fontSize: '9px'
                        }
                    }
                }],
                // Garis referensi tipis
                plotLines: [{
                    value: 0,
                    color: '#55BF3B',
                    width: 1,
                    dashStyle: 'dot',
                    zIndex: 1
                }, {
                    value: -2,
                    color: '#FFC107',
                    width: 1,
                    dashStyle: 'dash',
                    zIndex: 1
                }, {
                    value: 2,
                    color: '#FFC107',
                    width: 1,
                    dashStyle: 'dash',
                    zIndex: 1
                }, {
                    value: -3,
                    color: '#F44336',
                    width: 1,
                    dashStyle: 'dash',
                    zIndex: 1
                }, {
                    value: 3,
                    color: '#F44336',
                    width: 1,
                    dashStyle: 'dash',
                    zIndex: 1
                }]
            },
            tooltip: {
                shared: true,
                crosshairs: true,
                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                borderColor: '#CCC',
                borderRadius: 8,
                shadow: true,
                useHTML: true,
                formatter: function() {
                    let s = '<div style="padding: 5px;"><b>' + this.x + '</b><br/>';
                    this.points.forEach(function(point) {
                        let status = '';
                        let icon = '';
                        if (point.y >= -2 && point.y <= 2) {
                            status = 'Normal';
                            icon = '✅';
                        } else if (point.y >= -3 && point.y < -2 || point.y > 2 && point.y <= 3) {
                            status = 'Waspada';
                            icon = '⚠️';
                        } else {
                            status = 'Perlu Perhatian';
                            icon = '⚠️';
                        }
                        
                        let seriesName = point.series.name === 'Tinggi Badan (TB/U)' ? 'TB/U' : 'BB/U';
                        s += '<span style="color:' + point.color + '; font-size: 14px;">\u25CF</span> ' + 
                             '<b>' + seriesName + ':</b> ' + point.y.toFixed(2) + ' ' + icon + ' ' + status + '<br/>';
                    });
                    s += '</div>';
                    return s;
                }
            },
            plotOptions: {
                line: {
                    lineWidth: 3, // Garis lebih tebal
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return this.y.toFixed(1);
                        },
                        style: {
                            fontWeight: 'bold',
                            fontSize: '10px',
                            textOutline: '1px white'
                        }
                    },
                    enableMouseTracking: true,
                    marker: {
                        enabled: true,
                        radius: 4,
                        lineWidth: 2,
                        lineColor: '#FFFFFF'
                    }
                }
            },
            series: [{
                name: 'Tinggi Badan (TB/U)',
                data: {!! json_encode($graph['height']) !!},
                color: '#55BF3B', // Hijau - konsisten dengan Growth Monitoring
                marker: {
                    symbol: 'circle' // Lingkaran ●
                },
                zIndex: 2
            }, {
                name: 'Berat Badan (BB/U)',
                data: {!! json_encode($graph['weight']) !!},
                color: '#2196F3', // Biru - konsisten dengan Growth Monitoring
                marker: {
                    symbol: 'diamond' // Diamond ◆
                },
                zIndex: 2
            }]
        });
    </script>
@endpush
