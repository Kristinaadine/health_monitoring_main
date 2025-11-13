@extends('layouts.app')

@push('title')
    Profile
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#">
        <i class="icofont-rounded-left back-page"></i></a>
    <h6 class="fw-bold m-0 ms-3">{{__('profile.nutritionGoal')}}</h6>
@endsection

@section('content')
    <div id="edit_profile">
        <div class="p-3">
            <form action="{{ locale_route('profile.nutrition-update') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="calorie_target">{{__('profile.dailyCalorie')}} <span class="text-danger">*</span></label>
                    <input type="number" placeholder="{{__('profile.enterDailyCalorie')}}"
                        class="form-control @error('calorie_target') is-invalid @enderror" id="calorie_target"
                        name="calorie_target" value="{{ auth()->user()->calorie_target }}">
                    @error('calorie_target')
                        <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="nutrient_ration">{{__('profile.macronutrientRatio')}} <span class="text-danger">*</span></label>
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
                <div class="text-center d-grid">
                    <button type="submit" class="btn btn-success btn-lg">{{__('profile.saveChanges')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        //
    </script>
@endpush
