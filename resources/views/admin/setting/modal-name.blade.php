<div class="modal fade" id="modalName">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Website Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formName" action="#" method="PUT">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Website Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control is-valid" placeholder="" name="website_name" id="website_name" value="{{$setting->where('key', 'website_name')->first()->value}}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnName">Save Change</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $('#btnName').on('click', function() {
                let website_name = $('#website_name').val();

                if (!website_name) {
                    $("#website_name").notify(
                        "Website Name is required", {
                            position: "bottom"
                        }
                    );
                    return false;
                }


                const fd = $('#formName').serialize();
                $.ajax({
                    url: "{{ locale_route('administration.setting.update') }}",
                    type: "PUT",
                    data: fd,
                    dataType: "json",
                    beforeSend: function() {
                        $('#btnName').attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $.notify(response.message, "success");
                            $('#modalName').modal('hide');
                            $('#formName')[0].reset();
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            $.notify(response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        $.notify("An error occurred", "error");
                    },
                    complete: function() {
                    $('#btnName').attr('disabled', false);
                }
                });
            });
        });
    </script>
@endpush
