@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __("customer.customerAdd") }}</div>
            <div class="card-body">
                <form action="{{ route('customer.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="customerName" class="form-label">{{ __("customer.name") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="customer_name" class="form-control" id="customerName" value="{{ old('customer_name') }}">
                        </div>
                        <div class="col-auto">
                            @error('customer_name')
                            <span id="customerName" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="age" class="form-label">{{ __("customer.age") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="age" class="form-control" id="age" value="{{ old('age') }}">
                        </div>
                        <div class="col-auto">
                            @error('age')
                            <span id="age" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="customerEmail" class="form-label">{{ __("customer.email") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="customer_email" class="form-control" id="customerEmail" value="{{ old('customer_email') }}">
                        </div>
                        <div class="col-auto">
                            @error('customer_email')
                            <span id="customerEmail" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">{{ __("action.add") }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection