@extends('layouts.dashboard')

@push('csss')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('loans') }}">Prestamos</a></li>
                    <li class="breadcrumb-item active">Pagos</li>
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
                        <h3 class="card-title">Datos del Préstamo</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <p>
                                    <strong>Deudor: </strong>
                                    {{$person->first_name.' '.$person->last_name}}
                                </p>
                            </div>
                            <div class="col-sm-5">
                                <p>
                                    <strong>Garante: </strong>
                                    {{$person->first_name.' '.$person->last_name}}
                                </p>
                            </div>

                            <div class="col-sm-2">
                                <p>
                                    <strong>Monto: </strong>
                                    $100
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
                        <h3 class="card-title">Pagos</h3>
                        <div class="card-tools">
                            <div class="dt-buttons btn-group flex-wrap">
                                <a class="btn btn-secondary btn-sm" href="" target="_blank">
                                    <i class="far fa-file-pdf"></i>
                                </a>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <a href="#" class="create-modal btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <!-- <th>Nº</th> -->
                                    <th>Saldo</th>
                                    <th>P Capital</th>
                                    <th>P Interes</th>
                                    <th>P Mora</th>
                                    <th>Fecha Pago</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($payments as $dato)
                                <tr>
                                    <!--   <td>{{$dato['id']}}</td> -->
                                    <td style="text-align: right;">500</td>
                                    <td style="text-align: right;">{{'$' . number_format($dato['capital'], 2, ',', '.')}}</td>
                                    <td style="text-align: right;">{{'$' . number_format($dato['interest_amount'], 2, ',', '.')}}</td>
                                    <td style="text-align: right;">{{'$' . number_format($dato['must'], 2, ',', '.')}}</td>
                                    <td style="text-align: center;">{{substr($dato['date'], 0, 10)}}</td>
                                    <td>
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link" data-toggle="dropdown" href="#">
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
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
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->

@endsection

<div id="createPayment" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" action="{{route('payments.store')}}">
                    {{ csrf_field() }}

                    <input type="hidden" class="serdelete_val" id="loan_id" name="loan_id" value="{{$loan->id}}"> <!-- codigo del prestamo  -->

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="interest_amount "> Interes </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="interest_amount" name="interest_amount" onkeypress="return soloNumeros(event);" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="capital">Capital</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="capital" name="capital" onkeypress="return soloNumeros(event)" maxlength="10" required>
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="must">Mora</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="must" name="must" onkeypress="return soloNumeros(event)" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="date">Fecha Inicio</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control form-control-sm" id="date_start" name="date_start" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="date">Fecha Fin</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control form-control-sm" id="date_end" name="date_end" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit" id="add">
                            <span class="glyphicon glyphicon-plus"></span> Guardar
                        </button>
                        <button class="btn btn-warning" type="button" data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove"></span> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    /* SCRIPT FOR MODAL */
    $(document).on('click', '.create-modal', function() {
        $('#createPayment').modal('show');
        $('.form-horizontal').show();
        $('.modal-title').text('Registrar Nuevo Pago');
    });
</script>
@endpush