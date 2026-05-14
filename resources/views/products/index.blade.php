@extends('layouts.app')
@section('title', 'Products')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
    <h2 style="font-size:1.5rem;font-weight:700;color:#111827;margin:0;">Central Warehouse Inventory</h2>
    <a href="{{ route('products.create') }}" style="background:#004B9B;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:8px;transition:all .3s;">
        <span>+</span> Add Product
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

@if($products->count())
<div class="section-card">
    <div style="overflow-x:auto;">
        <table class="ims-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th>Supplier</th>
                    <th>Unit Cost</th>
                    <th>In Warehouse</th>
                    <th>Reorder Level</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td style="font-family:monospace;color:#6B7280;">{{ $product->sku }}</td>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td>{{ $product->supplier->name ?? '—' }}</td>
                    <td>UGX {{ number_format($product->unit_cost) }}</td>
                    <td @style="{ fontWeight: $product->quantity_in_warehouse <= $product->reorder_level ? '700' : '400', color: $product->quantity_in_warehouse <= $product->reorder_level ? '#B91C1C' : '#111827' }">{{ number_format($product->quantity_in_warehouse) }}</td>
                    <td>{{ number_format($product->reorder_level) }}</td>
                    <td>
                        @if($product->quantity_in_warehouse == 0)
                            <span class="badge badge-red">Out of Stock</span>
                        @elseif($product->quantity_in_warehouse <= $product->reorder_level)
                            <span class="badge badge-yellow">Low Stock</span>
                        @else
                            <span class="badge badge-green">In Stock</span>
                        @endif
                    </td>
                    <td style="text-align:center;font-size:.9rem;">
                        <a href="{{ route('products.edit', $product) }}" style="color:#004B9B;text-decoration:none;font-weight:600;margin-right:12px;">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
    <p style="font-size:1rem;margin-bottom:16px;">No products found in the warehouse.</p>
    <a href="{{ route('products.create') }}" style="background:#004B9B;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block;">Add First Product</a>
</div>
@endif

@endsection