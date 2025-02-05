@extends('layouts.app')

@section('content')

<div class="row m-3">
    @if (session('msg'))
    <div class="alert alert-primary" role="alert">
        {{ session('msg') }}
    </div>
    @endif
    <div class="row m-3">
        <div class="d-flex justify-content-center">
            <h1>{{__('attendance.attendanceRecordTable')}}</h1>
        </div>
    </div>
    <div class="row">
        <h3 class="col-auto d-flex align-items-center">
            {{ $query ?? '' }}
        </h3>
        <div class="col-auto">
            <form action="{{ route('attendance.search') }}" method="get">
                @csrf
                <input type="month" class="form-control" name="yearAndMonth" min="2024-01" max="2030-12" onchange="this.form.submit()">
            </form>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>{{ __("attendance.clockInTime") }}</th>
                <th>{{ __("attendance.clockOutTime") }}</th>
                <th>{{ __("attendance.breakTime") }}</th>
                <th>{{ __("attendance.workingTime") }}</th>
                <th>{{ __("attendance.nightWorkingTime") }}</th>
                <th>{{ __("attendance.overtime") }}</th>
                <th>{{ __("attendance.nightOvertime") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
            <tr>

                @php
                $clock_in = \Carbon\Carbon::parse($attendance->clock_in, 'UTC');
                $clock_out = \Carbon\Carbon::parse($attendance->clock_out, 'UTC');
                @endphp

                <td>{{ $clock_in->timezone(config('app.timezone'))->format('m-d H:i') }}</td>
                <td>{{ $clock_out->timezone(config('app.timezone'))->format('m-d H:i') }}</td>
                <td> @if ($attendance->break_times->isNotEmpty())
                    @foreach ($attendance->break_times as $break)

                    @php
                    $break_start = \Carbon\Carbon::parse($break->break_start, 'UTC');
                    $break_end = \Carbon\Carbon::parse($break->break_end, 'UTC');
                    @endphp

                    {{ $break_start->setTimezone(config('app.timezone'))->format('H:i') }}~{{ $break_end->format('H:i') }}<br>
                    @endforeach
                    @else
                    0
                    @endif
                </td>
                <td>{{ $attendance->working_time->total_working != null ? \Carbon\Carbon::now()->startOfDay()->addMinutes($attendance->working_time->total_working)->format('H:i') : 0 }}</td>
                <td>{{ $attendance->working_time->night_working != null ? \Carbon\Carbon::now()->startOfDay()->addMinutes($attendance->working_time->night_working)->format('H:i') : 0 }}</td>
                <td>{{ $attendance->working_time->overtime != null ? \Carbon\Carbon::now()->startOfDay()->addMinutes($attendance->working_time->overtime)->format('H:i') : 0 }}</td>
                <td>{{ $attendance->working_time->night_overtime != null ? \Carbon\Carbon::now()->startOfDay()->addMinutes($attendance->working_time->night_overtime)->format('H:i') : 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $attendances->links() }}
    </div>

</div>


@endsection