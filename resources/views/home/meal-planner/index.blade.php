@extends('layouts.app')

@push('title')
    Meal Planner
@endpush

@push('css')
    {{--  --}}
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">{{__('home.mealPlanner')}}</span>
    {{-- <a class="toggle ms-auto" href="#"><i class="icofont-navigation-menu"></i></a> --}}
@endsection

@section('content')
    <div class="order-body px-3 pt-3">
        <div class="pb-3">
            <div class="p-3 rounded shadow-sm bg-white">
                <div class="d-flex align-items-center mb-3">
                    <p class="bg-success text-white py-1 px-2 rounded small m-0">{{__('home.nutritionGoal')}}</p>
                    <p class="text-muted ms-auto small m-0"><span class="small"><a href="#" data-bs-toggle="modal"
                                data-bs-target="#modalEdit" class="text-decoration-none text-warning"><i
                                    class="icofont-edit"></i> {{__('home.edit')}}</a></span></p>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                        <div class="rounded shadow bg-info d-flex align-items-center p-3 text-white">
                            <div class="more text-center w-100">
                                <h6 class="m-0">{{__('home.calorie')}}</h6>
                                <p></p>
                                <p class="small m-0">{{ auth()->user()->calorie_target ?? 0 }} {{__('home.kcal')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                        <div class="rounded shadow bg-info d-flex align-items-center p-3 text-white ms-auto">
                            <div class="more text-center w-100">
                                <h6 class="m-0">{{__('home.protein')}}</h6>
                                <p class="small m-0" id="result-protein">0g</p>
                                <p class="small m-0">{{ auth()->user()->nutrient->protein ?? 0 }}% {{__('home.goals')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                        <div class="rounded shadow bg-info d-flex align-items-center p-3 text-white">
                            <div class="more text-center w-100">
                                <h6 class="m-0">{{__('home.carbohydrate')}}</h6>
                                <p class="small m-0" id="result-carbs">0g</p>
                                <p class="small m-0">{{ auth()->user()->nutrient->carbs ?? 0 }}% {{__('home.goals')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                        <div class="rounded shadow bg-info d-flex align-items-center p-3 text-white">
                            <div class="more text-center w-100">
                                <h6 class="m-0">{{__('home.fat')}}</h6>
                                <p class="small m-0" id="result-fat">0g</p>
                                <p class="small m-0">{{ auth()->user()->nutrient->fat ?? 0 }}% {{__('home.goals')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="osahan-recommend p-3">
        <div class="d-flex align-items-center mb-3">
            <h6 class="mb-2">{{__('home.whatdidyoueat')}}</h6>
            <button type="button" id="btnGenerate" class="btn btn-success rounded m-0 ms-auto"><i
                    class="icofont-curved-left"></i>{{__('home.getmealplan')}}</button>
        </div>
    </div>

    <div id="result-meal" style="display: none;">
        <div class="row px-3">
            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                <div class="form-check px-0 mb-3 position-relative border-custom-radio">
                    <input type="radio" id="breakfast" name="meal" class="form-check-input" checked>
                    <label class="form-check-label w-100" for="breakfast">
                        <div>
                            <div class="p-3 bg-white rounded shadow-sm w-100">
                                <div class="d-flex align-items-center mb-2">
                                    <p class="mb-0 h6">{{__('home.breakfast')}}</p>
                                </div>
                                <div class="d-flex align-items-center p-3">
                                    <img id="img1" src="{{ asset('') }}assets/img/logo/{{ $setting->where('key', 'website_logo')->first()->value }}"
                                        class="img-fluid shadow" style="height: 154px; width: 154px; object-fit: cover; border-radius: 15px">
                                    <div class="ms-3 text-dark text-decoration-none w-100">
                                        <h6 class="mb-1" id="name1"></h6>
                                        <p class="small text-muted m-0" id="protein1"></p>
                                        <p class="small text-muted m-0" id="carbs1"></p>
                                        <p class="small text-muted m-0" id="fiber1"></p>
                                        <p class="small text-muted m-0">{{__('home.description')}} : </p>
                                        <p class="small text-muted m-0" id="desc1"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                <div class="form-check px-0 mb-3 position-relative border-custom-radio">
                    <input type="radio" id="lunch" name="meal" class="form-check-input">
                    <label class="form-check-label w-100" for="lunch">
                        <div>
                            <div class="p-3 rounded bg-white shadow-sm w-100">
                                <div class="d-flex align-items-center mb-2">
                                    <p class="mb-0 h6">{{__('home.lunch')}}</p>
                                </div>
                                <div class="d-flex align-items-center p-3">
                                    <img id="img2" src="{{ asset('') }}assets/img/logo/{{ $setting->where('key', 'website_logo')->first()->value }}"
                                        class="img-fluid shadow" style="height: 154px; width: 154px; object-fit: cover; border-radius: 15px">
                                    <div class="ms-3 text-dark text-decoration-none w-100">
                                        <h6 class="mb-1" id="name2"></h6>
                                        <p class="small text-muted m-0" id="protein2"></p>
                                        <p class="small text-muted m-0" id="carbs2"></p>
                                        <p class="small text-muted m-0" id="fiber2"></p>
                                        <p class="small text-muted m-0">{{__('home.description')}} : </p>
                                        <p class="small text-muted m-0" id="desc2"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                <div class="form-check px-0 mb-3 position-relative border-custom-radio">
                    <input type="radio" id="dinner" name="meal" class="form-check-input">
                    <label class="form-check-label w-100" for="dinner">
                        <div>
                            <div class="p-3 rounded bg-white shadow-sm w-100">
                                <div class="d-flex align-items-center mb-2">
                                    <p class="mb-0 h6">{{__('home.dinner')}}</p>
                                </div>
                                <div class="d-flex align-items-center p-3">
                                    <img id="img3" src="{{ asset('') }}assets/img/logo/{{ $setting->where('key', 'website_logo')->first()->value }}"
                                        class="img-fluid shadow" style="height: 154px; width: 154px; object-fit: cover; border-radius: 15px">
                                    <div class="ms-3 text-dark text-decoration-none w-100">
                                        <h6 class="mb-1" id="name3"></h6>
                                        <p class="small text-muted m-0" id="protein3"></p>
                                        <p class="small text-muted m-0" id="carbs3"></p>
                                        <p class="small text-muted m-0" id="fiber3"></p>
                                        <p class="small text-muted m-0">{{__('home.description')}} : </p>
                                        <p class="small text-muted m-0" id="desc3"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>


    @include('home.meal-planner.modaledit')
@endsection

@push('js')
    <script>
        $(document).on('click', '#btnGenerate', function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ locale_route('meal-planner.get-meal') }}",
                data: {
                    'calorie_target': '{{ auth()->user()->calorie_target }}',
                    'nutrient_ration': '{{ auth()->user()->nutrient_ration }}'
                },
                dataType: "json",
                beforeSend: function() {
                    $('#btnGenerate').attr('disabled', true);
                    $('#btnGenerate').html('<i class="icofont-spinner-alt-2 icofont-spin"></i>');
                },
                success: function(response) {
                    if (response.status == 'success') {

                        $('#result-meal').show();
                        $.notify(response.message, "success");
                        // breakfast
                        $('#img1').attr('src', response.data.breakfast.image_path);
                        $('#name1').html(response.data.breakfast.name_food+" ("+response.data.breakfast.calories+" kcal)");
                        $('#protein1').html("Protein : "+response.data.breakfast.protein);
                        $('#carbs1').html("Carbs : "+response.data.breakfast.carbs);
                        $('#fiber1').html("Fiber : "+response.data.breakfast.fiber);
                        $('#desc1').html(response.data.breakfast.description);
                        // lunch
                        $('#img2').attr('src', response.data.lunch.image_path);
                        $('#name2').html(response.data.lunch.name_food+" ("+response.data.lunch.calories+" kcal)");
                        $('#protein2').html("Protein : "+response.data.lunch.protein);
                        $('#carbs2').html("Carbs : "+response.data.lunch.carbs);
                        $('#fiber2').html("Fiber : "+response.data.lunch.fiber);
                        $('#desc2').html(response.data.lunch.description);
                        // dinner
                        $('#img3').attr('src', response.data.dinner.image_path);
                        $('#name3').html(response.data.dinner.name_food+" ("+response.data.dinner.calories+" kcal)");
                        $('#protein3').html("Protein : "+response.data.dinner.protein);
                        $('#carbs3').html("Carbs : "+response.data.dinner.carbs);
                        $('#fiber3').html("Fiber : "+response.data.dinner.fiber);
                        $('#desc3').html(response.data.dinner.description);

                        // protein
                        $('#result-protein').html(response.data.protein+"g");
                        // carbs
                        $('#result-carbs').html(response.data.carbs+"g");
                        // fiber
                        $('#result-fat').html(response.data.fiber+"g");
                    } else {
                        $.notify(response.message, "error");
                        $('#result-meal').hide();
                    }
                },
                complete: function() {
                    $('#btnGenerate').attr('disabled', false);
                    $('#btnGenerate').html('<i class="icofont-curved-left"></i>Get Meal Planner');
                },
                error: function(xhr, status, error) {
                    $('#result-meal').hide();
                    $.notify("An error occurred. Please try again.", "error");
                }
            });
        });
    </script>
@endpush
