@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="col">
        <div class="card">
            <div class="card-header">{{ __("inventory.orderReceive") }}</div>
            <div class="card-body">
                <form action="{{ route('order.received') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="productName" class="form-label">{{ __("product.productName") }}</label>
                        </div>
                        <div class="col-auto">
                            <select class="form-select" aria-label="Default select example" name="productId">
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="price" class="form-label">{{ __("inventory.quantity") }}</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="quantity" class="form-control" id="price" value="{{ old('quantity') }}">
                        </div>
                        <div class="col-auto">
                            @error('quantity')
                            <span id="quantity" class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">{{ __("action.receive") }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection