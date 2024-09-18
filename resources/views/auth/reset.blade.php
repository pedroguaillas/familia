@extends('layouts.dashboard')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Cambiar contraseña</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- /.col-md-6 -->
            <div class="col-md-12">
                <!-- card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cambiar contraseña</h3>
                    </div>

                    <div class="card-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('setpass') }}">

                            {{ csrf_field() }}

                            <div class="input-group input-group-md mt-3">
                                <div class="input-group-prepend">
                                    <span style="width: 10em;" class="input-group-text">Nueva contraseña</span>
                                </div>
                                <input type="password" min="6" max="20" class="form-control @error('period') is-invalid @enderror" name="password" required>
                                <div class="input-group-apend">
                                    <button class="input-group-btn btn btn-primary" type="submit">
                                        <span class="glyphicon glyphicon-remove"></span> Establecer
                                    </button>
                                </div>
                            </div>

                        </form>
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