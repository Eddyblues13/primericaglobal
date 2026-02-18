@extends('layouts.admin')

@section('title', 'User Transactions - Admin')
@section('topTitle', 'Transactions: ' . $user->name)

@section('content')
<div class="wrap">
    <div style="margin-bottom: 12px;">
        <a href="{{ route('admin.users.show', $user) }}" style="padding: 8px 16px; border-radius: 8px; background: #6b7280; color: white; font-size: 12px; font-weight: 900; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            ← Back to User
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="whitePanel" style="margin-bottom: 12px;">
        <form method="GET" action="{{ route('admin.users.transactions', $user) }}" class="p-4">
            <div style="display: flex; gap: 12px; align-items: end; flex-wrap: wrap;">
                <div style="min-width: 150px;">
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Type</label>
                    <select name="type" style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                        <option value="">All Types</option>
                        <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                        <option value="investment" {{ request('type') == 'investment' ? 'selected' : '' }}>Investment</option>
                        <option value="admin_adjustment" {{ request('type') == 'admin_adjustment' ? 'selected' : '' }}>Admin Adjustment</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div style="min-width: 150px;">
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Status</label>
                    <select name="status" style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <button type="submit" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer;">
                    Filter
                </button>
                @if(request('type') || request('status'))
                    <a href="{{ route('admin.users.transactions', $user) }}" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; display: inline-flex; align-items: center; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="whitePanel">
        <div class="panelHead">
            <div>
                <h5>All Transactions</h5>
                <small>Total: {{ $transactions->total() }} transactions</small>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid rgba(0,0,0,.06);">
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">ID</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Type</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Title</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Amount</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Direction</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: #111827;">#{{ $transaction->id }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ ucfirst($transaction->type) }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ $transaction->title }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: {{ $transaction->direction === 'credit' ? '#10b981' : '#ef4444' }};">
                                {{ $transaction->direction === 'credit' ? '+' : '-' }}${{ number_format((float)$transaction->amount, 2) }}
                            </td>
                            <td style="padding: 12px 14px;">
                                @if($transaction->direction === 'credit')
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(16, 185, 129, .14); color: rgba(16, 185, 129, .95); border: 1px solid rgba(16, 185, 129, .25);">Credit</span>
                                @else
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(239, 68, 68, .14); color: rgba(220, 38, 38, .95); border: 1px solid rgba(220, 38, 38, .25);">Debit</span>
                                @endif
                            </td>
                            <td style="padding: 12px 14px;">
                                @if($transaction->status === 'Completed')
                                    <span class="status ok">{{ $transaction->status }}</span>
                                @elseif($transaction->status === 'Pending')
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(251, 191, 36, .14); color: rgba(217, 119, 6, .95); border: 1px solid rgba(217, 119, 6, .25);">{{ $transaction->status }}</span>
                                @else
                                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(239, 68, 68, .14); color: rgba(220, 38, 38, .95); border: 1px solid rgba(220, 38, 38, .25);">{{ $transaction->status }}</span>
                                @endif
                            </td>
                            <td style="padding: 12px 14px; font-size: 11px; color: #6b7280;">{{ $transaction->occurred_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: #6b7280; font-size: 13px;">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div style="padding: 14px; border-top: 1px solid rgba(0,0,0,.06);">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
