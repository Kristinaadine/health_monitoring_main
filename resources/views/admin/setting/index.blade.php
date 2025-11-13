@extends('layouts-admin.master')
@push('title')
    Setting
@endpush

@push('css')
    <style>
        .table tbody tr td {
            color: #090909;
        }

        .form-group label {
            color: #090909;
        }
    </style>
@endpush

@section('header')
    <div class="row submenu">
        <div class="container-fluid " id="submenu-container">
            <div class="row py-2">
                <!-- Submenu section starts -->
                <div class="col">
                    <h6 class="font-weight-bold mt-1">Setting<br><small class="text-mute">Setting your website</small></h6>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container main-container" id="main-container">
        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div class="position-relative h-150 text-center rounded py-5">
                <div class="background ">
                    <img src="{{ asset('') }}assets-admin/assets/img/background-part.png" alt="">
                </div>
            </div>
            <div class="card-body px-0">
                <div class="text-center mb-3 top-60 z-2">
                    <figure class="avatar avatar-120 mx-auto shadow"><img
                            src="{{ asset('') }}assets/img/logo/{{ $setting->where('key', 'website_logo')->first()->value }}"
                            alt=""></figure>
                </div>
                <h5 class="text-center mb-3">{{ $setting->where('key', 'website_name')->first()->value }}</h5>
                <ul class="list-group">
                    <li class="list-group-item border-top" href="#">
                        <div class="row">
                            <div class="col-auto align-self-center">
                                <i class="material-icons">language</i>
                            </div>
                            <div class="col pl-0">
                                <p class="mb-0">Website Name</p>
                                <p class="small text-mute text-trucated">Your Website Name</p>
                            </div>
                            <div class="col-auto align-self-center text-right pl-0">
                                <button type="button" class="btn btn-secondary" data-toggle="modal"
                                    data-target="#modalName">Change</button>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item border-top" href="#">
                        <div class="row">
                            <div class="col-auto align-self-center">
                                <i class="material-icons">image</i>
                            </div>
                            <div class="col pl-0">
                                <p class="mb-0">Website Logo</p>
                                <p class="small text-mute text-trucated">Your Website Logo</p>
                            </div>
                            <div class="col-auto align-self-center text-right pl-0">
                                <button type="button" class="btn btn-secondary" data-toggle="modal"
                                    data-target="#modalLogo">Change</button>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item border-top" href="#">
                        <div class="row">
                            <div class="col-auto align-self-center">
                                <i class="material-icons">settings</i>
                            </div>
                            <div class="col pl-0">
                                <p class="mb-0">Maintenance</p>
                                <p class="small text-mute text-trucated">Your Website under construction?</p>
                            </div>
                            <div class="col-auto align-self-center text-right pl-0">
                                <form id="formMaintenance" action="#" method="PUT">
                                    @csrf
                                    <label class="switch-wrap switch-success ml-2">
                                        <input type="checkbox" name="maintenance_mode" id="maintenance_mode"
                                            {{ $setting->where('key', 'maintenance_mode')->first()->value == 'true' ? 'checked' : '' }}>
                                        <div class="switch"></div>
                                    </label><br>
                                    <button type="button" class="btn btn-primary" id="btnMaintenance"
                                        style="display: none">Save Change</button>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @include('admin.setting.modal-name')
    @include('admin.setting.modal-logo')
@endsection

@push('js')
    <script>
        $("#maintenance_mode").change(function(e) {
            e.preventDefault();
            $("#btnMaintenance").show();
        });

        $('#btnMaintenance').on('click', function() {
            const fd = $('#formMaintenance').serializeArray();
            fd.push({
                name: 'maintenance_mode',
                value: $('#maintenance_mode').is(':checked') ? 'true' : 'false'
            });
            $.ajax({
                url: "{{ locale_route('administration.setting.update') }}",
                type: "PUT",
                data: fd,
                dataType: "json",
                beforeSend: function() {
                    $('#btnMaintenance').attr('disabled', true);
                },
                success: function(response) {
                    if (response.status == 'success') {
                        $.notify(response.message, "success");
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        $.notify(response.message, "error");
                    }
                },
                error: function(xhr) {
                    $.notify("An error occurred", "error");
                },
                complete: function() {
                    $('#btnMaintenances').attr('disabled', false);
                }
            });
        });
    </script>
@endpush
