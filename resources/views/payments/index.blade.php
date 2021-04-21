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
                        <h3 class="card-title">PAGOS</h3>
                        <div class="card-tools">
                            <div class="dt-buttons btn-group flex-wrap">
                                <a class="btn btn-secondary btn-sm" href="{{route('prestamos.pagos.pdf', $loan->id)}}" target="_blank">
                                    <i class="far fa-file-pdf"></i>
                                </a>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button onclick="showModalCreate('{{$loan->id}}')" class="create-modal btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Nº</th>
                                    <!-- <th>Saldo Actual</th> -->
                                    <th>DEUDA</th>
                                    <th>PAGO CAPITAL</th>
                                    <th>PAGO INTERES</th>
                                    <th>PAGO MORA</th>
                                    <th>FECHA PAGO</th>
                                    <th colspan="2"></th>
                                </tr>
                            </thead>
                            @php
                            $i=0;
                            $sum_capital=0;
                            $sum_interest=0;
                            $sum_must=0;
                            @endphp
                            <tbody>
                                @foreach ($payments as $payment)
                                <tr>
                                    @php
                                    $i++;
                                    $sum_capital+=$payment->capital;
                                    $sum_interest+=$payment->interest_amount;
                                    $sum_must+=$payment->must;
                                    @endphp
                                    <input type="hidden" class="serdelete_val" value="{{ $payment->id }}">
                                    <td style="text-align: center;">{{$i}}</td>
                                    <td style="text-align: right;">{{number_format($payment->debt, 2, ',', '.')}}</td>
                                    <td style="text-align: right;">{{number_format($payment->capital, 2, ',', '.')}}</td>
                                    <td style="text-align: right;">{{number_format($payment->interest_amount, 2, ',', '.')}}</td>
                                    <td style="text-align: right;">{{number_format($payment->must, 2, ',', '.')}}</td>
                                    <td style="text-align: center;">{{substr($payment->date, 0, 10)}}</td>
                                    <td style="text-align: center;">
                                        @if($payment->observation!==null)
                                        <span class="badge bg-info" title="{{$payment->observation}}"><i class="far fa-newspaper"></i></span>
                                        @endif
                                    </td>
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
                                                    <button class="dropdown-item" onclick='editPayment("{{$payment->id}}")'>
                                                        <i class="far fa-edit"></i> Editar
                                                    </button>
                                                    <button class="dropdown-item" onclick='paymentDelete("{{$payment->id}}")'>
                                                        <i class="far fa-trash-alt"></i> Anular
                                                    </button>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="text-align: center;">TOTAL</th>
                                    <th style="text-align: right;">
                                        {{'$' .(count($payments) ? number_format($payments[count($payments)-1]->debt - $payments[count($payments)-1]->capital, 2, ',', '.') : number_format($loan->amount, 2, ',', '.'))}}
                                    </th>
                                    <th style="text-align: right;">{{number_format($sum_capital, 2, ',', '.')}}</th>
                                    <th style="text-align: right;">{{number_format($sum_interest, 2, ',', '.')}}</th>
                                    <th style="text-align: right;">{{number_format($sum_must, 2, ',', '.')}}</th>
                                    <th></th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
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

<div id="createPayment" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Registrar Nuevo Pago</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" action="{{route('payments.store')}}">
                    {{ csrf_field() }}

                    <input type="hidden" id="loan_id" name="loan_id" value="{{$loan->id}}"> <!-- codigo del prestamo  -->

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="debt "> Deuda </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="debt" name="debt" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="interest_amount "> Interés </label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control form-control-sm" id="interest_amount" step="0.01" name="interest_amount" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="capital">Capital</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control form-control-sm" id="capital" step="0.01" name="capital" value="0" maxlength="10" required>
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="must">Mora</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control form-control-sm" id="must" step="0.01" name="must" value="0" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="date">Fecha Inicio</label>
                        <div class="col-sm-9">
                            <input type="date" value="{{date('Y-m-d')}}" class="form-control form-control-sm" id="date_start" name="date_start" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="date">Fecha Fin</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control form-control-sm" id="date_end" name="date_end">
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="boservation">Observación</label>
                        <div class="col-sm-9">
                            <textarea name="observation" class="form-control" rows="2"></textarea>
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

<!-- /.MODAL EDIT -->
<div class="modal fade" id="editModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Editar Pago</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" role="form" method="POST" id="editForm">
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="debt">Deuda</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-sm" id="debt_edit" name="debt" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="interest_amount ">Interes</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-sm" id="interest_amount_edit" name="interest_amount" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="capital">Capital</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-sm" id="capital_edit" name="capital" required>
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="must">Mora</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-sm" id="must_edit" name="must" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="date">Fecha</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control form-control-sm" id="date_edit" name="date" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="boservation">Observación</label>
                        <div class="col-sm-9">
                            <textarea name="observation" class="form-control" id="observation_edit" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">
                            Guardar
                        </button>
                        <button class="btn btn-warning" type="button" data-dismiss="modal">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
<script>
    // Show Modal Edit Payments
    function editPayment(id) {
        $.ajax({
            type: 'GET',
            url: "{{url('payments')}}/" + id,
            success: (response) => {
                let {
                    payment
                } = response
                $('#debt_edit').val(payment.debt)
                $('#interest_amount_edit').val(payment.interest_amount)
                $('#capital_edit').val(payment.capital)
                $('#must_edit').val(payment.must)
                $('#date_edit').val(payment.date.substring(0, 10))
                $('#observation_edit').val(payment.observation)
            },
            error: (error) => console.log(error)
        });

        $('#editForm').attr('action', "{{url('payments')}}/" + id)
        $('#editModal').modal('show')
    }

    function paymentDelete(id) {
        swal({
                title: "¿Esta seguro?",
                text: "Eliminar pago",
                icon: "warning",
                buttons: ["Cancelar", "Ok"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('payments')}}/" + id,
                        data: {
                            "_token": $('meta[name="csrf-token"]').content,
                            "_method": "DELETE"
                        },
                        success: () => {
                            swal({
                                    text: "Se elimino un pago",
                                    icon: "success"
                                })
                                .then((result) => {
                                    location.reload()
                                });
                        }
                    })
                }
            })
    }

    function showModalCreate(loan_id) {
        $.ajax({
            type: 'GET',
            url: "{{url('payments')}}/interestCalculate/" + loan_id,

            success: (response) => {
                $('#debt').val(response.debt)
                $('#interest_amount').val(response.interest)
            },
            error: (error) => console.log(error)
        });

        $('#createPayment').modal('show')
        $('.form-horizontal').show()
    }
</script>
@endpush