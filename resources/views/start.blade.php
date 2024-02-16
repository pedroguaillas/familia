@extends('layouts.dashboard')

@push('csss')
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
@endpush

@section('content')
<br />
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $countactions }}</h3>

                        <p>Número de acciones</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-stalker"></i>
                    </div>
                    <a href="{{url('people')}}" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $countdebtors }}</h3>

                        <p>Personas que tienen préstamo</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                    <a href="{{url('loans')}}" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{number_format($total, 2, ',', '.')}}</h3>

                        <p>Valor de cada acción</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{number_format($total_borrowed, 2, ',', '.')}}</h3>

                        <p>Monto prestado</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pricetags"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- /.col-md-6 -->
            <div class="col-lg-6">
                <!-- card -->
                <!-- Donut chart -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="far fa-chart-bar"></i>
                            Monto total en caja <span id="general_total">00,00</span>
                        </h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- <div id="donut-chart" style="height: 300px;"></div> -->
                        <div class="col-md-12">
                            <div class="chart-responsive">
                                <div id="donut-chart-general" style="height: 300px;"></div>
                                <!-- <canvas id="pieChart" height="150"></canvas> -->
                            </div>
                            <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-12">
                            <ul class="chart-legend clearfix">
                                <li><i style="color: #3c8dbc;" class="far fa-circle"></i> Aportes mensuales <span id="general_c_months">00,00</span></li>
                                <li><i style="color: #0073b7;" class="far fa-circle"></i> Interés <span id="general_interest">00,00</span></li>
                                <li><i style="color: #00c0ef;" class="far fa-circle"></i> Aportes anual <span id="general_c_year">00,00</span></li>
                            </ul>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                <!-- /.card-body-->
            </div>
            <!-- /.card -->
            <!-- /.col-md-6 -->
            <div class="col-lg-6">
                <!-- card -->
                <!-- Donut chart -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="far fa-chart-bar"></i>
                            Monto total en caja hasta la fecha actual <span id="members_total">00,00</span>
                        </h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- <div id="donut-chart" style="height: 300px;"></div> -->
                        <div class="col-md-12">
                            <div class="chart-responsive">
                                <div id="donut-chart-current" style="height: 300px;"></div>
                                <!-- <canvas id="pieChart" height="150"></canvas> -->
                            </div>
                            <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-12">
                            <ul class="chart-legend clearfix">
                                <li><i style="color: #3c8dbc;" class="far fa-circle"></i> Aportes mensuales <span id="members_c_months">00,00</span></li>
                                <li><i style="color: #0073b7;" class="far fa-circle"></i> Interés <span id="members_interest">00,00</span></li>
                                <li><i style="color: #00c0ef;" class="far fa-circle"></i> Aportes anual <span id="members_c_year">00,00</span></li>
                            </ul>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                <!-- /.card-body-->
            </div>
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
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- jQuery Mapael -->
<script src="{{ asset('plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
<!-- ChartJS -->
<!-- FLOT CHARTS -->
<script src="{{ asset('plugins/flot/jquery.flot.js') }}"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="{{ asset('plugins/flot/plugins/jquery.flot.resize.js') }}"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="{{ asset('plugins/flot/plugins/jquery.flot.pie.js') }}"></script>
<script>
    $(function() {
        /*
         * DONUT CHART
         * -----------
         */

        var data = [{
                label: 'Anual',
                color: '#00c0ef'
            }, {
                label: 'AportesMensual',
                color: '#3c8dbc'
            },
            {
                label: 'Interés',
                color: '#0073b7'
            }
        ]

        $.ajax({
            type: 'GET',
            url: "{{route('home.report')}}",
            success: (res) => {

                // General -----------------------------

                data[0].data = res.general_contributions[0].sum
                data[1].data = Number(res.general_contributions[1].sum) - res.spend_capital
                data[2].data = res.general_interest

                loadChart(data, 'donut-chart-general')

                let total = Number(res.general_contributions[0].sum) + Number(res.general_contributions[1].sum) + Number(res.general_interest) - Number(res.spend_capital)
                $('#general_total').text(formatter.format(total))
                $('#general_c_months').text(formatter.format(Number(res.general_contributions[1].sum) - res.spend_capital))
                $('#general_interest').text(formatter.format(res.general_interest))
                $('#general_c_year').text(formatter.format(res.general_contributions[0].sum))

                // Current ----------------------------

                data[0].data = res.current_contributions[0].sum
                data[1].data = Number(res.current_contributions[1].sum) - res.spend_capital
                data[2].data = res.current_interest

                loadChart(data, 'donut-chart-current')

                let members_total = Number(res.current_contributions[0].sum) + Number(res.current_contributions[1].sum) + Number(res.current_interest) - Number(res.spend_capital)
                $('#members_total').text(formatter.format(members_total))
                $('#members_c_months').text(formatter.format(Number(res.current_contributions[1].sum) - res.spend_capital))
                $('#members_interest').text(formatter.format(res.current_interest))
                $('#members_c_year').text(formatter.format(res.current_contributions[0].sum))
            },
            error: (error) => console.log(error)
        })
    })

    function loadChart(data, id) {
        $.plot('#' + id, data, {
            series: {
                pie: {
                    show: true,
                    radius: 1,
                    innerRadius: 0.5,
                    label: {
                        show: true,
                        radius: 2 / 3,
                        formatter: labelFormatter,
                        threshold: 0.1
                    }
                }
            },
            legend: {
                show: false
            }
        })
    }

    // Create our number formatter.
    var formatter = new Intl.NumberFormat('es-EC', {
        //     style: 'currency',
        //     currency: 'USD',

        //     // These options are needed to round to whole numbers if that's what you want.
        minimumFractionDigits: 2, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        maximumFractionDigits: 2, // (causes 2500.99 to be printed as $2,501)
    })

    /*
     * Custom Label formatter
     * ----------------------
     */
    function labelFormatter(label, series) {
        return '<div style="font-size:12px; text-align:center; color: #fff; font-weight: 400;">' +
            Math.round(series.percent) + '%</div>'
    }
</script>
@endpush