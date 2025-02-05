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
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->product->product_code }}</td>
                <td>{{ $order->product->product_name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>
                    <a class="btn btn-outline-danger" onclick="event.preventDefault();
                    if (Check()) document.getElementById('del-form{{ $loop->index }}').submit();
                    ">{{ __("action.cancel") }}</a>
                    <form id="del-form{{ $loop->index }}" action="{{ route('order.delete', $order->id) }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $order->id }}">
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="{{ asset('/js/delete.js') }}"></script>

@endsection