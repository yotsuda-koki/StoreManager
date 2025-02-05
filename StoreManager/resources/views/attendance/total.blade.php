@extends('layouts.app')

@section('content')
<div class="m-3">
    <div class="d-flex justify-content-center">
        <form action="{{ route('attendance.totalTable') }}" method="post">
            @csrf
            <input type="month" class="form-control" name="yearAndMonth" min="2024-01" max="2030-12" onchange="this.form.submit()">
        </form>
    </div>
</div>
@endsection