@extends('layouts-admin.master')
@push('title')
    Food Categories
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
                    <h6 class="font-weight-bold mt-1">Food Categories<br><small class="text-mute">Categories of food</small></h6>
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
                <table class="table datatable display responsive w-100" id="tablecategories">
                    <thead>
                        <tr>
                            <th class="all">No</th>
                            <th class="min-tablet">Name</th>
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
    @include('admin.food-category.modal-add')
    @include('admin.food-category.modal-edit')
@endsection

@push('js')
    <script>
        // Force reload - clear any cached scripts
        console.log('Script loaded at: {{ now()->timestamp }}');
        
        $(document).ready(function() {
            table()
        });

        function table() {
            var table = $('#tablecategories').DataTable({
                processing: true,
                serverSide: true,
                "bDestroy": true,
                searching: true,
                ajax: "{{ locale_route('administration.food-categories.index') }}",
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
            // Use route helper with placeholder
            var url = "{{ locale_route('administration.food-categories.edit', ['food_category' => '__ID__']) }}";
            url = url.replace('__ID__', id);
            
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        $('#id').val(id);
                        $('#Editname').val(response.data.name);
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
            
            // Use route helper with placeholder - will be replaced with actual ID
            var url = "{{ locale_route('administration.food-categories.destroy', ['food_category' => '__ID__']) }}";
            url = url.replace('__ID__', id);
            
            console.log('=== DELETE REQUEST ===');
            console.log('Delete URL:', url);
            console.log('Delete ID:', id);
            console.log('Timestamp:', new Date().toISOString());
            
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
                            type: 'POST', // Use POST instead of DELETE
                            data: {
                                _method: 'DELETE', // Method spoofing
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'json',
                            success: function(response) {
                                console.log('Delete response:', response);
                                if (response.status == 'success') {
                                    swal("Success!", response.message, "success");
                                    $('#tablecategories').DataTable().ajax.reload();
                                } else {
                                    swal("Error!", response.message, "error");
                                }
                            },
                            error: function(xhr) {
                                console.error('Delete error:', xhr);
                                console.error('Status:', xhr.status);
                                console.error('Response:', xhr.responseText);
                                let errorMsg = "An error occurred (Status: " + xhr.status + ")";
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                } else if (xhr.status === 404) {
                                    errorMsg = "Route not found. Please check the URL.";
                                } else if (xhr.status === 419) {
                                    errorMsg = "CSRF token mismatch. Please refresh the page.";
                                }
                                swal("Error!", errorMsg, "error");
                            }
                        });
                    }
                });
        });
    </script>
@endpush
