<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Botão para sincronizar dados -->
    <div class="mb-4">
        <x-primary-button type="button" onclick="sincronizarDados()" class="w-full justify-center">
            {{ __('Sincronizar Dados do Banco Oficial') }}
        </x-primary-button>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                {{ __('Cadastrar') }}
            </a>

            <x-primary-button class="ms-3">
                {{ __('Entrar') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Script para sincronização -->
    <script>
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
    </script>
</x-guest-layout>
