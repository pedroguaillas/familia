@extends('layouts.pdf')

@section('title', 'PRÉSTAMOS')

@section('content')
<h3 class="card-title">PRÉSTAMOS</h3>
<table style="font-size: 0.7rem;">
    <thead>
        <tr>
            <th style="width: 30px">Nº</th>
            <th style="width: 230px">DEUDOR</th>
            <th style="width: 80px">PRÉSTAMO</th>
            <th style="width: 40px">%</th>
            <th style="width: 70px">PAGADO</th>
            <th style="width: 60px">$ (%)</th>
            <th style="width: 40px">MORA</th>
            <th style="width: 70px">DEUDA</th>
            <th style="width: 70px">FECHA</th>
        </tr>
    </thead>
    @php
    $i = 1;
    $sumDebt = 0;
    $sumInterest = 0;
    $sumMust = 0;
    @endphp
    <tbody>
        @foreach ($loans as $loan)
        <tr>
            <td>{{ $i }}</td>
            <td style="text-align: left;">{{ $loan['first_name'] . ' ' . $loan['last_name'] }}</td>
            <td style="text-align: right;">{{ number_format($loan['amount'], 2, ',', '.') }}</td>
            <td style="text-align: right;">{{ $loan['interest_percentage'] . '%' }}</td>
            <td style="text-align: right;">{{ number_format($loan['sum_capital_paid'], 2, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($loan['interest_amount'], 2, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($loan['must'], 2, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($loan['amount'] - $loan['sum_capital_paid'], 2, ',', '.') }}</td>
            <td>{{ substr($loan['date'], 0, 10) }}</td>
        </tr>
        @php
        $i += 1;
        $sumDebt += $loan['amount'] - $loan['sum_capital_paid'];
        $sumInterest += $loan['interest_amount'];
        $sumMust += $loan['must'];
        @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5">TOTAL</th>
            <th style="text-align: right; padding-right: .5em;">{{ number_format($sumInterest, 2, ',', '.') }}</th>
            <th style="text-align: right; padding-right: .5em;">{{ number_format($sumMust, 2, ',', '.') }}</th>
            <th style="text-align: right; padding-right: .5em;">{{ number_format($sumDebt, 2, ',', '.') }}</th>
            <td></td>
        </tr>
    </tfoot>
</table>
@endsection