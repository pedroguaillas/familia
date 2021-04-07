@extends('layouts.pdf')

@section('title', 'Historial de Aportes')

@section('content')
<div class="card-header">
    <h3 class="card-title">Historial de Aportes</h3>
</div>
<div class="card-body">
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
        <tbody>
            @php
            $i=1;
            @endphp
            @foreach ($contributions as $contribution)
            <tr>
                <td>{{$i}}</td>
                <td>{{ substr($contribution->date, 5,2 )}}</td>
                <td>{{ substr($contribution->date, 0,4 )}}</td>
                <td style="text-align: right;">{{'$' . number_format($contribution->amount, 2, ',', '.')}}</td>
                <td style="text-align: right;">{{'$' . number_format($contribution->must, 2, ',', '.')}}</td>
                <td>{{$contribution->type}}</td>
            </tr>
            @php
            $i+=1;
            @endphp
            @endforeach
        </tbody>
    </table>
</div>
@endsection