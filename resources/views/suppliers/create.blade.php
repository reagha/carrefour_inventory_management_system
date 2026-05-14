@extends('layouts.app')
@section('title', 'Add Supplier')
@section('content')

<div style="max-width:600px;">
    <h2 style="font-size:1.5rem;font-weight:700;color:#111827;margin-bottom:28px;">Register New Supplier</h2>
    
    <div class="section-card">
        <form action="{{ route('suppliers.store') }}" method="POST" style="padding:24px;">
            @csrf
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:8px;">Supplier Company Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., Global Traders Ltd" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
                @error('name') <p style="color:#DC2626;font-size:.8rem;margin-top:4px;">{{ $message }}</p> @enderror
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:8px;">Contact Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email') }}" placeholder="supplier@example.com" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
                @error('contact_email') <p style="color:#DC2626;font-size:.8rem;margin-top:4px;">{{ $message }}</p> @enderror
            </div>
            
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:8px;">Contact Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="e.g., +256 414 123456" style="width:100%;padding:10px 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:.9rem;" required>
                @error('contact_phone') <p style="color:#DC2626;font-size:.8rem;margin-top:4px;">{{ $message }}</p> @enderror
            </div>
            
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('suppliers.index') }}" style="padding:10px 20px;border:1px solid #D1D5DB;border-radius:8px;color:#374151;text-decoration:none;font-weight:600;transition:all .3s;">Cancel</a>
                <button type="submit" style="padding:10px 24px;background:#004B9B;color:white;border:none;border-radius:8px;font-weight:700;cursor:pointer;transition:all .3s;">Save Supplier</button>
            </div>
        </form>
    </div>
</div>

@endsection