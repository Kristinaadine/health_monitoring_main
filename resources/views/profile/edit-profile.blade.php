@extends('layouts.app')

@push('title')
    Profile
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#">
        <i class="icofont-rounded-left back-page"></i></a>
    <h6 class="fw-bold m-0 ms-3">{{__('profile.EditProfile')}}</h6>
@endsection

@section('content')
    <div id="edit_profile">
        <div class="p-4 profile text-center border-bottom">
            <img src="{{ asset('') }}assets/img/user.png" class="img-fluid rounded-pill">
            <h6 class="fw-bold m-0 mt-2">{{ auth()->user()->name }}</h6>
            <p class="small text-muted m-0">{{ auth()->user()->email }}</p>
        </div>
        <div class="p-3">
            <form action="{{ locale_route('profile.profile-update') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">{{__('profile.fullName')}} <span class="text-danger">*</span></label>
                    <input type="text"
                        class="form-control @error('name')
                    is-invalid
                    @enderror"
                        id="name" name="name" value="{{ auth()->user()->name }}">
                    @error('name')
                        <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="phone">{{__('profile.mobile')}}</label>
                    <input type="number" class="form-control" id="phone" name="phone"
                        value="{{ auth()->user()->phone ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="email">{{__('profile.email')}} <span class="text-danger">*</span></label>
                    <input type="email"
                        class="form-control @error('email')
                    is-invalid
                    @enderror"
                        id="email" name="email" value="{{ auth()->user()->email }}">
                    @error('email')
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
