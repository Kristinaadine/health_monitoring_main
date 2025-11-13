@extends('layouts.app')

@push('title')
    {{ __('general.stunting_information') }}
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#">
        <i class="icofont-rounded-left back-page"></i>
    </a>
    <span class="fw-bold ms-3 h6 mb-0">{{ __('general.stunting_information') }}</span>
@endsection

@section('content')
    <div id="basics">
        <div class="mt-0 p-3">
            <h4 class="fw-semibold m-0">
                {{ __('general.understanding_stunting_in_children') }}
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE+UlEQVR4nO2Ze0xbVRzHS6eLMeJ7mrjEP1TG1AR1zrnEGUZc1sImSwYFRGBThMGQRBNxLYLntJGnqJm6RMI/k6k8VIxLBk6XkC0MlHPKq2Ub/jHjUAaZjsg2aUH4mnNvKY/2Ql9UZvpLvn/c0/P4fM/73qpUoQhFKEKxVBxI0D6h12lOkOjoG1TXWxgStFGGRM1lg06LA0nbE1QrNMIK4uPDFybqdLpVBp3WKuD1idqGFTsCRKdbLUHqNKOGRM1hMWVEukGnSZLTtT+TPdE3qVZyGBI1lwSsA3hSr4tNFr0up2nyVNdDFOp2rDUkag86oMf0iZoLkqHk2HWqlR7oJrej05gARqsvthSM/nnCAHtHsSQwch6MtIOTT2Amu0XemXKkfzLOaJ0YlNQ/GRt88J9Mj4LRI+B0EpzCQ02Ck1pR1gEPIWqduBA88L6yO8Dp52Bk2gvw+WJ0qsI6dm3GgNFivxgceG58Fpz+6jP4HB3vaZPhZelF/dUcN1KrvYJa7ENCRou9vLERqwIFrwEjtkDAg1MMmmsk+Ppe69Rkb2m8aINaJ2rmmJoZnfIAwJPt4HQ8UPDgFDZehmO9HZjiJvE8ftrcnE0t9mk3BvybXjCT+8DI5UDCw41aek7bXODFArfYh/zt/eblhgenqO4bdIF3jECp7/DMuCsY8OAUJda/F/b8HwLer0UMTluDAX/FXCVBH+kbwMnuH86SPtt6Aqh9BnceVEHq/WvmSmlXkp6l84Ws9wte7n3yxlINj7cVoevTXLQeysTZxnxMdxKXPCLtTEM+vj+4F/xwDmztxR6YIq/5b4DRBqUGphhBU9mLSNmyAfEbo5zqqt3vkre/Pn9enoyYTWgqTcU/bszOMfCFf/Cdpkgw8pvb+XqqEPqUGCfQ6wnRqMrdgbIsLaaZe6gP8naiYl8cCpK2OssVp23DlZOFSiYG0WWK8B4cRA1O3l/sntP8XoYEkB7zlNseX0qWujy8otks1SFGUTGvYGD0XUAV5rkBRnI8gThWlY6R42/6vGhHW/U4WpnmWX4zyfbcACd9gd5h/Bajvd6MwNVANTzdXoThjGcwcHc4BtaEY2TPFkyLlxyvDZCr/8kICPh+lWqehAkfDPR4YYBmBcrAwF23uBgQaT5MoUzPDUAVBk4rxBuTYoUf7pa1RMMz0C1qNb5Tq53P8+r5aJF6ZIYyr3Yhp5EuUwQ4GXNb8d6HgeQHgLpMjwx8o1ZLmmeg7mW5jpceUSr/l0/nwDwTjHzmtvKaF+TG0yOBo3neG2jKAdLXyXXUpCiUJ7V+wTsMbFLsYdM2GSD1IaA6GXBzLVho4Iw6DMORtwGpEXJZ+pxYoEoGNvttQDZBv3bbgAAu1cggQvsek+f0t3lAm3w9EPDnVqtx6p6bcS7qTkw8f/9s/lIt0Pm2EnxjQOAlA10la8DoiOJI1GcC+zfMgs0o5UEgaUGaUO7jchmuuHYuoaPk3oAZkExwshOM2pV3CwJ8lQ2UxwH5G4G0yFlgMV1efVL+7cusRaYMFbuOHYzEBRTeaYKROK8+qQhQpjRFlOCNu5YFfo6JrWD0F4+hPBY5Lz6YLSu808SP5FYwemjRKeX5KNnAyMdoq3D5g2T5jXS/s1a6q3My5D04/R2MVIrvTUEHd/8CZHwajBQ5XkEtjl1LfMUbByfDjrQGMPqWdLb4cjUIRShC8f+LfwE2bN5Mbjg1MQAAAABJRU5ErkJggg=="
                    alt="child-tasty">
            </h4>
        </div>

        <div id="basicsAccordion">
            <div class="box border-bottom border-top bg-white">
                <div id="basicsHeadingOne">
                    <h5 class="mb-0 d-grid">
                        <button class="shadow-none border-0 btn d-flex justify-content-between card-btn p-3 collapsed"
                            data-bs-toggle="collapse" data-bs-target="#basicsCollapseOne" aria-expanded="false"
                            aria-controls="basicsCollapseOne" style="font-size: 20px">
                            {{ __('general.what_is_stunting') }}
                        </button>
                    </h5>
                </div>
                <div id="basicsCollapseOne" class="collapse show" aria-labelledby="basicsHeadingOne"
                    data-bs-parent="#basicsAccordion">
                    <div class="card-body border-top p-3 text-muted" style="font-size: 16px">
                        {{ __('general.what_is_stunting_desc') }}
                    </div>
                </div>
            </div>

            <div class="box border-bottom bg-white">
                <div id="basicsHeadingTwo">
                    <h5 class="mb-0 d-grid">
                        <button class="shadow-none border-0 btn d-flex justify-content-between card-btn p-3 collapsed"
                            data-bs-toggle="collapse" data-bs-target="#basicsCollapseTwo" aria-expanded="false"
                            aria-controls="basicsCollapseTwo" style="font-size: 20px">
                            {{ __('general.key_characteristics_of_stunting') }}
                        </button>
                    </h5>
                </div>
                <div id="basicsCollapseTwo" class="collapse" aria-labelledby="basicsHeadingTwo"
                    data-bs-parent="#basicsAccordion">
                    <div class="card-body border-top p-3 text-muted" style="font-size: 16px">
                        <ul>
                            <li>{{ __('general.slow_growth') }}</li>
                            <li>{{ __('general.younger_facial_appearance') }}</li>
                            <li>{{ __('general.weight_not_increase') }}</li>
                            <li>{{ __('general.difficulty_focusing') }}</li>
                            <li>{{ __('general.quieter_tendency') }}</li>
                            <li>{{ __('general.slower_tooth_growth') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="box border-bottom bg-white">
                <div id="basicsHeadingThree">
                    <h5 class="mb-0 d-grid">
                        <button class="shadow-none border-0 btn d-flex justify-content-between card-btn p-3 collapsed"
                            data-bs-toggle="collapse" data-bs-target="#basicsCollapseThree" aria-expanded="false"
                            aria-controls="basicsCollapseThree" style="font-size: 20px">
                            {{ __('general.prevention_and_management') }}
                        </button>
                    </h5>
                </div>
                <div id="basicsCollapseThree" class="collapse" aria-labelledby="basicsHeadingThree"
                    data-bs-parent="#basicsAccordion">
                    <div class="card-body border-top p-3 text-muted">
                        <div class="row">
                            <div class="col-6">
                                <div class="rounded shadow bg-success d-flex align-items-center p-3 text-white">
                                    <div class="more">
                                        <h6 class="m-0">{{ __('general.during_pregnancy') }}</h6>
                                        <ul>
                                            <li>{{ __('general.adequate_maternal_nutrition') }}</li>
                                            <li>{{ __('general.regular_prenatal_checkups') }}</li>
                                            <li>{{ __('general.iron_and_folic_supplement') }}</li>
                                            <li>{{ __('general.proper_rest_and_exercise') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="rounded shadow bg-success d-flex align-items-center p-3 text-white">
                                    <div class="more">
                                        <h6 class="m-0">{{ __('general.early_childhood') }}</h6>
                                        <ul>
                                            <li>{{ __('general.exclusive_breastfeeding') }}</li>
                                            <li>{{ __('general.proper_complementary_feeding') }}</li>
                                            <li>{{ __('general.regular_growth_monitoring') }}</li>
                                            <li>{{ __('general.immunization_healthcare') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box border-bottom bg-white">
                <div id="basicsHeadingFour">
                    <h5 class="mb-0 d-grid">
                        <button class="shadow-none border-0 btn d-flex justify-content-between card-btn collapsed p-3"
                            data-bs-toggle="collapse" data-bs-target="#basicsCollapseFour" aria-expanded="false"
                            aria-controls="basicsCollapseFour" style="font-size: 20px">
                            {{ __('general.essential_nutrients') }}
                        </button>
                    </h5>
                </div>
                <div id="basicsCollapseFour" class="collapse" aria-labelledby="basicsHeadingFour"
                    data-bs-parent="#basicsAccordion">
                    <div class="card-body border-top p-3 text-muted" style="font-size: 16px">
                        <div class="row">
                            <div class="col-4">
                                <div class="rounded shadow border border-success d-flex align-items-center p-3">
                                    <div class="more">
                                        <h6 class="m-0 text-dark">{{ __('general.protein') }}</h6>
                                        <p>{{ __('general.protein_desc') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="rounded shadow border border-success d-flex align-items-center p-3">
                                    <div class="more">
                                        <h6 class="m-0 text-dark">{{ __('general.iron') }}</h6>
                                        <p>{{ __('general.iron_desc') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="rounded shadow border border-success d-flex align-items-center p-3">
                                    <div class="more">
                                        <h6 class="m-0 text-dark">{{ __('general.vitamin_a') }}</h6>
                                        <p>{{ __('general.vitamin_a_desc') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box border-bottom mb-2 bg-white">
                <div id="basicsHeadingFive">
                    <h5 class="mb-0 d-grid">
                        <button class="shadow-none border-0 btn d-flex justify-content-between card-btn collapsed p-3"
                            data-bs-toggle="collapse" data-bs-target="#basicsCollapseFive" aria-expanded="false"
                            aria-controls="basicsCollapseFive" style="font-size: 20px">
                            {{ __('general.when_to_seek_help') }}
                        </button>
                    </h5>
                </div>
                <div id="basicsCollapseFive" class="collapse" aria-labelledby="basicsHeadingFive"
                    data-bs-parent="#basicsAccordion">
                    <div class="card-body border-top p-3 text-muted">
                        <div class="rounded shadow bg-info d-flex align-items-center p-3 text-white">
                            <div class="more">
                                <h6 class="m-0">{{ __('general.consult_if_you_notice') }}</h6>
                                <ul>
                                    <li>{{ __('general.child_height_lower') }}</li>
                                    <li>{{ __('general.delayed_development') }}</li>
                                    <li>{{ __('general.poor_appetite') }}</li>
                                    <li>{{ __('general.frequent_infections') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection