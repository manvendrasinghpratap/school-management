@extends('portal.layouts.app',['title'=>'Teacher Profile'])
@section('content')<h2>Teacher Profile</h2><div class="card"><div class="card-body"><h4>{{ $staff->full_name }}</h4><p>Staff Number: {{ $staff->staff_number }}</p><p>Department: {{ $staff->department?->name }}</p><p>Phone: {{ $staff->phone }}</p><p>Status: {{ ucfirst($staff->status) }}</p></div></div>@endsection
