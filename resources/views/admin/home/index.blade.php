@extends('layouts-admin.master')
@push('title')
    Dashboard
@endpush
@section('content')
    <div class="container main-container" id="main-container">
        <h3 class="font-weight-light text-center mt-3">Welcome to <span class="text-template-primary">{{$setting->where('key', 'website_name')->first()->value}}</span></h3>
        <p class="text-template-primary text-center mb-5">This is the administrator of {{$setting->where('key', 'website_name')->first()->value}}</p>
    </div>
@endsection
