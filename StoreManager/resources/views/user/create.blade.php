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
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __("setting.userAdd") }}</div>
            <div class="card-body">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="name" class="form-label">{{ __("setting.name") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}">
                        </div>
                        <div class="col-auto">
                            @error('name')
                            <span id="name" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="email" class="form-label">{{ __("setting.email") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}">
                        </div>
                        <div class="col-auto">
                            @error('email')
                            <span id="email" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="password" class="form-label">{{ __("setting.password") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="password" name="password" class="form-control" id="password">
                        </div>
                        <div class="col-auto">
                            @error('password')
                            <span id="password" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="password_confirmation" class="form-label">{{ __("setting.confirmPassword") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="role" class="form-label">{{ __("setting.role") }}</label>
                        </div>
                        <div class="col-auto">
                            <select class="form-select" name="role">
                                <option value="0" selected>{{ __("setting.trainee") }}</option>
                                <option value="1">{{ __("setting.trainer") }}</option>
                                <option value="2">{{ __("setting.admin") }}</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            @error('role')
                            <span id="role" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">{{ __("action.add") }}</button>
                            <a class="btn btn-secondary" href="{{ route('user.index') }}">{{ __("action.back") }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection