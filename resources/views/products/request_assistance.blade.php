@extends('layouts.app')
@section('title', 'Product Assistance')
@section('content')

<div style="max-width:760px;margin:0 auto;padding:32px;">
    <div style="background:#F8FAFC;border:1px solid #CBD5E1;border-radius:16px;padding:32px;">
        <h2 style="font-size:1.75rem;font-weight:700;color:#0F172A;margin-bottom:16px;">Need help with adding a product?</h2>
        <p style="font-size:1rem;color:#334155;line-height:1.7;margin-bottom:24px;">
            Warehouse staff are able to view inventory, but creating or updating product master data is handled by procurement.
            Please contact the procurement department for assistance with adding new products.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:12px;">
            <a href="{{ route('products.index') }}" style="background:#004B9B;color:#fff;padding:12px 20px;border-radius:10px;text-decoration:none;font-weight:600;">Back to Products</a>
            <span style="display:inline-flex;align-items:center;padding:12px 20px;border:1px solid #CBD5E1;border-radius:10px;color:#334155;background:#F8FAFC;">Seek assistance from procurement</span>
        </div>
    </div>
</div>

@endsection