<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Stock Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong>Hold up!</strong> Please fix the following issues:
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- We use Alpine.js for interactive, modern form rows without heavy frameworks -->
                <form method="POST" action="{{ route('branch-requests.store') }}" x-data="orderForm()">
                    @csrf

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Request Items</h3>

                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700">Product</label>
                                    <!-- Using x-bind:name to dynamically assign array names like items[0][product_id] -->
                                    <select x-bind:name="`items[${index}][product_id]`" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select a product...</option>
                                        <!-- Real app will populate from $products variable -->
                                        <option value="1">SKU001 - Bottled Water</option>
                                        <option value="2">SKU002 - Rice 5kg</option>
                                    </select>
                                </div>
                                <div class="w-32">
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" min="1" x-bind:name="`items[${index}][quantity]`" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div class="pt-6">
                                    <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-900 font-bold" x-show="items.length > 1">
                                        &times; Remove
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-4">
                        <button type="button" @click="addItem()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            + Add Another Item
                        </button>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded shadow transition">
                            Submit Request
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Alpine.js logic for dynamic form rows -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderForm', () => ({
                items: [{ product_id: '', quantity: 1 }],

                addItem() {
                    this.items.push({ product_id: '', quantity: 1 });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                }
            }))
        })
    </script>
</x-app-layout>
