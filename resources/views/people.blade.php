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
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
            </div>
            <div class="col-sm-7 col-md-6">
                <div class="dt-buttons btn-group flex-wrap">
                    <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="example1" type="button">
                        <span>PDF</span>
                    </button>
                </div>
                <div class="dt-buttons btn-group flex-wrap">
                    <a href="#" class="create-modal btn btn-success btn-lg">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
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
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>NOMBRES</th>
                                    <th>APELLIDOS</th>
                                    <th>Nº CEDULA</th>
                                    <th>TELEFONO</th>
                                    <th>CORREO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($person as $dato)
                                <tr>
                                    <input type="hidden" class="serdelete_val" value="{{ $dato->id }}">
                                    <td>{{$dato['id']}}</td>
                                    <td>{{$dato['first_name']}}</td>
                                    <td>{{$dato['last_name']}}</td>
                                    <td>{{$dato['identification_card']}}</td>
                                    <td>{{$dato['phone']}}</td>
                                    <td>{{$dato['email']}}</td>
                                    <td>
                                        <a href="{{route('people.edit', $dato)}}" class="btn btn-warning btn-sm">
                                            <i class="far fa-edit"></i>
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

<div id="create" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="">
                    {{ csrf_field() }}
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="first_name"> Nombres </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Ingrese nombres" onkeypress="return soloLetras(event)" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="last_name "> Apellidos </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Ingrese apellidos" onkeypress="return soloLetras(event);" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="identification_card">C.I.</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="identification_card" name="identification_card" placeholder="Cédula de identidad" onkeypress="return soloNumeros(event)" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="phone">Teléfono</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Ingrese numero telefonico" onkeypress="return soloNumeros(event)" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="email">Correo</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Dirección correo" required>
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
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>


<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        $('.personDelete').click(function(e) {
            e.preventDefault();
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var delete_id = $(this).closest("tr").find('.serdelete_val').val();
            swal({
                    title: "¿Esta seguro?",
                    text: "Eliminar Socio",
                    icon: "warning",
                    buttons: ["Cancelar", "Ok"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        var data = {
                            "_token": $('input[name="csrf-token"]').val(),
                            "id": delete_id,
                        };

                        $.ajax({
                            type: "POST",
                            url: '/people.delete/' + delete_id,
                            data: data,
                            success: function(response) {
                                swal(response.status, {
                                        icon: "success",
                                    })
                                    .then((result) => {
                                        location.reload();
                                    });
                            }
                        });
                    }
                });
        });
    });
</script>

<script>
    $(function() {
        $('#example1').DataTable({

            responsive: true,
            autoWidth: false,
            "language": {
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "zeroRecords": "Sin resultados encontrados",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });
    });

    //FUNCION QUE MUESTRA EL MODAL DE INGRESO 
    $(document).on('click', '.create-modal', function() {
        $('#create').modal('show');
        $('.form-horizontal').show();
        $('.modal-title').text('Registrar Nuevo Socio');
    });
</script>



<script>
    function soloLetras(e) {
        var key = e.keyCode || e.which,
            tecla = String.fromCharCode(key).toLowerCase(),
            letras = " áéíóúabcdefghijklmnñopqrstuvwxyz",
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
<style>
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
</style>