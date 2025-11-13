@extends('layouts.app')

@push('title')
    Profile
@endpush

@section('leading')
    <h5 class="fw-bold m-0">{{__('profile.profile')}}</h5>
@endsection

@section('content')
    <div class="p-4 profile text-center border-bottom">
        <img src="{{ asset('') }}assets/img/user.png" class="img-fluid rounded-pill">
        <h6 class="fw-bold m-0 mt-2">{{ auth()->user()->name }}</h6>
        <p class="small text-muted">{{ auth()->user()->email }}</p>
        <a href="{{ locale_route('profile.profile-edit') }}" class="btn btn-success btn-sm"><i class="icofont-pencil-alt-5"></i> {{__('profile.EditProfile')}} </a>
    </div>
    <div class="account-sections">
        <ul class="list-group">
            <a href="{{ locale_route('profile.profile-change-password') }}" class="text-decoration-none text-dark">
                <li class="border-bottom bg-white d-flex align-items-center p-3">
                    <i class="icofont-lock osahan-icofont bg-success"></i>{{__('profile.changePassword')}}
                    <span class="badge badge-success p-1 badge-pill ms-auto"><i class="icofont-simple-right"></i></span>
                </li>
            </a>
            <a href="{{ locale_route('profile.nutrition') }}" class="text-decoration-none text-dark">
                <li class="border-bottom bg-white d-flex align-items-center p-3">
                    <i class="icofont-culinary osahan-icofont bg-info"></i>{{__('profile.Nutrition')}}
                    <span class="badge badge-success p-1 badge-pill ms-auto"><i class="icofont-simple-right"></i></span>
                </li>
            </a>
            {{-- <a href="{{ locale_route('profile.help') }}" class="text-decoration-none text-dark">
                <li class="border-bottom bg-white d-flex align-items-center p-3">
                    <i class="icofont-phone osahan-icofont bg-warning"></i>{{ __('profile.support') }}
                    <span class="badge badge-success p-1 badge-pill ms-auto"><i class="icofont-simple-right"></i></span>
                </li>
            </a> --}}
            <a href="#" class="text-decoration-none text-dark"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <li class="border-bottom bg-white d-flex  align-items-center p-3">
                    <i class="icofont-logout osahan-icofont bg-danger"></i> {{ __('profile.logout') }}
                </li>
            </a>
        </ul>
    </div>
@endsection

@push('js')
    <script>
        //
    </script>
@endpush
