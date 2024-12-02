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
                    closeModal(ref_evento);
                } else {
                    alert('Erro ao inscrever no evento. ' + data.message);
                    closeModal(ref_evento);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }

        function openModal(eventoId, userId) {
            const isOfflineMode = localStorage.getItem('offline') === 'true';
            if (isOfflineMode) {
                // Em modo offline, salva diretamente no localStorage
                let dadosOficiais = localStorage.getItem('dadosOficiais') ? JSON.parse(localStorage.getItem('dadosOficiais')) : []; 

                const verificar_inscricao = dadosOficiais.inscricao_evento.find(inscricao => inscricao.ref_evento === eventoId && inscricao.ref_pessoa === userId);
                if (verificar_inscricao) {
                    alert('Você já está inscrito neste evento');
                    return false;
                }
                
                const inscricao = {
                    ref_evento: eventoId,
                    ref_pessoa: userId,
                    dt_inscricao: new Date().toISOString()
                };

                dadosOficiais.inscricao_evento.push(inscricao);
                localStorage.setItem('dadosOficiais', JSON.stringify(dadosOficiais));
            } else {
                const modal = document.getElementById(`modal-${eventoId}`);
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

        // Obter o formulário
    const form = document.getElementById('formGerarCertificado');

    // Adicionar o evento de submit para o formulário
    if (form)
    {
        form.addEventListener('submit', function(event) {
            // Impedir que o formulário seja enviado normalmente (sem recarregar a página)
            event.preventDefault();
    
            // Criar o FormData para enviar os dados do formulário
            const formData = new FormData(form);
    
            // Enviar a requisição AJAX usando fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.blob()) // Esperar a resposta como blob (arquivo)
            .then(blob => {
                // Criar um link temporário para fazer o download do arquivo
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'certificado.pdf'; // Defina o nome do arquivo para o download
                link.click(); // Simular o clique para iniciar o download
            })
            .catch(error => {
                console.error('Erro ao gerar o certificado:', error);
            });
        });
    }
    </script>
</html>
