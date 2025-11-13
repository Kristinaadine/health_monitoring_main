<div class="modal fade" id="modalChange" tabindex="-1" role="dialog" aria-labelledby="modalChangeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="#" class="" method="GET" id="formChange">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalChangeLabel">{{__('monitoring.change_child_data')}}</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close">
                        <!-- <span aria-hidden="true">&times;</span> -->
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12 form-group mb-3" id="chooseNameChange">
                            <label class="form-label">{{__('monitoring.choose_name')}}</label>
                            <div class="input-group">
                                <select id="chooseChange" name="name" class="form-select">
                                    <option value="">{{__('monitoring.choose_name')}}</option>
                                    @foreach ($choosename as $values)
                                        <option value="{{ $values->name }}">{{ $values->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-0 border-0">
                    <div class="col-6 m-0 p-0">
                        <button type="button" class="btn border-top btn-lg w-100"
                            data-bs-dismiss="modal">{{__('monitoring.close')}}</button>
                    </div>
                    <div class="col-6 m-0 p-0">
                        <button type="submit" class="btn btn-success btn-lg w-100" id="btnChange">{{__('monitoring.change')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')
    <script>
        // $(document).ready(function() {
        //     $('#btnChange').on('click', function(e) {
        //         e.preventDefault();
        //         var url = "{{ locale_route('growth-monitoring.index') }}";
        //         $.ajax({
        //             type: "get",
        //             url: url,
        //             data: {
        //                         name: $("#chooseChange").val(),
        //                         _token: '{{ csrf_token() }}'
        //                     },
        //             dataType: "json",
        //             beforeSend: function() {
        //                 $('#btnChange').attr('disabled', true);
        //                 $('#btnChange').html(
        //                     '<i class="icofont-spinner-alt-2 icofont-spin"></i>');
        //             },
        //             success: function(response) {
        //                 if (response.status == 'success') {
        //                     $('#modalChange').modal('hide');
        //                     $.notify(response.message, "success");
        //                     setTimeout(function() {
        //                         location.replace(response.redirect);
        //                     }, 1500);
        //                 } else {
        //                     $.notify(response.message, "error");
        //                 }
        //             },
        //             complete: function() {
        //                 $('#btnChange').attr('disabled', false);
        //                 $('#btnChange').html('Save changes');
        //             },
        //             error: function(xhr, status, error) {
        //                 $.notify("An error occurred. Please try again.", "error");
        //             }
        //         });
        //     });
        // });
    </script>
@endpush
