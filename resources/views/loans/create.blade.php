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
                <h1>Registro de Prestamo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('loans') }}">Prestamos</a></li>
                    <li class="breadcrumb-item active">Registro de Prestamo</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col-md-6 -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Solicitante de Préstamo</h3>
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
                        <h3 class="card-title">Garante de Préstamo</h3>
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
                        <h3 class="card-title">Monto y Porcentaje</h3>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('loans.store') }}">
                            {{ csrf_field() }}
                            <input type="hidden" id="person_id" name="person_id" required>
                            <input type="hidden" id="guarantor_id" name="guarantor_id" required>
                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="amount ">Monto</label>
                                <div class="col-sm-10">
                                    <input type="number" onkeyup="keypressAmount(this)" max="10000" class="form-control" id="amount" name="amount" required>
                                </div>
                            </div>
                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="interest_percentage">Porcentaje</label>
                                <div class="col-sm-10">
                                    <select class="custom-select form-control" id="interest_percentage" name="interest_percentage" required>
                                        <option>Seleccione</option>
                                        <option value="0.9">0.9%</option>
                                        <option value="1">1%</option>
                                        <option value="2">2%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="date">Fecha</label>
                                <div class="col-sm-10">
                                    <input type="date" value="{{date('Y-m-d')}}" class="form-control" id="date-loans" name="date" required>
                                </div>
                            </div>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="example1" class="table table-bordered" style="width:100%">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-rows-modal" style="cursor: pointer;">
                        </tbody>
                    </table>
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
        $('#modal-title').text('Seleccionar Solicitante');
        getPeople()
    }

    // Mostrar el modal para seleccionar el Garante del prestamo
    function selectGuarantorApplicant() {
        $('#modal-title').text('Seleccionar Garante');
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
        });
    }

    // Load HTML with data in table
    function loadHml(people) {
        let html = ''
        people.map((person, index) => {
            html += '<tr onclick=select_person(' + person.id + ')>'
            html += '<td>' + person.identification_card + '</td>'
            html += '<td>' + person.first_name + '</td>'
            html += '<td>' + person.last_name + '</td>'
            html += '<td>'
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
        let modal_title = $('.modal-title').text();
        let person = people.filter(p => p.id === id)[0]
        let name = person.first_name + ' ' + person.last_name
        if (modal_title === 'Seleccionar Solicitante') {
            $('#person_id').val(id);
            $('#name_person_loan').val(name);
            if (person.type === 'particular') {
                $('#card-guarantor').removeAttr('hidden')
            }
        } else {
            $('#guarantor_id').val(id);
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
</script>
@endpush