
@extends('layouts.app')
@section('title', 'Warehouse Dashboard')
@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:28px;">
    <div class="stat-card" style="border-top:4px solid #004B9B;">
        <div class="stat-label">Trucks to Receive</div>
        <div class="stat-value" style="color:#004B9B;">{{ $trucksToReceive }}</div>
        <div class="stat-sub">Approved POs pending receipt</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #D97706;">
        <div class="stat-label">Overdue Canceled POs</div>
        <div class="stat-value" style="color:#D97706;">{{ $overdueCanceledOrders }}</div>
        <div class="stat-sub">Orders canceled after due date</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #E30613;">
        <div class="stat-label">Trucks to Dispatch</div>
        <div class="stat-value" style="color:#B91C1C;">{{ $trucksToDispatch }}</div>
        <div class="stat-sub">Approved branch requests</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #10B981;">
        <div class="stat-label">Total Products</div>
        <div class="stat-value">{{ $totalProducts }}</div>
        <div class="stat-sub">SKUs managed</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #F59E0B;">
        <div class="stat-label">Total Stock Value</div>
        <div class="stat-value" style="font-size:1.2rem;">UGX {{ number_format($totalStockValue) }}</div>
        <div class="stat-sub">Warehouse valuation</div>
    </div>
</div>

<div class="section-card" style="margin-bottom:28px;">
    <div class="section-header">
        <div class="section-title">Warehouse Purchase Order Summary</div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:center;padding:16px 0 0;">
        <div>
            <p style="margin:0;font-weight:600;color:#111827;">Approved orders awaiting receipt</p>
            <p style="margin:.5rem 0 0;color:#4B5563;">You have <strong>{{ $trucksToReceive }}</strong> approved purchase orders waiting to be received.</p>
        </div>
        <div style="text-align:right;">
            <a href="{{ route('purchase-orders.pending-receipts') }}" style="background:#004B9B;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600;">View Pending Receipts</a>
        </div>
    </div>
</div>

@if($lowStockProducts->count())
<div class="section-card" style="margin-bottom:28px;border-left:4px solid #E30613;">
    <div class="section-header">
        <div class="section-title" style="color:#B91C1C;">⚠️ Low Stock — {{ $lowStockProducts->count() }} products need reordering</div>
    </div>
    <div style="overflow-x:auto;">
        <table class="ims-table">
            <thead><tr><th>Product</th><th>In Warehouse</th><th>Reorder Level</th><th>Supplier</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($lowStockProducts as $p)
                <tr>
                    <td><strong>{{ $p->name }}</strong></td>
                    <td style="color:#B91C1C;font-weight:700;">{{ number_format($p->quantity_in_warehouse) }}</td>
                    <td>{{ number_format($p->reorder_level) }}</td>
                    <td>{{ $p->supplier->name ?? '—' }}</td>
                    <td><span class="badge {{ $p->quantity_in_warehouse == 0 ? 'badge-red' : 'badge-yellow' }}">{{ $p->quantity_in_warehouse == 0 ? 'Out of Stock' : 'Low Stock' }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:12px 16px;color:#15803D;margin-bottom:28px;">
    ✅ All products are above reorder levels.
</div>
@endif

<div class="section-card">
    <div class="section-header"><div class="section-title">Recent Branch Requests</div></div>
    <table class="ims-table">
        <thead><tr><th>Branch</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($recentActivity as $req)
            <tr>
                <td>{{ $req->branch->name ?? '—' }}</td>
                <td>{{ $req->created_at->format('d M Y') }}</td>
                <td><span class="badge {{ match($req->status ?? '') { 'approved'=>'badge-green','pending'=>'badge-yellow','dispatched'=>'badge-blue','rejected'=>'badge-red',default=>'badge-gray' } }}">{{ ucfirst($req->status ?? '') }}</span></td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;color:#9CA3AF;padding:24px;">No recent activity</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection