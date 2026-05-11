<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Outbound Logistics (Branch Requests)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header Actions -->
            <div class="mb-4 flex justify-between items-center bg-white p-4 rounded shadow">
                <h3 class="text-lg font-bold text-gray-700">Recent Requests</h3>

                @if(auth()->user()->role === 'branch_manager')
                    <a href="{{ route('branch-requests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                        + New Stock Request
                    </a>
                @endif
            </div>

            <!-- Data Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal border-b">
                                <th class="py-3 px-6">ID</th>
                                <th class="py-3 px-6">Branch</th>
                                <th class="py-3 px-6">Requested By</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6">Date</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            <!-- In a real scenario, $branchRequests is passed from the Controller. -->
                            <!-- We simulate iterating over them here. -->
                            {{-- @foreach($branchRequests as $req) --}}
                            <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                                <td class="py-3 px-6">#REQ-001</td>
                                <td class="py-3 px-6 font-bold">Oasis Mall Branch</td>
                                <td class="py-3 px-6">John Doe</td>
                                <td class="py-3 px-6">
                                    <span class="bg-yellow-200 text-yellow-700 py-1 px-3 rounded-full text-xs font-bold">
                                        Pending
                                    </span>
                                </td>
                                <td class="py-3 px-6">Oct 24, 2023</td>
                                <td class="py-3 px-6 text-center">

                                    <button class="text-blue-500 hover:text-blue-700 mx-2" title="View Details">
                                        View
                                    </button>

                                    <!-- Warehouse Worker Action -->
                                    @if(auth()->user()->role === 'warehouse_worker')
                                        <form method="POST" action="/branch-requests/1/dispatch" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded text-xs font-bold transition">
                                                Dispatch
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            {{-- @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
