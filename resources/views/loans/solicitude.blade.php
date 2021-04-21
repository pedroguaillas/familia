@extends('layouts.pdf')

@section('title', 'Solicitud compra de acciones')

@section('content')
<p style="text-align: right;">San Lucas, {{date('d')}} de abril de {{date('Y')}}</p>
<br />
<p>Jaime Ramiro Gualan Aguilar</p>
<strong>Gerente de la Caja de Ahorros y Prestamos Familiar (CAPFA).</strong>
<br />
<br />
<p style="text-align: justify;">
    Por medio del presente yo, {{$loan->person->first_name .' ' .$loan->person->last_name}},
    con número de cédula {{$loan->person->identification_card}},
    solicito se me otorgue un préstamo cuyo monto es ${{number_format($loan->amount, 2, ',', '.')}} dólares americanos,
    por un tiempo de 4 meses, comprometiéndome a pagar los intereses al {{$loan->interest_percentage}}% mensual y
    los recargos de mora en caso de atraso en los pagos de acuerdo a las normativas establecida por los socios de la
    <strong>Caja de Ahorro y Prestamos Familiar “CAPFA”{{$guarantor?',':'.'}}</strong>
    @if($guarantor!==null)
    para el mencionado préstamo el garante es el Sr. {{$guarantor->first_name .' ' .$guarantor->last_name}},
    con número de cédula {{$guarantor->identification_card}}, socio de la <strong>Caja de Ahorro y Prestamos Familiar “CAPFA”.</strong>
    @endif
</p>
<br />
<p>Por la atención al presente, anticipo mis agradecimientos.</p>
<br />
<br />
<p>Atentamente, </p>
<br />
<br />
<br />
@if($guarantor === null)
<p style="text-align: center;">………………………………</p>
<p style="text-align: center;">{{$loan->person->identification_card}}</p>
<p style="text-align: center;">{{$loan->person->first_name .' ' .$loan->person->last_name}}</p>
@else
<table>
    <tbody>
        <tr>
            <td style="width: 325px;">
                <p>………………………………</p>
                <p>{{$loan->person->identification_card}}</p>
                <p>{{$loan->person->first_name .' ' .$loan->person->last_name}}</p>
            </td>
            <td style="width: 325px;">
                <p>………………………………</p>
                <p>{{$guarantor->identification_card}}</p>
                <p>{{$guarantor->first_name .' ' .$guarantor->last_name}}</p>
            </td>
        </tr>
    </tbody>
</table>
@endif
@endsection