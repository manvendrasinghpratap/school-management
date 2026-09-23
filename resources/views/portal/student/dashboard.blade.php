@extends('portal.layouts.app',['title'=>'Student Dashboard'])
@section('content')
<h2>Student Dashboard</h2><p class="text-muted">Welcome, {{ $student->full_name }}.</p>
@include('portal.partials.cards',['cards'=>[
 ['label'=>'Attendance Records','value'=>$attendance['total']], ['label'=>'Present','value'=>$attendance['present']], ['label'=>'Absent','value'=>$attendance['absent']], ['label'=>'Outstanding Fees','value'=>number_format($outstanding,2)],
]])
<div class="card shadow-sm"><div class="card-body"><h5>Current Enrollment</h5>@if($enrollment)<p class="mb-1">{{ $enrollment->academicYear?->name }} · {{ $enrollment->class?->name }} · {{ $enrollment->section?->name }}</p>@else<p>No active enrollment found.</p>@endif</div></div>
@endsection
