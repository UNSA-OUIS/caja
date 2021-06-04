<!DOCTYPE>

<html>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title>Ticket de {{$comprobante->tipo_comprobante['nombre']}} {{$comprobante->serie}}-{{$comprobante->correlativo}}</title>

<style>
    @charset "utf-8";

    /* CSS Document */

    body {

        margin: 10px;
        padding: 0;

        background-image: url(../img/fondo.png);

        background-repeat: repeat;

        padding-bottom: 1px;

        font-size: 11px;



    }

    body,
    td,
    th {

        font-family: Arial, Helvetica, sans-serif;

        font-size: 12px;



    }

    a:link {
        color: #0B55C4;
        text-decoration: none;
    }

    a:visited {
        color: #0B55C4;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }









    /*---------------Juego de Campos----------------------*/

    fieldset {

        margin-bottom: 10px;

        border: 1px #ccc solid;

        padding: 5px;

        text-align: left;

    }



    fieldset p {
        margin: 10px 0px;
    }



    legend {

        color: #0B55C4;

        font-size: 12px;

        font-weight: bold;

    }



    fieldset.adminform {
        border: 1px solid #ccc;
        margin: 0 10px 10px 10px;
    }



    /*------------Tablas de la administracion------------*/

    table.adminlist {

        width: 100%;

        border-spacing: 1px;

        background-color: #e7e7e7;

        color: #666;

        font-size: 12px;

    }



    table.adminlist td,

    table.adminlist th {
        padding: 4px;
    }



    table.adminlist thead th {

        text-align: center;

        background: #f0f0f0;

        color: #666;

        border-bottom: 1px solid #999;

        border-left: 1px solid #fff;

    }



    table.adminlist thead a:hover {
        text-decoration: none;
    }



    table.adminlist thead th img {
        vertical-align: middle;
    }



    table.adminlist tbody th {
        font-weight: bold;
    }



    table.adminlist tbody tr {
        background-color: #fff;
        text-align: left;
    }

    table.adminlist tbody tr.row1 {
        background: #f9f9f9;
        border-top: 1px solid #fff;
    }



    table.adminlist tbody tr.row0:hover td,

    table.adminlist tbody tr.row1:hover td {
        background-color: #ffd;
    }



    table.adminlist tbody tr td {
        height: 30px;
        background: #fff;
        border: 1px solid #fff;
    }

    table.adminlist tbody tr.row1 td {
        background: #f9f9f9;
        border-top: 1px solid #FFF;
    }



    table.adminlist tfoot tr {
        text-align: center;
        color: #333;
    }

    table.adminlist tfoot td,

    table.adminlist tfoot th {
        background-color: #f3f3f3;
        border-top: 1px solid #999;
        text-align: center;
    }



    /*---------------------------------------------------*/

    /* standard form style table */



    table.admintable td {
        padding: 3px;
    }

    table.admintable td.key,

    table.admintable td.paramlist_key {

        background-color: #f6f6f6;

        text-align: right;

        width: 100%;

        color: #666;

        font-weight: bold;

        font-size: 12px;

        border-bottom: 1px solid #e9e9e9;

        border-right: 1px solid #e9e9e9;

    }



    table.paramlist td.paramlist_description {

        background-color: #f6f6f6;

        text-align: left;

        width: 100%;

        color: #333;

        font-weight: normal;

        border-bottom: 1px solid #e9e9e9;

        border-right: 1px solid #e9e9e9;

    }



    table.admintable td.key.vtop {
        vertical-align: top;
    }



    table.adminform {

        background-color: #f9f9f9;

        border: solid 1px #d5d5d5;

        width: 100%;

        border-collapse: collapse;

        margin: 8px 0 10px 0;

        margin-bottom: 15px;

        width: 100%;

    }

    table.adminform.nospace {
        margin-bottom: 0;
    }

    table.adminform tr.row0 {
        background-color: #f9f9f9;
    }

    table.adminform tr.row1 {
        background-color: #eeeeee;
    }



    table.adminform th {

        font-size: 11px;

        padding: 6px 2px 4px 4px;

        text-align: left;

        height: 25px;

        color: #000;

        background-repeat: repeat;

    }

    table.adminform td {
        padding: 3px;
        text-align: left;
    }



    table.adminform td.filter {

        text-align: left;

    }



    table.adminform td.helpMenu {

        text-align: right;

    }





    fieldset.adminform {
        border: 1px solid #ccc;
        margin: 0 10px 10px 10px;
    }

    /* Blocks */



    .block {

        width: 100%;

        padding-bottom: 5px;

        margin-bottom: 25px;

        clear: both;

        border-radius: 10px;

        /*background: #000 url(../img/bnd.gif) bottom center repeat-x;*/

        background-color: #fff;

        border: solid 1px #CCCCCC;

    }





    /* Contenedor*/



    .wrapper {

        width: 100%;



        margin: 0 auto;

        padding-top: 20px;

    }



    /* Block Content*/

    .block .block_content {

        overflow: hidden;

        background: #fff;

        width: 100%;

        /*border-left: 1px solid #ccc;

            border-right: 1px solid #ccc;*/

        border-top: 1px solid #CECECE;

        padding: 10px 20px 10px;

    }

    .block .block_content_1 {

        background: #FC9;

    }

    .block .block_content_2 {

        background: #3CF;

    }

    /* Block head */



    .block .block_head {

        height: 62px;

        background-color: #F5F5F5;

        -moz-border-radius: 10px 10px 0px 0px;

        -o-border-radius: 10px 10px 0px 0px;

        -webkit-border-radius: 10px 10px 0px 0px;

        -ms-border-radius: 10px 10px 0px 0px;



        overflow: visible;



    }

    /* Tool bar */

    .block .tool_bar {}



    .block .tool_bar ul li {

        display: block;

        background: none;

        float: left;

        padding-left: 10px;

    }

    .block .tool_bar ul li a:hover {

        background-color: #FFF;

        border: 1px solid #999;

        color: #333;

    }







    .block .titulo_head {

        height: 25px;

        width: 100%;

        float: left;

        font-family: "Titillium999", "Trebuchet MS", Arial, sans-serif;

        font-size: 18px;

        font-weight: normal;

        text-transform: uppercase;

        color: #555;

        text-shadow: 1px 1px 0 #fff;

        margin-left: 15px;

        padding-top: 20px;



    }

    .block .imagen_head {

        height: 25px;

        width: 100%;

        float: left;

        margin-left: 20px;

        padding-top: 10px;

    }

    .block .menu_head {

        height: 20px;







    }

    /* ---------------------------------------

                ICON DOCK

        ----------------------------------------- */



    #icon ul {
        padding-left: 1px
    }

    #icon ul li a img {
        margin-bottom: 1px;
    }

    /*distancia entre texto e imagen*/



    #icon ul li a {

        float: left;

        height: 40px;

        width: 100%;

        padding-bottom: 10px;



        position: relative;

        text-decoration: none;

        border: 1px solid #CCC;

        background-color: #F2F2F2;

        margin-bottom: 20px;

        margin-left: 0px;

        text-align: center;

        color: #666;

        font-size: 11px;

        font-weight: bold;



        /*Mozilla Browsers Only */

        -moz-border-radius: 5px;



    }

    #icon ul li {

        display: block;

        background: none;

        float: left;

        padding-left: 10px;

    }

    #icon ul li a:hover {

        background-color: #FFF;

        border: 1px solid #999;

        color: #333;

    }





    /* Inicio - Editar */

    .operaciones {

        font-family: Arial, Helvetica, sans-serif;

        font-size: 12px;

        font-weight: bold;

        text-align: center;



    }

    .operaciones a:link {
        color: #0B55C4;
        text-decoration: none;
    }

    .operaciones a:visited {
        color: #0B55C4;
        text-decoration: none;
    }

    .operaciones a:hover {
        text-decoration: underline;
    }



    /** toolbar **/



    div.toolbar {

        float: right;

        text-align: right;

        padding: 0;

        margin-right: 20px;

        margin-top: 5px;







    }



    table.toolbar {
        border-collapse: collapse;
        padding: 0;
        margin: 0;
    }

    table.toolbar td {
        padding: 1px 1px 1px 4px;
        text-align: center;
        color: #666;
        height: 48px;
    }

    table.toolbar td.spacer {
        width: 100%;
    }

    table.toolbar td.divider {
        border-right: 1px solid #eee;
        width: 100%;
    }



    table.toolbar span {
        float: none;
        width: 100%;
        height: 32px;
        margin: 0 auto;
        display: block;
    }



    table.toolbar a {

        display: block;
        float: left;

        background: #F5F5F5;

        white-space: nowrap;

        border: 1px solid #F5F5F5;

        padding: 1px 5px;

        cursor: pointer;

        font-size: 11px;

    }



    table.toolbar a:hover {

        border-left: 1px solid #eee;

        border-top: 1px solid #eee;

        border-right: 1px solid #ccc;

        border-bottom: 1px solid #ccc;

        text-decoration: none;

        color: #0B55C4;

    }









    /** button **/

    .button {

        display: block;
        float: left;

        white-space: nowrap;

        border: 1px solid #F5F5F5;

        padding: 1px 5px;

        cursor: pointer;

        font-family: Arial, Helvetica, sans-serif;

        font-size: 11px;

        color: #0B55C4;

        text-align: center center;

        background: #F5F5F5;

    }

    .button:hover {

        border-left: 1px solid #eee;

        border-top: 1px solid #eee;

        border-right: 1px solid #ccc;

        border-bottom: 1px solid #ccc;

        text-decoration: none;

        color: #0B55C4;

    }



    /* Estilo por defecto para validacion */

    #input:required:invalid {
        border: 1px solid red;
    }



    #input:required:valid {
        border: 1px solid green;
    }



    /*------------- Divisiones---------------- */

    .zona_total {

        width: 100%;

        float: left;

        margin-left: 50px;







    }

    .zona_impresion {



        width: 100%;

        padding: 10px 5px 10px 5px;



        float: left;

        margin-left: 00px;

        border-style: solid;

        border: 1px solid #999;

        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);



    }

    .box_contado {





        background-repeat: repeat;

        padding: 10px 10px 10px 10px;

        border: 0px solid #d2d3d4;

        border-bottom-color: #d2d3d4;

        border-radius: 6px;

        box-shadow: 0 2px 2px -2px rgba(0, 0, 0, 0.2)
    }

    .box_contado_texto {

        text-align: center;

        font-size: 16px;

        font-weight: bold;

        color: #0B55C4;

        padding: 10px 10px 10px 10px;

        border: 1px solid #d2d3d4;

        border-bottom-color: #CCC;



        background-color: #EAEAEA;

        box-shadow: 0 2px 2px -2px rgba(0, 0, 0, 0.2);



        border-top: 1px solid #ddd;

        -webkit-border-radius: 6px 6px 0px 0px;

        -moz-border-radius: 6px 6px 0px 0px;

        border-radius: 6px 6px 0px 0px;





    }

    .box_contado_dato {



        text-align: center;

        font-size: 20px;

        font-weight: bold;

        padding: 10px 10px 10px 10px;

        border: 1px solid #d2d3d4;

        border-bottom-color: #afb0b1;



        background-color: #FFF;

        box-shadow: 0 2px 2px -2px rgba(0, 0, 0, 0.2);



        border-top: 1px solid #ddd;

        -webkit-border-radius: 0px 0px 6px 6px;

        -moz-border-radius: 0px 0px 6px 6px;

        border-radius: 0px 0px 6px 6px;





    }
