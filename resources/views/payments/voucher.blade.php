@extends('layouts.pdf')

@section('title', 'Comprobante de Pago')

@section('content')
<table>
    <tr>
        <th colspan="2">COMPROBANTE DE PAGO</th>
    </tr>
    <tr>
        <th style="text-align: left;">Número de pago</th>
        <td style="text-align: right;">{{$payment->id}}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Fecha de pago</th>
        <td style="text-align: right;">{{substr($payment->date, 0, 10)}}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Cédula</th>
        <td style="text-align: right;">{{$payment->loan->person->identification_card}}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Nombre</th>
        <td style="text-align: right;">{{$payment->loan->person->first_name .' ' .$payment->loan->person->last_name}}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Capital</th>
        <td style="text-align: right;">{{number_format($payment->capital, 2, ',', '.')}}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Interés</th>
        <td style="text-align: right;">{{number_format($payment->interest_amount, 2, ',', '.')}}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Mora</th>
        <td style="text-align: right;">{{number_format($payment->must, 2, ',', '.')}}</td>
    </tr>
    <tr>
        <th style="text-align: left;">Monto total</th>
        <th style="text-align: right;">{{number_format($payment->capital + $payment->interest_amount + $payment->must, 2, ',', '.')}}</th>
    </tr>
</table>

@endsection