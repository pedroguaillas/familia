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
                <h1>{{$person->first_name.' '.$person->last_name}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('loans') }}">Prestamos</a></li>
                    <li class="breadcrumb-item active">Pagos</li>
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
                        <h3 class="card-title">Pagos</h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Capital</th>
                                    <th>Interes</th>
                                    <!-- <th>Mora</th> -->
                                    <th>FECHA</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>$100</td>
                                    <td>$50</td>
                                    <!-- <td>Mora</td> -->
                                    <td>2021-02-02</td>
                                    <td></td>
                                </tr>
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

<script>

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