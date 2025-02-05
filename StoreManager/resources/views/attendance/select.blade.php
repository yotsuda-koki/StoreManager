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
    <div class="col-2">
        <div class="card">
            <div class="list-group">
                <li class="list-group-item list-group-item-secondary">{{ __('attendance.employee') }}</li>
                @foreach ($users as $user)
                <a href="{{ route('attendance.edit', $user->id) }}" class="list-group-item">{{ $user->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection