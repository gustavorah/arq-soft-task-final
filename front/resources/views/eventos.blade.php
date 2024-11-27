
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Eventos') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="display: flex">
            @forelse ($eventos as $evento)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="width: 100%; justify-content: space-between;">
                <div class="p-6 text-gray-900 w-1/2" style="display: flex; justify-content: space-between">
                    {{ $evento['descricao'] }}
                
                    <div>
                        <x-primary-button class="ms-3">
                            {{ __("Inscrever")}}
                        </x-primary-button>
                        <div id="{{$evento['id']}}">
                            
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
