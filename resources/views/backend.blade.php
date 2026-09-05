@extends('backend.layout.default') 
@section('content')
<!-- start page title -->
@include('backend.layout.page_title')                         
<!-- end page title -->
@include('backend.layout.welcome')    
<!-- end row -->
@include('backend.layout.social') 
<!-- end row -->
@include('backend.layout.latest_transaction')                         
<!-- end row -->  
@endsection