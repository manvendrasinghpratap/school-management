<!doctype html>
<html lang="en">
    <head>        
        <meta charset="utf-8" />
        <title>@yield('title', 'Manvendra Horizontal Layout | Skote - Admin & Dashboard Template')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('backend/assets/images/favicon.ico')}}">
        @include('backend.layout.css')
        @yield('css') 
        <script src="{{asset('backend/assets/js/plugin.js')}}"></script>
    </head>
    <body>
        @yield('content')
        @include('backend.layout.js')
        @stack('scripts')       
    </body>
</html>