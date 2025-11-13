@extends('layouts.app')

@push('title')
    Diet - Growth Detection & Risk Prediction
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('growth-detection.index') }}"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">Diet (@t('Dewasa')/@t('Umum'))</span>
    <a href="{{ locale_route('growth-detection.diet-user') }}" class="btn btn-outline-success btn-sm ms-auto">{{__('general.tambah')}}</a>
@endsection

@section('content')
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
                                <p class="mb-0 h5">{{ $item->nama }} - {{ $item->usia }} @t('tahun')</p>
                            </div>
                            <hr>
                            @t('Status Gizi')
                            <div class="d-flex align-items-center mb-4">
                                <p class="mb-0 badge badge-primary">
                                    {{ $item->status_gizi }}
                                </p>
                            </div>
                            @t('Rekomendasi')
                            <div class="d-flex align-items-center mb-2">
                                <p class="mb-0 badge badge-warning">
                                    {{ $item->rekomendasi }}
                                </p>
                            </div>
                            <p class="pt-2 m-0 text-end">
                                <span class="small">
                                    <a href="{{ locale_route('growth-detection.diet-user.show', $item->id) }}"
                                        class="text-decoration-none text-primary"><i class="icofont-eye"></i> @t('Show')
                                    </a>
                                </span>
                            </p>
                        </div>
                    </div>
                </label>
            </div>
        @endforeach
    </div>
@endsection
