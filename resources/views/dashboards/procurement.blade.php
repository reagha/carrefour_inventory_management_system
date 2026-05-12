@extends('layouts.app')
@section('title', 'Procurement Dashboard')
@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:28px;">
    <div class="stat-card" style="border-top:4px solid #E30613;">
        <div class="stat-label">Total Pending Value</div>
        <div class="stat-value" style="font-size:1.4rem;color:#B91C1C;">UGX {{ number_format($totalPendingValue) }}</div>
        <div class="stat-sub">Across all pending POs</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #F59E0B;">
        <div class="stat-label">Pending</div>
        <div class="stat-value">{{ $posByStatus['pending'] ?? 0 }}</div>
        <div class="stat-sub">Awaiting approval</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #10B981;">
        <div class="stat-label">Approved</div>
        <div class="stat-value">{{ $posByStatus['approved'] ?? 0 }}</div>
        <div class="stat-sub">Ready to receive</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #6B7280;">
        <div class="stat-label">Received</div>
        <div class="stat-value">{{ $posByStatus['received'] ?? 0 }}</div>
        <div class="stat-sub">Completed orders</div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">Purchase Orders</div>
    </div>
    <div style="overflow-x:auto;">
        <table class="ims-table">
            <thead><tr><th>PO #</th><th>Supplier</th><th>Created</th><th>Items</th><th>Total Value</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($recentPOs as $po)
                <tr>
                    <td><strong>#{{ $po->id }}</strong></td>
                    <td>{{ $po->supplier->name ?? '—' }}</td>
                    <td>{{ $po->created_at->format('d M Y') }}</td>
                    <td>{{ $po->items->count() }}</td>
                    <td>UGX {{ number_format($po->items->sum(fn($i) => $i->quantity * $i->unit_price)) }}</td>
                    <td><span class="badge {{ match($po->status ?? '') { 'approved'=>'badge-green','pending'=>'badge-yellow','rejected'=>'badge-red','received'=>'badge-blue',default=>'badge-gray' } }}">{{ ucfirst($po->status ?? '') }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:#9CA3AF;padding:32px;">No purchase orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection