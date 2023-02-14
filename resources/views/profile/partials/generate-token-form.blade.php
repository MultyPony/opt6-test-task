<section id="generate-token-section">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Генерировать API Токен
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            ПОМЕНЯТЬ!!! Обновите информацию профиля своей учетной записи и адрес электронной почты.
        </p>
    </header>

    <form method="post" action="{{ route('tokens.create') }}" class="mt-6 space-y-6">
        @csrf
        @method('post')

        <div>
            <x-input-label for="token_name" value="Токен" />
            <x-text-input id="token_name" name="token_name" type="text" class="mt-1 block w-full"
                          :value="old('token_name', session('token'))"
                          required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('token_name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Генерировать</x-primary-button>

            @if (session('status') === 'token-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >Успешно</p>
            @endif
        </div>
    </form>
</section>
