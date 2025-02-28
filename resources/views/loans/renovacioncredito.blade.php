@extends('layouts.dashboard')

@push('csss')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Renovación de préstamo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('loans') }}">Préstamos</a></li>
                    <li class="breadcrumb-item active">Renovación de préstamo</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

@if ($errors->any())
<div class="alert alert-danger mx-3">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col-md-6 -->
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Formulario de renovación de préstamo</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group input-group-md">
                            <span style="width: 100%;" class="input-group-text">
                                <strong>Solicitante:</strong>&nbsp; {{ $loan->person->first_name . ' ' . $loan->person->last_name }}
                            </span>
                        </div>

                        <input type="hidden" id="debt" value="{{ round($loan->amount - $pagado, 2) }}">

                        <form class="form-horizontal" role="form" method="POST" action="{{ route('loan.renew', $loan->id) }}">

                            {{ csrf_field() }}
                            @method('PUT')

                            <div class="input-group input-group-md mt-3">
                                <span style="width: 100%;" class="input-group-text">
                                    <strong>Préstamo</strong>&nbsp; ({{ $loan->amount }})&nbsp; - &nbsp;<strong>Pagado</strong>&nbsp; ({{ $pagado }})&nbsp; = &nbsp; {{ $loan->amount - $pagado }}
                                </span>
                            </div>

                            <div class="input-group input-group-md mt-3">
                                <div class="input-group-prepend">
                                    <span style="width: 10em;" class="input-group-text"><strong>Deuda:</strong>&nbsp; {{ $loan->amount - $pagado }}</span>
                                </div>
                                <input type="number" max="20000" class="form-control" name="amount" id="newDebt" onkeyup="keypressAmount(this)" step="0.01" value="{{ old('amount') }}" placeholder="monto" required>
                                <div class="input-group-append">
                                    <span style="width: 6em;" class="input-group-text text-right" id="amount">{{ $loan->amount - $pagado }}</span>
                                </div>
                            </div>

                            <div class="input-group input-group-md mt-3">
                                <div class="input-group-prepend">
                                    <span style="width: 10em;" class="input-group-text"><strong>Interes:</strong>&nbsp; {{ ' ' . $loan->interest_percentage . '%' }}</span>
                                </div>
                                <input type="number" min="0.5" max="20.0" class="form-control" id="interest_percentage" name="interest_percentage" step="0.01" value="{{ $loan->interest_percentage }}" required>
                            </div>

                            <div class="input-group input-group-md mt-3">
                                <div class="input-group-prepend">
                                    <span style="width: 10em;" class="input-group-text"><strong>Fecha:</strong>&nbsp; {{ date('d/m/Y', strtotime(substr($loan->date, 0, 10))) }}</span>
                                </div>
                                <input type="date" class="form-control" id="date-loans" name="date" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="input-group input-group-md mt-3">
                                <div class="input-group-prepend">
                                    <span style="width: 10em;" class="input-group-text"><strong>Pago:</strong>&nbsp; {{ $loan->type }}</span>
                                </div>
                                <select class="custom-select form-control" id="type" name="type" required>
                                    <option value="mensual" {{ $loan->type === 'mensual' ? 'selected' : '' }}>Mensual</option>
                                    <option value="trimestral" {{ $loan->type === 'trimestral' ? 'selected' : '' }}>Trimestral</option>
                                    <option value="semestral" {{ $loan->type === 'semestral' ? 'selected' : '' }}>Semestral</option>
                                    <option value="anual" {{ $loan->type === 'anual' ? 'selected' : '' }}>Anual</option>
                                </select>
                            </div>

                            <div class="input-group input-group-md mt-3">
                                <div class="input-group-prepend">
                                    <span style="width: 10em;" class="input-group-text"><strong>N° pagos:</strong>&nbsp; {{ $loan->period }}</span>
                                </div>
                                <input type="number" min="1" max="120" class="form-control @error('period') is-invalid @enderror" id="deadline" name="period" step="1" value="{{ $loan->period - $cantPagados }}" required>
                            </div>

                            <div class="input-group input-group-md my-3">
                                <div class="input-group-prepend">
                                    <span style="width: 10em;" class="input-group-text"><strong>Tipo tabla:</strong>&nbsp; {{ $loan->method }}</span>
                                </div>
                                <select class="custom-select form-control" id="typetable" name="method" required>
                                    <option value="fija" {{ $loan->method === 'fija' ? 'selected' : '' }}>Fija</option>
                                    <option value="variable" {{ $loan->method === 'variable' ? 'selected' : '' }}>Variable</option>
                                </select>
                            </div>

                            <button class="btn btn-warning" type="button">
                                <span class="glyphicon glyphicon-remove"></span> Cancelar
                            </button>
                            <button onclick="creartablaamortizacion()" class="btn btn-info" type="button">
                                <span class="glyphicon glyphicon-plus"></span> Simular
                            </button>
                            <button class="btn btn-success" type="submit">
                                <span class="glyphicon glyphicon-plus"></span> Renovar
                            </button>

                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>

            <!-- col -->
            <div class="col-md-8">
                <!-- card -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Tabla de amortización</h3>
                    </div>
                    <div class="card-body">

                        <table id="example1" class="table table-bordered table-sm table-striped" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th style="width: 1em;">Nº</th>
                                    <th>D. Inicio</th>
                                    <th>Capital</th>
                                    <th>Int</th>
                                    <th>Pago</th>
                                    <th>Feha</th>
                                    <th>D. Final</th>
                                </tr>
                            </thead>
                            <tbody id="tbodymodalamortizacion"></tbody>
                            <tfoot id="tfootmodalamortizacion"></tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col -->

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->

