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
    <form action="{{ route('product.search') }}" method="GET" class="row">
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
                <th>{{ __("product.price") }}({{ $currency }})</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)

            <tr>
                <td>{{ $product->product_code }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->price }}{{ $currency }}</td>
                <td>
                    <a class="btn btn-outline-success" href="{{ route('product.edit',$product->id) }}">{{ __("action.edit") }}</a>
                    <a class="btn btn-outline-danger" onclick="event.preventDefault();
                    if (Check()) document.getElementById('del-form{{ $loop->index }}').submit();
                    ">{{ __("action.delete") }}</a>
                    <form id="del-form{{ $loop->index }}" action="{{ route('product.delete', $product->id) }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>

<script src="{{ asset('/js/delete.js') }}"></script>

@endsection