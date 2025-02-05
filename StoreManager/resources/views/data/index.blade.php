@extends('layouts.app')

@section('content')
<div class="m-3">
    @if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="d-flex justify-content-center">

        <form action="{{ route('data.analysis') }}" method="post">
            @csrf
            <div>
                @error('end')
                <span id="customerEmail" class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
                <div class="col-auto d-flex align-items-center">{{ __('data.start') }} : </div>
                <div class="col-auto">
                    <input type="date" class="form-control" name="start" id="'startDate">
                </div>
                <div class="col-auto d-flex align-items-center">{{ __('data.end') }} : </div>
                <div class="col-auto">
                    <input type="date" class="form-control" name="end" id="endDate">
                </div>
                <div class="col-auto">
                    <select class="form-select" aria-label="Default select example" name="productId">
                        <option value="0" selected>{{ __('data.allProducts') }}</option>
                        @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">{{ __('action.search') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection