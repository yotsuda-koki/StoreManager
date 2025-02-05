@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __("setting.userEdit") }}</div>
            <div class="card-body">
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('patch')
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="name" class="form-label">{{ __("setting.name") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="name" class="form-control" id="name" value="{{ $user['name'] }}">
                        </div>
                        <div class="col-auto">
                            @error('name')
                            <span id="name" class="form-text text-danger">{{ $message }}</span>
                            @enderror
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