<x-app-layout title-str="Товары">
    <x-slot name="header">
        <div class="flex justify-between">
            <x-button-link href="{{ route('products.create') }}">
                Добавить товар
            </x-button-link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl">Товары</h1>
                    @if ($productCount > 0)
                        <table id="products-table" class="table-auto display">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Название</th>
                            <th>Цена</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    @else
                        <div class="flex justify-center align-center">
                            <div class="p-10 bg-slate-700 rounded-lg">
                                <h2 class="text-2xl mb-5">Товары отсутствуют</h2>
                                <div class="flex justify-center">
                                    <x-button-link href="{{ route('products.create') }}">
                                        Добавить товар
                                    </x-button-link>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
