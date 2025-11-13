@extends('auth.layout')

@push('title')
    Login
@endpush

@section('content')
    <div class="p-3">
        <h2 class="my-0">{{__('login.welcome')}}</h2>
        <p class="small">{{__('login.signIntoContinue') }}.</p>
        <form action="{{ locale_route('login.login')}}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="email">{{__('login.email')}}</label>
                <input placeholder="{{__('login.email')}}" type="email" class="form-control" id="email"
                    aria-describedby="emailHelp" name="email" value="{{old('email')}}">
            </div>
            <div class="form-group mb-3">
                <label for="password">{{__('login.password')}}</label>
                <input placeholder="{{__('login.enterPassword')}}" type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-success btn-lg rounded w-100">{{__('login.signIn')}}</button>
        </form>
    </div>
@endsection

@section('footer')
    <a href="{{ locale_route('signup') }}" class="btn btn-block btn-lg bg-white">{{__('login.dontHaveAccount')}}</a>
@endsection
