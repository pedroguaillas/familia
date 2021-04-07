<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
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
            border: 1px solid #aaa;
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

        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 1px;
            font-size: 12px;
            text-align: center;
        }

        .margin {
            padding: 0;
        }

        thead,
        tfoot {
            background-color: #aaa;
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
</head>

<body>
    <br>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col-md-6 -->
                <div class="col-12">
                    <!-- card -->
                    <div class="card">
                        @yield('content')
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
</body>

</html>