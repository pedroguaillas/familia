@extends('layouts.dashboard')

@push('csss')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('loans') }}">Préstamos</a></li>
                    <li class="breadcrumb-item active">Renovaciones</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">DATOS DEL PRESTAMO</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <p>
                                    <strong>Deudor: </strong>
                                    {{$person->first_name.' '.$person->last_name}}
                                </p>
                            </div>
                            <div class="col-sm-4" {{ $guarantor !== null ? null : 'hidden'}}>
                                <p>
                                    <strong>Garante: </strong>
                                    {{$guarantor === null ? null : $guarantor->first_name.' '.$guarantor->last_name}}
                                </p>
                            </div>
                            <div class="col-sm-2">
                                <p>
                                    <strong>Monto: </strong>
                                    {{number_format($loan->amount, 2, ',', '.')}}
                                </p>
                            </div>
                            <div class="col-sm-2">
                                <p>
                                    <strong>Interés: </strong>
                                    {{$loan->interest_percentage .'%'}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        <div class="row">
            <!-- /.col-md-6 -->
            <div class="col-md-12">
                <!-- card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">RENOVACIONES</h3>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Nº</th>
                                    <th>PORCENTAJE DE INTERES</th>
                                    <th>MONTO</th>
                                    <th>FECHA DE RENOVACION</th>
                                </tr>
                            </thead>
                            @php
                            $i=0;
                            @endphp
                            <tbody>
                                @foreach ($loan_renewals as $item)
                                <tr>
                                    @php
                                    $i++;
                                    @endphp
                                    <td style="text-align: center;">{{$i}}</td>
                                    <td style="text-align: center;">%{{$item->interest_percentage}}</td>
                                    <td style="text-align: right;">{{number_format($item->amount, 2, ',', '.')}}</td>
                                    <td style="text-align: center;">{{substr($item->date, 0, 10)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
@endpush