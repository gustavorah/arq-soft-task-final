<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Eventos') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="display: flex; flex-direction: column; gap: 1rem;">

            <x-primary-button color="orange" onclick="syncWithDatabase()">
                {{ __("Sincronizar Para Oficial") }}
            </x-primary-button>
            <x-primary-button color="orange" onclick="sincronizarDados()">
                {{ __("Sincronizar Com Oficial") }}
            </x-primary-button>

            <div class="flex items-center gap-2">
                <span id="statusText">Online</span>
                <button onclick="toggleOfflineMode()" 
                        id="offlineButton"
                        class="px-4 py-2 rounded-md text-white bg-gray-500 hover:bg-gray-600">
                    Modo Offline
                </button>
            </div>

            @forelse ($eventos as $evento)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4" style="width: 100%; justify-content: space-between;">
                <div class="p-6 text-gray-900 w-1/2" style="display: flex; justify-content: space-between">
                    <div>
                        {{ $evento['descricao'] }}
                    </div>
                
                    <div>
                        <x-primary-button class="ms-3" onclick="openModal({{ $evento['id'] }})">
                            {{ __("Inscrever")}}
                        </x-primary-button>
                        @if ($user['is_admin'])
                        <a href="{{route('eventos.inscrever', ['evento' => $evento['id']])}}">
                            <x-primary-button>
                                {{__('Inscrever Pessoa')}}
                            </x-primary-button>
                        </a>
                        <a href="{{route('eventos.editar', ['evento' => $evento['id']])}}">
                            <x-primary-button>
                                {{__('Editar/Marcar Presença')}}
                            </x-primary-button>
                        </a>
                            <x-primary-button color="red">
                                {{__('Deletar')}}
                            </x-primary-button>
                        @endif
                        <div id="modal-{{$evento['id']}}" class="hidden">
                            <x-modal-dialog :evento="$evento" :user="$user"></x-modal-dialog>
                        </div>
                    </div>
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

    function saveToLocalStorage(inscricao) {
        let inscricoes = JSON.parse(localStorage.getItem('inscricoes')) || [];
        inscricoes.push(inscricao);
        localStorage.setItem('inscricoes', JSON.stringify(inscricoes));
        alert('Inscrição salva localmente!');
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

