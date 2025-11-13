@extends('layouts.app')

@push('title')
    Pre-Stunting - Risk Detection
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('growth-detection.index') }}"><i class="icofont-rounded-left back-page"></i></a>
    <span class="fw-bold ms-3 h6 mb-0">@t('Pre Stunting Risk Detection')</span>
    <a href="{{ locale_route('growth-detection.pre-stunting.create') }}" class="btn btn-outline-success btn-sm ms-auto">@t('Tambah')</a>
@endsection

@section('content')
    @php($data = $data ?? collect())
    <div class="p-3">
        <div class="mb-4">
            <div class="card">
                <div class="card-body">
                <canvas id="statusChart" style="width: 300px; height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ locale_route('growth-detection.pre-stunting.index') }}" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="nama" class="form-control" placeholder="@t('Search by Name')" value="{{ request('nama') }}">
                        <button class="btn btn-outline-secondary" type="submit">@t('Search')</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th class="">@t('No')</th>
                                <th>@t('Nama')</th>
                                <th>@t('Usia') (@t('th'))</th>
                                <th class="w-full">@t('Level Risiko')</th>
                                <th>@t('Tanggal Analisis')</th>
                                <th>@t('Aksi')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                    <td>{{ $item->nama ?? '-' }}</td>
                                    <td>{{ $item->usia ?? '-' }}</td>
                                    <td>
                                        <span class="px-2 py-2 rounded text-white
                                        @if ($item->level_risiko === 'Risiko rendah') bg-success
                                        @elseif($item->level_risiko === 'Risiko sedang') bg-warning
                                        @else bg-red @endif d-block w-full">
                                            <!-- {{ $item->level_risiko }} -->
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td class="d-flex gap-1">
                                        <a href="{{ locale_route('growth-detection.pre-stunting.result', urlencode(encrypt($item->id))) }}" class="btn btn-primary btn-sm">
                                            <i class="icofont-eye"></i> @t('Detail')
                                        </a>
                                        <a href="{{ locale_route('growth-detection.pre-stunting.edit', urlencode(encrypt($item->id))) }}" class="btn btn-warning btn-sm">
                                            <i class="icofont-edit"></i> @t('Edit')
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                            <i class="icofont-trash"></i> @t('Delete')
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">@t('Belum ada data analisis pre-stunting')</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} of {{ $data->total() }} @t('results')
                        </div>
                        <div class="fs-6">
                            {{ $data->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($data as $item)
    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true" style="height:30%;top:30%;width:80%;left:10%;border-radius:10px;">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content" style="height:auto;">
          <div class="modal-header">
            <h5 class="modal-title">@t('Konfirmasi Hapus')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @t('Apakah Anda yakin ingin menghapus data') <strong>{{ $item->nama ?? 'ini' }}</strong>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@t('Batal')</button>
            <form action="{{ locale_route('growth-detection.pre-stunting.destroy', urlencode(encrypt($item->id))) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">@t('Hapus')</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endforeach
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        const labels = [
            @json(__('general.risiko_rendah')),
            @json(__('general.risiko_sedang')),
            @json(__('general.risiko_tinggi'))
        ];

        const rawData = [{{ $low }}, {{ $mid }}, {{ $high }}];
        const colors = ['#22c55e', '#facc15', '#ef4444'];

        const ctx = document.getElementById('statusChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: @json(__('general.jumlah_ibu')),
                    data: rawData,
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        color: '#000',
                        font: { weight: 'bold', size: 12 },
                        anchor: 'end',
                        align: 'end',
                        formatter: (value) => value
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        title: { display: true, text: @json(__('general.jumlah_ibu')) } 
                    },
                    x: { 
                        title: { display: true, text: @json(__('general.level_risiko')) } 
                    }
                },
                maintainAspectRatio: false
            },
            plugins: [ChartDataLabels]
        });
    </script>
@endpush

{{-- Note: Please ensure the controller uses paginate(5) to limit the data per page. --}}