</style>

<body>

    <div class="zona_impresion">

        <!-- codigo imprimir -->

        <br>

        <table border="0" align="justify" width="100%">

            <tr>

                <td align="center">

                    <!-- Mostramos los datos de la empresa en el documento HTML -->

                    .::<strong>UNIVERSIDAD NACIONAL DE SAN AGUSTIN</strong>::.<br>

                    R.U.C. 20163646499<br>

                    CALLE SANTA CATALINA 117 AREQUIPA AREQUIPA<br>

                </td>

            </tr>

            <tr>

                <td align="center"></td>

            </tr>

            <tr>

                <!-- Mostramos los datos del cliente en el documento HTML -->

                <td><b>Cliente:</b> {{$comprobante->comprobanteable['razon_social']}}</td>

            </tr>
            <tr>

                <td><b>{{$comprobante->tipo_comprobante['nombre']}} ELECTRONICA:</b> </td>

            </tr>

            <tr>

                <td><b>Nº de venta:</b> {{$comprobante->serie}}-{{$comprobante->correlativo}}</td>

            </tr>

            <tr>

                <td><b>Fecha y hora de emision:</b> {{$comprobante->created_at}} </td>

            </tr>

        </table>

        <br>

        <!-- Mostramos los detalles de la venta en el documento HTML -->

        <table border="0" align="center" width="100%">

            <tr>

                <th>CANTIDAD</th>

                <th>CODIGO</th>

                <th align="right">DESCRIPCIÓN</th>

            </tr>

            <tr>

                <td colspan="3">=================================================</td>

            </tr>

            <!--$cantidad=0;-->

            @foreach($comprobante->detalles as $detalle)

            <tr>

                <td>{{$detalle->cantidad}}</td>

                <td>{{$detalle->concepto_id}}</td>

                <td align="right">{{$detalle->concepto->descripcion_imp}}</td>

            </tr>

            @endforeach

            <!-- Mostramos los totales de la venta en el documento HTML -->



            <tr>

                <td>&nbsp;</td>

                <td align="right"><b>Importe total:</b></td>

                <td align="right"><b>S/. {{$comprobante->total}}</b></td>

            </tr>

            <tr>

                <td colspan="3">&nbsp;</td>

            </tr>

            <tr>

                <td>&nbsp;</td>

                <td align="right"><b>OP. INAFECTA</b></td>

                <td align="right"><b>S/. {{$comprobante->total_inafecta}}</b></td>

            </tr>

            <tr>

                <td>&nbsp;</td>

                <td align="right"><b>OP. GRAVADA</b></td>

                <td align="right"><b>S/. {{$comprobante->total_gravada}}</b></td>

            </tr>

            <tr>

                <td>&nbsp;</td>

                <td align="right"><b>IGV 18%</b></td>

                <td align="right"><b>S/. {{$comprobante->total_impuesto}}</b></td>

            </tr>

            <tr>

                <td>&nbsp;</td>

                <td align="right"><b>Importe total:</b></td>

                <td align="right"><b>S/. {{$comprobante->total}}</b></td>

            </tr>

            <tr>

                <td colspan="3">&nbsp;</td>

            </tr>

        </table>

        <br>

    </div>

</body>

</html>
