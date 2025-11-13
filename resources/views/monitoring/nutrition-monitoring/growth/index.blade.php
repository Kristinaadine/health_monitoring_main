@extends('layouts.app')

@push('title')
    Log Pertumbuhan - Nutrition & Health Monitoring and Progress
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('nutrition-monitoring.children.show', encrypt($child->id)) }}"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">@t('Log Pertumbuhan')</span>
    {{-- <a class="toggle ms-auto" href="#"><i class="icofont-navigation-menu"></i></a> --}}
@endsection

@section('content')
    <div class="address p-3 bg-white">
        <h6 class="m-0 text-dark d-flex align-items-center">✨ @t('Log Pertumbuhan') - {{ $child->nama }} <span
                class="small ms-auto"><a
                    href="{{ locale_route('nutrition-monitoring.children.growth.create', encrypt($child->id)) }}"
                    class="fw-bold text-decoration-none text-success"><i class="icofont-plus"></i>
                    @t('Tambah Log')</a></span></h6>
    </div>
    <div class="p-3">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>@t('Tanggal')</th>
                    <th>@t('Berat')</th>
                    <th>@t('Tinggi')</th>
                    <th style="width: 150px;">@t('Aksi')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($growths as $g)
                    <tr id="row-{{ $g->id }}">
                        <td>{{ \Carbon\Carbon::parse($g->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>{{ $g->berat }} kg</td>
                        <td>{{ $g->tinggi }} cm</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit" 
                                    data-id="{{ $g->id }}"
                                    data-tanggal="{{ $g->tanggal }}"
                                    data-berat="{{ $g->berat }}"
                                    data-tinggi="{{ $g->tinggi }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEdit">
                                <i class="icofont-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" 
                                    data-id="{{ $g->id }}"
                                    data-nama="{{ $child->nama }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($g->tanggal)->locale('id')->translatedFormat('d F Y') }}">
                                <i class="icofont-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted">
                            <i class="icofont-info-circle"></i> Belum ada data pertumbuhan. 
                            <a href="{{ locale_route('nutrition-monitoring.children.growth.create', encrypt($child->id)) }}">
                                Tambah data pertama
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection


{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">✏️ Edit Log Pertumbuhan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEdit">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-id">
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="edit-tanggal" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Berat Badan (kg)</label>
                        <input type="number" step="0.1" class="form-control" id="edit-berat" min="2" max="50" required>
                        <small class="text-muted">Range: 2-50 kg</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" class="form-control" id="edit-tinggi" min="40" max="130" required>
                        <small class="text-muted">Range: 40-130 cm</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnSaveEdit">
                    <i class="icofont-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Handle Edit Button
    $('.btn-edit').on('click', function() {
        const id = $(this).data('id');
        const tanggal = $(this).data('tanggal');
        const berat = $(this).data('berat');
        const tinggi = $(this).data('tinggi');
        
        $('#edit-id').val(id);
        $('#edit-tanggal').val(tanggal);
        $('#edit-berat').val(berat);
        $('#edit-tinggi').val(tinggi);
    });
    
    // Handle Save Edit
    $('#btnSaveEdit').on('click', function() {
        const id = $('#edit-id').val();
        const tanggal = $('#edit-tanggal').val();
        const berat = $('#edit-berat').val();
        const tinggi = $('#edit-tinggi').val();
        
        // Validation
        if (!tanggal || !berat || !tinggi) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Semua field wajib diisi!'
            });
            return;
        }
        
        if (berat < 2 || berat > 50) {
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Valid',
                text: 'Berat badan harus antara 2-50 kg'
            });
            return;
        }
        
        if (tinggi < 40 || tinggi > 130) {
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Valid',
                text: 'Tinggi badan harus antara 40-130 cm'
            });
            return;
        }
        
        // AJAX Update
        $.ajax({
            url: `/{{ app()->getLocale() }}/nutrition-monitoring/children/{{ encrypt($child->id) }}/growth/${id}`,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                tanggal: tanggal,
                berat: berat,
                tinggi: tinggi
            },
            beforeSend: function() {
                $('#btnSaveEdit').prop('disabled', true).html('<i class="icofont-spinner-alt-2 icofont-spin"></i> Menyimpan...');
            },
            success: function(response) {
                $('#modalEdit').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil diupdate',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMsg
                });
            },
            complete: function() {
                $('#btnSaveEdit').prop('disabled', false).html('<i class="icofont-save"></i> Simpan Perubahan');
            }
        });
    });
    
    // Handle Delete Button
    $('.btn-delete').on('click', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const tanggal = $(this).data('tanggal');
        
        Swal.fire({
            title: 'Hapus Data?',
            html: `Apakah Anda yakin ingin menghapus data pertumbuhan <strong>${nama}</strong> pada tanggal <strong>${tanggal}</strong>?<br><br><small class="text-danger">Data yang dihapus tidak dapat dikembalikan!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="icofont-trash"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX Delete
                $.ajax({
                    url: `/{{ app()->getLocale() }}/nutrition-monitoring/children/{{ encrypt($child->id) }}/growth/${id}`,
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
                            $('#row-' + id).fadeOut(300, function() {
                                $(this).remove();
                                
                                // Check if table is empty
                                if ($('tbody tr').length === 0) {
                                    location.reload();
                                }
                            });
                        });
                    },
                    error: function(xhr) {
                        let errorMsg = 'Terjadi kesalahan saat menghapus data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMsg
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
