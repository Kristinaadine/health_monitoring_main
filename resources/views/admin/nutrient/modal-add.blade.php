<div class="modal fade" id="modalAdd">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Nutrient Ratio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAdd" action="#" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="name" id="name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4 col-md-4">
                            <label>Protein <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="protein" id="protein">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label>Carbs <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="carbs" id="carbs">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label>Fat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="fat" id="fat">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
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
                let name = $('#name').val();
                let protein = $('#protein').val();
                let carbs = $('#carbs').val();
                let fat = $('#fat').val();

                if (!name) {
                    $("#name").notify(
                        "Name is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!protein) {
                    $("#protein").notify(
                        "Protein is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!carbs) {
                    $("#carbs").notify(
                        "Carbs is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!fat) {
                    $("#fat").notify(
                        "Fat is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!name || !protein || !carbs || !fat) {
                    return false;
                }

                const fd = $('#formAdd').serialize();
                $.ajax({
                    url: "{{ locale_route('administration.nutrient.store') }}",
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
                            $('#tablenutrient').DataTable().ajax.reload();
                        } else {
                            $.notify(response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        $.notify("An error occurred", "error");
                    },
                    complete: function() {
                        $('#btnSubmit').attr('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
