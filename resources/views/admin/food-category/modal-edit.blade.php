<div class="modal fade" id="modalEdit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Food Categories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" action="#" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="name" id="Editname">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnUpdate">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $('#btnUpdate').on('click', function() {
                let name = $('#Editname').val();

                if (!name) {
                    $("#Editname").notify(
                        "Name is required", {
                            position: "bottom"
                        }
                    );
                    return false;
                }

                const fd = $('#formEdit').serialize();
                var id = $('#id').val();
                // Use route helper with placeholder
                var url = "{{ locale_route('administration.food-categories.update', ['food_category' => '__ID__']) }}";
                url = url.replace('__ID__', id);
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
                            swal("Success!", response.message, "success");
                            $('#modalEdit').modal('hide');
                            $('#formEdit')[0].reset();
                            $('#tablecategories').DataTable().ajax.reload();
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = "An error occurred";
                        
                        // Handle validation errors (422)
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                            swal({
                                title: "Validation Error!",
                                text: errorMsg,
                                icon: "warning",
                                button: "OK"
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                            swal("Error!", errorMsg, "error");
                        } else {
                            swal("Error!", errorMsg, "error");
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
