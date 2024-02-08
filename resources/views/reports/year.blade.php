@extends('layouts.dashboard')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Reporte</a></li>
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
                        <h3 class="card-title">REPORTE POR AÑOS</h3>
                        <!-- <div class="card-tools">
                            <div class="dt-buttons btn-group flex-wrap">
                                <a class="btn btn-secondary btn-sm" href="#" target="_blank">
                                    <i class="far fa-file-pdf"></i>
                                </a>
                            </div>
                        </div> -->
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>AÑO</th>
                                    <th>APORTES</th>
                                    <th>INTERES</th>
                                    <th colspan="2">MORA</th>
                                </tr>
                            </thead>
                            @php
                            $i = 0;
                            $sum_contributions = 0;
                            $sum_interest = 0;
                            $must = 0;
                            @endphp
                            <tbody>
                                @foreach ($contributions as $contribution)
                                <tr>
                                    <td style="text-align: center;">{{ $contribution->year }}</td>
                                    <td style="text-align: right;">{{ number_format($contribution->amount, 2, ',', '.') }}</td>
                                    <td style="text-align: right;">{{ number_format($payments[$i]->amount, 2, ',', '.') }}</td>
                                    <td style="text-align: right;">{{ number_format($contribution->must + $payments[$i]->must, 2, ',', '.') }}</td>
                                    <td style="width: 1em;">
                                        <a class="btn btn-success btn-sm" href="{{ route('reporte-mensual', $contribution->year) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php
                                $sum_contributions += $contribution->amount;
                                $sum_interest += $payments[$i]->amount;
                                $must += $contribution->must + $payments[$i]->must;
                                $i ++;
                                @endphp
                                @endforeach
                            </tbody>
                            <thead>
                                <tr>
                                    <th style="text-align: center;">TOTAL</th>
                                    <th style="text-align: right;">{{ number_format($sum_contributions, 2, ',', '.') }}</th>
                                    <th style="text-align: right;">{{ number_format($sum_interest, 2, ',', '.') }}</th>
                                    <th style="text-align: right;">{{ number_format($must, 2, ',', '.') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
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