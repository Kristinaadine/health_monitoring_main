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
        
        {{-- Info Box Singkat --}}
        <div class="px-3 mb-2">
            <div class="alert alert-info py-2 mb-2" style="font-size: 0.85rem;">
                <strong><i class="icofont-info-circle"></i> Cara Baca Grafik:</strong>
                <span class="d-block mt-1">
                    📊 <strong>Z-Score</strong> = Standar deviasi (perbandingan dengan anak sehat seusianya)
                    <br>✅ <strong>Normal:</strong> -2 sampai +2 | ⚠️ <strong>Perhatian:</strong> < -2 atau > +2
                </span>
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
                type: 'line'
            },
            title: {
                text: ''
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: {!! json_encode($graph['xAxis']) !!}
            },
            yAxis: {
                title: {
                    text: 'Z-Score (Standar Deviasi)'
                },
                plotLines: [{
                    value: -2,
                    color: '#FFA500',
                    dashStyle: 'dash',
                    width: 1,
                    label: {
                        text: 'Batas Bawah Normal',
                        align: 'right',
                        style: {
                            color: '#FFA500',
                            fontSize: '9px'
                        }
                    }
                }, {
                    value: 2,
                    color: '#FFA500',
                    dashStyle: 'dash',
                    width: 1,
                    label: {
                        text: 'Batas Atas Normal',
                        align: 'right',
                        style: {
                            color: '#FFA500',
                            fontSize: '9px'
                        }
                    }
                }, {
                    value: 0,
                    color: '#28a745',
                    dashStyle: 'solid',
                    width: 1,
                    label: {
                        text: 'Rata-rata',
                        align: 'right',
                        style: {
                            color: '#28a745',
                            fontSize: '9px'
                        }
                    }
                }]
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            tooltip: {
                shared: true,
                useHTML: true,
                formatter: function() {
                    let s = '<b>' + this.x + '</b><br/>';
                    this.points.forEach(function(point) {
                        let status = '';
                        if (point.y < -2) {
                            status = ' <span style="color:#dc3545">⚠️ Perlu Perhatian</span>';
                        } else if (point.y > 2) {
                            status = ' <span style="color:#ffc107">⚠️ Di Atas Normal</span>';
                        } else {
                            status = ' <span style="color:#28a745">✅ Normal</span>';
                        }
                        s += '<br/>' + point.series.name + ': <b>' + point.y.toFixed(2) + '</b>' + status;
                    });
                    return s;
                }
            },
            series: [{
                name: 'Tinggi (TB/U)',
                data: {!! json_encode($graph['height']) !!},
                color: '#17a2b8'
            }, {
                name: 'Berat (BB/U)',
                data: {!! json_encode($graph['weight']) !!},
                color: '#6f42c1'
            }]
        });
    </script>
@endpush
