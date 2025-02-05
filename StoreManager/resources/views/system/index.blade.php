@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="row my-3">
        @if (session('msg'))
        <div class="alert alert-primary" role="alert">
            {{ session('msg') }}
        </div>
        @endif
        <div class="col-2">
            <label for="language" class="form-label">{{ __("setting.langSet") }}</label>
        </div>
        <div class="col-4">
            <form action="{{ route('language.switch', app()->getLocale()) }}" method="post">
                @csrf
                <select class="form-select" name="locale" onchange="this.form.submit()">
                    <option selected>{{ __("setting.langSelect") }}</option>
                    @foreach(config('app.available_locales') as $key => $value)
                    <option value="{{ $key }}">
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-2">
            <label for="currency" class="form-label">{{ __("setting.currencySet") }}</label>
        </div>
        <div class="col-4">
            <form action="{{ route('currency.switch', app()->getLocale()) }}" method="post">
                @csrf
                <select class="form-select" name="currency" onchange="this.form.submit()">
                    <option selected>{{ __("setting.currencySelect") }}</option>
                    @foreach(config('app.available_currencies') as $key => $value)
                    <option value="{{ $key }}">
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-2">
            <label for="timezone" class="form-label">{{ __("setting.timeSet") }}</label>
        </div>
        <div class="col-4">
            <form action="{{ route('timezone.switch', app()->getLocale()) }}" method="post">
                @csrf
                <select class="form-select" name="timezone" onchange="this.form.submit()">
                    <option selected>{{ __("setting.timeSelect") }}</option>
                    @foreach(config('app.available_timezones') as $key => $value)
                    <option value="{{ $key }}">
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
</div>

@endsection