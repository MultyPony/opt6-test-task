<div class="add-product-modal">
    <x-secondary-button @click="formData.modalOpened =!formData.modalOpened">
        Добавить товар
    </x-secondary-button>

    <div x-show="formData.modalOpened" class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div
            class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
            <div x-cloak @click="formData.modalOpened = false" x-show="formData.modalOpened"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40"
                 aria-hidden="true"
            ></div>

            <div x-cloak x-show="formData.modalOpened"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform dark:bg-gray-800 rounded-lg shadow-xl 2xl:max-w-2xl"
            >
                <div class="flex items-center justify-between space-x-4">
                    <h1 class="text-xl font-medium dark:text-gray-200 ">
                        Выберите товар
                    </h1>

                    <button type="button" @click="formData.modalOpened = false"
                            class="text-gray-600 focus:outline-none hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                </div>

                <p class="mt-2 text-sm text-gray-500 ">
                    Введите название товара
                </p>

                <form class="mt-5">
                    <div class="bt-thin p-2">
                        <ul id="search-product-results" class="my-4">
                            <template x-for="product in lastProductSearch">
                                <li class="mb-3">
                                    <x-secondary-button class="w-full" x-on:click="addProduct(product)">
                                        <span x-text="product.name"></span> - <span x-text="product.price"></span>
                                    </x-secondary-button>
                                </li>
                            </template>
                        </ul>

                        <!-- Search input -->
                        <div class="relative flex items-center w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="search"
                                   x-on:input="searchProductByName(event.target.value)"
                                   class='pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full'>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div><?php
