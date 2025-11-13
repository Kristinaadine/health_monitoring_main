<div class="modal fade" id="modalAdd">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAdd" action="#" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-6 col-md-6">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="name" id="name">
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <label>email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="email" id="email">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Phone</label>
                            <input type="text" class="form-control" placeholder="" name="phone" id="phone">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Role <span class="text-danger">*</span></label>
                            <select class="form-control" name="roles" id="roles">
                                <option value="User">User</option>
                                <option value="Admin">Admin</option>
                            </select>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnSubmit">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $('#btnSubmit').on('click', function() {
                const fd = $('#formAdd').serialize();
                $.ajax({
                    url: "{{ locale_route('administration.user.store') }}",
                    type: "POST",
                    data: fd,
                    dataType: "json",
                    beforeSend: function() {
                        $('#btnSubmit').attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $.notify(response.message, "success");
                            $('#modalAdd').modal('hide');
                            $('#formAdd')[0].reset();
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
                        $('#btnSubmit').attr('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
