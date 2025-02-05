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
    <form action="{{ route('customer.search') }}" method="GET" class="row">
        <div class="col-2">
            <input type="text" name="query" class="form-control" placeholder="{{ __("action.search") }}" value="{{ old('query') }}">
        </div>
        <div class=" col-1">
            <button type="submit" class="btn btn-outline-success">{{ __("action.search") }}</button>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>{{ __("customer.id") }}</th>
                <th>{{ __("customer.name") }}</th>
                <th>{{ __("customer.age") }}</th>
                <th>{{ __("customer.email") }}</th>
                <th>{{ __("customer.pt") }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)

            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->customer_name }}</td>
                <td>{{ $customer->age }}</td>
                <td>{{ $customer->customer_email }}</td>
                <td>{{ $customer->point }}</td>
                <td>
                    <a class="btn btn-outline-success" href="{{ route('customer.edit', $customer->id) }}">{{ __("action.edit") }}</a>
                    <a class="btn btn-outline-danger" onclick="event.preventDefault();
                    if (Check()) document.getElementById('del-form{{ $loop->index }}').submit();
                    ">{{ __("action.delete") }}</a>
                    <form id="del-form{{ $loop->index }}" action="{{ route('customer.delete', $customer->id) }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $customer->id }}">
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center">
    {{ $customers->links() }}
</div>

<script src="{{ asset('/js/delete.js') }}"></script>

@endsection