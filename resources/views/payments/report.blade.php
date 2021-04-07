@extends('layouts.pdf')

@section('title', 'Pagos')

@section('content')
<div class="card-header">
    <h3 class="card-title">PAGOS</h3>
</div>
<div class="card-body">
    <table>
        <thead>
            <tr style="text-align: center;">
                <th style="width: 50px">Nº</th>
                <th style="width: 130px">DEUDA</th>
                <th style="width: 130px">PAGO CAPITAL</th>
                <th style="width: 130px">PAGO INTERES</th>
                <th style="width: 130px">PAGO MORA</th>
                <th style="width: 120px">FECHA PAGO</th>
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
            <tr>
                @php
                $i++;
                $sum_capital+=$payment->capital;
                $sum_interest+=$payment->interest_amount;
                $sum_must+=$payment->must;
                @endphp
                <td>{{$i}}</td>
                <td style="text-align: right;">{{'$' . number_format($payment->debt, 2, ',', '.')}}</td>
                <td style="text-align: right;">{{'$' . number_format($payment->capital, 2, ',', '.')}}</td>
                <td style="text-align: right;">{{'$' . number_format($payment->interest_amount, 2, ',', '.')}}</td>
                <td style="text-align: right;">{{'$' . number_format($payment->must, 2, ',', '.')}}</td>
                <td>{{substr($payment->date, 0, 10)}}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: center;">TOTAL</th>
                <th style="text-align: right;">
                    {{'$' .(count($payments) ? number_format($payments[count($payments)-1]->debt - $payments[count($payments)-1]->capital, 2, ',', '.') : number_format($loan->amount, 2, ',', '.'))}}
                </th>
                <th style="text-align: right;">{{'$' . number_format($sum_capital, 2, ',', '.')}}</th>
                <th style="text-align: right;">{{'$' . number_format($sum_interest, 2, ',', '.')}}</th>
                <th style="text-align: right;">{{'$' . number_format($sum_must, 2, ',', '.')}}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection