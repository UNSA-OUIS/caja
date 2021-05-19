<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUIyJ" crossorigin="anonymous">
    <title>{{$comprobante->tipo_comprobante['nombre']}} {{$comprobante->serie}}-{{$comprobante->correlativo}}</title>
</head>

<body>
    <div id="app">
        <div class="header">
            <img src="https://cdn.jsdelivr.net/gh/unsa-cdn/static@master/logo.png" alt="" height="50" class="logo logo-light float-left mr-2" />
            <div class="float-left">

                <h6><small>UNIVERSIDAD NACIONAL DE SAN AGUSTIN<br>
                        CALLE SANTA CATALINA 117 AREQUIPA AREQUIPA<br>
                        SISTEMA DE CAJAS/INGRESOS</small></h6>
            </div>
            <div class="row float-center">
                <h1 class="mt-5">Reporte de cobros</h1>
            </div>
            <div class="row float-center">
                <h1 class="mt-5">{{$comprobante}}</h1>
            </div>
        </div>



    </div>
    <script src="{{ asset(mix('js/app.js')) }}"></script>

</body>
</html>
