@extends('layouts.admin')

@section('title', 'Payment Methods - Admin')
@section('topTitle', 'Payment Methods')

@section('content')
<div class="wrap">
    <div class="whitePanel">
        <div class="panelHead">
            <div>
                <h5>Payment Methods</h5>
                <small>Manage all deposit and withdrawal methods shown to users</small>
            </div>
            <a href="{{ route('admin.payment-methods.create') }}" style="padding: 8px 16px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add Method
            </a>
        </div>

        @if(session('success'))
            <div style="margin: 12px 14px; padding: 12px; background: #d1fae5; border: 1px solid #10b981; border-radius: 8px; color: #065f46; font-size: 12px; font-weight: 700;">
                {{ session('success') }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid rgba(0,0,0,.06);">
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Order</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Name</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Slug</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Type</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Category</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($methods as $method)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 12px 14px; font-size: 12px; color: #6b7280;">{{ $method->display_order }}</td>
                            <td style="padding: 12px 14px;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    @if($method->logo_url)
                                        <span style="width:26px; height:26px; border-radius:999px; background:#f3f4f6; display:grid; place-items:center;">
                                            <img src="{{ $method->logo_url }}" alt="{{ $method->name }}" style="width:20px; height:20px; object-fit:contain;">
                                        </span>
                                    @else
                                        <span style="width:26px; height:26px; border-radius:999px; background:#f3f4f6; display:grid; place-items:center; font-size:11px; font-weight:900; color:#111827;">
                                            {{ strtoupper(substr($method->name,0,2)) }}
                                        </span>
                                    @endif
                                    <div>
                                        <div style="font-size: 12px; font-weight: 900; color: #111827;">{{ $method->name }}</div>
                                        <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">
                                            {{ Str::limit($method->details ?? '', 60) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #6b7280;">{{ $method->slug }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827; text-transform: capitalize;">
                                {{ $method->type }}
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827; text-transform: capitalize;">
                                {{ $method->category }}
                            </td>
                            <td style="padding: 12px 14px;">
                                @if($method->is_active)
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(16,185,129,.12); color: rgba(5,150,105,1); border: 1px solid rgba(16,185,129,.35);">
                                        Active
                                    </span>
                                @else
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(239,68,68,.12); color: rgba(220,38,38,1); border: 1px solid rgba(239,68,68,.35);">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 12px 14px;">
                                <div style="display: flex; gap: 6px;">
                                    <a href="{{ route('admin.payment-methods.edit', $method) }}" style="padding: 6px 12px; border-radius: 8px; background: #2563eb; color: white; font-size: 11px; font-weight: 900; text-decoration: none; cursor: pointer;">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.payment-methods.delete', $method) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this payment method?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 12px; border-radius: 8px; background: #ef4444; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: #6b7280; font-size: 13px;">
                                No payment methods found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($methods->hasPages())
            <div style="padding: 14px; border-top: 1px solid rgba(0,0,0,.06);">
                {{ $methods->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

