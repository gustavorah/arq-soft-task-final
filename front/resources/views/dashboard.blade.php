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
