<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>

    <script>
        function inscreverEvento(ref_pessoa, ref_evento)
        {
            fetch('/inscrever', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ ref_pessoa: ref_pessoa, ref_evento: ref_evento })
            })
            .then(async (response) => {
                const text = await response.text(); // Captura a resposta como texto bruto
                console.log('Resposta do servidor:', text);

                try {
                    return JSON.parse(text); // Tenta processar como JSON
                } catch (e) {
                    throw new Error('Resposta não é um JSON válido');
                }
            })
            .then(data => {
                console.log('Dados processados:', data);
                if (data.success) {
                    alert(data.message);
                } else {
                    alert('Erro ao inscrever no evento.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }

        function openModal(eventoId) {
            // Exibe o modal correspondente ao evento
            const modal = document.getElementById(`modal-${eventoId}`);
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeModal(eventoId) {
            // Oculta o modal correspondente ao evento
            const modal = document.getElementById(`modal-${eventoId}`);
            if (modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
</html>
