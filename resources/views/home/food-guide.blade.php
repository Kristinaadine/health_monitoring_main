@extends('layouts.app')

@push('title')
    Food Guide
@endpush

@push('css')
    <style>
        .imgnores {
            width: 30%;
        }

        @media screen and (max-width: 768px) {
            .imgnores {
                width: 50%;
            }
        }
    </style>
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#"><i
            class="icofont-rounded-left back-page"></i></a><span class="fw-bold ms-3 h6 mb-0">{{__('home.foodGuide')}}</span>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Handle diet user selection
            $('#dietUserSelect').on('change', function() {
                var dietUserId = $(this).val();
                if (dietUserId) {
                    var locale = '{{ app()->getLocale() }}';
                    var url = '/' + locale + '/food-guide/recommend/' + dietUserId;
                    window.location.href = url;
                }
            });
        });
    </script>
@endpush

@section('content')
    <!-- Diet User Recommendation Section -->
    @if(isset($dietUsers) && $dietUsers->count() > 0)
    <div class="osahan-recommend p-3">
        <div class="p-3 rounded shadow-sm bg-white mb-3">
            <div class="d-flex align-items-center mb-3">
                <p class="bg-info text-white py-1 px-2 rounded small m-0">{{__('home.dietRecommendation')}}</p>
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">{{__('home.selectDietAnalysis')}}</label>
                    <select class="form-control" id="dietUserSelect">
                        <option value="">-- {{__('home.selectDietUser')}} --</option>
                        @foreach($dietUsers as $du)
                            <option value="{{ $du->id }}" 
                                data-status="{{ $du->status_gizi }}"
                                data-bmi="{{ $du->bmi }}"
                                @if(isset($dietUser) && $dietUser->id == $du->id) selected @endif>
                                {{ $du->nama }} - BMI: {{ $du->bmi }} ({{ $du->status_gizi }}) - {{ $du->created_at->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @if(isset($analysis))
                <div class="col-12">
                    <div class="alert alert-info">
                        <h6 class="mb-2"><i class="icofont-info-circle"></i> {{__('home.analysisResult')}}</h6>
                        <p class="mb-1"><strong>{{__('home.name')}}:</strong> {{ $analysis['name'] }}</p>
                        <p class="mb-1"><strong>BMI:</strong> {{ $analysis['bmi'] }} ({{ $analysis['status'] }})</p>
                        <p class="mb-1"><strong>{{__('home.recommendation')}}:</strong> {{ $analysis['recommendation'] }}</p>
                        <hr>
                        <p class="mb-1"><strong>{{__('home.recommendedKeywords')}}:</strong></p>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach($analysis['keywords']['include'] as $keyword)
                                <span class="badge bg-success">✓ {{ ucfirst($keyword) }}</span>
                            @endforeach
                            @foreach($analysis['keywords']['exclude'] as $keyword)
                                <span class="badge bg-danger">✗ {{ ucfirst($keyword) }}</span>
                            @endforeach
                        </div>
                        <p class="mb-0 text-muted small">{{__('home.foundFoods')}}: {{ $analysis['food_count'] }} {{__('home.items')}}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Manual Search Section -->
    <div class="osahan-recommend p-3">
        <div class="d-flex align-items-center mb-3">
            <h6 class="mb-0">{{__('home.manualSearch')}}</h6>
        </div>
        <form action="{{ locale_route('food-guide.search') }}" method="POST" id="searchForm">
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-12 mb-3">
                    <div class="input-group rounded shadow-sm overflow-hidden bg-white">
                        <div class="input-group-prepend">
                            <button style="cursor: none" class="border-0 btn btn-outline-secondary text-success bg-white"><i
                                    class="icofont-search"></i></button>
                        </div>
                        <input type="text" class="shadow-none border-0 form-control ps-0" placeholder="{{__('home.searchFood')}}.."
                            aria-label="" aria-describedby="basic-addon1" id="name_food" name="name_food"
                            value="{{ request()->get('name_food') }}">
                    </div>
                </div>

                <div class="col-lg-5 col-md-12 mb-3">
                    <div class="input-group rounded shadow-sm overflow-hidden bg-white">
                        <div class="input-group-prepend">
                            <button style="cursor: none" class="border-0 btn btn-outline-secondary text-success bg-white"><i
                                    class="icofont-search"></i></button>
                        </div>
                        <select class="shadow-none border-0 form-control" name="id_categories" id="id_categories">
                            <option value="">{{__('home.searchCategories')}}..</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}" @if (request()->get('id_categories') == $item->id) selected @endif>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-3 col-md-12 mb-3">
                    <button type="submit" class="btn btn-success rounded w-100" id="searchBtn"><i
                            class="icofont-search"></i> {{__('home.search')}}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="pick_today px-3">
        <div class="row">
            @if (count($food) > 0)
                @foreach ($food as $item)
                    <div class="col-sm-12 col-md-4 col-lg-4 p-2">
                        <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image text-dark">
                                <div class="member-plan position-absolute"><span
                                        class="badge m-3 badge-primary">{{ $item->categories->name }}</span></div>
                                <div class="p-3">
                                    <img src="{{ $item->image_path != null ? Storage::url($item->image_path) : asset('assets/img/noimgfood.png') }}"
                                        class="img-fluid item-img w-100 mb-3 object-fit-fill">
                                    <h5>{{ $item->name_food }}</h5>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="price m-2"><b>{{__('home.protein')}} : </b>{{ $item->protein }}g</h6>
                                            <h6 class="price m-2"><b>{{__('home.carbohydrate')}} : </b>{{ $item->carbs }}g</h6>
                                            <h6 class="price m-2"><b>{{__('home.fiber')}} : </b>{{ $item->fiber }}g</h6>
                                            <h5 class="price m-2"><b>{{__('home.calorie')}} : </b>{{ $item->calories }}</h5>
                                        </div>
                                        <div class="ms-auto pr-2">
                                            <h6 class="price m-2"><b>{{__('home.descriptions')}} : </b></h6>
                                            <h6 class="price m-0"><?= $item->description ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                        <img src="{{ asset('') }}assets/img/no-res.jpg"
                            class="img-fluid rounded mx-auto d-block imgnores" alt="No Result">
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
