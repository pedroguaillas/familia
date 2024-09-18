@extends('layouts.pdf')

@section('title', $title)

@section('content')
<h3 class="card-title">{{ $title }}</h3>
<table>
    <tbody>
        <tr>
            <td style="width: 105px; text-align: left;">Deudor</td>
            <td style="width: 500px; text-align: left;">{{$person->first_name .' ' .$person->last_name}}</td>
        </tr>
        <tr>
            <td style="width: 105px; text-align: left;">Monto</td>
            <td style="text-align: left;">{{$loan->amount}}</td>
        </tr>
        <tr>
            <td style="width: 105px; text-align: left;">Interés</td>
            <td style="text-align: left;">{{$loan->interest_percentage .'%'}}</td>
        </tr>
    </tbody>
</table>
<br />
<table>
    <thead>
        <tr style="text-align: center;">
            <th style="width: 20px">Nº</th>
            <th style="width: 110px">DEUDA</th>
            <th style="width: 110px">PAGO CAPITAL</th>
            <th style="width: 110px">PAGO INTERES</th>
            <th style="width: 110px">PAGO MORA</th>
            <th style="width: 110px">PAGO TOTAL</th>
            <th style="width: 110px">PAGO FECHA</th>
        </tr>
    </thead>
    @php
    $i=0;
    $sum_capital=0;
    $sum_interest=0;
    $sum_must=0;
    @endphp
    <tbody>
        @foreach ($payments as $payment)
        <tr style="{{ $payment->state === 'inactivo' ? 'color: #888; font-style: oblique;' : '' }}">
            @php
            $i++;
            $sum_capital+=$payment->capital;
            $sum_interest+=$payment->interest_amount;
            $sum_must+=$payment->must;
            @endphp
            <td>{{ $i }}</td>
            <td style="text-align: right;">{{ number_format($payment->debt, 2, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($payment->capital, 2, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($payment->interest_amount, 2, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($payment->must, 2, ',', '.')}}</td>
            <td style="text-align: right;">{{ number_format($payment->capital + $payment->interest_amount + $payment->must, 2, ',', '.') }}</td>
            <td>{{substr($payment->date, 0, 10)}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <th style="text-align: right; padding-right: .5em;">TOTAL</th>
            <th style="text-align: right; padding-right: .5em;">{{number_format($sum_capital, 2, ',', '.')}}</th>
            <th style="text-align: right; padding-right: .5em;">{{number_format($sum_interest, 2, ',', '.')}}</th>
            <th style="text-align: right; padding-right: .5em;">{{number_format($sum_must, 2, ',', '.')}}</th>
            <th style="text-align: right; padding-right: .5em;">{{number_format($sum_capital + $sum_interest + $sum_must, 2, ',', '.')}}</th>
            <th></th>
        </tr>
    </tfoot>
</table>
@endsection