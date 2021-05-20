<!DOCTYPE html>
<html>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUIyJ" crossorigin="anonymous">
<title>{{$comprobante}} {{$comprobante->tipo_comprobante['nombre']}} {{$comprobante->serie}}-{{$comprobante->correlativo}}</title>

<body>
    <header>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <div id="logo">
            <img src="https://cdn.jsdelivr.net/gh/unsa-cdn/static@master/logo.png" alt="" height="70" class="logo logo-light float-left mr-2" />

        </div>
        <div id="datos">
            <div id="encabezado">
                <b>UNIVERSIDAD NACIONAL DE SAN AGUSTIN</b><br>
                CALLE SANTA CATALINA 117 AREQUIPA AREQUIPA<br>
                SISTEMA DE CAJAS/INGRESOS<br>
            </div>
        </div>
        <br>
        <div id="fact" align="center">
            <b>
                <h1>{{$comprobante->tipo_comprobante['nombre']}} ELECTRONICA</h1><br>
                <h2>R.U.C. 20163646499</h2><br>
                <h3>{{$comprobante->serie}}-{{$comprobante->correlativo}}</h3><br>
            </b>
        </div>
    </header>
    <br>
    @if ($comprobante->tipo_comprobante['nombre'] == 'FACTURA')
    <section>
        <div id="cliente">
            <table style="width:100%">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <b>Razon Social:</b> {{$comprobante->comprobanteable['razon_social']}}
                        </td>
                        <td>
                            <b>Ruc:</b> {{$comprobante->comprobanteable['ruc']}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <b>Direccion:</b> {{$comprobante->comprobanteable['direccion']}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>Fecha y hora de emision:</b> {{$comprobante->created_at}}
                        </td>
                        <td>
                            <b>Tipo moneda:</b> SOLES
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    @endif
    @if ($comprobante->tipo_comprobante['nombre'] == 'BOLETA')
    <section>
        <div id="cliente">
            <table style="width:100%">
                <tbody>
                    <tr>
                        <td colspan="3">
                            <b>Nombre:</b> {{$comprobante->comprobanteable['apn']}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>CUI:</b> {{$comprobante->comprobanteable['cui']}}
                        </td>
                        @php
                        $dni = substr($comprobante->comprobanteable['dic'], 1);
                        @endphp
                        <td>
                            <b>DNI:</b> {{$dni}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>Fecha y hora de emision:</b> {{$comprobante->created_at}}
                        </td>
                        <td>
                            <b>Tipo moneda:</b> SOLES
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    @endif

    <br>
    <section>
        <div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Codigo</th>
                        <th>Descripcion</th>
                        <th>Valor unitario</th>
                        <th>Valor total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comprobante->detalles as $detalle)
                    <tr>
                        <td> {{$detalle->cantidad}} </td>
                        <td> {{$detalle->concepto_id}} </td>
                        <td> {{$detalle->descripcion}} </td>
                        <td> {{$detalle->valor_unitario}} </td>
                        <td> {{$detalle->valor_unitario * $detalle->cantidad}} </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="right">SUBTOTAL</th>
                        <td align="right">S/. {{$comprobante->total - 18.00}}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="right">I.G.V. </th>
                        <td align="right">S/.18.00 </td>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="right">TOTAL</th>
                        <td align="right">S/. {{$comprobante->total}} </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
</body>
<style>
</style>

</html>
