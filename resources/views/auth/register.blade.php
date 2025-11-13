@extends('auth.layout')

@push('title')
    Register
@endpush

@section('content')
    <div class="p-3">
        <h2 class="my-0">{{__('login.getStarted')}}</h2>
        <p>{{__('login.createNewAccount')}}</p>
        <form action="{{ locale_route('signup.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="name">{{__('login.name')}} <span class="text-danger">*</span></label>
                <input placeholder="{{__('login.enterName')}}" type="text"
                    class="form-control @error('name')
                is-invalid
                @enderror"
                    id="name" aria-describedby="emailHelp" name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="phone">{{__('login.phone')}}</label>
                <input placeholder="{{__('login.enterPhone')}}" type="number"
                    class="form-control @error('phone')
                is-invalid
                @enderror"
                    id="phone" aria-describedby="emailHelp" name="phone" value="{{ old('phone') }}">
                @error('phone')
                    <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="email">{{__('login.email')}} <span class="text-danger">*</span></label>
                <input placeholder="{{__('login.enterEmail')}}" type="email"
                    class="form-control @error('email')
                is-invalid
                @enderror"
                    id="email" aria-describedby="emailHelp" name="email" value="{{ old('email') }}">
                @error('email')
                    <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="password">{{__('login.password')}} <span class="text-danger">*</span></label>
                <input placeholder="{{__('login.enterPassword')}}" type="password"
                    class="form-control @error('password')
                is-invalid
                @enderror"
                    id="password" name="password">
                @error('password')
                    <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="password2">{{__('login.confirmPassword')}} <span class="text-danger">*</span></label>
                <input placeholder="{{__('login.enterConfirmPassword')}}" type="password"
                    class="form-control @error('password2')
                is-invalid
                @enderror"
                    id="password2" name="password2">
                @error('password2')
                    <div class="text-danger" style="font-size: 12px">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success rounded btn-lg w-100">{{__('login.createAccount')}}</button>
        </form>
    </div>
@endsection

@section('footer')
    <a href="{{ locale_route('login') }}" class="btn btn-block btn-lg bg-white">{{__('login.haveAnAccount')}}</a>
@endsection
