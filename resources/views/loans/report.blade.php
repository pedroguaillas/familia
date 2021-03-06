@extends('layouts.pdf')

@section('title', 'PRESTAMOS')

@section('content')
<h3 class="card-title">PRESTAMOS</h3>
<table>
    <thead>
        <tr>
            <th style="width: 40px">Nº</th>
            <th style="width: 240px">DEUDOR</th>
            <th style="width: 85px">PRESTAMO</th>
            <th style="width: 50px">INTERES</th>
            <th style="width: 85px">PAGADO</th>
            <th style="width: 85px">DEUDA</th>
            <th style="width: 85px">FECHA</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i=1;
        @endphp
        @foreach ($loans as $loan)
        <tr>
            <td>{{$i}}</td>
            <td style="text-align: left;">{{$loan['first_name'].' '. $loan['last_name']}}</td>
            <td style="text-align: right;">{{number_format($loan['amount'], 2, ',', '.')}}</td>
            <td>{{$loan['interest_percentage']. '%'}}</td>
            <td style="text-align: right;">{{number_format($loan['sum_capital_paid'], 2, ',', '.')}}</td>
            <td style="text-align: right;">{{number_format($loan['amount'] - $loan['sum_capital_paid'], 2, ',', '.')}}</td>
            <td>{{substr($loan['date'], 0, 10)}}</td>
        </tr>
        @php
        $i+=1;
        @endphp
        @endforeach
    </tbody>
</table>
@endsection