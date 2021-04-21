<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-size: 1em;
            line-height: 1.5em;
            font-family: sans-serif;
        }

        table {
            border-collapse: collapse;
            /* border-style: solid;
            border-width: 1px; */
        }

        thead {
            background-color: #eee;
            color: #333;
        }

        th,
        td {
            font-size: 12px;
            text-align: center;
        }

        th {
            padding: 1px;
            padding-bottom: 3px;
        }

        td {
            padding: 0px 5px;
        }

        .card-title {
            text-align: center;
        }
    </style>
</head>

<body>
    <br>
    @yield('content')
</body>

</html>