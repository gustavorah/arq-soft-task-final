<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Eventos') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="display: flex; flex-direction: column; gap: 1rem;">
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
