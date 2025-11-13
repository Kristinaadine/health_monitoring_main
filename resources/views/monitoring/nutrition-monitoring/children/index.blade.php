@extends('layouts.app')

@push('title')
    Data Anak - Nutrition & Health Monitoring and Progress
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('nutrition-monitoring.index') }}"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">@t('Data Anak - Nutrition & Health Monitoring and Progress')</span>
    {{-- <a class="toggle ms-auto" href="#"><i class="icofont-navigation-menu"></i></a> --}}
@endsection

@section('content')
    <div class="address p-3 bg-white">
        <h6 class="m-0 text-dark d-flex align-items-center">👶 @t('Data Anak') <span class="small ms-auto"><a
                    href="{{ locale_route('nutrition-monitoring.children.create') }}"
                    class="fw-bold text-decoration-none text-success"><i class="icofont-plus"></i>
                    @t('Tambah Data Anak')</a></span></h6>
    </div>
    <div class="p-3">
        @if (count($children) > 0)

        @foreach ($children as $c)
            <div class="form-check px-0 mb-3 position-relative border-custom-radio">
                <input type="radio" id="customRadioInline1-{{ $c->id }}" name="customRadioInline1"
                    class="form-check-input">
                <label class="form-check-label w-100" for="customRadioInline1-{{ $c->id }}">
                    <div>
                        <div class="p-3 bg-white rounded shadow-sm w-100">
                            <div class="d-flex align-items-center mb-2">
                                <p class="mb-0 h5">{{ $c->nama }}</p>
                            </div>
                            <hr>
                            <div class="d-flex align-items-center mb-2">
                                <p class="mb-0 h6">@t('Jenis Kelamin') : <span
                                        class="px-2 py-1 rounded text-white
                                @if ($c->jenis_kelamin === 'L') bg-primary
                                @else bg-danger @endif">
                                        {{ $c->jenis_kelamin == 'L' ? __('general.laki_laki') : __('general.perempuan') }}
                                    </span>
                                </p>
                                <p class="mb-0 h6 ms-auto">@t('Tanggal Lahir') :
                                    {{ \Carbon\Carbon::parse($c->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            <p class="pt-2 m-0 text-end">
                                <span class="small">
                                    <a href="{{ locale_route('nutrition-monitoring.children.show', encrypt($c->id)) }}"
                                        class="text-decoration-none text-primary"><i class="icofont-eye"></i> @t('Detail')
                                    </a>
                                </span>
                                <span class="small mx-2">
                                    <a href="{{ locale_route('nutrition-monitoring.children.edit', encrypt($c->id)) }}"
                                        class="text-decoration-none text-warning"><i class="icofont-edit"></i> @t('Edit')
                                    </a>
                                </span>

                                <span class="small">
                                    <a href="#" data-id="{{ encrypt($c->id) }}"
                                        class="text-decoration-none text-danger deleteBtn"><i class="icofont-trash"></i> @t('Hapus')
                                    </a>
                                </span>
                            </p>
                        </div>
                    </div>
                </label>
            </div>
        @endforeach
        @else
            <div class="alert alert-info text-center">
                <i class="icofont-info-circle"></i> @t('Data Anak belum tersedia').
            </div>
        @endif
    </div>
@endsection

@push('js')
    <script>
        $(document).on('click', '.deleteBtn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            // Build URL manually to avoid locale_route placeholder issue
            var locale = '{{ app()->getLocale() }}';
            var url = '/' + locale + '/nutrition-monitoring/children/' + id;
            swal({
                    title: "@t('Apakah Kamu Yakin')?",
                    text: "@t('Ingin Menghapus Data ini')?",
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
