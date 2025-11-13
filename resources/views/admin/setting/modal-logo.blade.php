<div class="modal fade" id="modalLogo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Website Logo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formLogo" action="#">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <div class="col-lg-6 col-md-6">
                            <label>Image</label>
                            <input type="file" class="form-control is-valid" name="website_logo" id="website_logo"
                                accept="image/*">
                            <div class="valid-feedback">
                                image size 154px * 154px!
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <img src="{{ asset('') }}assets/img/logo/{{ $setting->where('key', 'website_logo')->first()->value }}"
                                alt="" width="200px" id="preview" class="img-thumbnail">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnLogo">Save Change</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        website_logo.onchange = evt => {
            const [file] = website_logo.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }
        $(document).ready(function() {
            $('#btnLogo').on('click', function() {
                let website_logo = $('#website_logo').val();

                if (!website_logo) {
                    $("#website_logo").notify(
                        "Website Logo is required", {
                            position: "bottom"
                        }
                    );
                    return false;
                }


                const fd = new FormData($('#formLogo')[0]);
                $.ajax({
                    url: "{{ locale_route('administration.setting.update') }}",
                    type: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#btnLogo').attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $.notify(response.message, "success");
                            $('#modalLogo').modal('hide');
                            $('#formLogo')[0].reset();
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
                        $('#btnLogo').attr('disabled', false);
                    },
                });
            });
        });
    </script>
@endpush
