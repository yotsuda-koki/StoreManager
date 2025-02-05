@extends('layouts.app')

@section('content')

<div class="row m-3">
    @if (session('msg'))
    <div class="alert alert-primary" role="alert">
        {{ session('msg') }}
    </div>
    @endif
    <div>
        <h1>{{ $query }}</h1>
    </div>
    <table class="table">
        <thead>
            <tr>
                <td>{{ __('attendance.employeeName') }}</td>
                <td>{{ __('attendance.totalWorkingTime') }}</td>
                <td>{{ __('attendance.totalNightWorkingTime') }}</td>
                <td>{{ __('attendance.totalOvertime') }}</td>
                <td>{{ __('attendance.totalNightOvertime') }}</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>
                    @php
                    $totalWorkingTime = 0;
                    @endphp
                    @foreach ($user->attendances as $attendance)
                    @if (strpos($attendance->clock_in, $query) !== false)
                    @php
                    $totalWorkingTime += $attendance->working_time->total_working
                    @endphp
                    @endif
                    @endforeach
                    {{ convertToHoursAndMinutes($totalWorkingTime) }}
                </td>

                <td>
                    @php
                    $totalNightWorkingTime = 0;
                    @endphp
                    @foreach ($user->attendances as $attendance)
                    @if (strpos($attendance->clock_in, $query) !== false)
                    @php
                    $totalNightWorkingTime += $attendance->working_time->night_working
                    @endphp
                    @endif
                    @endforeach
                    {{ convertToHoursAndMinutes($totalNightWorkingTime) }}
                </td>
                <td>
                    @php
                    $totalOvertime = 0;
                    @endphp
                    @foreach ($user->attendances as $attendance)
                    @if (strpos($attendance->clock_in, $query) !== false)
                    @php
                    $totalOvertime += $attendance->working_time->overtime
                    @endphp
                    @endif
                    @endforeach
                    {{ convertToHoursAndMinutes($totalOvertime) }}
                </td>
                <td>
                    @php
                    $totalNightOvertime = 0;
                    @endphp
                    @foreach ($user->attendances as $attendance)
                    @if (strpos($attendance->clock_in, $query) !== false)
                    @php
                    $totalNightOvertime += $attendance->working_time->night_overtime
                    @endphp
                    @endif
                    @endforeach
                    {{ convertToHoursAndMinutes($totalNightOvertime) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@php
function convertToHoursAndMinutes($minutes) {
$hours = floor($minutes / 60);
$minutes %= 60;
return sprintf('%d:%02d', $hours, $minutes);
}
@endphp