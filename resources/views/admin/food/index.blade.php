@extends('layouts-admin.master')
@push('title')
    Food
@endpush

@push('css')
    <style>
        .table tbody tr td {
            color: #090909;
        }

        .form-group label {
            color: #090909;
        }

        .ck.ck-reset_all,
        .ck.ck-reset_all * {
            color: #090909 !important;
        }
    </style>
@endpush

@section('header')
    <div class="row submenu">
        <div class="container-fluid " id="submenu-container">
            <div class="row py-2">
                <!-- Submenu section starts -->
                <div class="col">
                    <h6 class="font-weight-bold mt-1">Food<br><small class="text-mute">For Food Guide</small></h6>
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
                <div class="form-group row">
                    <div class="col-lg-4 col-md-4">
                        {{-- <label>Name <span class="text-danger">*</span></label> --}}
                        <input type="search" class="form-control" placeholder="Search Name Food" name="name_food" id="name_foodSearch">
                    </div>
                    <div class="col-lg-5 col-md-5">
                        {{-- <label>Categories <span class="text-danger">*</span></label> --}}
                        <select class="form-control" name="id_categories" id="id_categoriesSearch">
                            <option value="">Search Categories</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <button type="button" class="btn btn-success form-control" id="btnSearch">Search</button>
                    </div>
                </div>
                <table class="table datatable display responsive w-100" id="tablefood">
                    <thead>
                        <tr>
                            <th class="all"></th>
                            <th class="min-tablet">Name</th>
                            <th class="min-tablet">Categories</th>
                            <th class="">Composition</th>
                            <th class="min-desktop">Description</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-dark">
                        {{--  --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('admin.food.modal-add')
    @include('admin.food.modal-edit')
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            table()
        });

        function table() {
            var table = $('#tablefood').DataTable({
                processing: true,
                serverSide: true,
                "bDestroy": true,
                searching: false,
                ajax: {
                        url: "{{ locale_route('administration.food.index') }}",
                        data: function(d) {
                            d.name_food = $('#name_foodSearch').val(),
                            d.id_categories = $('#id_categoriesSearch').val()
                        },
                    },
                columns: [{
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name_food',
                        name: 'name_food',
                        className: 'text-dark'
                    },
                    {
                        data: 'categories',
                        name: 'categories',
                        className: 'text-dark text-center'
                    },
                    {
                        data: 'composition',
                        name: 'composition',
                        className: 'text-dark'
                    },
                    {
                        data: 'description',
                        name: 'description',
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
            $('#btnSearch').click(function(e) {
                e.preventDefault();
                table.draw();
            });
            return table;
        }

        // DELETE DATA
        $(document).on('click', '.deleteBtn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            var url = "{{ locale_route('administration.food.destroy', ':id') }}";
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
                                    $('#tablefood').DataTable().ajax.reload();
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
