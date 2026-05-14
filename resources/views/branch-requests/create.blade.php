@extends('layouts.app')
@section('title', 'Create Stock Request')
@section('content')

<div style="max-width:900px;">
    <h2 style="font-size:1.5rem;font-weight:700;color:#111827;margin-bottom:28px;">Create Stock Request</h2>
    
    @if($errors->any())
    <div style="background:#FEE2E2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;color:#B91C1C;margin-bottom:20px;">
        <strong style="display:block;margin-bottom:8px;">Please fix the following issues:</strong>
        <ul style="margin:0;padding-left:20px;font-size:.9rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid #F3F4F6;overflow:hidden;">
        <form method="POST" action="{{ route('branch-requests.store') }}" x-data="orderForm()" style="padding:24px;">
            @csrf
            
            <h3 style="font-size:1.125rem;font-weight:700;color:#111827;margin-bottom:20px;">Request Items</h3>
            
            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:24px;">
                <template x-for="(item, index) in items" :key="index">
                    <div style="border:1px solid #D1D5DB;border-radius:8px;padding:16px;background:#F9FAFB;">
                        <div style="display:grid;gap:12px;grid-template-columns:1fr 140px 120px;align-items:end;">
                            <div>
                                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:6px;">Product</label>
                                <select x-bind:name="`items[${index}][product_id]`" required style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;font-family:inherit;">
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->sku }} – {{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:6px;">Quantity</label>
                                <input type="number" min="1" x-bind:name="`items[${index}][quantity]`" value="1" required style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;font-family:inherit;">
                            </div>
                            
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1" style="background:#FEE2E2;color:#B91C1C;padding:10px 12px;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.875rem;font-family:inherit;">Remove</button>
                        </div>
                    </div>
                </template>
            </div>
            
            <button type="button" @click="addItem()" style="color:#004B9B;background:none;border:none;cursor:pointer;font-weight:600;font-size:.875rem;padding:0;text-decoration:underline;margin-bottom:24px;">+ Add Another Item</button>
            
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('branch-requests.index') }}" style="padding:10px 20px;border:1px solid #D1D5DB;border-radius:8px;color:#374151;text-decoration:none;font-weight:600;transition:all .3s;font-family:inherit;">Cancel</a>
                <button type="submit" style="padding:10px 24px;background:#004B9B;color:white;border:none;border-radius:8px;font-weight:700;cursor:pointer;transition:all .3s;font-family:inherit;">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderForm', () => ({
            items: [{ product_id: '', quantity: 1 }],

            addItem() {
                this.items.push({ product_id: '', quantity: 1 });
            },

            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            }
        }))
    })
</script>

@endsection
