<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal</title>
</head>

<body>
    <header id="main-header">
        <a id="logo-header" href="#">

            <img class="img-circle" src="dist/img/AdminLTELogo.png" style="width: 50px; height:60px;  border-radius: 50%;">
        </a>
        <nav>
            <ul>
                <h4>CAJA FAMILIAR</h4>
            </ul>
        </nav>
    </header><br>
    <hr>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col-md-6 -->
                <div class="col-12">
                    <!-- card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> {{ strtoupper( $people[0]->type) }} </h3>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($people as $dato)
                                    <tr>
                                        <input type="hidden" class="serdelete_val" value="{{ $dato->id }}">
                                        <td>{{$dato['identification_card']}}</td>
                                        <td>{{$dato['first_name']}}</td>
                                        <td>{{$dato['last_name']}}</td>
                                        <td>{{$dato['type']}}</td>
                                        <td>{{$dato['phone']}}</td>
                                        <td>{{$dato['email']}}</td>

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
    <footer>
        <hr>
        <h6 align="center"> Derechos Reserados &copy; 2020</h6>
    </footer>
</body>

</html>

<style>
    body {
        margin: 0;
        padding: 0;
        font-size: 1em;
        line-height: 1.5em;
        font-family: sans-serif;
    }

    #main-header {
        height: 50px;
        width: 100%;
        left: 0;
        top: 0;

    }

    hr {
        border: 1px solid #57B9DF;
    }

    /*Logo*/
    #logo-header {
        float: left;
        padding: 1px;
        text-decoration: none;
    }

    #logo-header .site-name {
        display: block;
    }

    #logo-header .site-desc {
        display: block;
        font-weight: 300;
        font-size: 0.8em;
        color: #999;
    }

    .logo {
        padding-top: 10px;
        height: 50px;
        float: right;
        margin: 5px;
    }

    /*   Navegación */
    nav {
        float: right;
    }

    nav ul {
        margin: 0;
        padding: 0;
        list-style: none;
        padding-right: 300px;
    }

    nav ul li a {
        text-decoration: none;
        color: black;
    }

    .table {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;

    }

    th,
    td {
        padding: 1px;
        font-size: 12px;
        letter-spacing: 1px;
        text-align: center;
    }

    .margin {
        padding: 0;
    }

    thead {
        background-color: #57B9DF;
        color: #FFF;
    }

    footer {
        color: black;
        width: 100%;
        height: 81px;
        position: absolute;
        bottom: 0;
        left: 0;
    }
</style>