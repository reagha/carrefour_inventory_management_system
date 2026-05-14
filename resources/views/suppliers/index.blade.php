@extends('layouts.app')
@section('title', 'Suppliers')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
    <h2 style="font-size:1.5rem;font-weight:700;color:#111827;margin:0;">Supplier Directory</h2>
    <a href="{{ route('suppliers.create') }}" style="background:#004B9B;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:8px;transition:all .3s;">
        <span>+</span> Add Supplier
    </a>
</div>

@if(session('success'))
<div style="background:#DCFCE7;border:1px solid #BBF7D0;border-radius:8px;padding:12px 16px;color:#15803D;margin-bottom:20px;font-weight:500;">
    ✓ {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background:#FEE2E2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;color:#B91C1C;margin-bottom:20px;font-weight:500;">
    ✕ {{ session('error') }}
</div>
@endif

@if($suppliers->count())
<div class="section-card">
    <div style="overflow-x:auto;">
        <table class="ims-table">
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Products Linked</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                <tr>
                    <td><strong>{{ $supplier->name }}</strong></td>
                    <td style="font-size:.85rem;">{{ $supplier->contact_email }}</td>
                    <td style="font-size:.85rem;">{{ $supplier->contact_phone }}</td>
                    <td>
                        <span class="badge badge-blue">{{ $supplier->products_count ?? 0 }} item{{ $supplier->products_count != 1 ? 's' : '' }}</span>
                    </td>
                    <td style="text-align:center;font-size:.9rem;">
                        <a href="{{ route('suppliers.edit', $supplier) }}" style="color:#004B9B;text-decoration:none;font-weight:600;margin-right:12px;">Edit</a>
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color:#E30613;background:none;border:none;cursor:pointer;font-weight:600;text-decoration:none;">Delete</button>
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
    <p style="font-size:1rem;margin-bottom:16px;">No suppliers found.</p>
    <a href="{{ route('suppliers.create') }}" style="background:#004B9B;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block;">Create First Supplier</a>
</div>
@endif

@endsection