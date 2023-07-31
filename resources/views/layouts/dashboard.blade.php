<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="{{ asset('css/all.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}" />
    <link rel="stylesheet" href="{{ asset('custom-update.css') }}">
    <script>
        //used in main.js for browser compatibility
        var loadingGIF = "{{asset('assets/images/gears.gif')}}";
        var loadingSVG = "{{asset('assets/images/gears.svg')}}";
    </script>
    <script src="{{ asset('js/all.js') }}"></script>
</head>

<body>

    <div class="se-pre-con">
        <div class="load">
            <img id="loading" alt="">
            <h2 class="building">Building your report</h2>
        </div>
    </div>
    <!-- start wrapper -->
    <div class="wrapper clearfix">
        <!-- start navbar -->
        <nav class="navbar navbar-light bg-faded user-navbar">
            <div class="top-nav desktop">
                <ul>
                    <li><a href="{{ route('auth.profile.view') }}">My Profile</a></li>
                    <li><a href="{{ route('auth.login.logout') }}">Logout</a></li>
                </ul>
            </div>
        </nav>

        <nav class="navbar navbar-toggleable-md navbar-light navbar-right bg-faded menu-navbar">
            <a class="navbar-brand" href="{{ route('dashboard.home.index') }}"><img src="{{ asset('/assets/images/logo2.png') }}" alt="" class="img-fluid"></a>
            <a class="fa-menu"><i class="fa fa-bars menu-mob" aria-hidden="true"></i></a>
            <div class="collapse navbar-collapse">
                <div class="navbar-nav desktop" style="width: 100%;">
                    <a class="nav-item nav-link {{ Arr::get(request()->route()->getAction(), 'as') == 'dashboard.home.index' ? 'active' : '' }}" href="{{ route('dashboard.home.index') }}"><span>Home</span> <span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link {{ Arr::get(request()->route()->getAction(), 'as') == 'dashboard.reports.index' ? 'active' : '' }}" href="{{ route('dashboard.reports.index') }}">Reports</a>
                    <a class="nav-item nav-link {{ strpos(Route::currentRouteName(), 'dashboard.invoices') === 0 ? 'active' : '' }}" href="{{ route('dashboard.invoices.index', [ 'newSearch' => true ]) }}">Invoices</a>
                    <a class="nav-item nav-link {{ \Illuminate\Support\Str::startsWith(Route::current()->uri(), 'dashboard/inventory') ? 'active' : '' }}" href="{{ route('dashboard.inventory.index', [ 'newSearch' => true ]) }}">Inventory</a>
                    @can('edit')<a class="nav-item nav-link {{ strpos(Route::currentRouteName(), 'dashboard.orders') === 0 ? 'active' : '' }}" href="{{ route('dashboard.orders.index') }}">Orders</a>@endcan
                    @can('edit')<a class="nav-item nav-link {{ strpos(Route::currentRouteName(), 'dashboard.carriers') === 0 ? 'active' : '' }}" href="{{ route('dashboard.carriers.index') }}">Carriers</a>@endcan
                    @can('edit')<a class="nav-item nav-link {{ strpos(Route::currentRouteName(), 'dashboard.settings') === 0 ? 'active' : '' }}" href="{{ route('dashboard.settings.index') }}">Settings</a>@endcan
                    @can('edit')<a class="nav-item nav-link {{ strpos(Route::currentRouteName(), 'dashboard.users') === 0 ? 'active' : '' }}" href="{{ route('dashboard.users.index') }}">Users</a>@endcan
                </div>
            </div>

            <div class="mobile">
                <a class="nav-item nav-link" href="{{ route('auth.profile.view') }}"><span>My Profile</span></a>
                <a class="nav-item nav-link" href="{{ route('auth.login.logout') }}"><span>Logout</span></a>
                <a class="nav-item nav-link {{ Arr::get(request()->route()->getAction(), 'as') == 'dashboard.home.index' ? 'active' : '' }}" href="{{ route('dashboard.home.index') }}"><span>Home</span> <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link {{ Arr::get(request()->route()->getAction(), 'as') == 'dashboard.reports.index' ? 'active' : '' }}" href="{{ route('dashboard.reports.index') }}">Reports</a>
                <a class="nav-item nav-link {{ strpos(Route::currentRouteName(), 'dashboard.invoices') === 0 ? 'active' : '' }}" href="{{ route('dashboard.invoices.index', [ 'newSearch' => true ]) }}">Invoices</a>
                <a class="nav-item nav-link {{ \Illuminate\Support\Str::startsWith(Route::current()->uri(), 'dashboard/inventory') ? 'active' : '' }}" href="{{ route('dashboard.inventory.index', [ 'newSearch' => true ]) }}">Inventory</a>
                @can('edit')<a class="nav-item nav-link {{ strpos(Route::currentRouteName(), 'dashboard.orders') === 0 ? 'active' : '' }}" href="{{ route('dashboard.orders.index') }}">Orders</a>@endcan
                @can('edit')<a class="nav-item nav-link {{ strpos(Route::currentRouteName(), 'dashboard.carriers') === 0 ? 'active' : '' }}" href="{{ route('dashboard.carriers.index') }}">Carriers</a>@endcan
                @can('edit')<a class="nav-item nav-link {{ strpos(Route::currentRouteName(), 'dashboard.settings') === 0 ? 'active' : '' }}" href="{{ route('dashboard.settings.index') }}">Settings</a>@endcan
                @can('edit')<a class="nav-item nav-link {{ strpos(Route::currentRouteName(), 'dashboard.users') === 0 ? 'active' : '' }}" href="{{ route('dashboard.users.index') }}">Users</a>@endcan
            </div>
        </nav>
        <!-- end navbar -->

        @if(session()->has('notification.success'))
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="col-xs-12">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <p class="mb-0">
                            <img src="{{ asset('assets/images/star-msg.png') }}" alt="" class="mr-2">
                            {{ session()->get('notification.success') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(session()->has('notification.error'))
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="col-xs-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <p class="mb-0">
                            <img src="{{ asset('assets/images/star-msg.png') }}" alt="" class="mr-2">
                            {{ session()->get('notification.error') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- begin content -->
        @yield('content')
        <!-- end content -->

    </div>
    <!-- end wrapper -->

    <!-- start footer -->
    @include('partials.layout-footer')
    <!-- end footer -->
    <script src="{{ asset('custom-update.js') }}" defer></script>
</body>

</html>
