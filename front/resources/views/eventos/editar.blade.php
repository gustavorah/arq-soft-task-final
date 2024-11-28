<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Evento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Exibir mensagens de erro ou sucesso -->
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="bg-red-500 text-white p-4 mb-4 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('eventos.atualizar', ['evento' => $evento['id']]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4">
                            <label for="descricao" class="block text-sm font-medium text-gray-700">{{ __('Descrição') }}</label>
                            <input type="text" id="descricao" name="descricao" value="{{ old('descricao', $evento['descricao']) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('descricao')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="dt_inicio" class="block text-sm font-medium text-gray-700">{{ __('Data de Início') }}</label>
                            <input type="datetime-local" id="dt_inicio" name="dt_inicio" value="{{ old('dt_inicio', $evento['dt_inicio'] ? $evento['dt_inicio']->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('dt_inicio')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="dt_fim" class="block text-sm font-medium text-gray-700">{{ __('Data de Fim') }}</label>
                            <input type="datetime-local" id="dt_fim" name="dt_fim" value="{{ old('dt_fim', $evento['dt_fim'] ? $evento['dt_fim']->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('dt_fim')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <x-primary-button color='green'>
                            {{ __('Atualizar Evento') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
