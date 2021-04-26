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
@elseif('session'('danger'))
<div class="col-12">
    <div class="alert alert-danger">
        {{session('danger')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
    </div>
</div>
@endif
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
                        <h3 class="card-title">GASTOS</h3>
                        <div class="card-tools">
                            <div class="dt-buttons btn-group flex-wrap">
                                <button onclick="create()" class="create-modal btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>NOMBRE</th>
                                    <th>MONTO</th>
                                    <th>FECHA</th>
                                    <th>OBSERVACION</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($spends as $spend)
                                <tr>
                                    <td>{{$spend->name}}</td>
                                    <td>{{number_format($spend->amount, 2, ',', '.')}}</td>
                                    <td>{{substr($spend->date, 0, 10)}}</td>
                                    <td>{{$spend->observation}}</td>
                                    <td>
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link" data-toggle="dropdown" href="#">
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                    <button class="dropdown-item" onclick='edit("{{$spend->id}}")'>
                                                        <i class="far fa-edit"></i> Editar
                                                    </button>
                                                    <button class="dropdown-item" onclick='deleted("{{$spend->id}}")'>
                                                        <i class="far fa-trash-alt"></i> Anular
                                                    </button>
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

<!-- /.MODAL ADD -->
<div id="create" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Registrar Gasto</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" action="{{route('spends.store')}}">
                    {{ csrf_field() }}

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="name"> Nombre </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" name="name" required>
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="amount">Monto</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control form-control-sm" step="0.01" name="amount" value="0" maxlength="10" required>
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="date">Fecha</label>
                        <div class="col-sm-9">
                            <input type="date" value="{{date('Y-m-d')}}" class="form-control form-control-sm" name="date" required>
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="boservation">Observación</label>
                        <div class="col-sm-9">
                            <textarea name="observation" class="form-control" rows="2"></textarea>
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
                <h4 class="modal-title" style="margin: auto;">Editar Gasto</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal" role="form" method="POST" id="edit-form">
                    {{ csrf_field() }}
                    {{method_field('PUT')}}

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="name"> Nombre </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="name_edit" name="name" required>
                        </div>
                    </div>

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="amount">Monto</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control form-control-sm" step="0.01" id="amount_edit" name="amount" value="0" maxlength="10" required>
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
                            <textarea id="observation_edit" name="observation" class="form-control" rows="2"></textarea>
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
    function create() {
        $('#create').modal('show')
        $('.form-horizontal').show()
    }

    function edit(id) {
        $.ajax({
            type: 'GET',
            url: "{{url('spends')}}/" + id,
            data: {
                "_token": $('meta[name="csrf-token"]').content
            },
            success: (res) => {
                let s = res.spend
                $('#name_edit').val(s.name)
                $('#amount_edit').val(s.amount)
                $('#date_edit').val(s.date.substring(0, 10))
                $('#observation_edit').val(s.observation)
                $('#edit-form').attr('action', "{{url('spends')}}/" + id)
                $('#edit-modal').modal('show')
            },
            error: (error) => console.log(error)
        })
    }

    function deleted(id) {
        swal({
                title: "¿Esta seguro?",
                text: "Eliminar este fasto",
                icon: "warning",
                buttons: ["Cancelar", "Ok"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('spends')}}/" + id,
                        data: {
                            "_token": $('meta[name="csrf-token"]').content,
                            "_method": "DELETE"
                        },
                        success: () => {
                            swal({
                                    text: "Se elimino un gasto",
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
</script>
@endpush