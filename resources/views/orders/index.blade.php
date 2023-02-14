<x-app-layout title-str="Заказы">
    <x-slot name="header">
        <div class="flex justify-between">
            <x-button-link href="{{ route('orders.create') }}">
                Создать заказ
            </x-button-link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <h1 class="text-2xl">Заказы</h1>
                    @if ($orderCount > 0)
                        <table id="order-table" class="table-auto">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Телефон</th>
                                <th>Email</th>
                                <th>Адрес</th>
                                <th>Сумма</th>
                                <th>Дата</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    @else
                        <div class="flex justify-center align-center">
                            <div class="p-10 bg-slate-700 rounded-lg">
                                <h2 class="text-2xl mb-5">Заказы отсутствуют</h2>
                                <div class="flex justify-center">
                                    <x-button-link href="{{ route('orders.create') }}">
                                        Создать заказ
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
