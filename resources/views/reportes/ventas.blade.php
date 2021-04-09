<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de ventas</title>
</head>
<body>
    <div id="app">
        <!-- Magic happens here! -->
        <basic-component :comprobantes="{{$comprobantes}}"></basic-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>