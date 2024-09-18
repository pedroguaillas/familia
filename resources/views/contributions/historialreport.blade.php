@extends('layouts.pdf')

@section('title', 'Historial de Aportes')

@section('content')
<h3 class="card-title">{{ $person->first_name .' ' .$person->last_name }}</h3>
<table>
    <tbody>
        <tr>
            <td style="width: 105px; text-align: left;">Total de aportes</td>
            <td style="text-align: left;">{{ number_format($amount, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="width: 105px; text-align: left;">N° de Acciones</td>
            <td style="text-align: left;">{{ $person->actions }}</td>
        </tr>
    </tbody>
</table>
<br />
<table>
    <thead>
        <tr>
            <th style="width: 85px">Nº</th>
            <th style="width: 120px">MES</th>
            <th style="width: 120px">AÑO</th>
            <th style="width: 120px">APORTE</th>
            <th style="width: 120px">MORA</th>
            <th style="width: 120px">TIPO</th>
        </tr>
    </thead>
    @php
    $i = 1;
    $sum_amount = 0;
    $sum_must = 0;
    @endphp
    <tbody>
        @foreach ($contributions as $contribution)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ substr($contribution->date, 5, 2) }}</td>
            <td>{{ substr($contribution->date, 0, 4) }}</td>
            <td style="text-align: right;">{{ number_format($contribution->amount, 2, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($contribution->must, 2, ',', '.') }}</td>
            <td>{{$contribution->type}}</td>
        </tr>
        @php
        $i += 1;
        $sum_amount += $contribution->amount;
        $sum_must += $contribution->must;
        @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"></td>
            <th>TOTAL</th>
            <th style="text-align: right; padding-right: .5em;">{{ number_format($sum_amount, 2, ',', '.') }}</th>
            <th style="text-align: right; padding-right: .5em;">{{ number_format($sum_must, 2, ',', '.') }}</th>
            <th></th>
        </tr>
    </tfoot>
</table>
@endsection