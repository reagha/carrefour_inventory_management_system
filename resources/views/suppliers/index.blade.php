<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Supplier Directory</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('suppliers.create') }}" class="bg-green-600 text-white px-4 py-2 rounded shadow">Add Supplier</a>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products Linked</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($suppliers as $supplier)
                        <tr>
                            <td class="px-6 py-4 font-bold">{{ $supplier->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ $supplier->contact_email }} <br> 
                                <span class="text-gray-500">{{ $supplier->contact_phone }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $supplier->products_count }} items</td>
                            <td class="px-6 py-4 flex space-x-2">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="text-blue-600">Edit</a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Strict deletion check will be performed. Proceed?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>