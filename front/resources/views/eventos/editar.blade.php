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

            <!-- Abas -->
            <div class="border-b border-gray-200">
                <div class="flex space-x-4">
                    <button id="tab-editar" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        {{ __('Editar Evento') }}
                    </button>
                    <button id="tab-presencas" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        {{ __('Marcar Presenças') }}
                    </button>
                </div>
            </div>

            <!-- Conteúdo das abas -->
            <div id="tab-content" class="mt-4">
                <!-- Aba 1: Editar Evento -->
                <div id="conteudo-editar" class="tab-pane">

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
                                    <input type="datetime-local" id="dt_inicio" name="dt_inicio" 
                                        value="{{ old('dt_inicio', $evento['dt_inicio'] ? \Carbon\Carbon::parse($evento['dt_inicio'])->format('Y-m-d\TH:i') : '') }}" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('dt_inicio')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="dt_fim" class="block text-sm font-medium text-gray-700">{{ __('Data de Fim') }}</label>
                                    <input type="datetime-local" id="dt_fim" name="dt_fim" 
                                        value="{{ old('dt_fim', $evento['dt_fim'] ? \Carbon\Carbon::parse($evento['dt_fim'])->format('Y-m-d\TH:i') : '') }}" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('dt_fim')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- <div class="mb-4">
                                    <label for="layout_certificado" class="block text-sm font-medium text-gray-700">{{ __('Layout Certificado') }}</label>
                                    <select id="layout_certificado" name="layout_certificado" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @foreach($layout_certificado as $layout)
                                            <option value="{{ $layout['id'] }}" {{ old('layout_certificado', $evento['layout_certificado_id']) == $layout['id'] ? 'selected' : '' }}>
                                                {{ $layout['nome'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('layout_certificado')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div> --}}

                                <x-primary-button color='green'>
                                    {{ __('Atualizar Evento') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Aba 2: Marcar Presenças -->
                <div id="conteudo-presencas" class="tab-pane hidden">
                    <form action="{{ route('presencas.marcar') }}" method="POST">
                        @csrf

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4">{{ __('Marcar Presenças') }}</h3>
                                
                                <input type="hidden" name="evento_id" value="{{ $evento['id'] }}">
                                <!-- Lista de usuários inscritos -->
                                <div class="space-y-4">
                                    @foreach ($inscricoes_evento as $inscricao)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="presencas[{{$inscricao['id']}}]" value="{{ $inscricao['ref_pessoa'] }}" class="mr-2">
                                            <span>{{ $inscricao['email'] }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <x-primary-button color='blue' class="mt-4">
                                    {{ __('Marcar Presenças') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para alternar abas -->
    <script>
        document.getElementById('tab-editar').addEventListener('click', function() {
            document.getElementById('conteudo-editar').classList.remove('hidden');
            document.getElementById('conteudo-presencas').classList.add('hidden');
            document.getElementById('tab-editar').classList.add('text-indigo-600');
            document.getElementById('tab-presencas').classList.remove('text-indigo-600');
        });

        document.getElementById('tab-presencas').addEventListener('click', function() {
            document.getElementById('conteudo-editar').classList.add('hidden');
            document.getElementById('conteudo-presencas').classList.remove('hidden');
            document.getElementById('tab-editar').classList.remove('text-indigo-600');
            document.getElementById('tab-presencas').classList.add('text-indigo-600');
        });
    </script>
</x-app-layout>
