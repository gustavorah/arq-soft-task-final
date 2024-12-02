<!-- resources/views/eventos/inscrever.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inscrever Pessoa no Evento: ') }} {{ $evento['descricao'] }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('inscrever-rapido') }}">
                    @csrf
                    
                    <input type="hidden" name="evento_id" value="{{ $evento['id'] }}">

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                        <input type="email" id="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-primary-button type="submit">
                            Inscrever
                        </x-primary-button>
                    </div>
                </form>

                @if (isset($error))
                    <script>
                        alert('Usuário já possui inscrição neste evento');
                    </script>
                @endif

                @if (isset($success))
                    <script>
                        alert('Inscrição realizada com sucesso');
                    </script>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
