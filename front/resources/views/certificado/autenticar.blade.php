<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Autenticar Certificado</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="text-center mb-4">Autenticar Certificado</h1>
                    <form method="POST" action="{{ route('certificado.autenticar') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="codigo_autenticador" class="form-label">Código do Certificado</label>
                            <input type="text" name="codigo_autenticador" id="codigo_autenticador" class="form-control" placeholder="Digite o código do certificado" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Autenticar</button>
                    </form>
                </div>
            </div>

            @if(isset($msg))
                <script>
                    alert('{{ $msg }}');
                </script>
            @endif
        </div>
    </body>
</html>
