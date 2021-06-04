<!DOCTYPE html>
<html>
<!-- Required meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>{{$comprobante->tipo_comprobante['nombre']}} {{$comprobante->serie}}-{{$comprobante->correlativo}}</title>

<style>
    body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif;
        font-size: 14px;
        /*font-family: SourceSansPro;*/
    }

    #logo {
        float: left;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
    }

    #imagen {
        width: 150px;
    }

    #datos {
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
    }

    #encabezado {
        text-align: center;
        margin-right: 50%;
        font-size: 15px;
    }

    #fact {
        /*position: relative;*/
        float: right;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 15px;
    }

    section {
        clear: left;
    }

    #cliente {
        text-align: left;
    }

    #facliente {
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-top: 15px;
    }

    #fac,
    #fv,
    #fa {
        color: #FFFFFF;
        font-size: 15px;
    }

    #facliente thead {
        padding: 20px;
        background: #2183E3;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;
    }

    #facvendedor {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
    }

    #facvendedor thead {
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
    }

    #facarticulo {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
    }

    #facarticulo thead {
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
    }

    #gracias {
        text-align: center;
    }
</style>

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
                R.U.C. 20163646499<br>
                {{$comprobante->tipo_comprobante['nombre']}} ELECTRONICA<br>
                {{$comprobante->serie}}-{{$comprobante->correlativo}}
            </b>
        </div>
    </header>
    <br>
    @if ($comprobante->tipo_comprobante['nombre'] == 'FACTURA')
    <section>
        <div>
            <table id="facliente">
                <thead>
                    <tr>
                        <th id="fac">Cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p id="cliente">
                                <b>Razon Social: {{$comprobante->comprobanteable['razon_social']}}</b><br>
                                <b>Ruc::</b> {{$comprobante->comprobanteable['ruc']}}<br>
                                <b>Dirección:</b> {{$comprobante->comprobanteable['direccion']}}<br>
                                <b>Fecha y hora de emision:</b> {{$comprobante->created_at}}<br>
                                <b>Tipo moneda: </b> SOLES
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    @endif
    @if ($comprobante->tipo_comprobante['nombre'] == 'BOLETA')
    <section>
        <div>
            <table id="facliente">
                <thead>
                    <tr>
                        <th id="fac">Cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p id="cliente">
                                <b>Nombre: </b>{{$comprobante->comprobanteable['apn']}}<br>
                                <b>CUI:</b> {{$comprobante->comprobanteable['cui']}}<br>
                                @php
                                $dni = substr($comprobante->comprobanteable['dic'], 1);
                                @endphp
                                <b>DNI:</b> {{$dni}}<br>
                                <b>Fecha y hora de emision:</b> {{$comprobante->created_at}}<br>
                                <b>Tipo moneda: </b> SOLES
                            </p>
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
            <table id="facarticulo">
                <thead>
                    <tr id="fa">
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
                        <td align="center"> {{$detalle->cantidad}} </td>
                        <td align="center"> {{$detalle->concepto_id}} </td>
                        <td> {{$detalle->concepto->descripcion}} </td>
                        <td align="center"> {{$detalle->valor_unitario}} </td>
                        <td align="right"> {{$detalle->valor_unitario * $detalle->cantidad}} </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="right">Importe total:</th>
                        <td align="right">S/. {{$comprobante->total}}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="right">OP. INAFECTA </th>
                        <td align="right">S/. {{$comprobante->total_inafecta}} </td>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="right">OP. GRAVADA</th>
                        <td align="right">S/. {{$comprobante->total_gravada}} </td>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="right">IGV 18%</th>
                        <td align="right">S/. {{$comprobante->total_impuesto}} </td>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="right">Importe total:</th>
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
