<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seus eventos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @forelse ($arrEventos as $arr)
                @foreach ($arr as $evento)
                    <div class="flex justify-between items-center bg-white overflow-hidden shadow-sm sm:rounded-lg" style="margin: 10px">
                        <div class="p-6 text-gray-900">
                            {{ $evento['descricao'] }}
                        </div>

                        <div class="p-6">
                            <x-primary-button color="red">
                                {{__("Cancelar")}}
                            </x-primary-button>
                        </div>
                    </div>
                @endforeach
            @empty
                <p>Sem eventos para exibir.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
