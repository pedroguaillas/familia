@extends('layouts.dashboard')

@push('csss')
<!-- DataTables -->
<link href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<!-- <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css"> -->
<link href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
<!-- <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css"> -->
<link href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<!-- <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
@endpush

@section('content')
<div class="col-12">
    @if('session'('mensaje'))
    <div class="alert alert-info">
        {{session('mensaje')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
    </div>
    @endif
</div>
</br>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col-md-6 -->
            <div class="col-12">
                <!-- card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">PRESTAMOS</h3>
                        <div class="card-tools">
                            <div class="dt-buttons btn-group flex-wrap">
                                <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="example1" type="button">
                                    <span>PDF</span>
                                </button>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <a href="{{ route('loans.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>DEUDOR</th>
                                    <th>MONTO</th>
                                    <th>INTERES</th>
                                    <th>PAGADO</th>
                                    <th>SALDO</th>
                                    <th>FECHA</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loans as $loan)
                                <tr>
                                    <!-- <input type="hidden" class="serdelete_val" value="1"> -->
                                    <td>{{$loan['id']}}</td>
                                    <td>{{$loan['first_name'].' '. $loan['last_name']}}</td>
                                    <td>{{'$'. number_format($loan['amount'], 2, ',', '.')}}</td>
                                    <td>{{$loan['interest_percentage']. '%'}}</td>
                                    <td>$500</td>
                                    <td>$800</td>
                                    <td>{{substr($loan['date'], 0, 10)}}</td>
                                    <td>
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link" data-toggle="dropdown" href="#">
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                    <a href="{{ route('prestamos.pagos', $loan['id']) }}" class="dropdown-item">
                                                        <i class="fa fa-money-bill"></i> Pagos
                                                    </a>
                                                    <a href="{{ route('prestamo.imprimir')}}" class="dropdown-item" target="_blank">
                                                        <i class="far fa-file"></i> Imprimir
                                                    </a>
                                                    <a href="#" class="dropdown-item">
                                                        <i class="far fa-edit"></i> Editar
                                                    </a>
                                                    <a href="#" class="dropdown-item">
                                                        <i class="far fa-trash-alt"></i> Anular
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@push('scripts')
<!-- Page specific script -->
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
@endpush