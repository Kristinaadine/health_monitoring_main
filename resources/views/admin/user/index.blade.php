@extends('layouts-admin.master')
@push('title')
   User
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
                    <h6 class="font-weight-bold mt-1">User<br><small class="text-mute">List all user</small></h6>
                </div>
                <div class="col-auto align-self-center"><button class="btn btn-success" data-toggle="modal"
                        data-target="#modalAdd">+ Add New</button></div>
                <!-- Submenu section ends -->
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container main-container" id="main-container">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body ">
                <table class="table datatable display responsive w-100" id="tableuser">
                    <thead>
                        <tr>
                            <th class="all">No</th>
                            <th class="min-tablet">Name</th>
                            <th class="min-desktop">Email</th>
                            <th class="">Phone</th>
                            <th class="min-desktop">Role</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{--  --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('admin.user.modal-add')
    @include('admin.user.modal-edit')
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            table()
        });

        function table() {
            var table = $('#tableuser').DataTable({
                processing: true,
                serverSide: true,
                "bDestroy": true,
                searching: true,
                ajax: "{{ locale_route('administration.user.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-dark text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-dark'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'text-dark'
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        className: 'text-dark'
                    },
                    {
                        data: 'roles',
                        name: 'roles',
                        className: 'text-dark text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            return table;
        }

        // SHOW EDIT DATA
        $(document).on('click', ".editBtn", function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            var url = "{{ locale_route('administration.user.edit', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        $('#id').val(response.id);
                        $('#Editname').val(response.data.name);
                        $('#Editemail').val(response.data.email);
                        $('#Editphone').val(response.data.phone);
                        $('#Editroles').val(response.data.roles);
                        $('#modalEdit').modal('show');
                    } else {
                        $.notify(response.message, "error");
                    }

                }
            });
        });

        // DELETE DATA
        $(document).on('click', '.deleteBtn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            var url = "{{ locale_route('administration.user.destroy', ':id') }}";
            url = url.replace(':id', id);
            swal({
                    title: "{{__('modal.confirm_delete')}}",
                    text: "{{__('modal.want_to_delete')}}",
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
                                    $('#tableuser').DataTable().ajax.reload();
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
