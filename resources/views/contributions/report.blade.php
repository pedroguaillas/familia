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
        $i=1;
        @endphp
        @foreach ($contributions as $contribution)
        <tr>
            <td>{{$i}}</td>
            <td style="text-align: left;">{{$contribution->first_name . ' ' . $contribution->last_name}}</td>
            <td>{{$contribution->actions}}</td>
            <td style="text-align: right;">{{number_format($contribution->amount, 2, ',', '.')}}</td>
        </tr>
        @php
        $i+=1;
        @endphp
        @endforeach
    </tbody>
</table>
@endsection