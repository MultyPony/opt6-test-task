<x-app-layout title-str="{{ isset($product) ? 'Редактировать ' : 'Добавить ' }} товар">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($product) ? 'Редактировать ' : 'Добавить ' }} товар
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <form method="post" action="{{ isset($product) ? route('products.update', [$product]) : route('products.store') }}" class="mt-6 space-y-6">
                            @csrf
                            @method(isset($product) ? 'put' : 'post')

                            <div>
                                <x-input-label for="name" value="Название товара" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="old('name', isset($product) ? $product->name : '')"
                                    min="3" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="price" value="Цена товара" />
                                <x-text-input id="price" name="price" type="number" class="mt-1 block w-full"
                                              placeholder="123.00"
                                              :value="old('price', isset($product) ? $product->price : '')"
                                              step="any"
                                              required />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>Сохранить</x-primary-button>

                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                    >Сохранено</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

