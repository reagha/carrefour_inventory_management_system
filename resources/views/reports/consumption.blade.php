@extends('layouts.app')
@section('title', 'Branch Consumption Analytics')
@section('content')

<form method="GET" style="display:flex;gap:12px;align-items:flex-end;margin-bottom:24px;flex-wrap:wrap;">
    <div>
        <label style="display:block;font-size:.75rem;font-weight:600;color:#6B7280;margin-bottom:4px;">Start Date</label>
        <input type="date" name="start_date" value="{{ $startDate }}" style="padding:8px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.875rem;">
    </div>
    <div>
        <label style="display:block;font-size:.75rem;font-weight:600;color:#6B7280;margin-bottom:4px;">End Date</label>
        <input type="date" name="end_date" value="{{ $endDate }}" style="padding:8px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.875rem;">
    </div>
    <button type="submit" style="background:#004B9B;color:#fff;padding:8px 20px;border-radius:8px;border:none;font-size:.875rem;cursor:pointer;">Filter</button>
    <a href="{{ route('reports.consumption') }}" style="padding:8px 16px;border:1px solid #D1D5DB;border-radius:8px;font-size:.875rem;color:#374151;text-decoration:none;">Reset</a>
</form>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

    <div class="section-card">
        <div class="section-header"><div class="section-title">Branch Activity</div></div>
        <table class="ims-table">
            <thead><tr><th>Branch</th><th>Total Requests</th><th>Approved</th><th>Pending</th></tr></thead>
            <tbody>
                @forelse($branchActivity as $branch)
                <tr>
                    <td><strong>{{ $branch->name }}</strong><br><span style="font-size:.75rem;color:#9CA3AF;">{{ $branch->location ?? '' }}</span></td>
                    <td>{{ $branch->total_requests }}</td>
                    <td><span class="badge badge-green">{{ $branch->approved_requests }}</span></td>
                    <td><span class="badge badge-yellow">{{ $branch->pending_requests }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:#9CA3AF;padding:24px;">No branch activity recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section-card">
        <div class="section-header"><div class="section-title">Top Requested Products</div></div>
        <table class="ims-table">
            <thead><tr><th>Product</th><th>Total Qty Requested</th></tr></thead>
            <tbody>
                @forelse($topProducts as $item)
                <tr>
                    <td>{{ $item->product->name ?? '—' }}</td>
                    <td><strong>{{ number_format($item->total_qty) }}</strong></td>
                </tr>
                @empty
                <tr><td colspan="2" style="text-align:center;color:#9CA3AF;padding:24px;">No data yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection