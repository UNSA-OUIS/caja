<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUIyJ" crossorigin="anonymous">
        <title>Reporte de cobros</title>
    </head>
<body>
    <div id="app" >
        <!-- Magic happens here! -->
        <div class="header">
            <img
                src="https://cdn.jsdelivr.net/gh/unsa-cdn/static@master/logo.png"
                alt=""
                height="50"
                class="logo logo-light float-left mr-2"
                />
            <div class="float-left">

                <h6><small>UNIVERSIDAD NACIONAL DE SAN AGUSTIN<br>
                CALLE SANTA CATALINA 117 AREQUIPA AREQUIPA<br>
                SISTEMA DE CAJAS/INGRESOS</small></h6>
            </div>
            <div class="float-right">
                <h6><small>reporte_cobros.pdf</small></h6>
            </div>
            <div class="row float-center">
                <h1 class="mt-5">Reporte de cobros</h1>
            </div>
        </div>
        
            
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Serie</th>
                    <th>Correlativo</th>
                    <th>Cliente</th>
                    <th>Precio total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comprobantes as $comprobante)
                <tr>
                    <td> {{$comprobante->codigo}} </td>
                    <td> {{$comprobante->serie}} </td>
                    <td> {{$comprobante->correlativo}} </td>
                    <td> {{$comprobante->cui}} </td>
                    <td> {{$comprobante->total}} </td>
                </tr>
                @endforeach
            </tbody>
        </table> 
    </div>
    <script src="{{ asset(mix('js/app.js')) }}"></script>
    
</body>
<script type="text/php">
    if (isset($pdf)) {
        $x = 500;
        $y = 10;
        $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
        $font = null;
        $size = 10;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
</script>
</html>