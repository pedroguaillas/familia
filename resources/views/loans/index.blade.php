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
                                <a class="btn btn-secondary btn-sm" href="{{route('loans.pdf')}}" target="_blank">
                                    <i class="far fa-file-pdf"></i>
                                </a>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <a href="{{ route('loans.create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Nº</th>
                                    <th>DEUDOR</th>
                                    <th>PRESTAMO</th>
                                    <th>INTERES</th>
                                    <th>PAGADO</th>
                                    <th>DEUDA</th>
                                    <th>FECHA</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i=1;
                                @endphp
                                @foreach ($loans as $loan)
                                <tr>
                                    <td style="text-align: center;">{{ $i }}</td>
                                    <td>{{ $loan->first_name.' '. $loan->last_name }}</td>
                                    <td style="text-align: right;">{{ number_format($loan->amount, 2, ',', '.') }}</td>
                                    <td style="text-align: center;">{{ $loan->interest_percentage. '%' }}</td>
                                    <td style="text-align: right;">{{ number_format($loan->sum_capital_paid, 2, ',', '.') }}</td>
                                    <td style="text-align: right;">{{ number_format($loan->amount - $loan->sum_capital_paid, 2, ',', '.') }}</td>
                                    <td style="text-align: center;">{{ substr($loan->date, 0, 10) }}</td>
                                    <td>
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link" data-toggle="dropdown" href="#">
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                    <a href="{{ route('prestamo.pagos', $loan->id) }}" class="dropdown-item">
                                                        <i class="fa fa-money-bill"></i> Pagos
                                                    </a>
                                                    @if($loan->method !== 'inicio')
                                                    <a href="{{ route('prestamo.renovacion', $loan->id) }}" class="dropdown-item">
                                                        <i class="far fa-list-alt"></i> Renovar
                                                    </a>
                                                    <!-- <button onclick='showModalNovacion("{{ $loan->id }}")' class="dropdown-item">
                                                        <i class="far fa-edit"></i> Renovar
                                                    </button> -->
                                                    @endif
                                                    <a href="{{ route('prestamo.renovaciones', $loan->id) }}" class="dropdown-item">
                                                        <i class="far fa-list-alt"></i> Renovaciones
                                                    </a>
                                                    <a href="{{ route('prestamos.solicitud', $loan->id) }}" class="dropdown-item" target="_blank">
                                                        <i class="far fa-file"></i> Solicitud
                                                    </a>
                                                    <a href="{{ route('loans.edit', $loan->id) }}" class="dropdown-item">
                                                        <i class="far fa-edit"></i> Editar
                                                    </a>
                                                    <button onclick='deleteLoan("{{ $loan->id }}")' title="delete" class="dropdown-item" style="border: none; background-color:transparent;">
                                                        <i class="fas fa-trash"></i> Anular
                                                    </button>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @php
                                $i+=1;
                                @endphp
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

<!-- /.Aporte EDIT -->
<div class="modal fade" id="novacion-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Renovación de crédito</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" id="novacion-form">
                    {{ csrf_field() }}

                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="person-name">Solicitante</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="person-name" disabled>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="date">Fecha de préstamo</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control form-control-sm" id="date-loan" disabled>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <table style="margin: .5em;" class="table table-bordered table-sm">
                            <tbody>
                                <tr>
                                    <td colspan="2">Nuevo préstamo</td>
                                    <td style="text-align: right;">
                                        <input name="amount" onchange="changeNewLoan(this)" class="form-control-sm" style="width: 7em;" type="number" value="0" step="1" min="0" max="10000" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Préstamo</td>
                                    <td style="text-align: right;" id="amount-loan"></td>
                                    <td style="text-align: right;" id="amount-loan-edit">900</td>
                                </tr>
                                <tr>
                                    <td>Capital pagado</td>
                                    <td style="text-align: right;" id="paid-loan"></td>
                                    <td style="text-align: right;" id="paid-loan-edit"></td>
                                </tr>
                                <tr>
                                    <td>Interés</td>
                                    <td style="text-align: right;" id="interest-loan"></td>
                                    <td style="text-align: right;">
                                        <select name="interest_percentage" class="custom-select form-control" style="width: 7em;" id="interest-loan-edit" required>
                                            <option>Seleccione</option>
                                            <option value="0.9">0.9%</option>
                                            <option value="1">1%</option>
                                            <option value="2">2%</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Deuda</td>
                                    <td style="text-align: right;" id="debt-loan"></td>
                                    <td style="text-align: right;" id="debt-loan-edit"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="date_renovation">Fecha de renovación</label>
                        <div class="col-sm-8">
                            <input name="date" type="date" onchange="changeDate(this)" class="form-control form-control-sm" id="date-loan-renovation" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-8" for="interest_ajust">Pago de ajuste de interés</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control form-control-sm" name="interest_amount" id="interest-ajust" step="0.01" required>
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
@endsection

