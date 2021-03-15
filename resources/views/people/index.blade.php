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
<br>
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
                            <div class="dt-buttons btn-group flex-wrap">
                                <ul class="navbar-nav ml-auto">
                                    <li class="nav-item dropdown">
                                        <a class="btn btn-secondary btn-sm" data-toggle="dropdown" href="#">
                                            <i class="far fa-file-pdf"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                            <a href="{{ route('personas.reporte', 'socio')}}" class="dropdown-item" target="_blank">
                                                <i class="fa fa-money-bill"></i> Socios
                                            </a>
                                            <a href="{{ route('personas.reporte', 'particular')}}" class="dropdown-item" target="_blank">
                                                <i class="far fa-file"></i> Particulares
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <a href="#" onclick="showModalCreate()" class="create-modal btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
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
                                @foreach ($people as $dato)
                                <tr>
                                    <input type="hidden" class="serdelete_val" value="{{ $dato->id }}">
                                    <td>{{$dato['identification_card']}}</td>
                                    <td>{{$dato['first_name']}}</td>
                                    <td>{{$dato['last_name']}}</td>
                                    <td>
                                        @if($dato['type'] === 'socio' )
                                        <span class="badge bg-success" style="font-size:0.9em">{{$dato['type']}}</span>
                                        @else
                                        <span class="badge bg-warning" style="font-size:0.9em">{{$dato['type']}}</span>
                                        @endif
                                    </td>
                                    <td>{{$dato['phone']}}</td>
                                    <td>{{$dato['email']}}</td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-sm" onclick="editPerson(this)">
                                            <i class="far fa-edit"></i>
                                            <!-- {{route('people.edit' , $dato)}}  -->
                                        </a>
                                        <button class="btn btn-danger btn-sm personDelete">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
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

<!-- /.MODAL ADD -->
<div id="create" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Registrar Personal</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" action="/people">
                    {{ csrf_field() }}
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="identification_card">Cédula</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="identification_card" name="identification_card" onkeypress="return soloNumeros(event)" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="first_name"> Nombres </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="first_name" name="first_name" onkeypress="return soloLetras(event)" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="last_name "> Apellidos </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="last_name" name="last_name" onkeypress="return soloLetras(event);" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="interest_percentage">Tipo</label>
                        <div class="col-sm-10">
                            <select class="custom-select form-control form-control-sm" id="type" name="type" required>
                                <option>Seleccione</option>
                                <option value="socio">Socio</option>
                                <option value="particular">Particular</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="phone">Teléfono</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="phone" name="phone" onkeypress="return soloNumeros(event)" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="email">Correo</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="email" name="email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit" id="add">
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
<div class="modal fade" id="editModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Editar Personal</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" role="form" method="POST" action="/people" id="editForm">
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="identification_card">Cédula</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="identification_card1" name="identification_card" onkeypress="return soloNumeros(event)" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="first_name"> Nombres </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="first_name1" name="first_name" onkeypress="return soloLetras(event)" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="last_name "> Apellidos </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="last_name1" name="last_name" onkeypress="return soloLetras(event);" required>
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
                            <input type="text" class="form-control form-control-sm" id="phone1" name="phone" onkeypress="return soloNumeros(event)" maxlength="10">
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="email">Correo</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="email1" name="email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit" id="add">
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
    function showModalCreate() {
        $('#create').modal('show');
        $('.form-horizontal').show();
    }

    function editPerson(edit) {
        /* console.log(edit.parentNode.parentNode.children[0].value); */
        let td = edit.parentNode.parentNode;
        let id = td.children[0].value;

        let identification_card = td.children[1].textContent;
        let first_name = td.children[2].textContent;
        let last_name = td.children[3].textContent;
        let type = td.children[4].children[0].textContent;
        let phone = td.children[5].textContent;
        let email = td.children[6].textContent;

        $('#identification_card1').val(identification_card);
        $('#first_name1').val(first_name);
        $('#last_name1').val(last_name);
        $('#type1').val(type);
        $('#phone1').val(phone)
        $('#email1').val(email);
        $('#editForm').attr('action', 'people/' + id);
        $('#editModal').modal('show');
    }
</script>


@endpush