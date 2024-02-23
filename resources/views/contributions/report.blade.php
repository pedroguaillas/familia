@extends('layouts.pdf')

@section('title', 'Aportes')

@section('content')
<h3 class="card-title">APORTES</h3>
<table>
    <thead>
        <tr>
            <th style="width: 85px">Nº</th>
            <th style="width: 400px">SOCIO</th>
            <th style="width: 100px">Nº ACCIONES</th>
            <th style="width: 100px">APORTES</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 1;
        $sum = 0;
        @endphp
        @foreach ($contributions as $contribution)
        <tr>
            <td>{{ $i }}</td>
            <td style="text-align: left;">{{ $contribution->first_name . ' ' . $contribution->last_name }}</td>
            <td>{{ $contribution->actions }}</td>
            <td style="text-align: right;">{{ number_format($contribution->amount, 2, ',', '.') }}</td>
        </tr>
        @php
        $i += 1;
        $sum += $contribution->amount;
        @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">TOTAL</th>
            <th style="text-align: right; padding-right: .5em;">{{ number_format($sum, 2, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>
@endsection