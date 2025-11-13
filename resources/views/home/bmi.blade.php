@extends('layouts.app')

@push('title')
    {{ __('general.bmi_calculator') }}
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#">
        <i class="icofont-rounded-left back-page"></i>
    </a>
    <span class="fw-bold ms-3 h6 mb-0">{{ __('general.bmi_calculator') }}</span>
@endsection

@section('content')
    <div class="osahan-recommend p-3">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                    <form method="POST" class="p-3">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="height">@t('Tinggi Badan (cm)')</label>
                            <input placeholder="{{ __('general.enter_your_height') }}" type="number" class="form-control"
                                id="height" name="height" value="{{ old('height') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="weight">{{ __('general.berat_badan') }} (kg)</label>
                            <input placeholder="{{ __('general.enter_your_weight') }}" type="number" class="form-control"
                                id="weight" name="weight" value="{{ old('weight') }}">
                        </div>
                        <button type="submit" class="btn btn-success btn-lg rounded w-100">
                            {{ __('general.calculate_bmi') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div id="resultbmi" style="display: none">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                        <h3 class="text-center py-3">{{ __('general.your_result') }}</h3>
                        <div class="p-1 px-3">
                            <p>
                                <span class="h6">BMI :</span>
                                <span id="bmi-res" class="h6 fw-light"></span>
                            </p>
                            <div class="d-flex align-items-center">
                                <h5>{{ __('general.category') }} :</h5>
                                <div id="cat-res" class="px-1"></div>
                            </div>
                            <p>
                                <h5>{{ __('general.recommendation') }} :</h5>
                                <span id="rec-res" class="h6 fw-light"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            var form = $("form");

            form.on("submit", function(e) {
                e.preventDefault();

                if ($("#height").val() == "") {
                    $("#height").notify("{{ __('general.the_height_field_is_required') }}", { position: "bottom" });
                    $("#resultbmi").hide();
                }
                if ($("#weight").val() == "") {
                    $("#weight").notify("{{ __('general.the_weight_field_is_required') }}", { position: "bottom" });
                    $("#resultbmi").hide();
                }
                if ($("#height").val() != "" && $("#weight").val() != "") {

                    function calcBMI() {
                        var weight = $("#weight").val();
                        var height = $("#height").val() / 100;
                        var bmi = weight / (height * height);
                        return bmi.toFixed(2);
                    }

                    function bmiState() {
                        if (calcBMI() < 18.5) return "{{ __('general.underweight') }}";
                        if (calcBMI() >= 18.5 && calcBMI() <= 24.9) return "{{ __('general.normal_weight') }}";
                        if (calcBMI() >= 25 && calcBMI() <= 29.9) return "{{ __('general.overweight') }}";
                        if (calcBMI() >= 30) return "{{ __('general.obesity') }}";
                    }

                    var color = 'success';
                    if (bmiState() == "{{ __('general.underweight') }}") color = 'warning';
                    else if (bmiState() == "{{ __('general.overweight') }}") color = 'primary';
                    else if (bmiState() == "{{ __('general.obesity') }}") color = 'danger';

                    function bmiRec() {
                        if (bmiState() == "{{ __('general.underweight') }}")
                            return "{{ __('general.rec_underweight') }}";
                        if (bmiState() == "{{ __('general.normal_weight') }}")
                            return "{{ __('general.rec_normal_weight') }}";
                        if (bmiState() == "{{ __('general.overweight') }}")
                            return "{{ __('general.rec_overweight') }}";
                        if (bmiState() == "{{ __('general.obesity') }}")
                            return "{{ __('general.rec_obesity') }}";
                    }

                    $("#bmi-res").text(calcBMI());
                    $("#cat-res").html(`<p class="bg-${color} text-white py-1 px-2 rounded small m-0">${bmiState()}</p>`);
                    $("#rec-res").text(bmiRec());
                    $("#resultbmi").show();
                }
            });
        });
    </script>
@endpush