@extends('layouts.app')

@section('content')
<div class="row m-3">
    <h1 class="d-flex justify-content-center">
        {{ $startDate->format('Y-m-d') }} ~ {{ $endDate->format('Y-m-d') }}
    </h1>
    <div class="col-6">
        <canvas id="myChart" width="auto" height="auto"></canvas>
    </div>
    <div class="col-6">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('data.date') }}</th>
                    <th>{{ __('data.sales') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dates as $date)
                <tr>
                    <td>{{ $date['date'] }}</td>
                    <td>{{ $date['sum'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var labels = @json(array_column($dates, 'date'));
        var data = @json(array_column($dates, 'sum'));
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '{{ $productName }}',
                    data: data,
                    borderColor: "rgb(75, 192, 192)",
                    fill: false
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</div>
@endsection