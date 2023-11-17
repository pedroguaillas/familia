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
                <h1>Registro de préstamo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('loans') }}">Préstamos</a></li>
                    <li class="breadcrumb-item active">Registro de préstamo</li>
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
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Solicitante de préstamo</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" id="name_person_loan" class="form-control">
                            <span class="input-group-append">
                                <button title="Buscar" type="button" onclick="selectPersonApplicant()" class="btn btn-secondary btn-flat">
                                    <i class="fas fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div id="card-guarantor" class="card card-primary" hidden>
                    <div class="card-header">
                        <h3 class="card-title">Garante de préstamo</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" id="name_guarantor_loan" class="form-control">
                            <span class="input-group-append">
                                <button title="Buscar" type="button" onclick="selectGuarantorApplicant()" class="btn btn-secondary btn-flat">
                                    <i class="fas fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-md-6 -->

            <!-- col-md-6 -->
            <div class="col-md-6">
                <!-- card -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Monto y porcentaje</h3>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('loans.store') }}">

                            {{ csrf_field() }}
                            <input type="hidden" id="person_id" name="person_id" required>
                            <input type="hidden" id="guarantor_id" name="guarantor_id" required>

                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="amount">Monto</label>
                                <div class="col-sm-10">
                                    <input type="number" max="10000" class="form-control" id="amount" name="amount" step="0.01" value="{{ old('amount') }}" required>
                                    <!-- <input type="number" onkeyup="keypressAmount(this)" max="10000" class="form-control" id="amount" name="amount" step="0.01" required> -->
                                </div>
                            </div>

                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="interest_percentage">Porcentaje</label>
                                <div class="col-sm-10">
                                    <input type="number" min="0.5" max="3.0" class="form-control" id="interest_percentage" name="interest_percentage" step="0.01" value="{{ old('interest_percentage') }}" required>
                                    <!-- <select class="custom-select form-control" id="interest_percentage" name="interest_percentage" required>
                                        <option>Seleccione</option>
                                        <option value="0.9">0.9%</option>
                                        <option value="1">1%</option>
                                        <option value="2">2%</option>
                                    </select> -->
                                </div>
                            </div>

                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="date">Fecha</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="date-loans" name="date" value="{{ old('date') ?? date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="type">Pago</label>
                                <div class="col-sm-10">
                                    <select class="custom-select form-control" id="type" name="type" required>
                                        <option value="">Seleccione</option>
                                        <option value="mensual" {{ old('type') === 'mensual' ? 'selected' : '' }}>Mensual</option>
                                        <option value="trimestral" {{ old('type') === 'trimestral' ? 'selected' : '' }}>Trimestral</option>
                                        <option value="semestral" {{ old('type') === 'semestral' ? 'selected' : '' }}>Semestral</option>
                                        <option value="anual" {{ old('type') === 'anual' ? 'selected' : '' }}>Anual</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="deadline">Período</label>
                                <div class="col-sm-10">
                                    <input type="number" min="1" max="120" class="form-control  @error('period') is-invalid @enderror" id="deadline" name="period" step="1" value="{{ old('period') }}" required>
                                </div>
                            </div>

                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="typetable">Tabla</label>
                                <div class="col-sm-10">
                                    <select class="custom-select form-control" id="typetable" name="method" required>
                                        <option value="">Seleccione</option>
                                        <option value="fija" {{ old('method') === 'fija' ? 'selected' : '' }}>Fija</option>
                                        <option value="variable" {{ old('method') === 'variable' ? 'selected' : '' }}>Variable</option>
                                    </select>
                                </div>
                            </div>

                            <button onclick="creartablaamortizacion()" class="btn btn-info" type="button">
                                <span class="glyphicon glyphicon-plus"></span> Calcular
                            </button>
                            <button class="btn btn-success" type="submit">
                                <span class="glyphicon glyphicon-plus"></span> Guardar
                            </button>
                            <button class="btn btn-warning" type="button">
                                <span class="glyphicon glyphicon-remove"></span> Cancelar
                            </button>

                        </form>
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
</div>
@endsection

@push('scripts')
<script>
    let people = undefined
    // Mostrar el modal para seleccionar la persona que solicita el prestamo
    function selectPersonApplicant() {
        $('#modal-title').text('Seleccionar solicitante')
        getPeople()
    }

    // Mostrar el modal para seleccionar el Garante del prestamo
    function selectGuarantorApplicant() {
        $('#modal-title').text('Seleccionar garante')
        // No se puede repetir el Solicitante con el Garante 
        // Por que solo cuando sea Particular requiere Garante y el Garante va ser socio
        // Para lo cual hay que filtrar solo socios
        let garantors = people.filter(p => p.type === 'socio')
        loadHml(garantors)
    }

    // Petition Ajax
    function getPeople() {
        $.ajax({
            type: 'GET',
            url: "{{route('people.index.json')}}",
            success: (response) => {
                people = response.people
                loadHml(people)
            },
            error: (error) => console.log(error)
        })
    }

    // Load HTML with data in table
    function loadHml(people) {
        let html = ''
        people.map((person, index) => {
            html += '<tr onclick=select_person(' + person.id + ')>'
            html += '<td style="text-align: center;">' + (person.identification_card ?? '') + '</td>'
            html += '<td>' + person.first_name + '</td>'
            html += '<td>' + person.last_name + '</td>'
            html += '<td style="text-align: center;">'
            html += '<span class="badge ' + (person.type === 'socio' ? 'bg-success' : 'bg-warning') + '" style="font-size:0.9em">' + person.type + '</span>'
            html += '</td>'
            html += "</tr>"
        })
        $('#tbody-rows-modal').html(html)
        $('#select-person').modal('show')
        $('.form-horizontal').show()
    }

    // Selecciona la persona del modal y Oculta el Modal
    function select_person(id) {
        let modal_title = $('#modal-title').text()
        let person = people.filter(p => p.id === id)[0]
        let name = person.first_name + ' ' + person.last_name
        if (modal_title === 'Seleccionar solicitante') {
            $('#person_id').val(id)
            $('#name_person_loan').val(name)
            if (person.type === 'particular') {
                $('#card-guarantor').removeAttr('hidden')
            }
        } else {
            $('#guarantor_id').val(id)
            $('#name_guarantor_loan').val(name)
        }
        $('#select-person').modal('hide')
    }

    // Selecciona la persona del modal y Oculta el Modal
    function keypressAmount(e) {
        let person = people.filter(p => Number(p.id) === Number($('#person_id').val()))[0]

        if (person.type === 'socio') {
            $('#interest_percentage').val(Number(e.value) > 999 ? 0.9 : 1)
        } else {
            $('#interest_percentage').val(2)
        }
    }

    // Crear la tabla de amortización
    function creartablaamortizacion() {

        let monto = Number($('#amount').val())
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
            capital = monto / periodo
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
        $('#modalamortizacion').modal('show')
    }
</script>
@endpush