<!DOCTYPE html>
<html>
<head>
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <h1>{{ $titulo }}</h1>
    <p>{{ $conteudo }}</p>

    <p>{{ $codigo_autenticador }}</p>

    <p>http://127.0.0.1:8003/certificado/autenticar</p>
</body>
</html>
