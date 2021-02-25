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
                                <button title="Buscar" type="button" id="select-person-modal" class="btn btn-secondary btn-flat">
                                    <i class="fas fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Garante de Préstamo</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" id="name_guarantor_loan" class="form-control">
                            <span class="input-group-append">
                                <button title="Buscar" type="button" id="select-guarantor-modal" class="btn btn-secondary btn-flat">
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
                            <input value="1" type="hidden" id="person_id" name="person_id" required>
                            <input value="1" type="hidden" id="guarantor_id" name="guarantor_id" required>
                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="interest_percentage">Porcentaje</label>
                                <div class="col-sm-10">
                                    <select class="custom-select form-control" id="interest_percentage" name="interest_percentage">
                                        <option>Seleccione</option>
                                        <option value="0.9">0.9%</option>
                                        <option value="1">1%</option>
                                        <option value="2">2%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="amount ">Monto</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="amount" name="amount" onkeypress="return soloNumeros(event);" required>
                                </div>
                            </div>
                            <div class="form-group row add">
                                <label class="control-label col-sm-2" for="date">Fecha</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="date" name="date" required>
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
    <div class="modal-dialog">
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
                                <th>CEDULA</th>
                                <th>NOMBRES</th>
                                <th>APELLIDOS</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-rows-modal">
                            @foreach ($people as $person)
                            <tr onclick='select_person({{$person['id']}})'>
                                <td>{{$person['identification_card']}}</td>
                                <td>{{$person['first_name']}}</td>
                                <td>{{$person['last_name']}}</td>
                            </tr>
                            @endforeach
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
    // Var global people
    // const people = "{{$people}}";

    //FUNCION QUE MUESTRA EL MODAL PARA SELECCIONAR SOLICITANTE 
    $(document).on('click', '#select-person-modal', function() {
        // alert(people);
        // const content_html = null;
        // for (let person in people) {
        //     content_html = '<tr onclick="select_person(); return false" style="cursor: pointer;">';
        //     content_html += '<td>cedula</td>';
        //     content_html += '<td>Nombre</td>';
        //     content_html += '<td>Apellido</td>';
        //     content_html += '</tr>';
        // }
        // $('#tbody-rows-modal').html(content_html);
        $('#modal-title').text('Seleccionar Solicitante');
        $('#select-person').modal('show');
        $('.form-horizontal').show();
    });

    //FUNCION QUE MUESTRA EL MODAL PARA SELECCIONAR GARANTE 
    $(document).on('click', '#select-guarantor-modal', function() {
        $('#modal-title').text('Seleccionar Garante');
        $('#select-person').modal('show');
        $('.form-horizontal').show();
    });

    function select_person(id) {
        let modal_title = $('.modal-title').text();
        if (modal_title === 'Seleccionar Solicitante') {
            $('#person_id').val(id);
            $('#name_person_loan').val(modal_title);
        } else {
            $('#guarantor_id').val(id);
            $('#name_guarantor_loan').val(modal_title);
        }
        $('#select-person').modal('hide');
    }

    function soloNumeros(e) {
        var key = e.keyCode || e.which,
            tecla = String.fromCharCode(key).toLowerCase(),
            letras = "0123456789",
            especiales = [8, 37, 39, 46],
            tecla_especial = false;

        for (var i in especiales) {
            if (key == especiales[i]) {
                tecla_especial = true;
                break;
            }
        }
        if (letras.indexOf(tecla) == -1 && !tecla_especial) {
            return false;
        }
    }
</script>
@endpush

<!-- <style>
    .modal-header {
        background-color: #57B9DF;
        color: white;
    }

    th {
        background-color: #57B9DF;
    }

    .text-center {
        background-color: white;
    }
</style> -->