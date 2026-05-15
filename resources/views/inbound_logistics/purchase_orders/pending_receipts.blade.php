@extends('layouts.app')
@section('title', 'Pending Receipts')
@section('content')

<h2 style="font-size:1.5rem;font-weight:700;color:#111827;margin-bottom:28px;">Approved Orders Awaiting Receipt</h2>

@if(session('success'))
<div style="background:#DCFCE7;border:1px solid #BBF7D0;border-radius:8px;padding:12px 16px;color:#15803D;margin-bottom:20px;font-weight:500;">
    ✓ {{ session('success') }}
</div>
@endif

@if($purchaseOrders->count())
<div class="section-card">
    <div style="overflow-x:auto;">
        <table class="ims-table">
            <thead>
                <tr>
                    <th>PO #</th>
                    <th>Supplier</th>
                    <th>Total Amount</th>
                    <th>Due Date</th>
                    <th>Payment</th>
                    <th>Approved By</th>
                    <th>Items</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrders as $po)
                <tr>
                    <td style="font-family:monospace;color:#6B7280;font-weight:600;">PO-{{ str_pad($po->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>{{ $po->supplier->name }}</strong></td>
                    <td style="font-weight:700;color:#111827;">UGX {{ number_format($po->total_amount) }}</td>
                    <td>{{ optional($po->due_date)->format('Y-m-d') }}</td>
                    <td>{{ $po->payment_terms === 'pre' ? 'Pre-payment' : 'Post-payment' }}</td>
                    <td>{{ $po->procurementManager->name }}</td>
                    <td style="font-size:.875rem;color:#6B7280;">{{ $po->items->count() }} item{{ $po->items->count() !== 1 ? 's' : '' }}</td>
                    <td style="text-align:center;">
                        <form action="{{ route('purchase-orders.receive', $po) }}" method="POST" style="display:inline;" onsubmit="return confirm('Confirm receipt of this purchase order?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="color:#059669;background:none;border:none;cursor:pointer;font-weight:600;text-decoration:none;padding:8px 12px;">Receive Stock</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div style="background:#F3F4F6;border:1px solid #E5E7EB;border-radius:8px;padding:32px;text-align:center;color:#6B7280;">
    <p style="font-size:1rem;margin:0;">No approved purchase orders are currently waiting for receipt.</p>
</div>
@endif

@endsection
