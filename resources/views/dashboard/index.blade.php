@extends('layouts.app')

@section('title', ucfirst($activeTab ?? 'Overview') . ' — AttendX Dashboard')

@section('content')
    <!-- Top KPI Metrics (rendered when stats are loaded) -->
    @if(isset($stats))
        @include('dashboard.partials.stats')
    @endif

    <!-- Dynamic View Based on Active Tab -->
    @if(($activeTab ?? 'overview') === 'employees')
        @include('dashboard.partials.employees')
    @elseif(($activeTab ?? 'overview') === 'attendance')
        @include('dashboard.partials.attendance')
    @elseif(($activeTab ?? 'overview') === 'leaves')
        @include('dashboard.partials.leaves')
    @elseif(($activeTab ?? 'overview') === 'organization')
        @include('dashboard.partials.organization')
    @else
        @include('dashboard.partials.overview')
    @endif
@endsection
