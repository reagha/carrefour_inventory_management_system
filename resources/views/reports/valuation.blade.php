{{-- ============================================================
     FILE 1: resources/views/reports/valuation.blade.php
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Inventory Valuation Report')
@section('content')

{{-- Date filter --}}
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
    <a href="{{ route('reports.valuation') }}" style="padding:8px 16px;border:1px solid #D1D5DB;border-radius:8px;font-size:.875rem;color:#374151;text-decoration:none;">Reset</a>
</form>

{{-- Summary cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:28px;">
    <div class="stat-card" style="border-top:4px solid #004B9B;">
        <div class="stat-label">Total Stock Value</div>
        <div class="stat-value" style="font-size:1.3rem;">UGX {{ number_format($grandTotal) }}</div>
        <div class="stat-sub">All warehouse stock</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #10B981;">
        <div class="stat-label">Total Products</div>
        <div class="stat-value">{{ $products->count() }}</div>
        <div class="stat-sub">SKUs tracked</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #F59E0B;">
        <div class="stat-label">Low Stock</div>
        <div class="stat-value" style="color:#B45309;">{{ $lowStockCount }}</div>
        <div class="stat-sub">Below reorder level</div>
    </div>
    <div class="stat-card" style="border-top:4px solid #E30613;">
        <div class="stat-label">Out of Stock</div>
        <div class="stat-value" style="color:#B91C1C;">{{ $outOfStockCount }}</div>
        <div class="stat-sub">Zero quantity</div>
    </div>
</div>

<div class="section-card">
    <div class="section-header"><div class="section-title">Product Valuation Breakdown</div></div>
    <div style="overflow-x:auto;">
        <table class="ims-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Supplier</th>
                    <th>Qty in Warehouse</th>
                    <th>Unit Cost (UGX)</th>
                    <th>Total Value (UGX)</th>
                    <th>Reorder Level</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td><strong>{{ $p->name }}</strong></td>
                    <td>{{ $p->supplier->name ?? '—' }}</td>
                    <td>{{ number_format($p->quantity_in_warehouse) }}</td>
                    <td>{{ number_format($p->unit_cost) }}</td>
                    <td><strong>{{ number_format($p->total_value) }}</strong></td>
                    <td>{{ number_format($p->reorder_level) }}</td>
                    <td>
                        @if($p->quantity_in_warehouse == 0)
                            <span class="badge badge-red">Out of Stock</span>
                        @elseif($p->quantity_in_warehouse <= $p->reorder_level)
                            <span class="badge badge-yellow">Low Stock</span>
                        @else
                            <span class="badge badge-green">OK</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#F9FAFB;">
                    <td colspan="4" style="padding:12px 16px;font-weight:700;text-align:right;">Grand Total</td>
                    <td style="padding:12px 16px;font-weight:800;color:#004B9B;">UGX {{ number_format($grandTotal) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection