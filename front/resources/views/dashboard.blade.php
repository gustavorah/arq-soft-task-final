<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seus eventos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @forelse ($inscricoes as $inscricao)
            <div class="flex justify-between items-center bg-white overflow-hidden shadow-sm sm:rounded-lg" style="margin: 10px">
                <div class="p-6 text-gray-900">
                        {{ $inscricao['evento']['descricao'] }}
                    </div>

                    <div class="p-6">

                        {{-- @if ($inscricao['evento']['pode_gerar'])     --}}
                            <form id="formGerarCertificado" style="all: unset" action="{{ route('certificado.gerar', ['ref_inscricao' => $inscricao['id']]) }}" method="post">
                                @csrf

                                <x-primary-button color="blue">
                                    {{ __("Gerar Certificado") }}
                                </x-primary-button>
                            </form>
                        {{-- @endif --}}

                        <form style="all: unset" method="POST" action="{{ route('inscricao.cancelar', ['id' => $inscricao['id']]) }}">
                            @csrf
                            @method('DELETE')

                            <x-primary-button color="red" :active="!$inscricao['evento']['dt_fim']">
                                {{ __("Cancelar") }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>
            @empty
                <p>Sem eventos para exibir.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>

<script>
    let isOfflineMode = {{ session('offline', false) ? 'true' : 'false' }};

    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('offlineButton');
        const statusText = document.getElementById('statusText');
        
        if (isOfflineMode) {
            button.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            button.classList.add('bg-green-500', 'hover:bg-green-600');
            statusText.textContent = 'Offline';
        }
    });

    function toggleOfflineMode() {
        isOfflineMode = !isOfflineMode;
        const button = document.getElementById('offlineButton');
        const statusText = document.getElementById('statusText');
        
        if (isOfflineMode) {
            button.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            button.classList.add('bg-green-500', 'hover:bg-green-600');
            statusText.textContent = 'Offline';
        } else {
            button.classList.remove('bg-green-500', 'hover:bg-green-600');
            button.classList.add('bg-gray-500', 'hover:bg-gray-600');
            statusText.textContent = 'Online';
            window.location.reload(); // Recarrega para buscar dados online
        }

        // Envia o estado offline para o servidor
        fetch('/api/set-offline-mode', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ offline: isOfflineMode })
        });
    }

    async function sincronizarDados() {
        try {
            const response = await fetch('/api/sincronizar-dados', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error('Erro ao sincronizar dados');
            }

            const dados = await response.json();
            localStorage.setItem('dadosOficiais', JSON.stringify(dados));
            alert('Dados sincronizados com sucesso!');
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao sincronizar dados. Por favor, tente novamente.');
        }
    }

    function syncWithDatabase() {
        let inscricoes = JSON.parse(localStorage.getItem('inscricoes')) || [];
        // Aqui você pode fazer uma requisição AJAX para enviar os dados para o servidor
        // Exemplo de como poderia ser feito:
        // fetch('/sincronizar', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //     },
        //     body: JSON.stringify({ inscricoes: inscricoes })
        // }).then(response => response.json())
        //   .then(data => {
        //       alert('Sincronização completa!');
        //       localStorage.removeItem('inscricoes');
        //   });
        alert('Funcionalidade de sincronização ainda não implementada.');
    }
</script>
