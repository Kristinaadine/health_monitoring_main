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
            <h6 class="m-0 text-dark d-flex align-items-center">{{ $data[0]->name }} <span class="small ms-auto"><a
                        href="#" class="fw-bold text-decoration-none text-success" data-bs-toggle="modal"
                        data-bs-target="#modalChange"><i class="icofont-location-arrow"></i> {{__('monitoring.change')}}</a></span></h6>
        </div>
        <div class="p-3">
            <div class="d-flex align-items-center mb-3">
                <h6 class="mb-0 fw-bold">{{__('monitoring.growth_chart')}}</h6>
            </div>
            
            {{-- Penjelasan Z-Score --}}
            <div class="alert alert-info mb-3" role="alert">
                <h6 class="alert-heading"><i class="icofont-info-circle"></i> Apa itu Z-Score?</h6>
                <p class="small mb-2">Z-Score adalah ukuran standar WHO untuk menilai pertumbuhan anak. Grafik ini menunjukkan perkembangan tinggi dan berat badan anak Anda dari waktu ke waktu.</p>
                <hr class="my-2">
                <p class="small mb-0"><strong>Cara Membaca:</strong></p>
                <ul class="small mb-0">
                    <li><strong>Garis Hijau (Height):</strong> Perkembangan tinggi badan</li>
                    <li><strong>Garis Biru (Weight):</strong> Perkembangan berat badan</li>
                    <li><strong>Z-Score -2 sampai +2:</strong> Normal ✅</li>
                    <li><strong>Z-Score < -2:</strong> Perlu perhatian ⚠️</li>
                    <li><strong>Z-Score > +2:</strong> Perlu perhatian ⚠️</li>
                </ul>
            </div>
            
            <figure class="highcharts-figure">
                <div id="container"></div>
            </figure>
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
        Highcharts.chart('container', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Grafik Perkembangan Pertumbuhan Anak',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold'
                }
            },
            subtitle: {
                text: 'Berdasarkan Standar WHO Z-Score'
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: {!! json_encode($graph['xAxis']) !!},
                title: {
                    text: 'Usia'
                }
            },
            yAxis: {
                title: {
                    text: 'Z-Score'
                },
                plotLines: [{
                    value: 0,
                    color: '#55BF3B',
                    width: 2,
                    label: {
                        text: 'Normal',
                        align: 'right',
                        style: {
                            color: '#55BF3B'
                        }
                    }
                }, {
                    value: -2,
                    color: '#D89A1E',
                    dashStyle: 'dash',
                    width: 1,
                    label: {
                        text: 'Batas Bawah Normal',
                        align: 'right',
                        style: {
                            color: '#D89A1E'
                        }
                    }
                }, {
                    value: 2,
                    color: '#D89A1E',
                    dashStyle: 'dash',
                    width: 1,
                    label: {
                        text: 'Batas Atas Normal',
                        align: 'right',
                        style: {
                            color: '#D89A1E'
                        }
                    }
                }, {
                    value: -3,
                    color: '#DF5353',
                    dashStyle: 'dash',
                    width: 1,
                    label: {
                        text: 'Perlu Perhatian',
                        align: 'right',
                        style: {
                            color: '#DF5353'
                        }
                    }
                }, {
                    value: 3,
                    color: '#DF5353',
                    dashStyle: 'dash',
                    width: 1,
                    label: {
                        text: 'Perlu Perhatian',
                        align: 'right',
                        style: {
                            color: '#DF5353'
                        }
                    }
                }]
            },
            tooltip: {
                shared: true,
                crosshairs: true,
                formatter: function() {
                    let s = '<b>' + this.x + '</b><br/>';
                    this.points.forEach(function(point) {
                        let status = '';
                        if (point.y >= -2 && point.y <= 2) {
                            status = ' (Normal ✅)';
                        } else if (point.y < -2 || point.y > 2) {
                            status = ' (Perlu Perhatian ⚠️)';
                        }
                        s += '<span style="color:' + point.color + '">\u25CF</span> ' + 
                             point.series.name + ': <b>' + point.y.toFixed(2) + '</b>' + status + '<br/>';
                    });
                    return s;
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return this.y.toFixed(1);
                        }
                    },
                    enableMouseTracking: true,
                    marker: {
                        enabled: true,
                        radius: 4
                    }
                }
            },
            series: [{
                name: 'Tinggi Badan (TB/U)',
                data: {!! json_encode($graph['height']) !!},
                color: '#55BF3B'
            }, {
                name: 'Berat Badan (BB/U)',
                data: {!! json_encode($graph['weight']) !!},
                color: '#2196F3'
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
