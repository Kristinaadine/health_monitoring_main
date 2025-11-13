@extends('layouts.app')

@push('title')
    Log Makanan Harian - Nutrition & Health Monitoring and Progress
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none"
        href="{{ locale_route('nutrition-monitoring.children.show', encrypt($child->id)) }}"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">@t('Log Makanan Harian')</span>
    {{-- <a class="toggle ms-auto" href="#"><i class="icofont-navigation-menu"></i></a> --}}
@endsection

@section('content')
    <div class="address p-3 bg-white">
        <h6 class="m-0 text-dark d-flex align-items-center">🍱 @t('Log Makanan Harian') - {{ $child->nama }} <span
                class="small ms-auto"><a href="{{ locale_route('nutrition-monitoring.children.food.create', encrypt($child->id)) }}"
                    class="fw-bold text-decoration-none text-success"><i class="icofont-plus"></i>
                    @t('Tambah Log')</a></span></h6>
    </div>
    <div class="p-3">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>@t('Tanggal')</th>
                    <th>@t('Nama Makanan')</th>
                    <th>@t('Porsi')</th>
                    <th>@t('Kalori')</th>
                    <th>@t('Protein')</th>
                    <th>@t('Lemak')</th>
                    <th>@t('Foto')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($foods as $food)
                    <tr>
                        <td>{{ $food->tanggal }}</td>
                        <td>{{ $food->nama_makanan }}</td>
                        <td>{{ $food->porsi }}</td>
                        <td>{{ $food->kalori }} kcal</td>
                        <td>{{ $food->protein }} g</td>
                        <td>{{ $food->lemak }} g</td>
                        <td>
                            @if ($food->foto)
                                <img src="{{ asset('storage/' . $food->foto) }}" alt="Foto Makanan" class="img-thumbnail"
                                    style="width:70px; cursor:pointer" data-bs-toggle="modal" data-bs-target="#imageModal"
                                    data-img="{{ asset('storage/' . $food->foto) }}">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="popupImage" src="" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const imageModal = document.getElementById('imageModal');
        imageModal.addEventListener('show.bs.modal', function(event) {
            let triggerImg = event.relatedTarget;
            let imgSrc = triggerImg.getAttribute('data-img');
            let popupImg = document.getElementById('popupImage');
            popupImg.src = imgSrc;
        });
    </script>
@endpush
