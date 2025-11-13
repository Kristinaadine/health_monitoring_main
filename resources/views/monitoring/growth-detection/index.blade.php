@extends('layouts.app')

@push('title')
    Growth Detection & Risk Prediction
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('home')}}"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">{{__('growth_detection.growth_title')}}</span>
    {{-- <a class="toggle ms-auto" href="#"><i class="icofont-navigation-menu"></i></a> --}}
@endsection

@section('content')
    <div class="p-3 osahan-categories">
        <h6 class="mb-2">{{__('growth_detection.what_do_you_looking_for')}}</h6>
        <div class="row m-0">
            <div class="col ps-0 pe-1 py-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('growth-detection.diet-user.list') }}">
                        <img src="{{ asset('') }}assets/img/categorie/1.svg" class="img-fluid px-2">
                        <p class="m-0 pt-2 text-muted text-center">{{__('growth_detection.diet')}}<br>({{__('growth_detection.adult')}} / {{__('growth_detection.general')}})</p>
                    </a>
                </div>
            </div>
            <div class="col p-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('growth-detection.stunting') }}">
                        <img src="{{ asset('') }}assets/img/categorie/2.svg" class="img-fluid px-2">
                        <p class="m-0 pt-2 text-muted text-center"> {{__('growth_detection.guardian_user')}}<br>({{__('growth_detection.child_stunting')}})</p>
                    </a>
                </div>
            </div>
            <div class="col p-1">
                <div class="bg-white shadow-sm rounded text-center  px-2 py-3 c-it">
                    <a href="{{ locale_route('growth-detection.pre-stunting.index') }}">
                        <img src="{{ asset('') }}assets/img/categorie/4.svg" class="img-fluid px-2">
                        <p class="m-0 pt-2 text-muted text-center"> {{__('growth_detection.pre-stunting')}} <br/>{{__('growth_detection.prediction')}}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/@floating-ui/core@1.7.0"></script>
<script src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.7.0"></script>
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
            var form = $("form");

            form.on("submit", function(e) {
                e.preventDefault();
                var resultDiv = $(".result .title");

                if ($("#height").val() == "") {
                    $("#height").notify(
                        "{{__('growth_detection.the_height_field_required')}}", {
                            position: "bottom"
                        }
                    );
                    $("#resultbmi").hide();
                }
                if ($("#weight").val() == "") {
                    $("#weight").notify(
                        "{{__('growth_detection.the_weight_field_required')}}", {
                            position: "bottom"
                        }
                    );
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
                        if (calcBMI() < 18.5) {
                            return "{{__('growth_detection.under_weight')}}";
                        }
                        if (calcBMI() >= 18.5 && calcBMI() <= 24.9) {
                            return "{{__('growth_detection.normal_weight')}}";
                        }
                        if (calcBMI() >= 25 && calcBMI() <= 29.9) {
                            return "{{__('growth_detection.over_weight')}}";
                        }
                        if (calcBMI() >= 30) {
                            return "{{__('growth_detection.obesity_weight')}}";
                        }
                    }

                    var color = 'success';
                    if (bmiState() == "Underweight") {
                        color = 'warning';
                    } else if (bmiState() == "Overweight") {
                        color = 'primary';
                    } else if (bmiState() == "Obesity") {
                        color = 'danger';
                    }

                    function bmiRec() {
                        if (bmiState() == "Underweight") {
                            return "{{__('growth_detection.consider_increasing')}}";
                        }
                        if (bmiState() == "Normal weight") {
                            return "{{__('growth_detection.maintain_your_weight')}}";
                        }
                        if (bmiState() == "Overweight") {
                            return "{{__('growth_detection.overweight')}}";
                        }
                        if (bmiState() == "Obesity") {
                            return "{{__('growth_detection.obesity_health')}}";
                        }
                    }

                    // resultDiv.html(calcBMI() + " -> " + bmiState());
                    $("#bmi-res").text(calcBMI())
                    $("#cat-res").html(`<p class="bg-` + color +
                        ` text-white py-1 px-2 rounded small m-0">` +
                        bmiState() + `</p>`)
                    $("#rec-res").text(bmiRec());
                    $("#resultbmi").show();
                }
            });
        });
    </script>
@endpush
