<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Eventos') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="display: flex; flex-direction: column; gap: 1rem;">

            {{-- <x-primary-button color="orange" onclick="syncWithDatabase()">
                {{ __("Sincronizar Para Oficial") }}
            </x-primary-button> --}}

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
                        <x-primary-button class="ms-3" onclick="openModal({{ $evento['id'] }}, {{ $user['id'] }})">
                            {{ __("Inscrever")}}
                        </x-primary-button>
                        @if ($user['is_admin'])
                        <a href="{{route('eventos.inscrever', ['evento' => $evento['id']])}}" 
                            onclick="return handleInscricaoPessoa(event, {{ $evento['id'] }})">
                             <x-primary-button>
                                 {{__('Inscrever Pessoa')}}
                             </x-primary-button>
                         </a>
                        <a href="{{route('eventos.editar', ['evento' => $evento['id']])}}"
                            onclick="return handleMarcarPresencas(event, {{ $evento['id'] }})">
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
            syncWithDatabase();
            alert('Dados sincronizados com sucesso!');
            window.location.reload(); // Recarrega para buscar dados online
        }

        sincronizarDados();

        localStorage.setItem('offline', isOfflineMode);
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
        const dadosOficiais = JSON.parse(localStorage.getItem('dadosOficiais')) || [];
        
        fetch('/api/sincronizar-dados-oficiais', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ dadosOficiais: dadosOficiais })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Sincronização completada com sucesso!');
                // Opcional: limpar dados locais após sincronização
                localStorage.removeItem('dadosOficiais');
            } else {
                throw new Error(data.message || 'Erro na sincronização');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao sincronizar dados: ' + error.message);
        });
    }

    function handleInscricaoPessoa(event, eventoId) {
        if (isOfflineMode) {
            event.preventDefault();
            
            // Abre um prompt para pegar o ID ou nome da pessoa
            const email = prompt('digite email da pessoa para inscrição');
            
            if (email) {
                let dadosOficiais = JSON.parse(localStorage.getItem('dadosOficiais')) || [];

                const pessoa = dadosOficiais.users.find(pessoa => pessoa.email === email);

                if (!pessoa) {
                    // criar pessoa
                    const pessoa = {
                        email: email,
                        password: 'teste'
                    };

                    dadosOficiais.users.push(pessoa);

                    localStorage.setItem('dadosOficiais', JSON.stringify(dadosOficiais));
                    // alert('Pessoa não encontrada');
                    // return false;
                }

                const inscricao = {
                    ref_evento: eventoId,
                    email: email,
                    dt_inscricao: new Date().toISOString()
                };
                

                dadosOficiais.inscricao_evento.push(inscricao);
                localStorage.setItem('dadosOficiais', JSON.stringify(dadosOficiais));
                
                alert('Inscrição administrativa salva localmente!');
            }
            
            return false;
        }
        return true; // Permite o comportamento normal do link quando online
    }

    function handleMarcarPresencas(event, eventoId) {
    if (isOfflineMode) {
        event.preventDefault();
        
        let dadosOficiais = JSON.parse(localStorage.getItem('dadosOficiais')) || [];
        
        // Filtra inscrições ativas do evento
        const inscricoes = dadosOficiais.inscricao_evento.filter(
            inscricao => {
                const jaTemPresenca = dadosOficiais.presencas.find(presenca => {
                    return presenca.ref_pessoa === inscricao.ref_pessoa && presenca.ref_inscricao_evento === inscricao.id;
                });
                
                return (
                    (inscricao.ref_evento === eventoId && inscricao.dt_cancelamento === null)
                    ||
                    (inscricao.email && inscricao.email.includes('@'))
                    && !jaTemPresenca
                )
            }
        );

        // Busca informações dos usuários
        const usuarios = dadosOficiais.users || [];

        const modal = document.createElement('div');
        modal.id = 'modalPresenca';
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center';
        
        const content = document.createElement('div');
        content.className = 'bg-white p-6 rounded-lg w-96';
        
        const inscricoesHtml = inscricoes.map(inscricao => {
            const email = inscricao.email;

            const usuario = usuarios.find(u => u.id === inscricao.ref_pessoa || u.email === email);
            const nomeUsuario = usuario ? (usuario.email ?? $email) : `Usuário ${inscricao.ref_pessoa}`;
            
            return `
                <div class="flex items-center gap-2 mb-2">
                    <input type="checkbox" 
                           id="presenca-${inscricao.id ?? inscricao.email}" 
                           ${inscricao.presente ? 'checked' : ''}>
                    <label for="presenca-${inscricao.id ?? inscricao.email}">${nomeUsuario}</label>
                </div>
            `;
        }).join('');

        content.innerHTML = `
            <h3 class="text-lg font-bold mb-4">Marcar Presenças</h3>
            <div class="max-h-96 overflow-y-auto">
                ${inscricoesHtml || '<p>Nenhuma inscrição ativa encontrada para este evento.</p>'}
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button onclick="fecharModalPresenca()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Cancelar
                </button>
                <button onclick="salvarPresencas(${eventoId})" 
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Salvar
                </button>
            </div>
        `;
        
        modal.appendChild(content);
        document.body.appendChild(modal);
        
        return false;
    }
    return true;
}

    function fecharModalPresenca() {
        const modal = document.getElementById('modalPresenca');
        if (modal) modal.remove();
    }

    function salvarPresencas(eventoId) {
        let dadosOficiais = JSON.parse(localStorage.getItem('dadosOficiais')) || [];
        
        if (!dadosOficiais.presencas) {
            dadosOficiais.presencas = [];
        }

        // Atualiza as presenças
        const checkboxes = document.querySelectorAll('input[id^="presenca-"]');
        checkboxes.forEach(checkbox => {
            const email = checkbox.id.replace('presenca-', '');
            const inscricaoIndex = dadosOficiais.inscricao_evento.findIndex(
                inscricao => inscricao.ref_evento === eventoId && inscricao.email === email
            );
            
            if (inscricaoIndex !== -1) {
                dadosOficiais.inscricao_evento[inscricaoIndex].presente = checkbox.checked;
                dadosOficiais.presencas.push({
                    email: email,
                    ref_evento: eventoId,
                    dt_criacao: new Date().toISOString()
                });
            }
        });

        localStorage.setItem('dadosOficiais', JSON.stringify(dadosOficiais));
        alert('Presenças salvas localmente!');
        fecharModalPresenca();
    }
</script>

