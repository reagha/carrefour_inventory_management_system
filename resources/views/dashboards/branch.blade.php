@extends('layouts.app')

@section('title', 'Branch Dashboard')

@section('content')

<!-- PAGE HEADER -->
<div style="
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:16px;
    margin-bottom:28px;
">

    <div>
        <h2 style="
            font-size:1.5rem;
            font-weight:800;
            color:#111827;
            margin-bottom:4px;
        ">
            {{ $branch->name ?? 'My Branch' }}
        </h2>

        <p style="
            color:#6B7280;
            font-size:.92rem;
        ">
            {{ $branch->location ?? 'Branch Location' }}
        </p>
    </div>

    <!-- QUICK ACTION -->
    <a href="{{ route('branch-requests.create') }}"
       style="
            background:#004B9B;
            color:white;
            text-decoration:none;
            padding:12px 18px;
            border-radius:10px;
            font-size:.9rem;
            font-weight:600;
            display:inline-flex;
            align-items:center;
            gap:8px;
       ">
        + New Stock Request
    </a>

</div>

<!-- DASHBOARD STATS -->
<div class="dashboard-grid" style="margin-bottom:28px;">

    <!-- PENDING -->
    <div class="stat-card" style="border-top:4px solid #F59E0B;">

        <div class="stat-label">
            Pending Requests
        </div>

        <div class="stat-value">
            {{ $requestCounts['pending'] ?? 0 }}
        </div>

        <div class="stat-sub">
            Awaiting warehouse approval
        </div>

    </div>

    <!-- APPROVED -->
    <div class="stat-card" style="border-top:4px solid #10B981;">

        <div class="stat-label">
            Approved Requests
        </div>

        <div class="stat-value">
            {{ $requestCounts['approved'] ?? 0 }}
        </div>

        <div class="stat-sub">
            Currently being prepared
        </div>

    </div>

    <!-- DISPATCHED -->
    <div class="stat-card" style="border-top:4px solid #004B9B;">

        <div class="stat-label">
            Dispatched
        </div>

        <div class="stat-value">
            {{ $requestCounts['dispatched'] ?? 0 }}
        </div>

        <div class="stat-sub">
            Inventory en route
        </div>

    </div>

    <!-- LOW STOCK -->
    <div class="stat-card" style="border-top:4px solid #E30613;">

        <div class="stat-label">
            Low Stock Alerts
        </div>

        <div class="stat-value" style="color:#B91C1C;">
            {{ $lowStockProducts->count() }}
        </div>

        <div class="stat-sub">
            Warehouse-wide shortage alerts
        </div>

    </div>

</div>

<!-- RECENT REQUESTS -->
<div class="section-card" style="margin-bottom:28px;">

    <div class="section-header">

        <div class="section-title">
            My Recent Requests
        </div>

        <div style="
            font-size:.82rem;
            color:#6B7280;
        ">
            {{ $myRequests->count() }} Recent Request(s)
        </div>

    </div>

    <div style="overflow-x:auto;">

        <table class="ims-table">

            <thead>
                <tr>
                    <th>Request #</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($myRequests as $req)

                    <tr>

                        <td style="font-weight:600;">
                            #{{ $req->id }}
                        </td>

                        <td>
                            {{ $req->created_at->format('d M Y') }}
                        </td>

                        <td>
                            {{ $req->items->count() }} item(s)
                        </td>

                        <td>

                            <span class="badge
                                {{ match($req->status ?? '') {
                                    'approved'   => 'badge-green',
                                    'pending'    => 'badge-yellow',
                                    'dispatched' => 'badge-blue',
                                    'rejected'   => 'badge-red',
                                    default      => 'badge-gray'
                                } }}
                            ">
                                {{ ucfirst($req->status ?? 'Unknown') }}
                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4">

                            <div style="
                                text-align:center;
                                padding:40px 20px;
                                color:#9CA3AF;
                            ">

                                <div style="
                                    font-size:2rem;
                                    margin-bottom:10px;
                                ">
                                    📦
                                </div>

                                <div style="
                                    font-size:1rem;
                                    font-weight:600;
                                    color:#6B7280;
                                    margin-bottom:6px;
                                ">
                                    No Requests Yet
                                </div>

                                <div style="
                                    font-size:.88rem;
                                ">
                                    Your branch has not submitted any stock requests yet.
                                </div>

                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- LOW STOCK TABLE -->
<div class="section-card">

    <div class="section-header">

        <div class="section-title">
            Low Stock Products
        </div>

        <span class="badge badge-red">
            {{ $lowStockProducts->count() }} Alert(s)
        </span>

    </div>

    <div style="overflow-x:auto;">

        <table class="ims-table">

            <thead>
                <tr>
                    <th>Product</th>
                    <th>Warehouse Qty</th>
                    <th>Reorder Level</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($lowStockProducts as $product)

                    <tr>

                        <td style="font-weight:600;">
                            {{ $product->name }}
                        </td>

                        <td style="color:#B91C1C;font-weight:700;">
                            {{ $product->quantity_in_warehouse }}
                        </td>

                        <td>
                            {{ $product->reorder_level }}
                        </td>

                        <td>
                            <span class="badge badge-red">
                                Low Stock
                            </span>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4">

                            <div style="
                                text-align:center;
                                padding:36px 20px;
                                color:#9CA3AF;
                            ">
                                No low stock alerts currently.
                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection