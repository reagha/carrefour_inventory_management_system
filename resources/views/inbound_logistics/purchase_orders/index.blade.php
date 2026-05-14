@extends('layouts.app')
@section('title', 'Purchase Orders')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
    <h2 style="font-size:1.5rem;font-weight:700;color:#111827;margin:0;">Purchase Orders</h2>
    <a href="{{ route('purchase-orders.create') }}" style="background:#004B9B;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:8px;transition:all .3s;">
        <span>+</span> Create Purchase Order
    </a>
</div>

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
                    <th>Status</th>
                    <th>Created By</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrders as $po)
                <tr>
                    <td style="font-family:monospace;color:#6B7280;font-weight:600;">PO-{{ str_pad($po->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>{{ $po->supplier->name }}</strong></td>
                    <td style="font-weight:700;color:#111827;">UGX {{ number_format($po->total_amount) }}</td>
                    <td>
                        @if($po->status === 'pending')
                            <span class="badge badge-yellow">Pending</span>
                        @elseif($po->status === 'approved')
                            <span class="badge badge-blue">Approved</span>
                        @else
                            <span class="badge badge-green">Received</span>
                        @endif
                    </td>
                    <td>{{ $po->procurementManager->name }}</td>
                    <td style="text-align:center;font-size:.9rem;">
                        @if($po->status === 'pending')
                            <form action="{{ route('purchase-orders.approve', $po) }}" method="POST" style="display:inline;" onsubmit="return confirm('Approve this purchase order?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="color:#059669;background:none;border:none;cursor:pointer;font-weight:600;text-decoration:none;">Approve</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div style="background:#F3F4F6;border:1px solid #E5E7EB;border-radius:8px;padding:32px;text-align:center;color:#6B7280;">
    <p style="font-size:1rem;margin-bottom:16px;">No purchase orders found.</p>
    <a href="{{ route('purchase-orders.create') }}" style="background:#004B9B;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block;">Create First Purchase Order</a>
</div>
@endif

@endsection
