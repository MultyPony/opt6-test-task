<x-app-layout title-str="{{ isset($order) ? 'Редактировать' : 'Создать' }} заказ">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($order) ? 'Редактировать' : 'Создать' }} заказ
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 py-12">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <form id="order-form" method="post"
                  novalidate
                  action="{{ isset($order) ? route('orders.update', [$order]) : route('orders.store') }}"
                  x-data="getData"
                  @submit.prevent="submitData"
                  class="mt-6 space-y-6">

                <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        @csrf
                        @method(isset($order) ? 'put' : 'post')

                        <!-- Дата заказа -->
                        <div>
                            <x-input-label for="created_at" value="Дата заказа"/>
                            <x-text-input id="created_at" name="created_at" type="text" class="mt-1 block w-full"
                                          :value="old('created_at', isset($order) ? $order->created_at->format('d.m.Y') : '')"
                                          x-mask="99.99.9999"
                                          placeholder="07.07.2023"
                                          x-model="formData.created_at"
                                          required autofocus/>
                            <span
                                x-show="validation.created_at"
                                class="text-sm text-red-600 dark:text-red-400 space-y-1">
                                    Введите дату
                                </span>
                            <x-input-error class="mt-2" :messages="$errors->get('created_at')"/>
                        </div>
                        <!-- Телефон -->
                        <div class="mt-4">
                            <x-input-label for="telephone" value="Телефон"/>
                            <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full"
                                          :value="old('telephone', isset($order) ? $order->telephone : '')"
                                          x-mask:dynamic="regexForTelephone"
                                          x-model="formData.telephone"
                                          placeholder="+7 999 999 99 99"/>
                            <span
                                x-show="validation.telephone"
                                class="text-sm text-red-600 dark:text-red-400 space-y-1">
                                    Введите телефон
                                </span>
                            <x-input-error class="mt-2" :messages="$errors->get('telephone')"/>
                        </div>
                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" value="Email"/>
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                          :value="old('email', isset($order) ? $order->email : '')"
                                          x-model="formData.email"
                                          required/>
                            <span
                                x-show="validation.email"
                                class="text-sm text-red-600 dark:text-red-400 space-y-1">
                                    Введите почту
                                </span>
                            <x-input-error class="mt-2" :messages="$errors->get('email')"/>
                        </div>
                        <!-- Адрес -->
                        <div class="mt-4">
                            <x-input-label for="address" value="Адрес"/>
                            <x-search-input id="address" name="address" type="text" class="mt-1 block w-full"
                                            :value="old('address', isset($order) ? $order->address : '')"
                                            x-model="formData.address"
                                            @keyup.enter="enterAddressInput"
                                            required/>
                            <span
                                x-show="validation.address"
                                x-text="addressError"
                                class="text-sm text-red-600 dark:text-red-400 space-y-1">
                            </span>
                            <span
                                x-show="addressHint.length > 0"
                                x-text="addressHint"
                                class="text-sm text-red-600 dark:text-red-400 space-y-1">
                            </span>
                            <ul class='text-sm text-red-600 dark:text-red-400 space-y-1'>
                                <li id="notice"></li>
                            </ul>

                            <div id="footer">
                                <div id="messageHeader"></div>
                                <div id="message"></div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('address')"/>
                        </div>
                    </div>
                    <div>
                        <div id="map" class="mt-6 w-full md:h-full h-72 bg-slate-900 p-2 rounded-lg"></div>
                    </div>
                </div>
                <div>
                    <!-- ТОВАРЫ -->
                    <div>
                        @include('components.add-product-modal')
                        <x-input-error class="mt-2" :messages="$errors->get('products')"/>

                        <div class="relative rounded-xl overflow-auto">
                            <div class="shadow-sm overflow-hidden my-8">
                                <table class="border-collapse table-fixed w-full text-sm">
                                    <thead>
                                    <tr>
                                        <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                            Название
                                        </th>
                                        <th class="border-b dark:border-slate-600 font-medium p-4 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                            Количество
                                        </th>
                                        <th class="border-b dark:border-slate-600 font-medium p-4 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                            -
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-slate-800">
                                    <template x-for="product in formData.products">
                                        <tr>
                                            <td
                                                x-text="product.name"
                                                class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400"></td>
                                            <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                                <div>
                                                    <x-secondary-button type="button"
                                                                        x-on:click="product.count > 1 ? product.count-- : ''">
                                                        -
                                                    </x-secondary-button>
                                                    <span x-text="product.count"></span>
                                                    <x-secondary-button type="button"
                                                                        x-on:click="product.count++">+
                                                    </x-secondary-button>
                                                </div>
                                            </td>
                                            <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                                <button type="button" x-on:click="removeProduct(product)"
                                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    <svg fill="none" width="20" height="20"
                                                         stroke="currentColor" stroke-width="1.5"
                                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                                         aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" name="products" x-model="JSON.stringify(formData.products)" value="{{ old('products', isset($order) ? json_encode($productsArray) : '') }}">
                        <input type="hidden" name="total" x-model="calculateTotal" value="{{ old('total', isset($order) ? $order->total : '') }}">

                        <p class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            Сумма заказа: <span id="total" x-text="calculateTotal"></span> ₽
                            <x-input-error class="mt-2" :messages="$errors->get('total')"/>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-4">
                    <x-primary-button>
                        Сохранить
                    </x-primary-button>

                    @if (session('status') === 'order-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400"
                        >Сохранено.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

