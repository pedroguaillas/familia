@extends('layouts.pdf')

@section('title', 'Solicitud compra de acciones')

@section('content')
<p style="text-align: right;">San Lucas, {{date('d')}} de abril de {{date('Y')}}</p>
<br />
<p>Jaime Ramiro Gualan Aguilar</p>
<strong>Gerente de la Caja de Ahorros y Prestamos Familiar (CAPFA).</strong>
<br />
<br />
<p style="text-align: justify;">Por medio del presente yo, {{$person->first_name .' ' .$person->last_name}}, con número de cédula {{$person->identification_card}}, solícito se me autorice la compra de {{$person->actions}} acciones, comprometiéndome a realizar todos los pagos que hasta la actualidad que tiene cada socio y los pagos para gastos de la caja de acuerdo a las normativas establecida por los socios de la <strong>Caja de Ahorro y Prestamos Familiar “CAPFA”.</strong></p>
<br />
<p>Por la atención al presente, anticipo mis agradecimientos.</p>
<br />
<br />
<p>Atentamente, </p>
<br />
<br />
<br />
<p style="text-align: center;">………………………………</p>
<p style="text-align: center;">{{$person->identification_card}}</p>
<p style="text-align: center;">{{$person->first_name .' ' .$person->last_name}}</p>
@endsection