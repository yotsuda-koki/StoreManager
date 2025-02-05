@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __("inventory.order") }}</div>
            <div class="card-body">
                <form action="{{ route('order.store', $product->id) }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="productCode" class="form-label">{{ __("product.productCode") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="product_code" class="form-control" id="productCode" value="{{ $product['product_code'] }}" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="productName" class="form-label">{{ __("product.productName") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="product_name" class="form-control" id="productName" value="{{ $product['product_name'] }}" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="quantity" class="form-label">{{ __("inventory.quantity") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="quantity" class="form-control" id="quantity" value="{{ old('quantity') }}">
                        </div>
                        <div class="col-auto">
                            @error('quantity')
                            <span id="quantity" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">{{ __("action.order") }}</button>
                            <a class="btn btn-secondary" href="{{ route('inventory.index') }}">{{ __("action.back") }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection