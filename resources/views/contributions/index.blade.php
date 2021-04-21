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
<!-- Main content -->
<br>
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <!-- /.col-md-6 -->
            <div class="col-md-12">
                <!-- card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">APORTES</h3>
                        <div class="card-tools">
                            <div class="dt-buttons btn-group flex-wrap">
                                <a class="btn btn-secondary btn-sm" href="{{ route('aportes.reporte')}}" target="_blank">
                                    <i class="far fa-file-pdf"></i>
                                </a>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button onclick="showModal()" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Nº</th>
                                    <th>SOCIO</th>
                                    <th>APORTES</th>
                                    <th>Nº ACCIONES</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                $i=1;
                                @endphp
                                @foreach ($contributions as $contribution)
                                <tr>
                                    <td style="text-align: center;">{{$i}}</td>
                                    <td>{{$contribution->first_name . ' ' . $contribution->last_name}}</td>
                                    <td style="text-align: right;">{{number_format($contribution->amount, 2, ',', '.')}}</td>
                                    <td style="text-align: center;">{{$contribution->actions}}</td>

                                    <td>
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link" data-toggle="dropdown" href="#">
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                    <a href="{{route('aportes.historial', $contribution->person_id)}}" class="dropdown-item">
                                                        <i class="far fa-file"></i> Historial
                                                    </a>
                                                    <button onclick='showModalPurchaseActions("{{$contribution->person_id}}")' class="dropdown-item">
                                                        <i class="far fa-file"></i> Comprar acciones
                                                    </button>
                                                    <a href="{{route('aportes.solicitude', $contribution->person_id)}}" class="dropdown-item" target="_blank">
                                                        <i class="far fa-file"></i> Solicitud
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

<!-- /.Aporte CREATE MASIVE -->
<div class="modal fade" id="addModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Registrar Aportes Masiva</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" action="{{route('contributions.create2')}}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="amount">Tipo</label>
                        <div class="col-sm-9">
                            <select name="type" class="form-control form-control-sm">
                                <option value="mensual">Mensual</option>
                                <option value="anual">Anual</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-3" for="date">Fecha</label>
                        <div class="col-sm-9">
                            <input type="date" name="date" value="{{date('Y-m-d')}}" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit" id="add">
                            <span class="glyphicon glyphicon-plus"></span> Cargar
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

<!-- /.Aporte CREATE MASIVE -->
<div class="modal fade" id="purchaseActionsModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin: auto;">Compra de acciones</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" action="{{route('people.purchaseactions')}}" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="person_id" id="person_id">

                    <div class="form-group row add">
                        <label class="control-label col-sm-6" for="amount">Monto por cada acción</label>
                        <div class="col-sm-6">
                            <input id="amount_purchase" name="amount" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-6" for="quantity_action">No. Acciones del socio</label>
                        <div class="col-sm-6">
                            <input id="quantity_action" name="quantity_action" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-6" for="quantity_action_purchase">No. Acciones a comprar</label>
                        <div class="col-sm-6">
                            <input type="number" onchange="AmountActions(this)" id="quantity_action_purchase" name="quantity_action_purchase" value="1" min="1" max="20" step="1" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-6" for="total_action">No. Total de Acciones</label>
                        <div class="col-sm-6">
                            <input id="total_action" name="total_action" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-6" for="amount_to_pay">Monto a pagar</label>
                        <div class="col-sm-6">
                            <input id="amount_to_pay" name="amount_to_pay" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-4" for="boservation">Observación</label>
                        <div class="col-sm-8">
                            <textarea name="observation" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit" id="add">
                            <span class="glyphicon glyphicon-plus"></span> Comprar
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
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
<script>
    function showModal() {
        $('#addModal').modal('show')
    }

    let action = {
        amount: 483,
        quantity_action: 3
    }

    function showModalPurchaseActions(id) {
        $.ajax({
            type: 'GET',
            url: "{{url('home/reportcurrent')}}/" + id,
            success: (res) => {
                action.amount = res.amount
                action.quantity_action = res.person_actions
                $("#person_id").val(id)
                $("#amount_purchase").val(action.amount)
                $("#quantity_action").val(action.quantity_action)
                $("#quantity_action_purchase").val(1)
                $("#total_action").val(res.person_actions + 1)
                $("#amount_to_pay").val(action.amount)
                $('#purchaseActionsModal').modal('show')
            },
            error: (error) => console.log(error)
        })
    }

    function AmountActions(e) {
        let value = Number(e.value)
        $("#total_action").val(value + action.quantity_action)
        $("#amount_to_pay").val(value * action.amount)
    }
</script>
@endpush