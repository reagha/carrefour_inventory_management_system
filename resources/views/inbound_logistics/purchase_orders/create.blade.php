<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create Purchase Order') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('purchase-orders.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700">{{ __('Supplier') }}</label>
                            <select id="supplier_id" name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Choose supplier') }}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold">{{ __('Order Items') }}</h3>
                                <button type="button" id="add-item" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">{{ __('Add Item') }}</button>
                            </div>

                            <div id="items-container" class="space-y-4">
                                <div class="grid gap-4 sm:grid-cols-3 items-end item-row">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Product') }}</label>
                                        <select name="items[0][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">{{ __('Select product') }}</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->sku }} – {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Quantity') }}</label>
                                        <input type="number" name="items[0][quantity]" min="1" value="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <button type="button" class="remove-item inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">{{ __('Remove') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700">{{ __('Save Purchase Order') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <template id="item-row-template">
        <div class="grid gap-4 sm:grid-cols-3 items-end item-row">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('Product') }}</label>
                <select name="items[0][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('Select product') }}</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->sku }} – {{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('Quantity') }}</label>
                <input type="number" name="items[0][quantity]" min="1" value="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>

            <div class="flex items-center space-x-2">
                <button type="button" class="remove-item inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">{{ __('Remove') }}</button>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('items-container');
            const template = document.getElementById('item-row-template');
            const addButton = document.getElementById('add-item');

            function updateIndexes() {
                container.querySelectorAll('.item-row').forEach((row, index) => {
                    const select = row.querySelector('select');
                    const input = row.querySelector('input[type="number"]');
                    select.name = `items[${index}][product_id]`;
                    input.name = `items[${index}][quantity]`;
                });
            }

            addButton.addEventListener('click', function () {
                const clone = template.content.firstElementChild.cloneNode(true);
                container.appendChild(clone);
                updateIndexes();
            });

            container.addEventListener('click', function (event) {
                if (event.target.closest('.remove-item')) {
                    const row = event.target.closest('.item-row');
                    if (container.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                        updateIndexes();
                    }
                }
            });
        });
    </script>
</x-app-layout>
