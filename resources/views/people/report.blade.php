@extends('layouts.pdf')

@section('title', $people[0]->type)

@section('content')
<!-- <div class="card-header">
    <h3 class="card-title"> {{ strtoupper( $people[0]->type) }} </h3>
</div> -->
<div style="margin-top: 1em;" class="card-body">
    <table style="width:100%;">
        <thead>
            <tr>
                <th>CEDULA</th>
                <th>NOMBRES</th>
                <th>APELLIDOS</th>
                <th>TIPO</th>
                <th>TELEFONO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($people as $dato)
            <tr>
                <td>{{$dato['identification_card']}}</td>
                <td style="text-align: left;">{{$dato['first_name']}}</td>
                <td style="text-align: left;">{{$dato['last_name']}}</td>
                <td>{{$dato['type']}}</td>
                <td>{{$dato['phone']}}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection