@extends('layouts.app')
@section('title', 'Create Purchase Order')
@section('content')

<div style="max-width:900px;">
    <h2 style="font-size:1.5rem;font-weight:700;color:#111827;margin-bottom:28px;">Create Purchase Order</h2>
    
    @if($errors->any())
    <div style="background:#FEE2E2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;color:#B91C1C;margin-bottom:20px;">
        <ul style="margin:0;padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="section-card">
        <form action="{{ route('purchase-orders.store') }}" method="POST" style="padding:24px;">
            @csrf
            
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:8px;">Supplier</label>
                <select name="supplier_id" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-bottom:24px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                    <h3 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Order Items</h3>
                    <button type="button" id="add-item" style="background:#004B9B;color:#fff;padding:8px 16px;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.875rem;">+ Add Item</button>
                </div>
                
                <div id="items-container" style="display:flex;flex-direction:column;gap:16px;">
                    <div class="item-row" style="display:grid;gap:12px;grid-template-columns:1fr 150px 140px;align-items:end;">
                        <div>
                            <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:6px;">Product</label>
                            <select name="items[0][product_id]" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
                                <option value="">-- Select Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->sku }} – {{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:6px;">Quantity</label>
                            <input type="number" name="items[0][quantity]" min="1" value="1" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
                        </div>
                        <button type="button" class="remove-item" style="background:#E5E7EB;color:#6B7280;padding:10px 12px;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.875rem;" disabled>Remove</button>
                    </div>
                </div>
            </div>
            
            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:32px;">
                <a href="{{ route('purchase-orders.index') }}" style="padding:10px 20px;border:1px solid #D1D5DB;border-radius:8px;color:#374151;text-decoration:none;font-weight:600;transition:all .3s;">Cancel</a>
                <button type="submit" style="padding:10px 24px;background:#059669;color:white;border:none;border-radius:8px;font-weight:700;cursor:pointer;transition:all .3s;">Create Purchase Order</button>
            </div>
        </form>
    </div>
</div>

<template id="item-row-template">
    <div class="item-row" style="display:grid;gap:12px;grid-template-columns:1fr 150px 140px;align-items:end;">
        <div>
            <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:6px;">Product</label>
            <select name="product_id" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->sku }} – {{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:6px;">Quantity</label>
            <input type="number" name="quantity" min="1" value="1" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
        </div>
        <button type="button" class="remove-item" style="background:#FEE2E2;color:#B91C1C;padding:10px 12px;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.875rem;">Remove</button>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('items-container');
        const template = document.getElementById('item-row-template');
        const addButton = document.getElementById('add-item');

        function updateIndexes() {
            container.querySelectorAll('.item-row').forEach((row, index) => {
                const select = row.querySelector('select');
                const inputs = row.querySelectorAll('input, select');
                inputs.forEach(input => {
                    if (input.name.startsWith('items')) {
                        input.name = `items[${index}][${input.name.includes('product') ? 'product_id' : 'quantity'}]`;
                    }
                });
                
                const removeBtn = row.querySelector('.remove-item');
                if (container.querySelectorAll('.item-row').length > 1) {
                    removeBtn.disabled = false;
                    removeBtn.style.background = '#FEE2E2';
                    removeBtn.style.color = '#B91C1C';
                    removeBtn.style.cursor = 'pointer';
                } else {
                    removeBtn.disabled = true;
                    removeBtn.style.background = '#E5E7EB';
                    removeBtn.style.color = '#6B7280';
                    removeBtn.style.cursor = 'not-allowed';
                }
            });
        }

        addButton.addEventListener('click', function () {
            const clone = template.content.firstElementChild.cloneNode(true);
            container.appendChild(clone);
            updateIndexes();
        });

        container.addEventListener('click', function (event) {
            if (event.target.closest('.remove-item') && !event.target.disabled) {
                const row = event.target.closest('.item-row');
                row.remove();
                updateIndexes();
            }
        });
        
        updateIndexes();
    });
</script>

@endsection
