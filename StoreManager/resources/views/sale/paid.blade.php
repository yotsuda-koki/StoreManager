@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="col-4"></div>
    <div class="col-4">
        <div class="d-flex justify-content-center">{{ $sale->created_at }}</div>
        <div class="border-top"></div>
        @php
        $subtotal = 0;
        @endphp
        @foreach ($saleItems as $saleItem)
        <div class="row">
            <div class="col-8">{{ $saleItem->product->product_name }}*{{ $saleItem->quantity }}{{ $currency }}</div>
            <div class="col-4 d-flex justify-content-end">{{ $saleItem->product->price*$saleItem->quantity }}{{ $currency }}</div>
        </div>
        @php
        $subtotal += $saleItem->product->price*$saleItem->quantity;
        @endphp
        @endforeach
        <div class="row">
            <div class="col-8">{{ __('sale.subtotal') }}</div>
            <div class="col-4 d-flex justify-content-end">{{ $subtotal }}{{ $currency }}</div>
        </div>
        <div class="row">
            <div class="col-8">{{ __('sale.tax') }}</div>
            <div class="col-4 d-flex justify-content-end">{{ $subtotal * (1 + $taxRate) }}{{ $currency }}</div>
        </div>
        <div class="border-top"></div>
        <div class="row">
            <div class="col-8">{{ __('sale.total') }}</div>
            <div class="col-4 d-flex justify-content-end">{{ $sale->subtotal }}{{ $currency }}</div>
        </div>
        <div class="row">
            <div class="col-8">{{ __('sale.receivedMoney') }}</div>
            <div class="col-4 d-flex justify-content-end">{{ $receivedMoney }}{{ $currency }}</div>
        </div>
        <div class="row">
            <div class="col-8">{{ __('sale.usePoint') }}</div>
            <div class="col-4 d-flex justify-content-end">{{ $usePoint ?? 0 }}pt</div>
        </div>
        <div class="row">
            <div class="col-8">{{ __('sale.charge') }}</div>
            <div class="col-4 d-flex justify-content-end">{{ $charge }}{{ $currency }}</div>
        </div>
        <div class="row">
            <div class="col-8">{{ __('sale.rewardPoint') }}</div>
            <div class="col-4 d-flex justify-content-end">{{ $rewardPoint }}pt</div>
        </div>
        <div class="row">
            <div class="col-8">{{ __('sale.pointBalance') }}</div>
            <div class="col-4 d-flex justify-content-end">{{ $pointBalance ?? 0 }}pt</div>
        </div>
        <div class="d-flex justify-content-end">
            <a class="btn btn-secondary" href="{{ route('sale.index') }}">{{ __("action.back") }}</a>
        </div>
    </div>
    <div class="col-4"></div>
</div>

@endsection