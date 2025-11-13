@extends('layouts.app')

@push('title')
    Diet - Growth Detection & Risk Prediction
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('growth-detection.diet-user.list') }}"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">Diet (@t('Dewasa')/@t('Umum'))</span>
@endsection

@section('content')
    <div class="osahan-success bg-success vh-100">
        <div class="p-5 text-center">
            <i class="icofont-check-circled display-1 text-warning"></i>
            <h3 class="text-white fw-bold">@t('Hasil Analisis Diet User') 🎉</h3>
        </div>
        <div class="bg-white rounded p-3 m-2">
            {{-- <h6 class="fw-bold mb-2">@t('Preparing your order')</h6> --}}
            <table style="width: 100%" class="table table-striped">
                <tr>
                    <th>@t('Nama')</th>
                    <td>:</td>
                    <td>{{ $dietUser->nama }}</td>
                </tr>
                <tr>
                    <th>@t('Usia')</th>
                    <td>:</td>
                    <td>{{ $dietUser->usia }}</td>
                </tr>
                <tr>
                    <th>@t('Jenis Kelamin')</th>
                    <td>:</td>
                    <td>{{ $dietUser->jenis_kelamin == 'L' ? __('general.laki_laki') : __('general.perempuan') }}</td>
                </tr>
                <tr>
                    <th>@t('BMI')</th>
                    <td>:</td>
                    <td>{{ $dietUser->bmi }}</td>
                </tr>
                <tr>
                    <th>@t('Status Gizi')</th>
                    <td>:</td>
                    <td>{{ $dietUser->status_gizi }}</td>
                </tr>
                <tr>
                    <th>@t('Rekomendasi')</th>
                    <td>:</td>
                    <td>{{ $dietUser->rekomendasi }}</td>
                </tr>
            </table>
        </div>
    </div>
    <!-- continue -->
@endsection
