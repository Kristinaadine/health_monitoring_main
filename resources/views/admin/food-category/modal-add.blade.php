<div class="modal fade" id="modalAdd">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Food Categories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAdd" action="javascript:void(0)" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="name" id="name">
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
        console.log('Modal Add script loaded');
        
        $(document).ready(function() {
            console.log('Modal Add ready');
            
            // Prevent form submit
            $('#formAdd').on('submit', function(e) {
                console.log('Form submit prevented');
                e.preventDefault();
                return false;
            });
            
            $('#btnSubmit').on('click', function(e) {
                console.log('Submit button clicked');
                e.preventDefault();
                let name = $('#name').val();

                if (!name) {
                    $("#name").notify(
                        "Name is required", {
                            position: "bottom"
                        }
                    );
                    return false;
                }

                const fd = $('#formAdd').serialize();
                var locale = '{{ app()->getLocale() }}';
                var url = '/' + locale + '/administration/food-categories';
                
                $.ajax({
                    url: url,
                    type: "POST",
                    data: fd,
                    dataType: "json",
                    beforeSend: function() {
                        $('#btnSubmit').attr('disabled', true);
                    },
                    success: function(response) {
                        console.log('Add response:', response);
                        if (response.status == 'success') {
                            swal("Success!", response.message, "success");
                            $('#modalAdd').modal('hide');
                            $('#formAdd')[0].reset();
                            $('#tablecategories').DataTable().ajax.reload();
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        console.error('Add error:', xhr);
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
                        $('#btnSubmit').attr('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
