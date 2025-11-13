@extends('layouts.app')

@push('title')
    Stunting - Growth Detection & Risk Prediction
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('growth-detection.index') }}"><i
            class="icofont-rounded-left back-page"></i></a>
    <span class="fw-bold ms-3 h6 mb-0"></span>
    <a href="{{ locale_route('growth-detection.stunting.create') }}" class="btn btn-outline-success btn-sm ms-auto">@t('Tambah')</a>
@endsection

@section('content')
    <div class="p-3">
        <div class="mb-4">
            <canvas id="statusChart" style="width: 300px; height: 300px;"></canvas>
        </div>
        @foreach ($data as $item)
            {{-- @dd($item->history[0]) --}}
            <div class="form-check px-0 mb-3 position-relative border-custom-radio">
                <input type="radio" id="customRadioInline1-{{ $item->id }}" name="customRadioInline1"
                    class="form-check-input">
                <label class="form-check-label w-100" for="customRadioInline1-{{ $item->id }}">
                    <div>
                        <div class="p-3 bg-white rounded shadow-sm w-100">
                            <div class="d-flex align-items-center mb-2">
                                <p class="mb-0 h5">{{ $item->nama }} - {{ $item->usia }} @t('bulan')</p>
                            </div>
                            <hr>
                            <div class="d-flex align-items-center mb-2">
                                <p class="mb-0 h6">Status Pertumbuhan : <span
                                        class="px-2 py-1 rounded text-white
                                    @if ($item->status_pertumbuhan === 'Normal') bg-success
                                    @elseif(in_array($item->status_pertumbuhan, ['Stunted', 'Wasting'])) bg-warning
                                    @else bg-danger @endif">
                                        {{ $item->status_pertumbuhan }}
                                    </span>
                                </p>
                                <p class="mb-0 h6 ms-auto">Level Risiko : <span
                                        class="px-2 py-1 rounded text-white
                                    @if ($item->level_risiko === 'Rendah') bg-success
                                    @elseif($item->level_risiko === 'Sedang') bg-warning
                                    @else bg-danger @endif">
                                        {{ $item->level_risiko }}
                                    </span>
                                </p>
                                <p class="mb-0 h6 ms-auto">Tanggal Analisis : {{ $item->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                            <p class="pt-2 m-0 text-end">
                                <span class="small">
                                    <a href="{{ locale_route('growth-detection.stunting.result', encrypt($item->id)) }}"
                                        class="text-decoration-none text-primary"><i class="icofont-eye"></i> Show
                                    </a>
                                </span>
                            </p>
                        </div>
                    </div>
                </label>
            </div>
        @endforeach
    </div>
    @php
        $normal = $data->where('status_pertumbuhan', 'Normal')->count();
        $stunted = $data->where('status_pertumbuhan', 'Stunted')->count();
        $wasting = $data->where('status_pertumbuhan', 'Wasting')->count();
        $sev_stunted = $data->where('status_pertumbuhan', 'Severely Stunted')->count();
        $sev_wasting = $data->where('status_pertumbuhan', 'Severe Wasting')->count();
    @endphp
@endsection


@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        const labels = ['Normal', 'Stunted', 'Wasting', 'Severely Stunted', 'Severe Wasting'];
        const rawData = [
            {{ $data->where('status_pertumbuhan','Normal')->count() }},
            {{ $data->where('status_pertumbuhan','Stunted')->count() }},
            {{ $data->where('status_pertumbuhan','Wasting')->count() }},
            {{ $data->where('status_pertumbuhan','Severely Stunted')->count() }},
            {{ $data->where('status_pertumbuhan','Severe Wasting')->count() }}
        ];
        const colors = ['#22c55e', '#facc15', '#fd7e14', '#ef4444', '#d63384'];

        // 🔑 Filter: ambil hanya yang > 0
        const filteredLabels = labels.filter((_, i) => rawData[i] > 0);
        const filteredData = rawData.filter(v => v > 0);
        const filteredColors = colors.filter((_, i) => rawData[i] > 0);

        const ctx = document.getElementById('statusChart');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: filteredLabels,
                datasets: [{
                    data: filteredData,
                    backgroundColor: filteredColors
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    datalabels: {
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: (value, context) => {
                            let label = context.chart.data.labels[context.dataIndex];
                            return label + '\n' + value;
                        }
                    }
                },
                maintainAspectRatio: false
            },
            plugins: [ChartDataLabels]
        });
    </script>
@endpush
