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
@if('session'('info'))
<div class="col-12">
    <div class="alert alert-info">
        {{session('info')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
    </div>
</div>
@elseif('session'('warning'))
<div class="col-12">
    <div class="alert alert-warning">
        {{session('warning')}}
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
<br>

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
            <div class="col-12">
                <!-- card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">SOCIOS Y DEUDORES</h3>
                        <div class="card-tools">
                            <div class="dt-buttons btn-group flex-wrap" style="width: 300px;">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Presidente</span>
                                    </div>
                                    <input value="{{ $directive->person->first_name .' ' .$directive->person->last_name }}" class="form-control">
                                    <div class="input-group-append">
                                        <button onclick="showModalDirective()" class="input-group-text">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <ul class="navbar-nav ml-auto">
                                    <li class="nav-item dropdown">
                                        <button class="btn btn-secondary btn-sm" data-toggle="dropdown">
                                            <i class="far fa-file-pdf"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                            <a href="{{ route('personas.reporte', 'socio') }}" class="dropdown-item" target="_blank">
                                                <i class="fa fa-money-bill"></i> Socios
                                            </a>
                                            <a href="{{ route('personas.reporte', 'particular') }}" class="dropdown-item" target="_blank">
                                                <i class="far fa-file"></i> Particulares
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button onclick="showModalCreate()" class="create-modal btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>CEDULA</th>
                                    <th>NOMBRES</th>
                                    <th>APELLIDOS</th>
                                    <th>TIPO</th>
                                    <th>TELEFONO</th>
                                    <th>CORREO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($people as $person)
                                <tr>
                                    <td>{{ $person->identification_card }}</td>
                                    <td>{{ $person->first_name }}</td>
                                    <td>{{ $person->last_name }}</td>
                                    <td style="text-align: center">
                                        <span class="badge bg-{{ $person->type === 'socio' ? 'success' : 'warning' }}" style="font-size:0.9em">{{ $person->type }}</span>
                                    </td>
                                    <td>{{ $person->phone }}</td>
                                    <td>{{ $person->email }}</td>
                                    <td>
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link" data-toggle="dropdown" href="#">
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                    <button class="dropdown-item" onclick='editPerson("{{ $person->id }}")'>
                                                        <i class="far fa-edit"></i> Editar
                                                    </button>
                                                    @if($person->type === 'socio')
                                                    <button class="dropdown-item" onclick='showDialogDelete("{{ $person->id }}")'>
                                                        <i class="far fa-trash-alt"></i> Anular
                                                    </button>
                                                    @else
                                                    <button class="dropdown-item" onclick='deletePerson("{{ $person->id }}")'>
                                                        <i class="far fa-trash-alt"></i> Anular
                                                    </button>
                                                    @endif
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /.MODAL ELIMINAR SOCIO -->
<div id="eliminar-socio" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Información para eliminar socio</h4>
            </div>
            <div class="modal-body">
                <form id="person-delete-form" action="#" class="form-horizontal" role="form" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <p id='socio-name-delete'></p>

                    <table>
                        <tbody>
                            <tr>
                                <td>Valor de la acción</td>
                                <td id="val-de-la-accion" style="width: 4em; text-align: right;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: right;" colspan="2">*</td>
                            </tr>
                            <tr>
                                <td>Acciones del socio</td>
                                <td style="text-align: right;">
                                    <input id="num-accion-socio" style="text-align: right;" name="action_delete" type="number" value="1" step="1" min="1" />
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;" colspan="2">=</td>
                            </tr>
                            <tr>
                                <td>TOTAL</td>
                                <td id="total-accion-socio" style="text-align: right;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: right;" colspan="2">-</td>
                            </tr>
                            <tr>
                                <td>Aporte a caja</td>
                                <td style="text-align: right;">50</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;" colspan="2">=</td>
                            </tr>
                            <tr>
                                <td>Valor para el socio</td>
                                <td id="entregar-accion-socio" style="text-align: right;"></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">
                            Confirmar
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

<!-- /.MODAL DIRECTIVE -->
<div id="directive" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Presidente</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('directives.update', 1)}}" class="form-horizontal" role="form" method="POST">
                    {{ csrf_field() }}
                    {{method_field('PUT')}}

                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="interest_percentage">Tipo</label>
                        <div class="col-sm-10">
                            <select class="custom-select form-control form-control-sm" name="person_id" required>
                                <option>Seleccione</option>
                                @foreach ($people as $person)
                                @if($person->type === 'socio' )
                                <option value="{{$person->id}}">{{$person->first_name .' ' .$person->last_name}}</option>
                                @endif
                                @endforeach
                            </select>
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

