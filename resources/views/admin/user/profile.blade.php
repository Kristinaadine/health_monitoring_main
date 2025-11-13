@extends('layouts-admin.master')
@push('title')
    Dashboard
@endpush
@section('content')
    <div class="container main-container" id="main-container">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header p-0 overflow-hidden">
                <div class="row no-gutters align-items-center position-relative gradient-pink">
                    <figure class="background opac">
                        <img src="{{ asset('') }}assets-admin/assets/img/background-part.png" alt=""
                            class="">
                    </figure>
                    <div class="container p-4">
                        <div class="row align-items-center ">
                            <div class="col-12 col-sm-auto text-center">
                                <a href="profile.html">
                                    <figure class="avatar avatar-150 rounded-circle mx-auto my-3">
                                        <img src="{{ asset('') }}assets/img/user.png" alt="">
                                    </figure>
                                </a>
                            </div>
                            <div class="col-12 col-sm text-center text-sm-left text-white">
                                <h3 class="mb-0">{{ auth()->user()->name }}</h3>
                                <p class="">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id" id="id" value="{{ encrypt(auth()->user()->id) }}">
            <div class="card-body">
                <div class="row justify-content-center">
                    <form id="formEdit" action="#" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-6 col-md-6">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="" name="name" id="name"
                                    value="{{ auth()->user()->name }}">
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <label>email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="" name="email" id="email"
                                    value="{{ auth()->user()->email }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-12 col-md-12">
                                <label>Phone</label>
                                <input type="text" class="form-control" placeholder="" name="phone" id="phone"
                                    value="{{ auth()->user()->phone }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6 col-md-6">
                                <label>Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" placeholder="" name="password" id="password">
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <label>Confirmation Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" placeholder="" name="password2" id="password2">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary float-right" id="btnUpdate">Save changes</button>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $('#btnUpdate').on('click', function() {
            const fd = $('#formEdit').serializeArray();
            fd.push({
                name: 'roles',
                value: 'Admin'
            });
            var id = $('#id').val();
            var url = "{{ locale_route('administration.user.update', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                type: "PUT",
                data: fd,
                dataType: "json",
                beforeSend: function() {
                    $('#btnUpdate').attr('disabled', true);
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
                    if (xhr.status == 422) {
                        var errors = xhr.responseJSON.errors
                        $.map(errors, function(val, index) {
                            $.notify(val, "error");
                        });
                    }
                },
                complete: function() {
                    $('#btnUpdate').attr('disabled', false);
                }
            });
        });
    </script>
@endpush
