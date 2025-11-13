<div class="modal fade" id="modalEdit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Nutrient Ratio</h5>
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
                    <div class="form-group row">
                        <div class="col-lg-4 col-md-4">
                            <label>Protein <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="protein" id="Editprotein">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label>Carbs <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="carbs" id="Editcarbs">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label>Fat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="fat" id="Editfat">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
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
                let protein = $('#Editprotein').val();
                let carbs = $('#Editcarbs').val();
                let fat = $('#Editfat').val();

                if (!name) {
                    $("#Editname").notify(
                        "Name is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!protein) {
                    $("#Editprotein").notify(
                        "Protein is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!carbs) {
                    $("#Editcarbs").notify(
                        "Carbs is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!fat) {
                    $("#Editfat").notify(
                        "Fat is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!name || !protein || !carbs || !fat) {
                    return false;
                }

                const fd = $('#formEdit').serialize();
                var id = $('#id').val();
                var url = "{{ locale_route('administration.nutrient.update', ':id') }}";
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
                            $('#tablenutrient').DataTable().ajax.reload();
                        } else {
                            $.notify(response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        $.notify("An error occurred", "error");
                    },
                    complete: function() {
                    $('#btnUpdate').attr('disabled', false);
                }
                });
            });
        });
    </script>
@endpush
