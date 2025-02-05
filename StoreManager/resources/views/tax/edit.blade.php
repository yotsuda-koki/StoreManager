@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __("setting.taxEdit") }}</div>
            <div class="card-body">
                <form action="{{ route('tax.update', $tax->id) }}" method="POST">
                    @csrf
                    @method('patch')
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="effective_from" class="form-label">{{ __("setting.effectiveDate") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="date" name="effective_from" class="form-control" id="effective_from" value="{{ $tax['effective_date'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="tax_rate" class="form-label">{{ __("setting.rate") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="tax_rate" class="form-control" id="tax_rate" value="{{ $tax['tax_rate'] }}">
                        </div>
                        <div class="col-auto">
                            @error('tax_rate')
                            <span id="tax_rate" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">{{ __("action.edit") }}</button>
                            <a class="btn btn-secondary" href="{{ route('tax.index') }}">{{ __("action.back") }}</a>
                        </div>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

@endsection