@extends('layouts.app')

@push('title')
    Profile
@endpush

@section('leading')
    <a class="fw-bold text-success text-decoration-none" onclick="history.back()" href="#">
        <i class="icofont-rounded-left back-page"></i></a>
    <h6 class="fw-bold m-0 ms-3">{{__('login.changePassword')}}</h6>
@endsection

@section('content')
    <div id="edit_profile">
        <div class="p-3">
            <form action="{{ locale_route('profile.profile-change-password-update') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="current_password">{{__('login.currentPassword')}} <span class="text-danger">*</span></label>
                    <input type="password" placeholder="{{__('login.enterCurrentPassword')}}"
                        class="form-control @error('current_password')  is-invalid @enderror" id="current_password"
                        name="current_password">
                    @error('current_password')
                        <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="new_password">{{__('login.newPassword')}} <span class="text-danger">*</span></label>
                    <input type="password" placeholder="{{__('login.enterNewPassword')}}"
                        class="form-control @error('new_password') is-invalid @enderror" id="new_password"
                        name="new_password">
                    @error('new_password')
                        <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-center d-grid">
                    <button type="submit" class="btn btn-success btn-lg">{{__('login.saveChanges')}}</button>
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
