<x-app-layout title-str="Главная">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl mb-5">Заказы - {{ $orderCount }}</h2>
                    @if ($orderCount === 0)
                        <p>Сперва добавьте первый товар</p>
                        <a href="{{ route('orders.create') }}"
                           class='inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>
                            Добавить товар
                        </a>
                    @endif
                    <h2 class="text-2xl mb-5">Товары - {{ $productCount }}</h2>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