<div id="select-person" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-height: 85%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="example1" class="table table-bordered table-sm table-hover" style="width:100%">
                    <thead>
                        <tr style="text-align: center;">
                            <th>Cédula</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-rows-modal" style="cursor: pointer;">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de amortizacion -->
<div id="modalamortizacion" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-height: 85%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tabla de amortización</h4>
                <button type="button" class="close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Selecciona la persona del modal y Oculta el Modal
    function keypressAmount(e) {
        $('#amount').text((Number(e.value) + Number($('#debt').val())).toFixed(2))
    }

    // Crear la tabla de amortización
    function creartablaamortizacion() {

        let monto = Number($('#newDebt').val()) + Number($('#debt').val())
        let periodo = Number($('#deadline').val())
        let interes = Number($('#interest_percentage').val()) * 0.01
        let date = new Date(`${$('#date-loans').val()} GMT-5`);
        let tipo = $('#typetable').val()

        let conthtml = ''
        let deudainicial = monto

        let interescal = deudainicial * interes
        let capital = 0
        let pago = 0

        if (tipo === 'variable') {
            capital = Number((monto / periodo).toFixed(2))
            if (capital * periodo < monto) {
                capital += 0.01
            }
            pago = interescal + capital
        } else {
            // Pago con dos decimales para convertirle en fijo durante todo el periodo
            pago = Number((interescal / (1 - Math.pow(1 + interes, -periodo))).toFixed(2))
            capital = pago - interescal
        }
        let deudafinal = deudainicial - capital
        let sum = 0

        let month = '';

        switch ($('#type').val()) {
            case 'mensual':
                month = 1;
                break;
            case 'trimestral':
                month = 3;
                break;
            case 'semestral':
                month = 6;
                break;
            case 'anual':
                month = 12;
                break;
        }

        for (let i = 0; i < periodo; i++) {
            if (i > 0) {

                deudainicial = deudafinal
                interescal = deudainicial * interes

                if (tipo === 'variable') {
                    pago = interescal + capital
                } else {
                    capital = pago - interescal
                }

                deudafinal = deudainicial - capital
            }

            sum += pago

            date.setMonth(date.getMonth() + month)

            conthtml += '<tr class="text-right">'
            conthtml += '<td style="text-align: center;">' + (i + 1) + '</td>'
            conthtml += '<td>' + deudainicial.toFixed(2) + '</td>'
            conthtml += '<td>' + capital.toFixed(2) + '</td>'
            conthtml += '<td>' + interescal.toFixed(2) + '</td>'
            conthtml += '<td>' + pago.toFixed(2) + '</td>'
            conthtml += '<td style="text-align: center;">' + date.toLocaleDateString() + '</td>'
            conthtml += '<td>' + deudafinal.toFixed(2).replace('-', '') + '</td>'
            conthtml += "</tr>"
        }

        $('#tbodymodalamortizacion').html(conthtml)

        let tfoot = '<tr>'
        tfoot += '<th style="text-align: center;" colspan="4">TOTAL</th>'
        tfoot += '<th class="text-right">' + sum.toFixed(2) + '</th>'
        tfoot += '<th></th>'
        tfoot += '<th></th>'
        tfoot += '</tr>'

        $('#tfootmodalamortizacion').html(tfoot)
    }
</script>
@endpush