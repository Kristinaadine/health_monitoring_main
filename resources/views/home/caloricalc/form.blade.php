@extends('layouts.app')

@push('title')
    Calorie Calculator
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" href="{{ locale_route('caloric') }}"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">@t('Daily Calorie Calculator')</span>
@endsection

@section('content')
    <div class="osahan-recommend p-3">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                    <form id="calorie-calculator" class="p-3">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="age">@t('Age')</label>
                            <input placeholder="@t('Enter Your Age')" type="number" class="form-control" id="age"
                                name="age" value="{{ old('age') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="sex">@t('gender')</label>
                            <br>
                            <input type="radio" class="form-check-input" id="male" name="sex" value="male" required>
                            <label class="form-check-label" style="padding-right: 30px" for="male">@t('laki_laki')</label>
                            <input type="radio" class="form-check-input" id="female" name="sex" value="female" required>
                            <label class="form-check-label" for="female">@t('perempuan')</label>
                        </div>
                        <div class="form-group mb-3">
                            <label for="height">@t('Height') (cm)</label>
                            <input placeholder="@t('Enter Your Height')" type="number" class="form-control" id="height"
                                name="height" value="{{ old('height') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="weight">@t('Weight') (kg)</label>
                            <input placeholder="@t('Enter Your Weight')" type="number" class="form-control" id="weight"
                                name="weight" value="{{ old('weight') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="activity_level">@t('Activity Level')</label>
                            <select class="form-select" id="activity_level" name="activity_level" required>
                                <option value="">@t('Select an Activity Level')</option>
                                <option value="1.2">@t('Little to no exercise')</option>
                                <option value="1.375">@t('Light exercise (1−3 days per week)')</option>
                                <option value="1.55">@t('Moderate exercise (3−5 days per week)')</option>
                                <option value="1.725">@t('Heavy exercise (6−7 days per week)')</option>
                                <option value="1.9">@t('Very heavy exercise (twice per day, extra heavy workouts)')</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="gain_loss_amount">{{ __('general.choose_goal') }}</label>
                            <select class="form-select" id="gain_loss_amount" name="gain_loss_amount" required>
                                <option value="">{{ __('general.select_a_goal') }}</option>
                                <option value="-1000">{{ __('general.lose_2_pounds_per_week') }}</option>
                                <option value="-750">{{ __('general.lose_15_pounds_per_week') }}</option>
                                <option value="-500">{{ __('general.lose_1_pound_per_week') }}</option>
                                <option value="-250">{{ __('general.lose_05_pound_per_week') }}</option>
                                <option value="0">{{ __('general.stay_the_same_weight') }}</option>
                                <option value="250">{{ __('general.gain_05_pound_per_week') }}</option>
                                <option value="500">{{ __('general.gain_1_pound_per_week') }}</option>
                                <option value="750">{{ __('general.gain_15_pounds_per_week') }}</option>
                                <option value="1000">{{ __('general.gain_2_pounds_per_week') }}</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg rounded w-100">
                            {{ __('general.calculate_daily_calorie') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div id="resultCal" style="display: none">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                        <h3 class="text-center py-3">{{ __('general.your_daily_calorie_needs') }}</h3>

                        <div class="p-1 px-3 d-flex align-items-center">
                            <span class="h5 px-1">{{ __('general.estimated_daily_calories') }}</span>
                            <p class="bg-info text-white py-1 px-2 rounded m-0 h6" id="result"
                                style="font-size: 24px"></p>
                        </div>

                        <div class="p-3 osahan-categories">
                            <div class="row m-0">
                                <div class="col ps-0 pe-1 py-1">
                                    <div class="bg-white shadow-sm rounded text-center px-2 py-3 c-it">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('') }}assets/img/categorie/6.svg" class="img-fluid px-2">
                                            <p class="m-0 text-muted text-center"
                                                style="padding-right: 10px; font-size: 20px">
                                                {{ __('general.carbs') }}
                                            </p>
                                            <p class="m-0 bg-info text-white py-1 px-2 text-center rounded"
                                                id="carbs" style="font-size: 20px"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col p-1">
                                    <div class="bg-white shadow-sm rounded text-center px-2 py-3 c-it">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('') }}assets/img/categorie/2.svg" class="img-fluid px-2">
                                            <p class="m-0 text-muted text-center"
                                                style="padding-right: 10px; font-size: 20px">
                                                {{ __('general.protein') }}
                                            </p>
                                            <p class="m-0 bg-info text-white py-1 px-2 text-center rounded"
                                                id="protein" style="font-size: 20px"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col p-1">
                                    <div class="bg-white shadow-sm rounded text-center px-2 py-3 c-it">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('') }}assets/img/categorie/3.svg" class="img-fluid px-2">
                                            <p class="m-0 text-muted text-center"
                                                style="padding-right: 10px; font-size: 20px">
                                                {{ __('general.fat') }}
                                            </p>
                                            <p class="m-0 bg-info text-white py-1 px-2 text-center rounded"
                                                id="fat" style="font-size: 20px"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3">
                            <button type="button" id="btnSaveResult" class="btn btn-primary btn-lg rounded w-100">
                                <i class="icofont-save"></i> Simpan Hasil Perhitungan
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let calculatedData = {};

    $('#calorie-calculator').submit(function(e) {
        e.preventDefault();
        if ($('#age').val() == '' || $('input[name="sex"]:checked').val() == '' || $('#weight').val() == '' ||
            $('#height').val() == '' || $('#activity_level').val() == '' || $('#gain_loss_amount').val() == ''
            ) {
                $("#resultCal").hide();
                $.notify("Fill all fields", "error");
        } else {
            $("#resultCal").show();
            $("html, body").animate({ scrollTop: $(document).height() }, 1000);
            calcDailyCals();
        }
    });

    function calcDailyCals() {
        let age = parseInt($('#age').val());
        let sex = $('input[name="sex"]:checked').val();
        let weighttolbs = parseFloat($('#weight').val()) * 2.20462;
        let weight = weighttolbs * 0.453592;
        let heighttoinc = parseFloat($("#height").val()) / 2.54;
        let height = heighttoinc * 2.54;
        let activity = parseFloat($('#activity_level').val());
        let goal = parseInt($('#gain_loss_amount').val());

        let result;

        if (sex === 'male') {
            result = (88.362 + (13.397 * weight) + (4.799 * height) - (5.677 * age)) * activity;
        } else {
            result = (447.593 + (9.247 * weight) + (3.098 * height) - (4.33 * age)) * activity;
        }

        result = Math.round(result + goal);

        let macros = calcDailyMacros(result);
        $('#result').text(result);

        // Store calculated data
        calculatedData = {
            age: age,
            sex: sex,
            height: parseFloat($("#height").val()),
            weight: parseFloat($('#weight').val()),
            activity_level: activity,
            gain_loss_amount: goal,
            daily_calories: result,
            carbs: macros.carbs,
            protein: macros.protein,
            fat: macros.fat
        };

        function calcDailyMacros(result) {
            let carbs = Math.round((result * .4) / 4);
            let protein = Math.round((result * .3) / 4);
            let fat = Math.round((result * .3) / 9);

            $('#carbs').text(carbs);
            $('#protein').text(protein);
            $('#fat').text(fat);

            return { carbs, protein, fat };
        }
    }

    // Save Result
    $('#btnSaveResult').on('click', function() {
        if (!calculatedData.daily_calories) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Silakan hitung kalori terlebih dahulu!'
            });
            return;
        }

        $.ajax({
            url: '{{ locale_route("caloric.store") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ...calculatedData
            },
            beforeSend: function() {
                $('#btnSaveResult').prop('disabled', true).html('<i class="icofont-spinner-alt-2 icofont-spin"></i> Menyimpan...');
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil disimpan',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = response.redirect;
                });
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMsg
                });
                $('#btnSaveResult').prop('disabled', false).html('<i class="icofont-save"></i> Simpan Hasil Perhitungan');
            }
        });
    });
</script>
@endpush
