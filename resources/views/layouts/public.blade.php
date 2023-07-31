<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/all.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <script>
        //used in main.js for browser compatibility
        var loadingGIF = "{{asset('assets/images/gears.gif')}}";
        var loadingSVG = "{{asset('assets/images/gears.svg')}}";
    </script>
    <script src="{{ asset('js/all.js') }}"></script>
</head>

<body>
    <div class="container clearfix">
        @yield('content')
    </div>

    <div class="container">
        @include('partials.layout-footer')
    </div>
</body>

</html>