<!-- /.MODAL ADD -->
<div id="create" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Registrar personal</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" action="/people">
                    {{ csrf_field() }}
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="identification_card">Cédula</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="identification_card" name="identification_card" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="first_name"> Nombres </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="first_name" name="first_name" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="last_name"> Apellidos </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="interest_percentage">Tipo</label>
                        <div class="col-sm-10">
                            <select class="custom-select form-control form-control-sm" id="type" name="type" required>
                                <option value="">Seleccione</option>
                                <option value="socio">Socio</option>
                                <option value="particular">Particular</option>

                            </select>
                        </div>
                    </div>
                    <div id="hidden_val_contribution" class="form-group row add" hidden>
                        <label id="lab_val_contribution" class="control-label col-sm-8" for="val_contribution"></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm" style="text-align: right;" id="val_contribution" name="val_contribution" />
                        </div>
                    </div>
                    <div class="form-group row add" hidden>
                        <label class="control-label col-sm-2" for="phone">Teléfono</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="phone" name="phone" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="email">Correo</label>
                        <div class="col-sm-10">
                            <input type="mail" class="form-control form-control-sm" id="email" name="email">
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

<!-- /.MODAL EDIT -->
<div class="modal fade" id="edit-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Editar personal</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" role="form" method="POST" id="edit-form">
                    {{ csrf_field() }}
                    {{method_field('PUT')}}

                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="identification_card">Cédula</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="identification_card1" name="identification_card" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="first_name"> Nombres </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="first_name1" name="first_name" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="last_name "> Apellidos </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="last_name1" name="last_name" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="type">Tipo</label>
                        <div class="col-sm-10">
                            <select class="custom-select form-control form-control-sm" id="type1" name="type" required>
                                <option>Seleccione</option>
                                <option value="socio">Socio</option>
                                <option value="particular">Particular</option>

                            </select>
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="phone">Teléfono</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="phone1" name="phone" maxlength="10">
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="email">Correo</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="email1" name="email">
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
<!-- Page specific script -->
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>

<script>
    function showModalDirective() {
        $('#directive').modal('show')
    }

    function showModalCreate() {
        $.ajax({
            type: 'GET',
            url: "{{ url('home/reportcurrent') }}/0",
            data: {
                "_token": $('meta[name="csrf-token"]').content
            },
            success: (res) => {
                // let p = res.person
                $('#lab_val_contribution').text(`Valor de la acción ($${res.amount}) + $50`)
                $('#val_contribution').val(res.amount + 50)
                $('#create').modal('show')
                $('.form-horizontal').show()
            },
            error: (error) => console.log(error)
        })
    }

    function editPerson(id) {
        $.ajax({
            type: 'GET',
            url: "{{url('people')}}/" + id,
            data: {
                "_token": $('meta[name="csrf-token"]').content
            },
            success: (res) => {
                let p = res.person
                $('#identification_card1').val(p.identification_card)
                $('#first_name1').val(p.first_name)
                $('#last_name1').val(p.last_name)
                $('#type1').val(p.type)
                $('#phone1').val(p.phone)
                $('#email1').val(p.email)
                $('#edit-form').attr('action', "{{url('people')}}/" + id)
                $('#edit-modal').modal('show')
            },
            error: (error) => console.log(error)
        })
    }

    let resDelete = undefined

    function showDialogDelete(id) {
        $.ajax({
            type: 'GET',
            url: "{{ url('home/reportcurrent') }}/" + id,
            data: {
                "_token": $('meta[name="csrf-token"]').content
            },
            success: (res) => {
                resDelete = res
                $('#socio-name-delete').html(`<strong>Socio: </strong> ${res.person.first_name} ${res.person.last_name}`)
                $('#val-de-la-accion').html(res.amount.toFixed(2))
                $('#num-accion-socio').val(1)
                $('#num-accion-socio').attr('max', res.person.actions)
                $('#total-accion-socio').html((res.amount).toFixed(2))
                $('#entregar-accion-socio').html((res.amount - 50).toFixed(2))
                $('#person-delete-form').attr('action', "{{url('people')}}/" + id)
                $('#eliminar-socio').modal('show')
            },
            error: (error) => console.log(error)
        })
    }

    $('#num-accion-socio').change(function(e) {
        e.preventDefault()

        let actions = Number($(this).val())
        console.log(resDelete.amount * actions)
        $('#total-accion-socio').html((resDelete.amount * actions).toFixed(2))
        $('#entregar-accion-socio').html((resDelete.amount * actions - 50).toFixed(2))
    })

    // Agregar o eliminar hidden
    $('#type').change(function(e) {
        e.preventDefault()

        if ($(this).val() === 'socio') {
            $('#hidden_val_contribution').removeAttr('hidden')
        } else {
            $('#hidden_val_contribution').attr('hidden')
        }
    })

    function deletePerson(id) {
        swal({
                title: "¿Esta seguro?",
                text: "Eliminar esta persona",
                icon: "warning",
                buttons: ["Cancelar", "Ok"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('people')}}/" + id,
                        data: {
                            "_token": $('meta[name="csrf-token"]').content,
                            "_method": "DELETE"
                        },
                        success: () => {
                            swal({
                                    text: "Se elimino una persona",
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