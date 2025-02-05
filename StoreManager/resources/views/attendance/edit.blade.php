@extends('layouts.app')

@section('content')

<div class="row m-3">
    @if (session('msg'))
    <div class="alert alert-primary" role="alert">
        {{ session('msg') }}
    </div>
    @endif
    <div class="row m-3">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __("attendance.clockInTime") }}</th>
                    <th>{{ __("attendance.clockOutTime") }}</th>
                    <th>{{ __("attendance.breakTime") }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $attendance)

                @php
                $clock_in = \Carbon\Carbon::parse($attendance->clock_in, 'UTC');
                $clock_out = \Carbon\Carbon::parse($attendance->clock_out, 'UTC');
                @endphp

                <tr>
                    <form action="{{ route('attendance.update') }}" method="post">
                        <div class="row">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                            <td class="col-3">
                                <div class="my-3">
                                    <input type="datetime-local" name="clock_in" class="form-control" value="{{ $clock_in->timezone(config('app.timezone'))->format('Y-m-d\TH:i') }}">
                                </div>
                            </td>
                            <td class="col-3">
                                <div class="my-3">
                                    <input type="datetime-local" name="clock_out" class="form-control" value="{{ $clock_out->timezone(config('app.timezone'))->format('Y-m-d\TH:i') }}">
                                </div>
                            </td>
                            @if ($attendance->break_times->isNotEmpty())
                            <td class="col-5">
                                @foreach ($attendance->break_times as $index => $break)

                                @php
                                $break_start = \Carbon\Carbon::parse($break->break_start, 'UTC');
                                $break_end = \Carbon\Carbon::parse($break->break_end, 'UTC');
                                @endphp

                                <input type="hidden" name="break[{{ $index }}][]" value="{{ $break->id }}">
                                <div class="row row-cols-2">
                                    <div class="col my-3">
                                        <input type="datetime-local" name="break[{{ $index }}][]" class="form-control" value="{{ $break_start->timezone(config('app.timezone'))->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <div class="col my-3">
                                        <input type="datetime-local" name="break[{{ $index }}][]" class="form-control" value="{{ $break_end->timezone(config('app.timezone'))->format('Y-m-d\TH:i') }}">
                                    </div>
                                </div>
                                @endforeach
                            </td>

                            @else
                            <td class="col-5">
                                <div class="d-flex justify-content-center my-3">
                                    0
                                </div>
                            </td>
                            @endif
                            <td class="col-1">
                                <div class="my-3">
                                    <button type="submit" class="btn btn-success">{{ __("action.edit") }}</button>
                                </div>
                            </td>
                        </div>
                    </form>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @endsection