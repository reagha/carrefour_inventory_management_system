@extends('layouts.app')
@section('title', 'Edit Product')
@section('content')

<div style="max-width:700px;">
    <h2 style="font-size:1.5rem;font-weight:700;color:#111827;margin-bottom:28px;">Edit Product</h2>
    
    @if ($errors->any())
    <div style="background:#FEE2E2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;color:#B91C1C;margin-bottom:20px;">
        <ul style="margin:0;padding-left:20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="section-card">
        <form action="{{ route('products.update', $product->id) }}" method="POST" style="padding:24px;">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:8px;">Product SKU (Unique ID)</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="e.g., PROD-1001" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:8px;">Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g., Canned Tomatoes 400g" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:8px;">Supplier</label>
                <select name="supplier_id" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:8px;">Unit Cost (UGX)</label>
                <input type="number" name="unit_cost" value="{{ old('unit_cost', $product->unit_cost) }}" placeholder="e.g., 5000" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
                <p style="font-size:.75rem;color:#6B7280;margin-top:4px;">Enter amount as a whole number (no commas)</p>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:8px;">Reorder Level (Alert Threshold)</label>
                <input type="number" name="reorder_level" value="{{ old('reorder_level', $product->reorder_level) }}" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
            </div>
            
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:8px;">Stock Quantity</label>
                <input type="number" name="quantity_in_warehouse" value="{{ old('quantity_in_warehouse', $product->quantity_in_warehouse) }}" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;">
            </div>
            
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('products.index') }}" style="padding:10px 20px;border:1px solid #D1D5DB;border-radius:8px;color:#374151;text-decoration:none;font-weight:600;transition:all .3s;">Cancel</a>
                <button type="submit" style="padding:10px 24px;background:#004B9B;color:white;border:none;border-radius:8px;font-weight:700;cursor:pointer;transition:all .3s;">Update Product</button>
            </div>
        </form>
    </div>
</div>

@endsection