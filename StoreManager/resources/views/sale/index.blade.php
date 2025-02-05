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
    <form action="{{ route('sale.buy')}}" method="post">
        @csrf
        <div class="row my-3">
            <div class="col-4">
                <input type="text" name="product_code" class="form-control" id="productCode" placeholder="{{ __('product.productCode') }}" autofocus>
                @error('product_code')
                <span id="productCode" class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-2">
                <input type="text" name="quantity" class="form-control" id="quantity" placeholder="{{ __('inventory.quantity') }}">
                @error('quantity')
                <span id="quantity" class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-outline-primary">{{ __('action.add')}}</button>
            </div>
        </div>
    </form>
    <form action="{{ route('sale.customer')}}" method="post">
        @csrf
        <div class="row my-3">
            <div class="col-3">
                <input type="text" name="customer_id" id="customerId" class="form-control" placeholder="{{ __('sale.customerId') }}">
                @error('customer_id')
                <span id="customerId" class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-outline-primary">{{ __('action.add')}}</button>
            </div>
            <div class="col-2">
                <a href="{{ route('sale.reset') }}" class="btn btn-outline-danger">{{ __('action.reset')}}</a>
            </div>
        </div>
    </form>
    <div class="card my-3">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('product.productName') }}</th>
                    <th>{{ __('inventory.quantity') }}</th>
                    <th>{{ __('product.price') }}</th>
                    <th>{{ __('sale.subtotal') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php
                $sales = session('sales', []);
                $point = session('point');
                $total = 0;
                @endphp
                @if (count($sales) > 0)
                @foreach ($sales as $index => $sale)
                <tr>
                    <td>{{ $sale['product_name'] }}</td>
                    <td>{{ $sale['quantity'] }}</td>
                    <td>{{ $sale['price'] }}{{ $currency }}</td>
                    <td>{{ $sale['subTotal'] }}{{ $currency }}</td>
                    <td><a href="{{ route('sale.cancel', $index) }}" class="btn btn-outline-danger">{{ __('action.cancel') }}</a></td>
                    @php
                    $total += $sale['subTotal'];
                    @endphp
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end">
        <p>{{ __('sale.total') }}: {{ $total }}{{ $currency }}</p>
    </div>
    <div class="d-flex justify-content-end">
        <p>{{ __('sale.point') }}: {{ !empty($point) ? $point : 0 }}{{ __('sale.pt') }}</p>
    </div>

    <form action="{{ route('sale.pay') }}" method="post">
        @csrf
        <input type="hidden" name="total" value="{{ $total }}">
        <div class="row d-flex justify-content-end">
            <div class="col-2">
                <input type="text" name="receivedMoney" class="form-control" placeholder="{{ __('sale.receivedMoney') }}">
            </div>
            <div class="col-2">
                <input type="text" name="point" class="form-control" placeholder="{{ __('sale.usePoints') }}">
            </div>
            <div class="col-1">
                <button type="submit" class="btn btn-primary">{{ __('sale.payment') }}</button>
            </div>
        </div>
    </form>
</div>

@endsection