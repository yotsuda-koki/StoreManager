<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    @php
    if(\App\Models\User::where('id', auth()->id())->exists()){
    $role = \App\Models\User::where('id', auth()->id())->first()->role;
    }
    @endphp
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    @if(isset($role))
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @if ($role == 2)
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('sale.index') }}">{{ __('nav.sale') }}</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.product') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('product.index') }}">{{ __('nav.productList') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('product.create') }}">{{ __('nav.productAdd') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.inventory') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('inventory.index') }}">{{ __('nav.inventoryList') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('order.plan') }}">{{ __('nav.orderList') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('order.receive') }}">{{ __('nav.orderReceive') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.customer') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('customer.index') }}">{{ __('nav.customerList') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.create') }}">{{ __('nav.customerAdd') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.attendance') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('attendance.clock') }}">{{ __('nav.stamping') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('attendance.table') }}">{{ __('nav.attendanceRecord') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('attendance.select') }}">{{ __('nav.attendanceEdit') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('attendance.total') }}">{{ __('nav.workingTimeRecord') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('data.index') }}">{{ __('nav.data') }}</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.settings') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('user.index') }}">{{ __('nav.userManagement') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.parsonal') }}">{{ __('nav.mailAndPassEdit') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('system.index') }}">{{ __('nav.systemEdit') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('tax.index') }}">{{ __('nav.setTaxRateChangeSchedule') }}</a></li>
                            </ul>
                        </li>
                        @elseif ($role == 1)
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('sale.index') }}">{{ __('nav.sale') }}</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.product') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('product.index') }}">{{ __('nav.productList') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('product.create') }}">{{ __('nav.productAdd') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.inventory') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('inventory.index') }}">{{ __('nav.inventoryList') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('order.plan') }}">{{ __('nav.orderList') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('order.receive') }}">{{ __('nav.orderReceive') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.customer') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('customer.index') }}">{{ __('nav.customerList') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.create') }}">{{ __('nav.customerAdd') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.attendance') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('attendance.clock') }}">{{ __('nav.stamping') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('attendance.table') }}">{{ __('nav.attendanceRecord') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('data.index') }}">{{ __('nav.data') }}</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.settings') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('user.parsonal') }}">{{ __('nav.mailAndPassEdit') }}</a></li>
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('sale.index') }}">{{ __('nav.sale') }}</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.attendance') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('attendance.clock') }}">{{ __('nav.stamping') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('attendance.table') }}">{{ __('nav.attendanceRecord') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.settings') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('user.parsonal') }}">{{ __('nav.mailAndPassEdit') }}</a></li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                    @endif

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>