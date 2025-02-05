@extends('layouts.app')

@section('content')

<div class="row m-3">
    <div class="my-3">
        <a class="btn btn-primary" href="{{ route('user.create') }}">{{ __("setting.newUser") }}</a>
    </div>
    <div class="my-3">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __("setting.userName") }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)

                <tr>
                    <td>{{ $user->name }}</td>
                    <td>
                        <a class="btn btn-outline-success" href="{{ route('user.edit', $user->id) }}">{{ __("action.edit") }}</a>
                        <a class="btn btn-outline-danger" onclick="event.preventDefault();
                    if (Check()) document.getElementById('del-form{{ $loop->index }}').submit();
                    ">{{ __("action.delete") }}</a>
                        <form id="del-form{{ $loop->index }}" action="{{ route('user.delete', $user->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                        </form>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="{{ asset('/js/delete.js') }}"></script>

@endsection