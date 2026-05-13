@extends('layouts.app')
@section('title', 'Stock Requests')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
    <h2 style="font-size:1.5rem;font-weight:700;color:#111827;margin:0;">Stock Requests</h2>
    @if(auth()->user()->role === 'branchManager')
        <a href="{{ route('branch-requests.create') }}" style="background:#004B9B;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:8px;transition:all .3s;">
            <span>+</span> New Request
        </a>
    @endif
</div>

@if(session('success'))
<div style="background:#DCFCE7;border:1px solid #BBF7D0;border-radius:8px;padding:12px 16px;color:#15803D;margin-bottom:20px;font-weight:500;">
    ✓ {{ session('success') }}
</div>
@endif

@if($branchRequests->count())
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid #F3F4F6;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#F9FAFB;border-bottom:1px solid #E5E7EB;">
                    <th style="padding:10px 16px;text-align:left;font-size:.75rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:.05em;">Request ID</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.75rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:.05em;">Branch</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.75rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:.05em;">Requested By</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.75rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:.05em;">Items</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.75rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                    <th style="padding:10px 16px;text-align:left;font-size:.75rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:.05em;">Date</th>
                    <th style="padding:10px 16px;text-align:center;font-size:.75rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($branchRequests as $request)
                <tr style="border-bottom:1px solid #F3F4F6;transition:background .15s;">
                    <td style="padding:12px 16px;font-size:.875rem;color:#374151;font-family:monospace;color:#6B7280;font-weight:600;">REQ-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td style="padding:12px 16px;font-size:.875rem;color:#374151;"><strong>{{ $request->branch->name }}</strong></td>
                    <td style="padding:12px 16px;font-size:.875rem;color:#374151;">{{ $request->branchManager->name }}</td>
                    <td style="padding:12px 16px;font-size:.875rem;color:#6B7280;">{{ $request->items->count() }} item{{ $request->items->count() !== 1 ? 's' : '' }}</td>
                    <td style="padding:12px 16px;font-size:.875rem;color:#374151;">
                        @if($request->status === 'pending')
                            <span style="display:inline-block;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:999px;background:#FEF9C3;color:#854D0E;">Pending</span>
                        @elseif($request->status === 'dispatched')
                            <span style="display:inline-block;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:999px;background:#DCFCE7;color:#15803D;">Dispatched</span>
                        @else
                            <span style="display:inline-block;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:999px;background:#DBEAFE;color:#1D4ED8;">{{ ucfirst($request->status) }}</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px;font-size:.875rem;color:#6B7280;">{{ $request->created_at->format('M d, Y') }}</td>
                    <td style="padding:12px 16px;font-size:.875rem;color:#374151;text-align:center;">
                        @if(auth()->user()->role === 'warehouse' && $request->status === 'pending')
                            <form method="POST" action="{{ route('branch-requests.dispatch', $request) }}" style="display:inline;" onsubmit="return confirm('Dispatch this stock request?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="color:#059669;background:none;border:none;cursor:pointer;font-weight:600;text-decoration:none;padding:8px 12px;">Dispatch</button>
                            </form>
                        @elseif($request->status === 'pending')
                            <span style="font-size:.75rem;color:#6B7280;">Awaiting</span>
                        @else
                            <span style="font-size:.75rem;color:#6B7280;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($branchRequests->hasPages())
<div style="margin-top:20px;">{{ $branchRequests->links() }}</div>
@endif

@else
<div style="background:#F3F4F6;border:1px solid #E5E7EB;border-radius:8px;padding:32px;text-align:center;color:#6B7280;">
    <p style="font-size:1rem;margin-bottom:16px;">No stock requests found.</p>
    @if(auth()->user()->role === 'branchManager')
        <a href="{{ route('branch-requests.create') }}" style="background:#004B9B;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block;">Create First Request</a>
    @endif
</div>
@endif

@endsection
