
<!DOCTYPE html>
<html lang="en">
 <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>School Management System</title>
    <link rel="shortcut icon" href="{{ asset('frontend/assets/images/logo/favicon.ico') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- STYLESHEETS -->
    @include('frontend.layouts.css')    
  </head>
  <body>
    <!-- preloader start ↓-->
    @include('frontend.layouts.preloader') 
    <!-- preloader end ↑-->

    <!-- sidebar area start ↓--> 
    @include('frontend.layouts.mobile_sidebar')     
    <!-- sidebar area end ↑-->

    <!-- HEADER SECTION STARTS HERE ↓-->
    @include('frontend.layouts.top_main_header')
    <!-- HEADER SECTION ENDS HERE ↑-->

    <!-- BANNER SECTION STARTS HERE ↓-->
    @include('frontend.pages.banner')    
    <!-- BANNER SECTION ENDS HERE ↑-->

    <!-- ABOUT SECTION STARTS HERE -->
    @include('frontend.pages.about')    
    <!-- ABOUT SECTION ENDS HERE -->

    <!-- ADMISSION SECTION STARTS HERE -->
    @include('frontend.pages.admission')
    <!-- ADMISSION SECTION ENDS HERE -->

    <!-- PROGRAMS SECTION STARTS HERE -->
    @include('frontend.pages.program')
    <!-- PROGRAMS SECTION ENDS HERE -->

    <!-- RESEARCH AND INNOVATION SECTION STARTS HERE -->
    @include('frontend.pages.research-and-innovative')
    <!-- RESEARCH AND INNOVATION  SECTION ENDS HERE -->

    <!-- CAMPUS SECTION STARTS HERE -->
    @include('frontend.pages.campus')    
    <!-- CAMPUS SECTION ENDS HERE -->

    <!-- TESTIMONIAL SECTION STARTS HERE -->
    @include('frontend.pages.testimonial')    
    <!-- TESTIMONIAL SECTION ENDS HERE -->

    <!-- BLOGS SECTION STARTS HERE -->
    @include('frontend.pages.blog')    
    <!-- BLOGS SECTION ENDS HERE -->

    <!-- SOCIAL HANDLES SECTION STARTS HERE --> 
    @include('frontend.pages.social')    
    <!-- SOCIAL HANDLES SECTION ENDS HERE -->

    <!-- FOOTER SECTION STARTS HERE -->
    @include('frontend.layouts.footer')
    <!-- FOOTER SECTION STARTS HERE -->

    <!-- JS FILES ↓ -->
    @include('frontend.layouts.js')
    <script src="{{ asset('common/js/swal.js') }}"></script> 
    <script src="{{ asset('common/js/ajax.js') }}"></script> 
    @stack('scripts')
    @include('frontend.modal.login')  
    @include('frontend.modal.forgot_password')  
    
  </body>
</html>