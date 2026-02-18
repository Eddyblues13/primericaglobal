@extends('layouts.admin')

@section('title', 'Transactions Management - Admin')
@section('topTitle', 'Transactions Management')

@section('content')
<div class="wrap">
    <!-- Search and Filters -->
    <div class="whitePanel" style="margin-bottom: 12px;">
        <form method="GET" action="{{ route('admin.transactions') }}" class="p-4">
            <div style="display: flex; gap: 12px; align-items: end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user or transaction ID..." 
                        class="w-full h-10 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20" />
                </div>
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
                @if(request('search') || request('type') || request('status'))
                    <a href="{{ route('admin.transactions') }}" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; display: inline-flex; align-items: center; text-decoration: none;">
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
            <a href="{{ route('admin.transactions.create') }}" style="padding: 8px 16px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" /></svg>
                Add Transaction
            </a>
        </div>

        @if(session('success'))
            <div style="margin: 12px 14px; padding: 12px; background: #d1fae5; border: 1px solid #10b981; border-radius: 8px; color: #065f46; font-size: 12px; font-weight: 700;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="margin: 12px 14px; padding: 12px; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; color: #dc2626; font-size: 12px; font-weight: 700;">
                {{ session('error') }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid rgba(0,0,0,.06);">
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">ID</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">User</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Type</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Title</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Amount</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Direction</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Date</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: #111827;">#{{ $transaction->id }}</td>
                            <td style="padding: 12px 14px;">
                                <div style="font-size: 12px; font-weight: 900; color: #111827;">{{ $transaction->user->name }}</div>
                                <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">{{ $transaction->user->email }}</div>
                            </td>
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
                            <td style="padding: 12px 14px;">
                                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                    @if($transaction->status === 'Pending')
                                        <form method="POST" action="{{ route('admin.transactions.approve', $transaction) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" style="padding: 6px 10px; border-radius: 8px; background: #10b981; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.transactions.reject', $transaction) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" style="padding: 6px 10px; border-radius: 8px; background: #ef4444; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">Reject</button>
                                        </form>
                                    @endif
                                    <button onclick="openTransactionModal({{ $transaction->id }}, '{{ $transaction->status }}')" 
                                        style="padding: 6px 10px; border-radius: 8px; background: #6b7280; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">
                                        Status
                                    </button>
                                    <a href="{{ route('admin.transactions.edit', $transaction) }}" style="padding: 6px 10px; border-radius: 8px; background: #2563eb; color: white; font-size: 11px; font-weight: 900; text-decoration: none;">Edit</a>
                                    <form method="POST" action="{{ route('admin.transactions.delete', $transaction) }}" style="display: inline;" onsubmit="return confirm('Delete this transaction? Balance may be adjusted.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 10px; border-radius: 8px; background: #dc2626; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 40px; text-align: center; color: #6b7280; font-size: 13px;">
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

<!-- Transaction Status Modal -->
<div id="transactionModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 100; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 14px; padding: 24px; max-width: 400px; width: 90%;">
        <h3 style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 16px;">Update Transaction Status</h3>
        <form method="POST" id="transactionForm">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Status</label>
                <select name="status" id="transactionStatus" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; color: #111827; background: white;">
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; height: 40px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Update
                </button>
                <button type="button" onclick="closeTransactionModal()" style="flex: 1; height: 40px; border-radius: 8px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openTransactionModal(transactionId, currentStatus) {
    document.getElementById('transactionForm').action = `/admin/transactions/${transactionId}/status`;
    document.getElementById('transactionStatus').value = currentStatus;
    document.getElementById('transactionModal').style.display = 'flex';
}

function closeTransactionModal() {
    document.getElementById('transactionModal').style.display = 'none';
}

document.getElementById('transactionModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeTransactionModal();
    }
});
</script>
@endsection
