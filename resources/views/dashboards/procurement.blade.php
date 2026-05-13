@extends('layouts.app')

@section('title', 'Procurement Dashboard')

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
            Procurement Overview
        </h2>

        <p style="
            color:#6B7280;
            font-size:.92rem;
        ">
            Monitor supplier purchasing activity and purchase order performance.
        </p>
    </div>

    <!-- QUICK ACTION -->
    <a href="{{ route('purchase-orders.create') }}"
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
        + Create Purchase Order
    </a>

</div>

<!-- STATISTICS -->
<div class="dashboard-grid" style="margin-bottom:28px;">

    <!-- TOTAL PENDING VALUE -->
    <div class="stat-card" style="border-top:4px solid #E30613;">

        <div class="stat-label">
            Total Pending Value
        </div>

        <div class="stat-value"
             style="
                font-size:1.5rem;
                color:#B91C1C;
             ">
            UGX {{ number_format($totalPendingValue) }}
        </div>

        <div class="stat-sub">
            Monetary value awaiting fulfillment
        </div>

    </div>

    <!-- PENDING -->
    <div class="stat-card" style="border-top:4px solid #F59E0B;">

        <div class="stat-label">
            Pending Orders
        </div>

        <div class="stat-value">
            {{ $posByStatus['pending'] ?? 0 }}
        </div>

        <div class="stat-sub">
            Awaiting management approval
        </div>

    </div>

    <!-- APPROVED -->
    <div class="stat-card" style="border-top:4px solid #10B981;">

        <div class="stat-label">
            Approved Orders
        </div>

        <div class="stat-value">
            {{ $posByStatus['approved'] ?? 0 }}
        </div>

        <div class="stat-sub">
            Ready for supplier processing
        </div>

    </div>

    <!-- RECEIVED -->
    <div class="stat-card" style="border-top:4px solid #004B9B;">

        <div class="stat-label">
            Received Orders
        </div>

        <div class="stat-value">
            {{ $posByStatus['received'] ?? 0 }}
        </div>

        <div class="stat-sub">
            Successfully completed purchases
        </div>

    </div>

</div>

<!-- RECENT PURCHASE ORDERS -->
<div class="section-card" style="margin-bottom:28px;">

    <div class="section-header">

        <div class="section-title">
            Recent Purchase Orders
        </div>

        <div style="
            font-size:.82rem;
            color:#6B7280;
        ">
            {{ $recentPOs->count() }} Recent PO(s)
        </div>

    </div>

    <div style="overflow-x:auto;">

        <table class="ims-table">

            <thead>
                <tr>
                    <th>PO #</th>
                    <th>Supplier</th>
                    <th>Created</th>
                    <th>Items</th>
                    <th>Total Value</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($recentPOs as $po)

                    <tr>

                        <!-- PO ID -->
                        <td style="font-weight:700;">
                            #{{ $po->id }}
                        </td>

                        <!-- SUPPLIER -->
                        <td>
                            {{ $po->supplier->name ?? 'N/A' }}
                        </td>

                        <!-- DATE -->
                        <td>
                            {{ $po->created_at->format('d M Y') }}
                        </td>

                        <!-- ITEM COUNT -->
                        <td>
                            {{ $po->items->count() }} item(s)
                        </td>

                        <!-- VALUE -->
                        <td style="font-weight:600;">

                            UGX
                            {{
                                number_format(
                                    $po->items->sum(
                                        fn($i) => $i->quantity * $i->unit_price
                                    )
                                )
                            }}

                        </td>

                        <!-- STATUS -->
                        <td>

                            <span class="badge
                                {{ match($po->status ?? '') {
                                    'approved' => 'badge-green',
                                    'pending'  => 'badge-yellow',
                                    'rejected' => 'badge-red',
                                    'received' => 'badge-blue',
                                    default    => 'badge-gray'
                                } }}
                            ">

                                {{ ucfirst($po->status ?? 'Unknown') }}

                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6">

                            <div style="
                                text-align:center;
                                padding:40px 20px;
                                color:#9CA3AF;
                            ">

                                <div style="
                                    font-size:2rem;
                                    margin-bottom:10px;
                                ">
                                    📄
                                </div>

                                <div style="
                                    font-size:1rem;
                                    font-weight:600;
                                    color:#6B7280;
                                    margin-bottom:6px;
                                ">
                                    No Purchase Orders Found
                                </div>

                                <div style="
                                    font-size:.88rem;
                                ">
                                    No procurement records have been created yet.
                                </div>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- LOW STOCK ALERTS -->
<div class="section-card">

    <div class="section-header">

        <div class="section-title">
            Low Stock Alerts
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
                    <th>Recommended Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($lowStockProducts as $product)

                    <tr>

                        <td style="font-weight:600;">
                            {{ $product->name }}
                        </td>

                        <td style="
                            color:#B91C1C;
                            font-weight:700;
                        ">
                            {{ $product->quantity_in_warehouse }}
                        </td>

                        <td>
                            {{ $product->reorder_level }}
                        </td>

                        <td>

                            <span class="badge badge-yellow">
                                Reorder Required
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