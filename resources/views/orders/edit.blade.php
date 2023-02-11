<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Создать заказ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <form method="post" action="{{ route('orders.store') }}" class="mt-6 space-y-6">
                            @csrf
                            @method(isset($order) ? 'put' : 'post')

                            <div>
                                <x-input-label for="created_at" value="Дата заказа" />
                                <x-text-input id="created_at" name="created_at" type="text" class="mt-1 block w-full"
                                              {{--                                              :value="old('name', $user->name)" --}}
                                              x-mask="99.99.9999"
                                              x-data="{}"
                                              placeholder="07.07.2023"
                                              required autofocus autocomplete="telephone" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="telephone" value="Телефон" />
                                <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full"
{{--                                              :value="old('name', $user->name)" --}}
                                    x-mask:dynamic="$input.startsWith('+') ?
                                              ($input.startsWith('+7 ') ?
                                                '+7 999 999 99 99' :
                                                '+79999999999'
                                              ) :
                                              '89999999999'"
                                              x-data="{}"
                                     placeholder="+7 999 999 99 99"
                                     autocomplete="telephone" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
{{--                                              :value="old('email', $user->email)" --}}
                                              required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />

{{--                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())--}}
{{--                                    <div>--}}
{{--                                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">--}}
{{--                                            {{ __('Your email address is unverified.') }}--}}

{{--                                            <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">--}}
{{--                                                {{ __('Click here to re-send the verification email.') }}--}}
{{--                                            </button>--}}
{{--                                        </p>--}}

{{--                                        @if (session('status') === 'verification-link-sent')--}}
{{--                                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">--}}
{{--                                                {{ __('A new verification link has been sent to your email address.') }}--}}
{{--                                            </p>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                @endif--}}
                            </div>
                            <div id="map" style="width: 600px; height: 400px"></div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>Сохранить</x-primary-button>

                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                    >{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            // Функция ymaps.ready() будет вызвана, когда
            // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
            ymaps.ready(init);
            function init(){
                // Создание карты.
                var myMap = new ymaps.Map("map", {
                    // Координаты центра карты.
                    // Порядок по умолчанию: «широта, долгота».
                    // Чтобы не определять координаты центра карты вручную,
                    // воспользуйтесь инструментом Определение координат.
                    center: [55.76, 37.64],
                    // Уровень масштабирования. Допустимые значения:
                    // от 0 (весь мир) до 19.
                    zoom: 7
                });
            }
        </script>
    @endpush
</x-app-layout>

