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
    </style>
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{locale_route('growth-monitoring.index')}}"><i
        class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">{{__('monitoring.back')}}</span>
    <a href="{{ locale_route('growth-monitoring.download-report', encrypt($data->id)) }}" 
       class="btn btn-sm btn-danger ms-auto" 
       style="float: right;">
        <i class="icofont-file-pdf"></i> Download Laporan PDF
    </a>
@endsection

@section('content')
    <div class="pick_today p-3">
        <div class="row">
            <div class="col-12">
                <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm p-3">
                    <table width="100%" style="font-size: 0.95rem;">
                        <tr>
                            <td style="width: 20%; padding: 8px 0;"><strong>Nama Lengkap</strong></td>
                            <td style="width: 2%; padding: 8px 0;">:</td>
                            <td style="width: 28%; padding: 8px 0;">{{ $data->name }} <span class="badge bg-info text-white">{{ $data->age }} bulan</span></td>

                            <td style="width: 20%; padding: 8px 0;"><strong>Tanggal Input</strong></td>
                            <td style="width: 2%; padding: 8px 0;">:</td>
                            <td style="width: 28%; padding: 8px 0;">{{ \Carbon\Carbon::parse($data->created_at)->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;"><strong>Jenis Kelamin</strong></td>
                            <td style="padding: 8px 0;">:</td>
                            <td style="padding: 8px 0;">
                                @if($data->gender == 'L')
                                    <span class="badge bg-primary">👦 Laki-laki</span>
                                @else
                                    <span class="badge bg-danger">👧 Perempuan</span>
                                @endif
                            </td>

                            <td style="padding: 8px 0;"><strong>Tinggi / Berat</strong></td>
                            <td style="padding: 8px 0;">:</td>
                            <td style="padding: 8px 0;">
                                <span class="badge bg-success">📏 {{ $data->height }} cm</span>
                                <span class="badge bg-warning text-dark">⚖️ {{ $data->weight }} kg</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="pick_today p-3">
        <div class="row">
            <div class="col-12">
                <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm p-3">
                    <div class="schedule">
                        <ul class="nav nav-tabs justify-content-center nav-fill" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active text-dark" id="tbu-tab" data-bs-toggle="tab" href="#tbu"
                                    role="tab" aria-controls="tbu" aria-selected="true">
                                    <p class="mb-0 fw-bold">{{__('monitoring.diagnosis')}}</p>
                                    <p class="mb-0">TB/U (Tinggi Badan/Umur)</p>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-dark" id="bbu-tab" data-bs-toggle="tab" href="#bbu"
                                    role="tab" aria-controls="bbu" aria-selected="false">
                                    <p class="mb-0 fw-bold">{{__('monitoring.diagnosis')}}</p>
                                    <p class="mb-0">BB/U (Berat Badan/Umur)</p>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content filter bg-white" id="myTabContent">
                            <div class="tab-pane fade show active" id="tbu" role="tabpanel" aria-labelledby="tbu-tab">
                                
                                <!-- Info Box untuk TB/U -->
                                <div class="alert alert-light border mb-3" role="alert">
                                    <h6 class="mb-2"><i class="icofont-info-circle text-info"></i> <strong>Apa itu TB/U (Tinggi Badan menurut Umur)?</strong></h6>
                                    <p class="mb-2 small">TB/U adalah indikator untuk menilai status gizi anak berdasarkan tinggi badan dibandingkan dengan umurnya. Indikator ini digunakan untuk mendeteksi <strong>stunting</strong> (anak pendek).</p>
                                    <hr>
                                    <h6 class="mb-2"><strong>Cara Membaca Grafik:</strong></h6>
                                    <ul class="small mb-0">
                                        <li><strong>Jarum hitam</strong> menunjukkan posisi Z-Score anak Anda</li>
                                        <li><strong>Zona Hijau (Normal):</strong> Tinggi badan sesuai umur ✅</li>
                                        <li><strong>Zona Kuning (Perhatian):</strong> Perlu monitoring lebih ketat ⚠️</li>
                                        <li><strong>Zona Merah (Bahaya):</strong> Perlu penanganan segera 🚨</li>
                                    </ul>
                                </div>

                                <figure class="highcharts-figure">
                                    <div id="graph"></div>
                                </figure>
                                @php
                                    $history0 = $data->history[0] ?? null;
                                    $color = 'info';
                                    
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
                                @endphp
                                @if($history0)
                                    <div class="alert alert-{{ $color }}" role="alert">
                                        <div class="d-flex align-items-center mb-2">
                                            <h4 class="alert-heading mb-0">{{ $history0->hasil_diagnosa }}</h4>
                                            <span class="badge bg-dark ms-auto">Z-Score: {{ number_format($history0->zscore, 2) }}</span>
                                        </div>
                                        
                                        <!-- Penjelasan Status -->
                                        <div class="mb-2">
                                            <strong>Apa artinya?</strong>
                                            <p class="mb-0">{{ $history0->deskripsi_diagnosa }}</p>
                                        </div>
                                        
                                        <!-- Interpretasi Z-Score -->
                                        <div class="mb-2">
                                            <strong>Interpretasi Z-Score:</strong>
                                            <p class="mb-0 small">
                                                @if($history0->zscore >= -2 && $history0->zscore <= 2)
                                                    ✅ Tinggi badan anak Anda berada dalam <strong>rentang normal</strong> sesuai standar WHO.
                                                @elseif($history0->zscore < -2 && $history0->zscore >= -3)
                                                    ⚠️ Tinggi badan anak Anda <strong>di bawah normal</strong>. Perlu perhatian khusus untuk mencegah stunting.
                                                @elseif($history0->zscore < -3)
                                                    🚨 Tinggi badan anak Anda <strong>sangat pendek</strong>. Segera konsultasi dengan tenaga kesehatan.
                                                @elseif($history0->zscore > 2 && $history0->zscore <= 3)
                                                    ⚠️ Tinggi badan anak Anda <strong>di atas normal</strong>. Monitoring rutin diperlukan.
                                                @else
                                                    🚨 Tinggi badan anak Anda <strong>sangat tinggi</strong>. Konsultasi dengan dokter untuk evaluasi lebih lanjut.
                                                @endif
                                            </p>
                                        </div>
                                        
                                        <hr>
                                        
                                        <!-- Rekomendasi -->
                                        <div>
                                            <strong><i class="icofont-doctor"></i> Rekomendasi Tindakan:</strong>
                                            <p class="mb-0">{{ $history0->penanganan }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        <p class="mb-0">Data diagnosis TB/U tidak tersedia.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="bbu" role="tabpanel" aria-labelledby="bbu-tab">
                                
                                <!-- Info Box untuk BB/U -->
                                <div class="alert alert-light border mb-3" role="alert">
                                    <h6 class="mb-2"><i class="icofont-info-circle text-info"></i> <strong>Apa itu BB/U (Berat Badan menurut Umur)?</strong></h6>
                                    <p class="mb-2 small">BB/U adalah indikator untuk menilai status gizi anak berdasarkan berat badan dibandingkan dengan umurnya. Indikator ini digunakan untuk mendeteksi <strong>gizi kurang</strong> atau <strong>gizi lebih</strong>.</p>
                                    <hr>
                                    <h6 class="mb-2"><strong>Cara Membaca Grafik:</strong></h6>
                                    <ul class="small mb-0">
                                        <li><strong>Jarum hitam</strong> menunjukkan posisi Z-Score anak Anda</li>
                                        <li><strong>Zona Hijau (Normal):</strong> Berat badan sesuai umur ✅</li>
                                        <li><strong>Zona Kuning (Perhatian):</strong> Risiko gizi lebih ⚠️</li>
                                        <li><strong>Zona Merah (Bahaya):</strong> Gizi kurang atau obesitas 🚨</li>
                                    </ul>
                                </div>

                                <figure class="highcharts-figure">
                                    <div id="graph2"></div>
                                </figure>

                                @php
                                    $history1 = $data->history[1] ?? null;
                                    $color2 = 'info';
                                    
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
                                @if($history1)
                                    <div class="alert alert-{{ $color2 }}" role="alert">
                                        <div class="d-flex align-items-center mb-2">
                                            <h4 class="alert-heading mb-0">{{ $history1->hasil_diagnosa }}</h4>
                                            <span class="badge bg-dark ms-auto">Z-Score: {{ number_format($history1->zscore, 2) }}</span>
                                        </div>
                                        
                                        <!-- Penjelasan Status -->
                                        <div class="mb-2">
                                            <strong>Apa artinya?</strong>
                                            <p class="mb-0">{{ $history1->deskripsi_diagnosa }}</p>
                                        </div>
                                        
                                        <!-- Interpretasi Z-Score -->
                                        <div class="mb-2">
                                            <strong>Interpretasi Z-Score:</strong>
                                            <p class="mb-0 small">
                                                @if($history1->zscore >= -2 && $history1->zscore < 1)
                                                    ✅ Berat badan anak Anda berada dalam <strong>rentang normal</strong> sesuai standar WHO.
                                                @elseif($history1->zscore < -2 && $history1->zscore >= -3)
                                                    ⚠️ Berat badan anak Anda <strong>kurang</strong>. Perlu peningkatan asupan nutrisi.
                                                @elseif($history1->zscore < -3)
                                                    🚨 Berat badan anak Anda <strong>sangat kurang</strong>. Segera konsultasi dengan ahli gizi.
                                                @elseif($history1->zscore >= 1 && $history1->zscore < 2)
                                                    ⚠️ Berat badan anak Anda <strong>berisiko gizi lebih</strong>. Perhatikan pola makan.
                                                @elseif($history1->zscore >= 2 && $history1->zscore < 3)
                                                    🚨 Anak Anda mengalami <strong>gizi lebih</strong>. Konsultasi dengan ahli gizi untuk program diet.
                                                @else
                                                    🚨 Anak Anda mengalami <strong>obesitas</strong>. Segera konsultasi dengan dokter dan ahli gizi.
                                                @endif
                                            </p>
                                        </div>
                                        
                                        <hr>
                                        
                                        <!-- Rekomendasi -->
                                        <div>
                                            <strong><i class="icofont-doctor"></i> Rekomendasi Tindakan:</strong>
                                            <p class="mb-0">{{ $history1->penanganan }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        <p class="mb-0">Data diagnosis BB/U tidak tersedia.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Penjelasan Umum Z-Score -->
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="mb-2"><i class="icofont-question-circle"></i> <strong>Apa itu Z-Score?</strong></h6>
                        <p class="small mb-2">
                            Z-Score adalah nilai standar yang digunakan WHO untuk menilai status gizi anak. 
                            Nilai ini membandingkan tinggi/berat badan anak Anda dengan standar anak sehat seusianya.
                        </p>
                        
                        <h6 class="mb-2"><strong>Rentang Z-Score:</strong></h6>
                        <div class="row small">
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2" style="width: 60px;">Normal</span>
                                    <span>-2 sampai +2</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning me-2" style="width: 60px;">Perhatian</span>
                                    <span>-3 sampai -2 atau +2 sampai +3</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-danger me-2" style="width: 60px;">Bahaya</span>
                                    <span>< -3 atau > +3</span>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6 class="mb-2"><strong>Kapan Harus ke Dokter?</strong></h6>
                        <ul class="small mb-0">
                            <li>Z-Score TB/U < -2 (anak pendek/stunting)</li>
                            <li>Z-Score BB/U < -2 (gizi kurang)</li>
                            <li>Z-Score BB/U > +2 (gizi lebih/obesitas)</li>
                            <li>Penurunan Z-Score yang signifikan dalam 2-3 bulan terakhir</li>
                        </ul>
                    </div>
                    
                    <div class="mt-2">
                        <small class="text-muted">{{__('monitoring.disclaimer')}}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.chart('graph', {
            chart: {
                type: 'gauge',
                plotBackgroundColor: null,
                plotBackgroundImage: null,
                plotBorderWidth: 0,
                plotShadow: false,
                height: '40%'
            },
            title: {
                text: '{{__('monitoring.diagnosis')}} TB/U'
            },
            credits: {
                enabled: false
            },
            pane: {
                startAngle: -90,
                endAngle: 89.9,
                background: null,
                center: ['50%', '75%'],
                size: '110%'
            },
            yAxis: {
                min: -4,
                max: 4,
                tickPixelInterval: 75,
                tickPosition: 'inside',
                tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
                tickLength: 20,
                tickWidth: 0,
                minorTickInterval: null,
                labels: {
                    distance: 30,
                    style: {
                        fontSize: '14px'
                    },
                    formatter: function() {
                        return '';
                    }
                },
                lineWidth: 0,
                plotBands: [{
                    from: -4,
                    to: -3,
                    color: '#DF5353',
                    thickness: 20,
                    label: {
                        text: '{{__('monitoring.short')}}',
                        style: {
                            color: 'black',
                            fontWeight: '500',
                            fontSize: '16px'
                        },
                        align: 'right',
                        x: -10
                    }
                }, {
                    from: -3,
                    to: -2,
                    color: '#D89A1E',
                    thickness: 20,
                    label: {
                        text: '{{__('monitoring.medium')}}',
                        style: {
                            color: 'black',
                            fontWeight: '500',
                            fontSize: '16px'
                        },
                        align: 'right',
                        x: -10
                    }
                }, {
                    from: -2,
                    to: 2,
                    color: '#55BF3B',
                    thickness: 20,
                    label: {
                        text: '{{__('monitoring.normal')}}',
                        style: {
                            color: 'black',
                            fontWeight: '500',
                            fontSize: '16px'
                        },
                        align: 'right',
                        y: -10,
                        x: 40
                    }
                }, {
                    from: 2,
                    to: 3,
                    color: '#D89A1E',
                    thickness: 20,
                    label: {
                        text: '{{__('monitoring.above_normal')}}',
                        style: {
                            color: 'black',
                            fontWeight: '500',
                            fontSize: '16px'
                        }
                    }
                }, {
                    from: 3,
                    to: 4,
                    color: '#DF5353',
                    thickness: 20,
                    label: {
                        text: '{{__('monitoring.tall')}}',
                        style: {
                            color: 'black',
                            fontWeight: '500',
                            fontSize: '16px'
                        }
                    }
                }]
            },
            series: [{
                name: 'Z-Score TB/U',
                data: [parseFloat('<?= $history0->zscore ?? 0 ?>')],
                tooltip: {
                    valueSuffix: '',
                    pointFormat: '<b>Z-Score: {point.y:.2f}</b><br/>' +
                                'Status: <b><?= $history0->hasil_diagnosa ?? "N/A" ?></b><br/>' +
                                '<span style="font-size: 10px">Nilai menunjukkan standar deviasi dari rata-rata</span>'
                },
                dataLabels: {
                    format: '<b>{y:.2f}</b>',
                    borderWidth: 0,
                    color: (
                        Highcharts.defaultOptions.title &&
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || '#333333',
                    style: {
                        fontSize: '20px',
                        fontWeight: 'bold'
                    }
                },
                dial: {
                    radius: '80%',
                    backgroundColor: 'black',
                    baseWidth: 12,
                    baseLength: '0%',
                    rearLength: '0%'
                },
                pivot: {
                    backgroundColor: 'black',
                    radius: 6
                }
            }]
        });
    </script>

    <script>
        Highcharts.chart('graph2', {
            chart: {
                type: 'gauge',
                plotBackgroundColor: null,
                plotBackgroundImage: null,
                plotBorderWidth: 0,
                plotShadow: false,
                height: '40%'
            },
            title: {
                text: '{{__('monitoring.diagnosis')}} BB/U'
            },
            credits: {
                enabled: false
            },
            pane: {
                startAngle: -90,
                endAngle: 89.9,
                background: null,
                center: ['50%', '75%'],
                size: '110%'
            },
            yAxis: {
                min: -4,
                max: 4,
                tickPixelInterval: 75,
                tickPosition: 'inside',
                tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
                tickLength: 20,
                tickWidth: 0,
                minorTickInterval: null,
                labels: {
                    distance: 30,
                    style: {
                        fontSize: '14px'
                    },
                    formatter: function() {
                        return '';
                    }
                },
                lineWidth: 0,
                plotBands: [{
                    from: -4,
                    to: -2,
                    color: '#DF5353',
                    thickness: 20,
                    label: {
                        text: 'Gizi Kurang',
                        style: {
                            color: 'black',
                            fontWeight: '500',
                            fontSize: '14px'
                        },
                        align: 'right',
                        x: -10
                    }
                }, {
                    from: -2,
                    to: 1,
                    color: '#55BF3B',
                    thickness: 20,
                    label: {
                        text: 'Gizi Normal',
                        style: {
                            color: 'black',
                            fontWeight: '500',
                            fontSize: '16px'
                        },
                        align: 'right',
                        x: -10
                    }
                }, {
                    from: 1,
                    to: 2,
                    color: '#f2df13',
                    thickness: 20,
                    label: {
                        text: '{{__('monitoring.risk_highly_nutrition')}}',
                        style: {
                            color: 'black',
                            fontWeight: '500',
                            fontSize: '16px'
                        }
                    }
                }, {
                    from: 2,
                    to: 3,
                    color: '#D89A1E',
                    thickness: 20,
                    label: {
                        text: '{{__('monitoring.highly_nutrition')}}',
                        style: {
                            color: 'black',
                            fontWeight: '500',
                            fontSize: '16px'
                        }
                    }
                }, {
                    from: 3,
                    to: 4,
                    color: '#DF5353',
                    thickness: 20,
                    label: {
                        text: '{{__('monitoring.obesity')}}',
                        style: {
                            color: 'black',
                            fontWeight: '500',
                            fontSize: '16px'
                        }
                    }
                }]
            },
            series: [{
                name: 'Z-Score BB/U',
                data: [parseFloat('<?= $history1->zscore ?? 0 ?>')],
                tooltip: {
                    valueSuffix: '',
                    pointFormat: '<b>Z-Score: {point.y:.2f}</b><br/>' +
                                'Status: <b><?= $history1->hasil_diagnosa ?? "N/A" ?></b><br/>' +
                                '<span style="font-size: 10px">Nilai menunjukkan standar deviasi dari rata-rata</span>'
                },
                dataLabels: {
                    format: '<b>{y:.2f}</b>',
                    borderWidth: 0,
                    color: (
                        Highcharts.defaultOptions.title &&
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || '#333333',
                    style: {
                        fontSize: '20px',
                        fontWeight: 'bold'
                    }
                },
                dial: {
                    radius: '80%',
                    backgroundColor: 'black',
                    baseWidth: 12,
                    baseLength: '0%',
                    rearLength: '0%'
                },
                pivot: {
                    backgroundColor: 'black',
                    radius: 6
                }
            }]
        });
    </script>
@endpush