@push('scripts')
<!-- Page specific script -->
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script>
    function showModalNovacion(id) {
        $.ajax({
            type: 'GET',
            url: "{{url('loans')}}/" + id,
            success: (res) => {
                let {
                    loan
                } = res

                $('#novacion-form').attr('action', "{{url('loans/renovation')}}/" + id)

                $('#person-name').val(res.person.first_name + ' ' + res.person.last_name)
                $('#date-loan').val(loan.date.substring(0, 10))
                $('#amount-loan').text(loan.amount)
                $('#amount-loan-edit').text(loan.amount)
                $('#paid-loan').text(loan.debt)
                $('#paid-loan-edit').text(loan.debt)
                $('#interest-loan').text(loan.interest_percentage + '%')
                $('#interest-loan-edit').val(loan.interest_percentage)

                let debt = Number(res.loan.amount) - Number(res.loan.debt)
                let interest30days = debt * Number(loan.interest_percentage) * 0.01

                let day_difference = calDays(loan.date, $('#date-loan-renovation').val())

                $('#debt-loan').text(debt.toFixed(2))
                $('#debt-loan-edit').text(debt.toFixed(2))
                $('#interest-ajust').val((interest30days * day_difference / 30).toFixed(2))

                $('#novacion-modal').modal('show')
            },
            error: (error) => console.log(error)
        })
    }

    function calDays(date_loan, date_renovation) {

        let date_l = date_loan.substring(0, 10).split("-")
        let date_r = date_renovation.substring(0, 10).split("-")

        let day_l = parseInt(date_l[2])
        let day_r = parseInt(date_r[2])

        if (day_r >= day_l) {
            return day_r - day_l
        } else {
            let mouhts = parseInt(date_r[1])
            // El mes es mayor a enero
            if (mouhts > 1) {
                date_l[1] = (mouhts - 1).toString()
                date_l[0] = date_r[0]
            } else {
                date_l[1] = (12).toString()
                date_l[0] = (parseInt(date_r[0]) - 1).toString()
            }
            let new_date_l = new Date(date_l.join("-"))
            let new_date_r = new Date(date_r.join("-"))

            // return parseInt((new_date_r - new_date_l) / (1000 * 60 * 60 * 24))
            // 86400000 Milisegundos
            return Math.round((new_date_r - new_date_l) / 86400000)
        }
    }

    function changeNewLoan(e) {
        let value = Number(e.value)
        let amount = Number($('#amount-loan').text())
        let new_amount = amount + value
        $('#amount-loan-edit').text(new_amount)
        let paid = Number($('#paid-loan').text())
        $('#debt-loan-edit').text(new_amount - paid)
        let interest_percentage = $('#interest-loan').text().replace('%', '')
        $('#interest-loan-edit').val(Number(interest_percentage) === 1 && new_amount > 999 ? '0.9' : interest_percentage)
    }

    function changeDate(e) {

        let debt = Number($('#amount-loan').text()) - Number($('#paid-loan').text())
        let interest30days = debt * Number($('#interest-loan').text().replace('%', '')) * 0.01

        let day_difference = calDays($('#date-loan').val(), e.value)

        $('#interest-ajust').val((interest30days * day_difference / 30).toFixed(2))
    }

    function deleteLoan(id) {
        swal({
                title: "¿Esta seguro?",
                text: "Eliminar préstamo",
                icon: "warning",
                buttons: ["Cancelar", "Ok"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('loans')}}/" + id,
                        data: {
                            "_token": $('meta[name="csrf-token"]').content,
                            "_method": "DELETE"
                        },
                        success: () => {
                            swal({
                                    text: "Se elimino un préstamo",
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
</script>
@endpush