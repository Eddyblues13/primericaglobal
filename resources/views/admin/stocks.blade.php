@extends('layouts.admin')

@section('title', 'Stocks Management - Admin')
@section('topTitle', 'Stocks Management')

@section('content')
<div class="wrap">
    <!-- Stocks Table -->
    <div class="whitePanel">
        <div class="panelHead">
            <div>
                <h5>Stocks</h5>
                <small>Total: {{ $stocks->total() }} stocks</small>
            </div>
            <a href="{{ route('admin.stocks.create') }}" style="padding: 8px 16px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add Stock
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
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Ticker</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Name</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Price</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Change</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Change %</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Volume</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Market Cap</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 12px 14px;">
                                <div style="font-size: 12px; font-weight: 900; color: #111827;">{{ $stock->ticker }}</div>
                            </td>
                            <td style="padding: 12px 14px;">
                                <div style="font-size: 12px; font-weight: 900; color: #111827;">{{ $stock->name }}</div>
                                @if($stock->domain)
                                    <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">{{ $stock->domain }}</div>
                                @endif
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: #111827;">${{ number_format((float)$stock->price, 2) }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; color: {{ (float)$stock->change >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ (float)$stock->change >= 0 ? '+' : '' }}${{ number_format((float)$stock->change, 2) }}
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; color: {{ (float)$stock->change_percent >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ (float)$stock->change_percent >= 0 ? '+' : '' }}{{ number_format((float)$stock->change_percent, 2) }}%
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ number_format((int)($stock->volume ?? 0)) }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ $stock->market_cap ?? 'N/A' }}</td>
                            <td style="padding: 12px 14px;">
                                <div style="display: flex; gap: 6px;">
                                    <a href="{{ route('admin.stocks.edit', $stock) }}" style="padding: 6px 12px; border-radius: 8px; background: #2563eb; color: white; font-size: 11px; font-weight: 900; text-decoration: none; cursor: pointer;">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.stocks.delete', $stock) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this stock?');">
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
                            <td colspan="8" style="padding: 40px; text-align: center; color: #6b7280; font-size: 13px;">
                                No stocks found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($stocks->hasPages())
            <div style="padding: 14px; border-top: 1px solid rgba(0,0,0,.06);">
                {{ $stocks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
