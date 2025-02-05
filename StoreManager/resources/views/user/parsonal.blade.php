@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __("setting.userEdit") }}</div>
            <div class="card-body">
                <form action="{{ route('user.parsonalUpdate', $user->id) }}" method="POST">
                    @csrf
                    @method('patch')
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
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">{{ __("action.edit") }}</button>
                            <a class="btn btn-secondary" href="{{ route('user.index') }}">{{ __("action.back") }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection