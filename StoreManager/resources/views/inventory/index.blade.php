@extends('layouts.app')

@section('content')

<div class="row m-3">
    @if (session('msg'))
    <div class="alert alert-primary" role="alert">
        {{ session('msg') }}
    </div>
    @endif
    <form action="{{ route('inventory.search') }}" method="GET" class="row">
        <div class="col-2">
            <input type="text" name="query" class="form-control" placeholder="{{ __("action.search") }}">
        </div>
        <div class="col-1">
            <button type="submit" class="btn btn-outline-success">{{ __("action.search") }}</button>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>{{ __("product.productCode") }}</th>
                <th>{{ __("product.productName") }}</th>
                <th>{{ __("inventory.quantity") }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)

            <tr>
                <td>{{ $product->product_code }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->inventory->quantity }}</td>
                <td>
                    <a class="btn btn-outline-success" href="{{ route('inventory.edit', $product->id) }}">{{ __("action.edit") }}</a>
                    <a class="btn btn-outline-primary" href="{{ route('order.create', $product->id) }}">{{ __("action.order") }}</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>

@endsection