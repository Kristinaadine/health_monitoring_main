
<!-- Sidebar starts -->
<div class="sidebar">
    <!-- Logo DIH -->
    <a href="" class="logo">
        <img src="{{ asset('') }}assets/img/logo/{{$setting->where('key', 'website_logo')->first()->value}}" style="width: 20%" alt="" class="logo-icon">
        <div class="logo-text">
            <h5 class="fs22 mb-0">{{$setting->where('key', 'website_name')->first()->value}}</h5>
            <p class="text-uppercase fs11">Admin Dashboard</p>
        </div>
    </a>
    <!-- Logo DIH ends -->

    <!-- Home Page sidebar-->
    {{-- <h6 class="subtitle fs11">Home Page</h6> --}}
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ locale_route('administration.home') }}"><i class="material-icons icon">dashboard</i><span>Dashboard</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ locale_route('administration.food-categories.index') }}"><i class="material-icons icon">room_service</i><span>Food Categories</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ locale_route('administration.food.index') }}"><i class="material-icons icon">local_dining</i><span>Food</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ locale_route('administration.nutrient.index') }}"><i class="material-icons icon">grain</i><span>Nutrient Ratio</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ locale_route('administration.user.index') }}"><i class="material-icons icon">person</i><span>User</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ locale_route('administration.setting.index') }}"><i class="material-icons icon">settings</i><span>Setting</span></a>
        </li>
    </ul>
</div>
<!-- Sidebar ends -->
