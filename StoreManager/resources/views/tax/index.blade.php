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
    <div class="row my-3">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('setting.effectiveDate') }}</th>
                    <th>{{ __('setting.rate') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($taxes as $tax)
                <tr>
                    <td>{{ $tax->effective_date->format('Y-m-d') }}</td>
                    <td>{{ $tax->tax_rate }}</td>
                    <td>
                        <a href="{{ route('tax.edit', $tax->id) }}" class="btn btn-success">{{ __('action.edit') }}</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row my-3">
        <form action="{{ route('tax.changeTax') }}" method="post">
            @csrf
            <div class="row my-3">
                <div class="col-2"><label for="effective_from">{{ __('setting.effectiveDate') }}</label></div>
                <div class="col-4"><input type="date" class="form-control" id="effective_from" name="effective_from"></div>
            </div>
            <div class="col-auto">
                @error('effective_from')
                <span id="effective_from" class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="row my-3">
                <div class="col-2"><label for="rate">{{ __('setting.rate') }}</label></div>
                <div class="col-4"><input type="text" class="form-control" id="rate" name="rate"></div>
            </div>
            <div class="col-auto">
                @error('rate')
                <span id="rate" class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">{{ __('action.save') }}</button>
        </form>
    </div>
</div>


@endsection