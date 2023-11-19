@extends('layouts.dashboard')

@push('csss')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
@endpush

@section('content')
@if('session'('success'))
<div class="col-12">
    <div class="alert alert-info">
        {{session('success')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
    </div>
</div>
@elseif('session'('danger'))
<div class="col-12">
    <div class="alert alert-danger">
        {{session('danger')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
    </div>
</div>
@endif

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
                                    {{$guarantor === null ? null : $guarantor->first_name . ' ' . $guarantor->last_name}}
                                </p>
                            </div>
                            <div class="col-sm-2">
                                <p>
                                    <strong>Monto: </strong>
                                    {{ number_format($loan->amount, 2, ',', '.') }}
                                </p>
                            </div>
                            <div class="col-sm-2">
                                <p>
                                    <strong>Interés: </strong>
                                    {{ $loan->interest_percentage .'%' }}
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
                                <a class="btn btn-secondary btn-sm" href="{{ route('prestamo.pagos.reporte', $loan->id) }}" target="_blank">
                                    <i class="far fa-file-pdf"></i>
                                </a>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button onclick="showModalCreate('{{ $loan->id }}')" class="create-modal btn btn-success btn-sm">
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
                                    <th>PAGO TOTAL</th>
                                    <th>FECHA PAGO</th>
                                    <th colspan="2"></th>
                                </tr>
                            </thead>
                            @php
                            $i = 0;
                            $sum_capital = 0;
                            $sum_interest = 0;
                            $sum_must = 0;
                            @endphp
                            <tbody>
                                @foreach ($payments as $payment)
                                <tr>
                                    @php
                                    $i++;
                                    $sum_capital += $payment->capital;
                                    $sum_interest += $payment->interest_amount;
                                    $sum_must += $payment->must;
                                    @endphp
                                    <input type="hidden" class="serdelete_val" value="{{ $payment->id }}">
                                    <td style="text-align: center;">{{ $i }}</td>
                                    <td style="text-align: right;">{{ number_format($payment->debt, 2, ',', '.') }}</td>
                                    <td style="text-align: right;">{{ number_format($payment->capital, 2, ',', '.') }}</td>
                                    <td style="text-align: right;">{{ number_format($payment->interest_amount, 2, ',', '.') }}</td>
                                    <td style="text-align: right;">{{ number_format($payment->must, 2, ',', '.') }}</td>
                                    <td style="text-align: right;">{{ number_format($payment->capital + $payment->interest_amount + $payment->must, 2, ',', '.') }}</td>
                                    <td style="text-align: center;">{{ substr($payment->date, 0, 10) }}</td>
                                    <td style="text-align: center;">
                                        @if($payment->observation !== null)
                                        <span class="badge bg-info" title="{{ $payment->observation }}"><i class="far fa-newspaper"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link" data-toggle="dropdown" href="#">
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                    <a href="{{ route('prestamo.pago.comprobante', $payment->id) }}" class="dropdown-item" target="_blank">
                                                        <i class="far fa-file"></i> Imprimir
                                                    </a>
                                                    <button class="dropdown-item" onclick='editPayment("{{ $payment->id }}")'>
                                                        <i class="far fa-edit"></i> Editar
                                                    </button>
                                                    <button class="dropdown-item" onclick='paymentDelete("{{ $payment->id }}")'>
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
                                        {{ '$' . (count($payments) ? number_format($payments[count($payments)-1]->debt - $payments[count($payments)-1]->capital, 2, ',', '.') : number_format($loan->amount, 2, ',', '.')) }}
                                    </th>
                                    <th style="text-align: right;">{{ number_format($sum_capital, 2, ',', '.') }}</th>
                                    <th style="text-align: right;">{{ number_format($sum_interest, 2, ',', '.') }}</th>
                                    <th style="text-align: right;">{{ number_format($sum_must, 2, ',', '.') }}</th>
                                    <th style="text-align: right;">{{ number_format($sum_capital + $sum_interest + $sum_must, 2, ',', '.') }}</th>
                                    <th colspan="3"></th>
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

<div id="create-payment" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Registrar nuevo pago</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('payments.store') }}">
                    {{ csrf_field() }}

                    <input type="hidden" id="loan_id" name="loan_id" value="{{ $loan->id }}"> <!-- codigo del prestamo  -->
                    <input type="hidden" id="debt" name="debt"> <!-- Deuda actual  -->
                    <input type="hidden" id="payment_id" name="payment_id"> <!-- Deuda actual  -->

                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="debt "> Deuda </label>
                        <div class="col-sm-8">
                            <input class="form-control form-control-sm" id="debt_input" required disabled>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="interest_amount"> Interés </label>
                        <div class="col-sm-8">
                            <input onchange="sumtotal()" type="number" class="form-control form-control-sm" id="interest_amount" step="0.01" name="interest_amount" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="capital">Capital</label>
                        <div class="col-sm-8">
                            <input onchange="sumtotal()" type="number" class="form-control form-control-sm" id="capital" step="0.01" name="capital" value="0" maxlength="10" required>
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="must">Mora</label>
                        <div class="col-sm-8">
                            <input onchange="sumtotal()" type="number" class="form-control form-control-sm" id="must" step="0.01" name="must" value="0" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="date-loan">Fecha préstamo</label>
                        <div class="col-sm-8">
                            <input value="{{ date('d/m/Y', strtotime(substr($loan->date, 0, 10))) }}" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="date">Fecha {{ $loan->method === 'inicio' ? 'Inicio' : 'Pago' }}</label>
                        <div class="col-sm-8">
                            <input type="date" value="{{ date('Y-m-' . substr($loan->date, 8, 2)) }}" class="form-control form-control-sm" id="date_start" name="date_start" required>
                        </div>
                    </div>
                    <div class="form-group row add" {{ $loan->method !== 'inicio' ? 'hidden' : null }}>
                        <label class="control-label col-sm-4" for="date">Fecha Fin</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control form-control-sm" id="date_end" name="date_end" min="{{ (int)date('m') < 12 ? date('Y-m-' .substr($loan->date, 8, 2), strtotime(date('Y-m-d'). ' +1 month')) : date('Y-m-' .substr($loan->date, 8, 2)) }}">
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="boservation">Observación</label>
                        <div class="col-sm-8">
                            <textarea name="observation" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="boservation">Total</label>
                        <label id="total-create" class="control-label col-sm-3">0.00</label>
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
                <h4 class="modal-title" style="margin: auto;">Editar pago</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" role="form" method="POST" id="editForm">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="debt">Deuda</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-sm" id="debt_edit" name="debt" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="interest_amount ">Interés</label>
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
        })

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
                        url: "{{ url('payments') }}/" + id,
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
                                })
                        }
                    })
                }
            })
    }

    let interest_amount = undefined
    let loan_day = undefined

    function showModalCreate(loan_id) {
        $.ajax({
            type: 'GET',
            url: "{{ url('payments') }}/interestCalculate/" + loan_id,
            success: (response) => {
                $('#debt').val(response.debt)
                $('#debt_input').val(response.debt)
                $('#interest_amount').val(response.interest)
                if (response.method !== 'inicio') {
                    // $('#date_start').val(response.day)
                    $('#payment_id').val(response.payment_id)
                }
                let day = parseInt($('#date_start').val().substring(8, 10))
                loan_day = response.day
                $('#must').val((day - loan_day > 0) ? (day - loan_day) * .25 : 0)
                $('#capital').val(response.method === 'inicio' ? 0 : response.capital)
                interest_amount = response.interest
                sumtotal()
            },
            error: (error) => console.log(error)
        })

        $('#create-payment').modal('show')
        $('.form-horizontal').show()
    }

    function sumtotal() {
        const interest = Number($('#interest_amount').val())
        const must = Number($('#must').val())
        const capital = Number($('#capital').val())
        $('#total-create').text((interest + must + capital).toFixed(2))
    }

    $('#date_start').change(function() {

        let date_str = $(this).val().substring(0, 10).split("-")
        let month = parseInt(date_str[1])

        if (month < 9) {
            // Suma mes
            date_str[1] = '0' + (month + 1)
        } else {
            if (month < 12) {
                // Suma mes
                date_str[1] = month + 1
            }
            if (month === 12) {
                // Suma año
                date_str[0] = parseInt(date_str[0]) + 1
                // Pone el mes 1
                date_str[1] = '0' + 1
            }
        }

        let day = Number(date_str[2])

        var date = date_str.join('-')
        $('#date_end').attr('min', date)
        // le multiplica 0.25 ctv por cada dia de atraso  
        $('#must').val((day - loan_day > 0) ? (day - loan_day) * .25 : 0)
        sumtotal()
    })

    // Desabilita el teclado numerico para la fecha final
    $('#date_end').on('keydown keypress', function(e) {
        e.preventDefault()
    })

    $('#date_end').change(function() {
        let moths_difference = calMonths($('#date_start').val(), $(this).val())
        let interest = interest_amount * moths_difference
        const must = Number($('#must').val())
        const capital = Number($('#capital').val())
        $('#total-create').text((interest + must + capital).toFixed(2))
    })

    function calMonths(date_start, date_end) {

        // d(date)
        let d_start = date_start.substring(0, 10).split("-")
        let d_end = date_end.substring(0, 10).split("-")

        // m(Month)
        let m_start = parseInt(d_start[1])
        let m_end = parseInt(d_end[1])

        return (m_end - m_start) + 1;
    }
</script>
@endpush