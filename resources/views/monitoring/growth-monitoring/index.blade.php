@extends('layouts.app')

@push('title')
    Growth Monitoring for Stunting
@endpush

@push('css')
    <style>
        .floatbtn {
            position: fixed;
            bottom: 70px;
            right: 12px;
            z-index: 99999;
            width: 40px;
            height: 40px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .imgnores {
            width: 50%;
        }

        @media screen and (max-width: 768px) {
            .imgnores {
                width: 100%;
            }
        }
    </style>
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a>
    <span class="fw-bold ms-3 h6 m-0">{{__('monitoring.growth_monitoring_for_stunting')}}</span>
    <button type="button" class="btn btn-outline-success btn-sm ms-auto" data-bs-toggle="modal"
        data-bs-target="#modalAddNew">{{__('monitoring.add')}}</button>
@endsection

@section('content')
    {{-- Alert Messages --}}
    @include('components.alert')
    
    @if (count($data) > 0)
        <div class="address p-3 bg-white">
            <div class="d-flex align-items-center">
                {{-- Foto Anak --}}
                @if($data[0]->photo)
                    <img src="{{ asset('uploads/growth-monitoring/' . $data[0]->photo) }}" 
                         class="rounded-circle me-3" 
                         style="width: 60px; height: 60px; object-fit: cover; border: 3px solid #28a745;">
                @else
                    <div class="rounded-circle bg-secondary me-3 d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px;">
                        <i class="icofont-baby icofont-2x text-white"></i>
                    </div>
                @endif
                
                <div class="flex-grow-1">
                    <h6 class="m-0 text-dark">{{ $data[0]->name }}</h6>
                    @if($data[0]->child_id)
                        <div class="d-flex align-items-center">
                            <small class="text-muted me-2">🏥 ID: <strong>{{ $data[0]->child_id }}</strong></small>
                            <button class="btn btn-sm btn-outline-secondary py-0 px-2" 
                                    onclick="copyToClipboard('{{ $data[0]->child_id }}')" 
                                    title="Copy ID">
                                <i class="icofont-copy"></i>
                            </button>
                        </div>
                    @endif
                </div>
                
                <span class="small">
                    <a href="#" class="fw-bold text-decoration-none text-success" data-bs-toggle="modal"
                       data-bs-target="#modalChange">
                        <i class="icofont-location-arrow"></i> {{__('monitoring.change')}}
                    </a>
                </span>
            </div>
        </div>
        <div class="p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="mb-0 fw-bold">{{__('monitoring.growth_chart')}}</h6>
                @if(count($data) > 0)
                <a href="{{ locale_route('growth-monitoring.download-complete-report', ['name' => $data[0]->name]) }}" 
                   class="btn btn-sm btn-danger">
                    <i class="icofont-file-pdf"></i> Unduh Laporan PDF
                </a>
                @endif
            </div>
            
            {{-- Ringkasan Data Terbaru --}}
            @if(count($data) > 0)
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="icofont-chart-line"></i> Ringkasan Terbaru ({{ $data[0]->age }} bulan)</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-success me-2">🟢</span>
                                <strong>Tinggi Badan (TB/U)</strong>
                            </div>
                            @php
                                $latestHeight = $data[0]->history->where('type', 'LH')->first();
                                $heightStatus = 'Normal';
                                $heightStatusIcon = '✅';
                                $heightColor = 'success';
                                if ($latestHeight && $latestHeight->zscore) {
                                    if ($latestHeight->zscore < -3 || $latestHeight->zscore > 3) {
                                        $heightStatus = 'Perlu Perhatian';
                                        $heightStatusIcon = '⚠️';
                                        $heightColor = 'danger';
                                    } elseif ($latestHeight->zscore < -2 || $latestHeight->zscore > 2) {
                                        $heightStatus = 'Waspada';
                                        $heightStatusIcon = '⚠️';
                                        $heightColor = 'warning';
                                    }
                                }
                            @endphp
                            <p class="mb-0">
                                <span class="fs-5 fw-bold text-success">{{ $latestHeight ? number_format($latestHeight->zscore, 2) : 'N/A' }}</span>
                                <br>
                                <span class="badge bg-{{ $heightColor }}">{{ $heightStatusIcon }} {{ $heightStatus }}</span>
                            </p>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2">🔵</span>
                                <strong>Berat Badan (BB/U)</strong>
                            </div>
                            @php
                                $latestWeight = $data[0]->history->where('type', 'W')->first();
                                $weightStatus = 'Normal';
                                $weightStatusIcon = '✅';
                                $weightColor = 'success';
                                if ($latestWeight && $latestWeight->zscore) {
                                    if ($latestWeight->zscore < -3 || $latestWeight->zscore > 3) {
                                        $weightStatus = 'Perlu Perhatian';
                                        $weightStatusIcon = '⚠️';
                                        $weightColor = 'danger';
                                    } elseif ($latestWeight->zscore < -2 || $latestWeight->zscore > 2) {
                                        $weightStatus = 'Waspada';
                                        $weightStatusIcon = '⚠️';
                                        $weightColor = 'warning';
                                    }
                                }
                            @endphp
                            <p class="mb-0">
                                <span class="fs-5 fw-bold text-primary">{{ $latestWeight ? number_format($latestWeight->zscore, 2) : 'N/A' }}</span>
                                <br>
                                <span class="badge bg-{{ $weightColor }}">{{ $weightStatusIcon }} {{ $weightStatus }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            {{-- Grafik --}}
            <figure class="highcharts-figure">
                <div id="container"></div>
            </figure>
            
            {{-- Interpretasi Otomatis --}}
            @if(count($data) > 0)
            <div class="alert alert-light border mb-3" role="alert">
                <h6 class="alert-heading"><i class="icofont-info-circle"></i> Interpretasi Otomatis</h6>
                @php
                    $latestHeight = $data[0]->history->where('type', 'LH')->first();
                    $latestWeight = $data[0]->history->where('type', 'W')->first();
                @endphp
                <p class="mb-0 small">
                    Pada usia <strong>{{ $data[0]->age }} bulan</strong>, 
                    @if($latestHeight && $latestHeight->zscore)
                        tinggi badan anak berada dalam kategori 
                        <strong class="text-{{ $latestHeight->zscore >= -2 && $latestHeight->zscore <= 2 ? 'success' : 'danger' }}">
                            {{ $latestHeight->zscore >= -2 && $latestHeight->zscore <= 2 ? 'Normal' : 'Perlu Perhatian' }}
                        </strong>
                    @endif
                    dan 
                    @if($latestWeight && $latestWeight->zscore)
                        berat badan berada dalam kategori 
                        <strong class="text-{{ $latestWeight->zscore >= -2 && $latestWeight->zscore <= 2 ? 'success' : 'danger' }}">
                            {{ $latestWeight->zscore >= -2 && $latestWeight->zscore <= 2 ? 'Normal' : 'Perlu Perhatian' }}
                        </strong>
                    @endif
                    berdasarkan standar WHO.
                </p>
            </div>
            @endif
            
            {{-- Penjelasan Z-Score --}}
            <div class="alert alert-info border-0 mb-3" role="alert">
                <h6 class="alert-heading"><i class="icofont-bulb"></i> Apa itu Z-Score?</h6>
                <p class="mb-0 small">
                    <strong>Z-score</strong> menunjukkan posisi tinggi/berat anak dibanding standar WHO. 
                    <br>• <strong>0</strong> = rata-rata (normal)
                    <br>• <strong>Negatif</strong> (contoh: -1.5) = di bawah rata-rata
                    <br>• <strong>Positif</strong> (contoh: +1.5) = di atas rata-rata
                    <br><em>Semakin jauh dari 0, semakin perlu perhatian khusus.</em>
                </p>
            </div>
            
            {{-- Legenda Sederhana --}}
            <div class="card border-0 bg-light mb-3">
                <div class="card-body p-3">
                    <h6 class="mb-2"><i class="icofont-question-circle"></i> Cara Membaca Grafik</h6>
                    <div class="row small">
                        <div class="col-6">
                            <p class="mb-1"><span class="badge bg-success">🟢</span> Tinggi Badan (TB/U)</p>
                            <p class="mb-1"><span class="badge bg-primary">🔵</span> Berat Badan (BB/U)</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><span class="badge bg-success">✅</span> Normal (-2 s/d +2)</p>
                            <p class="mb-1"><span class="badge bg-warning">⚠️</span> Waspada (-3 s/d -2 atau +2 s/d +3)</p>
                            <p class="mb-1"><span class="badge bg-danger">⚠️</span> Perlu Perhatian (< -3 atau > +3)</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <p class="mb-0 small text-muted">
                        <i class="icofont-info-circle"></i> Grafik menunjukkan perkembangan anak dibandingkan standar WHO. 
                        Garis hijau untuk tinggi, biru untuk berat. Area hijau = normal, kuning = waspada, merah = perlu perhatian.
                    </p>
                </div>
            </div>
        </div>
        <div class="address p-3 bg-white">
            <h6 class="m-0 text-dark">{{__('monitoring.history')}} - {{ $data[0]->name }} </h6>
        </div>
        <div class="p-3">
            @foreach ($data as $item)
                {{-- @dd($item->history[0]) --}}
                <div class="form-check px-0 mb-3 position-relative border-custom-radio">
                    <input type="radio" id="customRadioInline1-{{ $item->id }}" name="customRadioInline1"
                        class="form-check-input">
                    <label class="form-check-label w-100" for="customRadioInline1-{{ $item->id }}">
                        <div>
                            <div class="p-3 bg-white rounded shadow-sm w-100">
                                <div class="d-flex align-items-center mb-2">
                                    <p class="mb-0 h5">{{__('monitoring.age')}} : {{ $item->age }} {{__('monitoring.month')}}</p>
                                </div>
                                <hr>
                                @php
                                    $color = '';
                                    $color2 = '';

                                    $history0 = $item->history[0] ?? null;
                                    $history1 = $item->history[1] ?? null;

                                    if ($history0) {
                                        if (
                                            $history0->hasil_diagnosa == 'Tinggi Badan Sangat Tinggi' ||
                                            $history0->hasil_diagnosa == 'Sangat Pendek'
                                        ) {
                                            $color = 'danger';
                                        } elseif (
                                            $history0->hasil_diagnosa == 'Tinggi' ||
                                            $history0->hasil_diagnosa == 'Pendek'
                                        ) {
                                            $color = 'warning';
                                        } elseif ($history0->hasil_diagnosa == 'Tinggi Badan Normal') {
                                            $color = 'success';
                                        }
                                    }

                                    if ($history1) {
                                        if (
                                            $history1->hasil_diagnosa == 'Obesitas' ||
                                            $history1->hasil_diagnosa == 'Gizi Kurang'
                                        ) {
                                            $color2 = 'danger';
                                        } elseif (
                                            $history1->hasil_diagnosa == 'Gizi Lebih' ||
                                            $history1->hasil_diagnosa == 'Risiko Gizi Lebih'
                                        ) {
                                            $color2 = 'warning';
                                        } elseif ($history1->hasil_diagnosa == 'Gizi Normal') {
                                            $color2 = 'success';
                                        }
                                    }
                                @endphp
                                <div class="d-flex align-items-center mb-2">
                                    <p class="mb-0 h6">{{__('monitoring.height')}} : {{ $item->height }} cm</p>
                                    <p class="mb-0 h6 ms-auto">Weight : {{ $item->weight }} kg</p>
                                </div>
                               {{--<div class="d-flex align-items-center mb-2">
                                    <p class="mb-0">{{__('monitoring.zscore')}} : {{ $item->history[0]->zscore }}</p>
                                    <p class="mb-0 ms-auto">{{__('monitoring.zscore')}} : {{ $item->history[1]->zscore }}</p>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <p class="mb-0 badge badge-{{ $color }}">
                                        {{ $item->history[0]->hasil_diagnosa }}
                                    </p>
                                    <p class="mb-0 badge badge-{{ $color2 }} ms-auto">
                                        {{ $item->history[1]->hasil_diagnosa }}</p>
                                </div> --}}
                                <div class="d-flex align-items-center mb-2">
                                    <p class="mb-0">{{__('monitoring.zscore')}} : {{ $history0 && $history0->zscore !== null ? number_format($history0->zscore, 2) : '0.00' }}</p>
                                    <p class="mb-0 ms-auto">{{__('monitoring.zscore')}} : {{ $history1 && $history1->zscore !== null ? number_format($history1->zscore, 2) : '0.00' }}</p>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <p class="mb-0 badge badge-{{ $color }}">
                                        {{ $history0->hasil_diagnosa ?? 'Data tidak tersedia' }}
                                    </p>
                                    <p class="mb-0 badge badge-{{ $color2 }} ms-auto">
                                        {{ $history1->hasil_diagnosa ?? 'Data tidak tersedia' }}
                                    </p>
                                </div>
                                <p class="pt-2 m-0 text-end">
                                    <span class="small">
                                        <a href="{{ locale_route('growth-monitoring.show', encrypt($item->id)) }}"
                                            class="text-decoration-none text-primary"><i class="icofont-eye"></i> {{__('monitoring.show')}}
                                        </a>
                                    </span>
                                    {{-- <span class="small ms-3">
                                        <a href="#" data-id="{{ encrypt($item->id) }}" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" class="text-decoration-none text-success">
                                            <i class="icofont-edit"></i> {{__('monitoring.edit')}}
                                        </a>
                                    </span> --}}
                                    <span class="small ms-3"><a href="#" data-id="{{ encrypt($item->id) }}"
                                            class="text-decoration-none text-danger deleteBtn"><i class="icofont-trash"></i>
                                            {{__('monitoring.delete')}}</a></span>
                                            
                                </p>
                            </div>
                        </div>
                    </label>
                </div>
            @endforeach
        </div>
    @else
        <div class="pick_today p-3">
            <div class="row">
                <div class="col-12">
                    <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm p-3">
                        <img src="{{ asset('') }}assets/img/Nodata.svg"
                            class="img-fluid rounded mx-auto d-block imgnores" alt="No Result">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalForm"
                            class="btn btn-success btn-lg rounded w-100"> + {{__('monitoring.add_data')}}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{--  --}}
    @include('monitoring.growth-monitoring.modalform')
    @include('monitoring.growth-monitoring.modalchange')
    @include('monitoring.growth-monitoring.modaladdnew')
@endsection

@push('js')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        // Copy to Clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                $.notify("ID berhasil dicopy: " + text, "success");
            }).catch(function(err) {
                // Fallback for older browsers
                const tempInput = document.createElement('input');
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                $.notify("ID berhasil dicopy: " + text, "success");
            });
        }
    </script>
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'line',
                backgroundColor: '#FAFAFA'
            },
            title: {
                text: 'Grafik Perkembangan Pertumbuhan Anak',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            },
            subtitle: {
                text: 'Berdasarkan Standar WHO Z-Score',
                style: {
                    fontSize: '12px',
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
                        fontWeight: 'bold'
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Z-Score',
                    style: {
                        fontWeight: 'bold'
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
                            fontWeight: 'bold'
                        }
                    }
                }, {
                    from: -3,
                    to: -2,
                    color: 'rgba(255, 193, 7, 0.1)', // Kuning muda - Waspada
                    label: {
                        text: 'Waspada',
                        style: {
                            color: '#FFC107'
                        }
                    }
                }, {
                    from: 2,
                    to: 3,
                    color: 'rgba(255, 193, 7, 0.1)', // Kuning muda - Waspada
                    label: {
                        text: 'Waspada',
                        style: {
                            color: '#FFC107'
                        }
                    }
                }, {
                    from: -5,
                    to: -3,
                    color: 'rgba(244, 67, 54, 0.1)', // Merah muda - Perlu Perhatian
                    label: {
                        text: 'Perlu Perhatian',
                        style: {
                            color: '#F44336'
                        }
                    }
                }, {
                    from: 3,
                    to: 5,
                    color: 'rgba(244, 67, 54, 0.1)', // Merah muda - Perlu Perhatian
                    label: {
                        text: 'Perlu Perhatian',
                        style: {
                            color: '#F44336'
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
                            textOutline: '1px white'
                        }
                    },
                    enableMouseTracking: true,
                    marker: {
                        enabled: true,
                        radius: 5,
                        lineWidth: 2,
                        lineColor: '#FFFFFF'
                    }
                }
            },
            series: [{
                name: 'Tinggi Badan (TB/U)',
                data: {!! json_encode($graph['height']) !!},
                color: '#55BF3B', // Hijau
                marker: {
                    symbol: 'circle' // Lingkaran ●
                },
                zIndex: 2
            }, {
                name: 'Berat Badan (BB/U)',
                data: {!! json_encode($graph['weight']) !!},
                color: '#2196F3', // Biru
                marker: {
                    symbol: 'diamond' // Diamond ◆
                },
                zIndex: 2
            }]
        });


        $(document).on('click', '.deleteBtn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            var url = "{{ locale_route('growth-monitoring.destroy', ':id') }}";
            url = url.replace(':id', id);
            swal({
                    title: "{{__('modal.confirm_delete')}}",
                    text: "{{__('modal.want_to_delete')}}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    $.notify(response.message, "success");
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1500);
                                } else {
                                    $.notify(response.message, "error");
                                }
                            },
                            error: function(xhr) {
                                $.notify("An error occurred", "error");
                            }
                        });
                    }
                });
        });
    </script>
@endpush
