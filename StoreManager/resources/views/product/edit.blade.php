@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __("product.productEdit") }}</div>
            <div class="card-body">
                <form action="{{ route('product.update', $product->id) }}" method="POST">
                    @csrf
                    @method('patch')
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="productCode" class="form-label">{{ __("product.productCode") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="product_code" class="form-control" id="productCode" value="{{ $product['product_code'] }}">
                        </div>
                        <div class="col-auto">
                            @error('product_name')
                            <span id="productName" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="productName" class="form-label">{{ __("product.productName") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="product_name" class="form-control" id="productName" value="{{ $product['product_name'] }}">
                        </div>
                        <div class="col-auto">
                            @error('productName')
                            <span id="productName" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="price" class="form-label">{{ __("product.price") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="price" class="form-control" id="price" value="{{ $product['price'] }}">
                        </div>
                        <div class="col-auto">
                            @error('price')
                            <span id="price" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">{{ __("action.edit") }}</button>
                            <a class="btn btn-secondary" href="{{ route('product.index') }}">{{ __("action.back") }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection