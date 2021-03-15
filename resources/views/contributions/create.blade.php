@extends('layouts.dashboard')

@push('csss')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
@endpush
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('contributions') }}">Aportes</a></li>
                    <li class="breadcrumb-item active">Registro</li>
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
            <div class="col-md-12">
                <!-- card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aportes</h3>
                        <div class="card-tools">
                            <div class="dt-buttons btn-group flex-wrap">
                                <select name="type" id="type">
                                    <option value="mensual">Mensual</option>
                                    <option value="anual">Anual</option>
                                </select>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button class="btn btn-success btn-sm" onclick="save()">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Nº</th>
                                    <th>Socio</th>
                                    <th>Nº Acciones</th>
                                    <th>Aporte</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($contributions as $contribution)
                                <tr>
                                    <input type="hidden" name="person_id" value="{{$contribution['id']}}">
                                    <td style="text-align: center;">1</td>
                                    <td>{{$contribution['first_name'] . ' ' . $contribution['last_name']}}</td>
                                    <td style="text-align: center;">{{$contribution['actions']}}</td>
                                    <td>
                                        <input name="amount" value="{{$contribution['actions'] * 10}}">
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
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>

<script>
    function save() {
        let array = []
        $('tbody tr').each(function(index) {
            let item = {
                person_id: $(this).children('input').val(),
                amount: $(this).children('td:last-child').children('input').val(),
            }
            array.push(item)
        });

        $.ajax({
            type: 'POST',
            url: "{{route('contributions.store')}}",
            data: {
                contributions: JSON.stringify(array),
                type: $('#type').val()
            },
            success: (response) => {
                location.href = "{{route('contributions.index') }}";

            },
            error: (error) => console.log(error)
        });

    }
</script>
@endpush