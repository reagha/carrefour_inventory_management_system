<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Product to Warehouse') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- SKU -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product SKU (Unique ID)</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="e.g. PROD-1001" required>
                        </div>

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <!-- Supplier Dropdown -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Supplier</label>
                            <select name="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">-- Select Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Unit Cost -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit Cost (UGX)</label>
                            <input type="number" name="unit_cost" value="{{ old('unit_cost') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                            <p class="text-xs text-gray-500 mt-1">Enter amount as a whole number (no commas).</p>
                        </div>

                        <!-- Reorder Level -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Reorder Level (Alert Threshold)</label>
                            <input type="number" name="reorder_level" value="{{ old('reorder_level', 10) }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                         <!-- Initial Stock -->
                         <div>
                            <label class="block text-sm font-medium text-gray-700">Initial Stock Quantity</label>
                            <input type="number" name="quantity_in_warehouse" value="{{ old('quantity_in_warehouse', 0) }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('products.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition font-bold">
                            Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>