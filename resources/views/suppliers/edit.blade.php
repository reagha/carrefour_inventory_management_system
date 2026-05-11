<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Supplier</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Supplier Company Name</label>
                            <input type="text" name="name" value="{{ old('name', $supplier->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $supplier->contact_email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Phone (e.g. 0414...)</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $supplier->contact_phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('suppliers.index') }}" class="text-gray-600 mr-4">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded font-bold">Save Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>