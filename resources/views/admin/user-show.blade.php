@extends('layouts.admin')

@section('title', 'User Details - Admin')
@section('topTitle', 'User Details')

@section('content')
<div class="wrap">
    @if(session('success'))
        <div style="margin-bottom: 12px; padding: 12px; background: #d1fae5; border: 1px solid #10b981; border-radius: 8px; color: #065f46; font-size: 12px; font-weight: 700;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="margin-bottom: 12px; padding: 12px; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; color: #dc2626; font-size: 12px; font-weight: 700;">
            {{ session('error') }}
        </div>
    @endif

    <!-- User Info Card -->
    <div class="whitePanel" style="margin-bottom: 12px;">
        <div class="panelHead">
            <div>
                <h5>{{ $user->name }}</h5>
                <small>{{ $user->email }}</small>
            </div>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('admin.users.edit', $user) }}" style="padding: 8px 16px; border-radius: 8px; background: #2563eb; color: white; font-size: 12px; font-weight: 900; text-decoration: none;">Edit</a>
                <form method="POST" action="{{ route('admin.users.delete', $user) }}" style="display: inline;" onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 8px 16px; border-radius: 8px; background: #dc2626; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">Delete</button>
                </form>
                <a href="{{ route('admin.users') }}" style="padding: 8px 16px; border-radius: 8px; background: #6b7280; color: white; font-size: 12px; font-weight: 900; text-decoration: none;">← Back</a>
            </div>
        </div>

        <div style="padding: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <div style="font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Full Name</div>
                <div style="font-size: 14px; font-weight: 700; color: #111827;">{{ $user->name }}</div>
            </div>
            <div>
                <div style="font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Email</div>
                <div style="font-size: 14px; font-weight: 700; color: #111827;">{{ $user->email }}</div>
            </div>
            @if($user->username)
            <div>
                <div style="font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Username</div>
                <div style="font-size: 14px; font-weight: 700; color: #111827;">@{{ $user->username }}</div>
            </div>
            @endif
            <div>
                <div style="font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Balance</div>
                <div style="font-size: 18px; font-weight: 900; color: #10b981;">${{ number_format((float)($user->available_balance ?? 0), 2) }}</div>
            </div>
            <div>
                <div style="font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Total Profit</div>
                <div style="font-size: 18px; font-weight: 900; color: #8b5cf6;">${{ number_format((float)($user->total_profit ?? 0), 2) }}</div>
            </div>
            <div>
                <div style="font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">KYC Status</div>
                @php
                    $latestKyc = $user->latestKycSubmission;
                    $isVerified = $user->isKycVerified();
                @endphp
                @if($isVerified)
                    <span class="status ok">Verified</span>
                @elseif($latestKyc && $latestKyc->status === 'Pending')
                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(251, 191, 36, .14); color: rgba(217, 119, 6, .95); border: 1px solid rgba(217, 119, 6, .25);">Pending</span>
                @elseif($latestKyc && $latestKyc->status === 'Rejected')
                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(239, 68, 68, .14); color: rgba(220, 38, 38, .95); border: 1px solid rgba(220, 38, 38, .25);">Rejected</span>
                @else
                    <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: rgba(156, 163, 175, .14); color: rgba(107, 114, 128, .95); border: 1px solid rgba(107, 114, 128, .25);">Not Submitted</span>
                @endif
            </div>
            <div>
                <div style="font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Joined</div>
                <div style="font-size: 14px; font-weight: 700; color: #111827;">{{ $user->created_at->format('M d, Y') }}</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="padding: 0 20px 20px; display: flex; gap: 8px; flex-wrap: wrap;">
            <button onclick="openBalanceModal({{ $user->id }}, '{{ $user->name }}', {{ $user->available_balance ?? 0 }})" 
                style="padding: 8px 16px; border-radius: 8px; background: #10b981; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                Update Balance
            </button>
            <button onclick="openProfitModal({{ $user->id }}, '{{ $user->name }}', {{ $user->total_profit ?? 0 }})" 
                style="padding: 8px 16px; border-radius: 8px; background: #8b5cf6; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                Update Profit
            </button>
            <button onclick="openEmailModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" 
                style="padding: 8px 16px; border-radius: 8px; background: #2563eb; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                Send Email
            </button>
            <a href="{{ route('admin.users.transactions', $user) }}" 
                style="padding: 8px 16px; border-radius: 8px; background: #6b7280; color: white; font-size: 12px; font-weight: 900; text-decoration: none;">
                View Transactions
            </a>
            <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" style="display: inline;">
                @csrf
                <button type="submit" style="padding: 8px 16px; border-radius: 8px; background: #8b5cf6; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Login as User
                </button>
            </form>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="whitePanel" style="margin-bottom: 12px;">
        <div class="panelHead">
            <div>
                <h5>Recent Transactions</h5>
                <small>Latest 10 transactions</small>
            </div>
            <a href="{{ route('admin.users.transactions', $user) }}" style="padding: 8px 16px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; text-decoration: none;">
                View All
            </a>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid rgba(0,0,0,.06);">
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Type</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Title</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Amount</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ ucfirst($transaction->type) }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ $transaction->title }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: {{ $transaction->direction === 'credit' ? '#10b981' : '#ef4444' }};">
                                {{ $transaction->direction === 'credit' ? '+' : '-' }}${{ number_format((float)$transaction->amount, 2) }}
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
                            <td colspan="5" style="padding: 40px; text-align: center; color: #6b7280; font-size: 13px;">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Orders -->
    @if($orders->count() > 0)
    <div class="whitePanel" style="margin-bottom: 12px;">
        <div class="panelHead">
            <div>
                <h5>Recent Orders</h5>
                <small>Latest 10 orders</small>
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid rgba(0,0,0,.06);">
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Vehicle</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Total</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ $order->car->name ?? 'N/A' }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: #111827;">${{ number_format((float)$order->total_price, 2) }}</td>
                            <td style="padding: 12px 14px;">
                                <span class="status {{ $order->status === 'completed' ? 'ok' : '' }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td style="padding: 12px 14px; font-size: 11px; color: #6b7280;">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Balance Update Modal -->
