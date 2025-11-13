@extends('layouts.app')

@push('title')
    Calorie Calculator - History
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">@t('Daily Calorie Calculator')</span>
@endsection

@section('content')
    <div class="p-3">
        @if($histories->isEmpty())
            {{-- Empty State --}}
            <div class="text-center py-5">
                <div class="mb-4">
                    <img src="{{ asset('assets/img/categorie/6.svg') }}" alt="No Data" style="width: 120px; opacity: 0.5;">
                </div>
                <h5 class="text-muted mb-3">📊 Belum Ada Data</h5>
                <p class="text-muted mb-4">Anda belum pernah menghitung kebutuhan kalori harian.<br>Mulai hitung sekarang!</p>
                <a href="{{ locale_route('caloric.create') }}" class="btn btn-success btn-lg rounded">
                    <i class="icofont-plus-circle"></i> Tambah Data
                </a>
            </div>
        @else
            {{-- Header with Add Button --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="m-0">📋 Riwayat Perhitungan</h6>
                <a href="{{ locale_route('caloric.create') }}" class="btn btn-success btn-sm">
                    <i class="icofont-plus"></i> Tambah
                </a>
            </div>

            {{-- History List --}}
            @foreach($histories as $history)
                <div class="card mb-3 shadow-sm" id="history-{{ $history->id }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <small class="text-muted">
                                    <i class="icofont-calendar"></i> 
                                    {{ \Carbon\Carbon::parse($history->created_at)->locale('id')->translatedFormat('d F Y, H:i') }}
                                </small>
                            </div>
                            <button class="btn btn-sm btn-danger btn-delete" 
                                    data-id="{{ $history->id }}"
                                    data-date="{{ \Carbon\Carbon::parse($history->created_at)->locale('id')->translatedFormat('d F Y') }}">
                                <i class="icofont-trash"></i>
                            </button>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <small class="text-muted">Usia</small>
                                <div class="fw-bold">{{ $history->age }} tahun</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Jenis Kelamin</small>
                                <div class="fw-bold">{{ $history->sex == 'male' ? '👨 Laki-laki' : '👩 Perempuan' }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Tinggi</small>
                                <div class="fw-bold">{{ $history->height }} cm</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Berat</small>
                                <div class="fw-bold">{{ $history->weight }} kg</div>
                            </div>
                        </div>

                        <div class="alert alert-info mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">🔥 Kebutuhan Kalori Harian</span>
                                <span class="badge bg-primary" style="font-size: 18px;">{{ $history->daily_calories }} kcal</span>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <small class="text-muted d-block">Karbohidrat</small>
                                    <strong class="text-success">{{ $history->carbs }}g</strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <small class="text-muted d-block">Protein</small>
                                    <strong class="text-primary">{{ $history->protein }}g</strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <small class="text-muted d-block">Lemak</small>
                                    <strong class="text-warning">{{ $history->fat }}g</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Handle Delete
    $('.btn-delete').on('click', function() {
        const id = $(this).data('id');
        const date = $(this).data('date');
        
        Swal.fire({
            title: 'Hapus Data?',
            html: `Apakah Anda yakin ingin menghapus data perhitungan pada <strong>${date}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="icofont-trash"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/{{ app()->getLocale() }}/caloric/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: 'Data berhasil dihapus',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            $('#history-' + id).fadeOut(300, function() {
                                $(this).remove();
                                
                                // Check if no more history
                                if ($('.card').length === 0) {
                                    location.reload();
                                }
                            });
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus data'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
