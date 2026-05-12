@extends('layouts.app')
@section('title', 'Branch Dashboard')
@section('content')

<div style="margin-bottom:20px;">
    <h2 style="font-size:1.3rem;font-weight:700;color:#111827;">{{ $branch->name ?? 'My Branch' }}</h2>
    <p style="color:#6B7280;font-size:.875rem;">{{ $branch->location ?? '' }}</p>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px;margin-bottom:28px;">
    <div class="stat-card" style="border-top:4px solid #F59E0B;">
        <div class="stat-label">Pending Requests</div>
        <div class="stat-value">{{ $requestCounts['pending'] ?? 0 }}</div>
        <div class="stat-sub">Awaiting warehouse</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #10B981;">
        <div class="stat-label">Approved</div>
        <div class="stat-value">{{ $requestCounts['approved'] ?? 0 }}</div>
        <div class="stat-sub">Being prepared</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #004B9B;">
        <div class="stat-label">Dispatched</div>
        <div class="stat-value">{{ $requestCounts['dispatched'] ?? 0 }}</div>
        <div class="stat-sub">En route</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #E30613;">
        <div class="stat-label">Low Stock Items</div>
        <div class="stat-value" style="color:#B91C1C;">{{ $lowStockProducts->count() }}</div>
        <div class="stat-sub">Warehouse-wide alert</div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">My Recent Requests</div>
    </div>
    <table class="ims-table">
        <thead><tr><th>Request #</th><th>Date</th><th>Items</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($myRequests as $req)
            <tr>
                <td>#{{ $req->id }}</td>
                <td>{{ $req->created_at->format('d M Y') }}</td>
                <td>{{ $req->items->count() }} item(s)</td>
                <td><span class="badge {{ match($req->status ?? '') { 'approved'=>'badge-green','pending'=>'badge-yellow','dispatched'=>'badge-blue','rejected'=>'badge-red',default=>'badge-gray' } }}">{{ ucfirst($req->status ?? '') }}</span></td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#9CA3AF;padding:24px;">No requests submitted yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection