@extends('layouts.app')
@section('title', 'Supplier Expenditure Report')
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
    <a href="{{ route('reports.supplier-expenditure') }}" style="padding:8px 16px;border:1px solid #D1D5DB;border-radius:8px;font-size:.875rem;color:#374151;text-decoration:none;">Reset</a>
</form>

<div class="stat-card" style="margin-bottom:24px;border-top:4px solid #004B9B;">
    <div class="stat-label">Total Committed to Suppliers</div>
    <div class="stat-value" style="font-size:1.5rem;">UGX {{ number_format($grandTotal) }}</div>
    <div class="stat-sub">{{ $startDate }} → {{ $endDate }}</div>
</div>

<div class="section-card">
    <div class="section-header"><div class="section-title">Supplier Breakdown</div></div>
    <div style="overflow-x:auto;">
        <table class="ims-table">
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Contact</th>
                    <th>Total Orders</th>
                    <th>Approved</th>
                    <th>Pending</th>
                    <th>Total Committed (UGX)</th>
                    <th>Share</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                <tr>
                    <td><strong>{{ $supplier->name }}</strong></td>
                    <td style="font-size:.8rem;color:#6B7280;">{{ $supplier->email ?? $supplier->phone ?? '—' }}</td>
                    <td>{{ $supplier->total_orders }}</td>
                    <td><span class="badge badge-green">{{ $supplier->approved_orders }}</span></td>
                    <td><span class="badge badge-yellow">{{ $supplier->pending_orders }}</span></td>
                    <td><strong>{{ number_format($supplier->total_committed) }}</strong></td>
                    <td>
                        @if($grandTotal > 0)
                            {{ number_format(($supplier->total_committed / $grandTotal) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#F9FAFB;">
                    <td colspan="5" style="padding:12px 16px;font-weight:700;text-align:right;">Grand Total</td>
                    <td style="padding:12px 16px;font-weight:800;color:#004B9B;">UGX {{ number_format($grandTotal) }}</td>
                    <td style="padding:12px 16px;font-weight:700;">100%</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
