<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">{{__('home.editnutrition')}}</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <span aria-hidden="true">&times;</span> -->
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="formEdit">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="calorie_target">{{__('home.dailyCalorie')}} <span class="text-danger">*</span></label>
                        <input type="number" placeholder="Enter Daily Calorie Target"
                            class="form-control @error('calorie_target') is-invalid @enderror" id="calorie_target"
                            name="calorie_target" value="{{ auth()->user()->calorie_target }}">
                        @error('calorie_target')
                            <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="nutrient_ration">{{__('home.macronutrientratio')}} <span class="text-danger">*</span></label>
                        @foreach ($ratio as $item)
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="{{ $item->id }}" name="nutrient_ration"
                                    value="{{ $item->id }}" @if (auth()->user()->nutrient_ration == $item->id) checked @endif>
                                <label class="form-check-label" for="{{ $item->id }}">{{ $item->name }}
                                    ({{ $item->protein }}%
                                    protein, {{ $item->carbs }}% carbs, {{ $item->fat }}% fat)</label>
                            </div>
                        @endforeach

                        @error('nutrient_ration')
                            <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer p-0 border-0">
                <div class="col-6 m-0 p-0">
                    <button type="button" class="btn border-top btn-lg w-100" data-bs-dismiss="modal">{{__('home.close')}}</button>
                </div>
                <div class="col-6 m-0 p-0">
                    <button type="button" class="btn btn-success btn-lg w-100" id="btnSubmit">{{__('home.saveChanges')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function () {
            $('#btnSubmit').on('click', function (e) {
                e.preventDefault();
                var url = "{{ locale_route('meal-planner.update-nutrition') }}";
                var fd = $('#formEdit').serialize();

                $.ajax({
                    type: "POST",
                    url: url,
                    data: fd,
                    dataType: "json",
                    beforeSend: function () {
                        $('#btnSubmit').attr('disabled', true);
                        $('#btnSubmit').html('<i class="icofont-spinner-alt-2 icofont-spin"></i>');
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            $('#modalEdit').modal('hide');
                            $.notify(response.message, "success");
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } else {
                            $.notify(response.message, "error");
                        }
                    },
                    complete: function () {
                        $('#btnSubmit').attr('disabled', false);
                        $('#btnSubmit').html('Save changes');
                    },
                    error: function (xhr, status, error) {
                        $.notify("An error occurred. Please try again.", "error");
                    }
                });
            });
        });
    </script>
@endpush
