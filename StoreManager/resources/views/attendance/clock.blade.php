@extends('layouts.app')

@section('content')

<div class="row m-3">
    @if (session('msg'))
    <div class="alert alert-primary" role="alert">
        {{ session('msg') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif
    <div class="row m-3">
        <div id="clock" class="d-flex justify-content-center fs-1"></div>
    </div>
    <div class="row m-3">
        <div class="d-flex justify-content-center">
            <div class="col-1">
                @if (!isset($isActive))
                <a href=" {{ route('attendance.in') }}" class="btn btn-outline-primary">{{ __('attendance.clockIn') }}</a>
                @else
                <button class="btn btn-secondary" disabled>{{ __('attendance.clockIn') }}</button>
                @endif
            </div>
            <div class="col-1">
                @if (isset($isActive))
                <a href="{{ route('attendance.out') }}" class="btn btn-outline-danger">{{ __('attendance.clockOut') }}</a>
                @else
                <button class="btn btn-secondary" disabled>{{ __('attendance.clockOut') }}</button>
                @endif
            </div>
            <div class="col-1">
                @if (!isset($isBreak) && isset($isActive))
                <a href="{{ route('attendance.start') }}" class="btn btn-outline-success">{{ __('attendance.breakStart') }}</a>
                @else
                <button class="btn btn-secondary" disabled>{{ __('attendance.breakStart') }}</button>
                @endif
            </div>
            <div class="col-1">
                @if (isset($isBreak) && isset($isActive))
                <a href="{{ route('attendance.end') }}" class="btn btn-outline-warning">{{ __('attendance.breakEnd') }}</a>
                @else
                <button class="btn btn-secondary" disabled>{{ __('attendance.breakEnd') }}</button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    var timezone = @json($timezone);
</script>
<script src="{{ asset('/js/clock.js') }}"></script>


@endsection