<div id="balanceModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 100; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 14px; padding: 24px; max-width: 400px; width: 90%;">
        <h3 style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 16px;">Update Balance</h3>
        <form method="POST" id="balanceForm">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">User</label>
                <input type="text" id="userName" readonly style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); background: #f9fafb; font-size: 13px; color: #111827;" />
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Current Balance</label>
                <input type="text" id="currentBalance" readonly style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); background: #f9fafb; font-size: 13px; font-weight: 700; color: #10b981;" />
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Action</label>
                <select name="action" id="balanceAction" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; color: #111827; background: white;">
                    <option value="add">Add Amount</option>
                    <option value="subtract">Subtract Amount</option>
                    <option value="set">Set Amount</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Amount</label>
                <input type="number" name="amount" step="0.01" min="0" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; color: #111827; background: white;" />
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; height: 40px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Update
                </button>
                <button type="button" onclick="closeBalanceModal()" style="flex: 1; height: 40px; border-radius: 8px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Profit Update Modal -->
<div id="profitModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 100; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 14px; padding: 24px; max-width: 400px; width: 90%;">
        <h3 style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 16px;">Update Profit</h3>
        <form method="POST" id="profitForm">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">User</label>
                <input type="text" id="profitUserName" readonly style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); background: #f9fafb; font-size: 13px; color: #111827;" />
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Current Profit</label>
                <input type="text" id="currentProfit" readonly style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); background: #f9fafb; font-size: 13px; font-weight: 700; color: #8b5cf6;" />
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Action</label>
                <select name="action" id="profitAction" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; color: #111827; background: white;">
                    <option value="add">Add Amount</option>
                    <option value="subtract">Subtract Amount</option>
                    <option value="set">Set Amount</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Amount</label>
                <input type="number" name="amount" step="0.01" min="0" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; color: #111827; background: white;" />
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; height: 40px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Update
                </button>
                <button type="button" onclick="closeProfitModal()" style="flex: 1; height: 40px; border-radius: 8px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Email Modal -->
<div id="emailModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 100; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 14px; padding: 24px; max-width: 500px; width: 90%;">
        <h3 style="font-size: 16px; font-weight: 900; color: #111827; margin-bottom: 16px;">Send Email</h3>
        <form method="POST" id="emailForm">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">To</label>
                <input type="text" id="userEmail" readonly style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); background: #f9fafb; font-size: 13px; color: #111827;" />
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Subject *</label>
                <input type="text" name="subject" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; color: #111827; background: white;" />
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Message *</label>
                <textarea name="message" rows="6" required style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; resize: vertical; color: #111827; background: white;"></textarea>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; height: 40px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Send
                </button>
                <button type="button" onclick="closeEmailModal()" style="flex: 1; height: 40px; border-radius: 8px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; border: none;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBalanceModal(userId, userName, currentBalance) {
    document.getElementById('balanceForm').action = `/admin/users/${userId}/balance`;
    document.getElementById('userName').value = userName;
    document.getElementById('currentBalance').value = '$' + parseFloat(currentBalance).toFixed(2);
    document.getElementById('balanceModal').style.display = 'flex';
}

function closeBalanceModal() {
    document.getElementById('balanceModal').style.display = 'none';
}

function openProfitModal(userId, userName, currentProfit) {
    document.getElementById('profitForm').action = `/admin/users/${userId}/profit`;
    document.getElementById('profitUserName').value = userName;
    document.getElementById('currentProfit').value = '$' + parseFloat(currentProfit).toFixed(2);
    document.getElementById('profitModal').style.display = 'flex';
}

function closeProfitModal() {
    document.getElementById('profitModal').style.display = 'none';
}

function openEmailModal(userId, userName, userEmail) {
    document.getElementById('emailForm').action = `/admin/users/${userId}/email`;
    document.getElementById('userEmail').value = userEmail;
    document.getElementById('emailModal').style.display = 'flex';
}

function closeEmailModal() {
    document.getElementById('emailModal').style.display = 'none';
}

document.getElementById('balanceModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeBalanceModal();
    }
});

document.getElementById('profitModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeProfitModal();
    }
});

document.getElementById('emailModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEmailModal();
    }
});
</script>
@endsection
