@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:28px;">

    <div class="stat-card">
        <div class="stat-icon" style="background:#EFF6FF;">
            <svg style="width:24px;height:24px;color:#1D4ED8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="stat-label">Total Products</div>
        <div class="stat-value">{{ number_format($totalProducts) }}</div>
        <div class="stat-sub">SKUs in system</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#F0FDF4;">
            <svg style="width:24px;height:24px;color:#15803D;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-label">Stock Value</div>
        <div class="stat-value" style="font-size:1.3rem;">UGX {{ number_format($totalStockValue) }}</div>
        <div class="stat-sub">Total warehouse value</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#FFF7ED;">
            <svg style="width:24px;height:24px;color:#C2410C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div class="stat-label">Pending POs</div>
        <div class="stat-value">{{ $pendingPOs }}</div>
        <div class="stat-sub">Awaiting approval</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#FDF4FF;">
            <svg style="width:24px;height:24px;color:#7E22CE;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div class="stat-label">Branch Requests</div>
        <div class="stat-value">{{ $pendingRequests }}</div>
        <div class="stat-sub">Pending dispatch</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#F0F9FF;">
            <svg style="width:24px;height:24px;color:#0369A1;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
        </div>
        <div class="stat-label">Branches</div>
        <div class="stat-value">{{ $totalBranches }}</div>
        <div class="stat-sub">Active locations</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#FEF9C3;">
            <svg style="width:24px;height:24px;color:#854D0E;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
        </div>
        <div class="stat-label">Suppliers</div>
        <div class="stat-value">{{ $totalSuppliers }}</div>
        <div class="stat-sub">Active vendors</div>
    </div>

</div>

@if($lowStockProducts->count())
<div class="section-card" style="margin-bottom:28px;border-left:4px solid #E30613;">
    <div class="section-header">
        <div class="section-title" style="color:#B91C1C;">⚠️ Low Stock Alerts ({{ $lowStockProducts->count() }} products)</div>
        <a href="{{ route('reports.valuation') }}" style="font-size:.8rem;color:#004B9B;">View full report →</a>
    </div>
    <div style="overflow-x:auto;">
        <table class="ims-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>In Warehouse</th>
                    <th>Reorder Level</th>
                    <th>Supplier</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $product)
                <tr>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td style="font-weight:700;color:#B91C1C;">{{ number_format($product->quantity_in_warehouse) }}</td>
                    <td>{{ number_format($product->reorder_level) }}</td>
                    <td>{{ $product->supplier->name ?? '—' }}</td>
                    <td>
                        @if($product->quantity_in_warehouse == 0)
                            <span class="badge badge-red">Out of Stock</span>
                        @else
                            <span class="badge badge-yellow">Low Stock</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">Recent Purchase Orders</div>
        </div>
        <table class="ims-table">
            <thead><tr><th>PO #</th><th>Supplier</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($recentPOs as $po)
                <tr>
                    <td>#{{ $po->id }}</td>
                    <td>{{ $po->supplier->name ?? '—' }}</td>
                    <td><span class="badge {{ match($po->status ?? '') { 'approved'=>'badge-green','pending'=>'badge-yellow','rejected'=>'badge-red',default=>'badge-gray' } }}">{{ ucfirst($po->status ?? 'N/A') }}</span></td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:#9CA3AF;padding:24px;">No purchase orders yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section-card">
        <div class="section-header">
            <div class="section-title">Recent Branch Requests</div>
        </div>
        <table class="ims-table">
            <thead><tr><th>Branch</th><th>Date</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($recentRequests as $req)
                <tr>
                    <td>{{ $req->branch->name ?? '—' }}</td>
                    <td>{{ $req->created_at->format('d M Y') }}</td>
                    <td><span class="badge {{ match($req->status ?? '') { 'approved'=>'badge-green','pending'=>'badge-yellow','rejected'=>'badge-red','dispatched'=>'badge-blue',default=>'badge-gray' } }}">{{ ucfirst($req->status ?? 'N/A') }}</span></td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:#9CA3AF;padding:24px;">No branch requests yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection