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
    <body data-topbar="dark" data-layout="horizontal" data-layout-size="boxed">
        <!-- Loader -->
        @include('backend.layout.preloader')        
        <!-- Begin page -->
        <div id="layout-wrapper">
            @include('backend.layout.top_header')   
            @include('backend.layout.navbar')      
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        @yield('content')                      
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
                @include('backend.layout.footer')                
            </div>
            <!-- end main content-->
        </div> 
        <!-- END layout-wrapper -->
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        @include('backend.modal.transaction-detail')
       <!-- @include('backend.modal.subscribe')  -->         <!-- JAVASCRIPT -->
        @include('backend.layout.js')
        @yield('js')       
    </body>

</html>