<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">    
        <title>Codigo Bancario</title>
        
    </head>
    <body>
        <div class="card-container" style="border-color: black">
            <table style="width: 100%;">
                <tr>
                    <td>
                        <img src="./img/unsapaytransparente.png" width="220" height="100">
                    </td>
                    <td>
                        <img src="./img/ouistransparente.png" width="200" height="90">
                    </td>
                    <td>
                        <img src="./img/ouistransparente.png" width="200" height="90">
                    </td>
                </tr>
            </table>
            
            
            <h3>{{ $alumno->nombres }} {{ $alumno->apellidos }}</h3>
            <h4>{{ $escuela }}</h4>
            <h2>Modificación de Matrícula<h2>
            <hr style="width:80%; color:#3e0b16;">
            <div>
                <h6>Indicaciones para el pago:</h6>
                <p>
                Dirígete al agente bcp más cercano o ingresa a la Aplicación del BCP, luego
                indica o busca, el nombre de la cuenta: "<u>UNSA VIRTUAL</u>".
                Especifica o escribe tu Código Bancario que se encuentra en la parte inferior de este documento.
                </p>
            </div>
            <div class="skills">
                <h6>Código Bancario:</h6>
                <ul>
                    <li>{{ $codigo }}</li>
                </ul>
                <h6>Monto:</h6>
                <ul>
                    <li>S/ 200.00</li>
                </ul>
            </div>
        </div>
    </body>
</html>
<style>
        @page {  

            margin: 40;
            margin-bottom: 0;
            padding:0;
            background-color: #ffffff;
        }
        * {
            box-sizing: border-box;
        }

        body {

            font-family: arial;
            
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border-color: #000000;
            min-height: 400vh;
            margin: 0;
        }

        h1 {
            margin: 20px 0;
        }

        h2 {
            margin: 20px 0;
        }

        h3 {
            margin: 20px 0;
        }

        h6 {
            margin: 5px 0;
            text-transform: uppercase;
        }

        h4 {
            margin: 20px 0;
            margin-bottom:10px 0;
            text-transform: uppercase;
        }

        p {
            font-size: 18px;
            line-height: 22px;
            text-align: justify;
            padding: 10px;
        }

        .card-container {
            background-color: #ffffff;
            border-radius: 10px;
            color: #000000;
            padding-top: 30px;
            position: relative;
            width: 500px;
            height: 650px;
            max-width: 100%;
            text-align: center;
            border: 2px solid #000000;
        }

        .card-container .round {
            border: 2px solid #c9b06f;
            border-radius: 10px;
            padding: 2px;
        }

        .skills {
            background-color: #3e0b16;
            text-align: center;
            color: #ffffff;
            padding: 2px;
            margin-top: 0px;
            border-radius: 10px;
            position: absolute;
            bottom: 22;
        }

        .skills ul {
            list-style-type: none;
            margin: 3;
            padding: 2px;
            text-align:center;
        }

        .skills ul li {
            border: 2px solid #ffffff;
            border-radius: 10px;

            font-size: 30px;
            margin: 0 2px 2px 0;
            padding: 2px;
            text-align:center;
        }

        .card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, 0.125);
            border-radius: 10px;
        }
</style>
