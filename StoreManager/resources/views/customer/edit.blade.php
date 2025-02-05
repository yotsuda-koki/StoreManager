@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __("customer.customerEdit") }}</div>
            <div class="card-body">
                <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('patch')
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="customerName" class="form-label">{{ __("customer.name") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="customer_name" class="form-control" id="customerName" value="{{ $customer['customer_name'] }}">
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
                            <input type="text" name="age" class="form-control" id="age" value="{{ $customer['age'] }}">
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
                            <input type="text" name="customer_email" class="form-control" id="customerEmail" value="{{ $customer['customer_email'] }}">
                        </div>
                        <div class="col-auto">
                            @error('customer_email')
                            <span id="customerEmail" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="point" class="form-label">{{ __("customer.point") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="point" class="form-control" id="point" value="{{ $customer['point'] }}">
                        </div>
                        <div class="col-auto">
                            @error('point')
                            <span id="point" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">{{ __("action.edit") }}</button>
                            <a class="btn btn-secondary" href="{{ route('customer.index') }}">{{ __("action.back") }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection