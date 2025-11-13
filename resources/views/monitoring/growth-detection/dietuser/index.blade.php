@extends('layouts.app')

@push('title')
    Diet - Growth Detection & Risk Prediction
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">Diet (@t('Dewasa')/@t('Umum'))</span>
@endsection

@section('content')
    <div class="osahan-payment">
        <div class="payment p-3">
            <div class="accordion" id="accordionExample">
                <div class="osahan-card rounded shadow-sm bg-white mb-3">
                    <div class="osahan-card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="d-flex p-3 align-items-center border-0 w-100" type="button" aria-expanded="true"
                                aria-controls="collapseOne">
                                <i class="icofont-edit me-3"></i> @t('Form Diet User')
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                        data-bs-parent="#accordionExample">
                        <div class="osahan-card-body p-3 border-top">
                            <form action="{{ locale_route('growth-detection.diet-user.store') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Nama')</label>
                                        <div class="input-group">
                                            <input placeholder="@t('Contoh'): John Doe" type="text" class="form-control @error('nama') is-invalid @enderror" name="nama"
                                                id="nama">
                                        </div>
                                        @error('nama')
                                        <small class="text-danger" style="font-size: 12px">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Usia')</label>
                                        <div class="input-group">
                                            <input placeholder="@t('Contoh'): 20" type="number" class="form-control" name="usia"
                                                id="usia">
                                        </div>
                                    </div>
                                    <div class="col-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Jenis Kelamin')</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                            <option value="L">@t('Laki-laki')</option>
                                            <option value="P">@t('Perempuan')</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Berat Badan') (kg)</label>
                                        <input class="form-control" type="number" placeholder="@t('Contoh'): 60" step="0.1" name="berat_badan"
                                            id="berat_badan">
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Tinggi Badan') (cm)</label>
                                        <input class="form-control" type="number" placeholder="@t('Contoh'): 160" step="0.1" name="tinggi_badan"
                                            id="tinggi_badan">
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Frekuensi Sayur') (1-5)</label>
                                        <input class="form-control" type="number" placeholder="@t('Contoh'): 2" name="frekuensi_sayur" min="1"
                                            max="5" id="frekuensi_sayur">
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Konsumsi Protein') (1-5)</label>
                                        <input class="form-control" type="number" placeholder="@t('Contoh'): 3" name="konsumsi_protein" min="1"
                                            max="5" id="konsumsi_protein">
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Konsumsi Karbohidrat') (1-5)</label>
                                        <input type="number" placeholder="@t('Contoh'): 1" name="konsumsi_karbo" min="1" max="5"
                                            id="konsumsi_karbo" class="form-control">
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Konsumsi Gula') (1-5)</label>
                                        <input type="number" placeholder="@t('Contoh'): 5" name="konsumsi_gula" min="1" max="5"
                                            id="konsumsi_gula" class="form-control">
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <input class="form-check-input" type="checkbox" name="vegetarian" id="vegetarian"
                                            value="1">
                                        <label class="form-check-label form-label fw-bold small" for="vegetarian">
                                            @t('Vegetarian') ?
                                        </label>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Frekuensi Jajan Luar')</label>
                                        <input type="number" placeholder="@t('Contoh'): 60" name="frekuensi_jajan" min="0" id="frekuensi_jajan"
                                            class="form-control">
                                    </div>
                                    <div class="col-12 form-group mb-3">
                                        <label class="form-label fw-bold small">@t('Target')</label>
                                        <select name="target" id="target" class="form-control">
                                            <option value="">--@t('Pilih')--</option>
                                            <option value="menurunkan_bb">@t('Menurunkan BB')</option>
                                            <option value="kontrol_gula">@t('Kontrol Gula Darah')</option>
                                            <option value="meningkatkan_otot">@t('Meningkatkan Massa Otot')</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success btn-lg rounded w-100">@t('Submit')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
