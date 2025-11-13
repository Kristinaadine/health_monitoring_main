<div class="modal fade" id="modalEdit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('home.edituser')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" action="#" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group row">
                        <div class="col-lg-6 col-md-6">
                            <label>{{__('home.name')}} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="name" id="Editname">
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <label>{{__('home.email')}} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="email" id="Editemail">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>{{__('home.phone')}}</label>
                            <input type="text" class="form-control" placeholder="" name="phone" id="Editphone">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>{{__('home.role')}} <span class="text-danger">*</span></label>
                            <select class="form-control" name="roles" id="Editroles">
                                <option value="User">{{__('home.user')}}</option>
                                <option value="Admin">{{__('home.admin')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6 col-md-6">
                            <label>{{__('home.password')}} <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" placeholder="" name="password" id="password">
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <label>{{__('home.confirmPassword')}} <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" placeholder="" name="password2" id="password2">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnUpdate">{{__('home.update')}}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('home.close')}}</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $('#btnUpdate').on('click', function() {
                const fd = $('#formEdit').serialize();
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
                            $('#modalEdit').modal('hide');
                            $('#formEdit')[0].reset();
                            $('#tableuser').DataTable().ajax.reload();
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
        });
    </script>
@endpush
