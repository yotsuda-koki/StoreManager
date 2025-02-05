@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __("product.productAdd") }}</div>
            <div class="card-body">
                <form action="{{ route('product.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="productCode" class="form-label">{{ __("product.productCode") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="product_code" class="form-control" id="productCode" value="{{ old('product_code') }}">
                        </div>
                        <div class="col-auto">
                            @error('product_code')
                            <span id="productCode" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="productName" class="form-label">{{ __("product.productName") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="product_name" class="form-control" id="productName" value="{{ old('product_name') }}">
                        </div>
                        <div class="col-auto">
                            @error('product_name')
                            <span id="productName" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="price" class="form-label">{{ __("product.price") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="price" class="form-control" id="price" value="{{ old('price') }}">
                        </div>
                        <div class="col-auto">
                            @error('price')
                            <span id="price" class="form-text text-danger">{{ $message }}</span>
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