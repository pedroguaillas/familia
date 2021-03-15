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
</br>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('contributions') }}">Aportes</a></li>
                    <li class="breadcrumb-item active">Historial Aportes</li>
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
                        <h3 class="card-title">Socio</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <p>
                                    <strong>Socio </strong>
                                    {{$person->first_name.' '.$person->last_name}}
                                </p>
                            </div>
                            <div class="col-sm-5">
                                <p>
                                    <strong>Acciones: </strong>
                                    {{$person->actions}}
                                </p>
                            </div>

                            <div class="col-sm-2">
                                <p>
                                    <strong>Monto: </strong>
                                    {{'$' . number_format($amount, 2, ',', '.')}}
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
                        <h3 class="card-title">Aportes</h3>
                        <div class="card-tools">
                            <div class="dt-buttons btn-group flex-wrap">
                                <a class="btn btn-secondary btn-sm" href="" target="_blank">
                                    <i class="far fa-file-pdf"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Nº</th>
                                    <th>Mes</th>
                                    <th>Año</th>
                                    <th>Aporte</th>
                                    <th>Tipo</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                $i=1;
                                @endphp
                                @foreach ($contributions as $contribution)
                                <tr>
                                    <input type="hidden" name="contribution_id" value="{{$contribution['id']}}">
                                    <input type="hidden" name="date" value="{{substr($contribution['date'], 0, 10)}}">
                                    <input type="hidden" name="amount" value="{{$contribution['amount']}}">
                                    <td style="text-align: center;">{{$i}}</td>
                                    <td style="text-align: center;">{{ substr($contribution['date'], 5,2 )}}</td>
                                    <td style="text-align: center;">{{ substr($contribution['date'], 0,4 )}}</td>
                                    <td style="text-align: right;">{{'$' . number_format($contribution['amount'], 2, ',', '.')}}</td>
                                    <td style="text-align: center;">
                                        @if($contribution['type'] === 'mensual' )
                                        <span class="badge bg-success" style="font-size:0.9em">{{$contribution['type']}}</span>
                                        @else
                                        <span class="badge bg-warning" style="font-size:0.9em">{{$contribution['type']}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link" data-toggle="dropdown" href="#">
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                    <a href="#" class="dropdown-item paymentDelete" onclick="editContribution(this)">
                                                        <i class="far fa-edit"></i> Editar
                                                    </a>
                                                    <a href="#" class="dropdown-item" target="_blank">
                                                        <i class="far fa-trash-alt"></i> Anular
                                                    </a>
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
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->

@endsection

<!-- /.Aporte EDIT -->
<div class="modal fade" id="editModal1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Editar Aporte</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST" id="editForm">
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="date">Fecha</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control form-control-sm" id="date" name="date" required>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="amount"> Monto </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="amount" name="amount" onkeypress="return soloNumeros(event);" required>
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


@push('scripts')

<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
<script>
    function editContribution(edit) {
        /* a > div > li > ul > td */
        let tr = edit.parentNode.parentNode.parentNode.parentNode.parentNode;
        let id = tr.children[0].value
        let date = tr.children[1].value
        let amount = tr.children[2].value;

        /* $('#contribution_id').val(id); */
        $('#date').val(date);
        $('#amount').val(amount);

        $('#editForm').attr('action', 'contributions/' + id);
        $('#editModal1').modal('show');
    }


    function deleteContribution() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        $('.paymentDelete').click(function(e) {
            e.preventDefault();
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var delete_id = $(this).closest("tr").find('.serdelete_val').val();
            swal({
                    title: "¿Esta seguro?",
                    text: "Eliminar Pago",
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
                            url: '/payments.delete/' + delete_id,
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
    }
</script>
@endpush