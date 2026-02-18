@extends('layouts.admin')

@section('title', 'Users Management - Admin')
@section('topTitle', 'Users Management')

@section('content')
<div class="wrap">
    <!-- Search and Filters -->
    <div class="whitePanel" style="margin-bottom: 12px;">
        <form method="GET" action="{{ route('admin.users') }}" class="p-4">
            <div style="display: flex; gap: 12px; align-items: end;">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Search Users</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or username..." 
                        class="w-full h-10 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20" />
                </div>
                <button type="submit" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #111827; color: white; font-size: 12px; font-weight: 900; cursor: pointer;">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users') }}" style="height: 40px; padding: 0 20px; border-radius: 10px; background: #ef4444; color: white; font-size: 12px; font-weight: 900; cursor: pointer; display: inline-flex; align-items: center; text-decoration: none;">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="whitePanel">
        <div class="panelHead">
            <div>
                <h5>All Users</h5>
                <small>Total: {{ $users->total() }} users</small>
            </div>
            <a href="{{ route('admin.users.create') }}" style="padding: 8px 16px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" /></svg>
                Add User
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
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">User</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Email</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Balance</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">KYC Status</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Joined</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td data-label="User" style="padding: 12px 14px;">
                                <div style="font-size: 12px; font-weight: 900; color: #111827;">{{ $user->name }}</div>
                                @if($user->username)
                                    <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">@{{ $user->username }}</div>
                                @endif
                            </td>
                            <td data-label="Email" style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ $user->email }}</td>
                            <td data-label="Balance" style="padding: 12px 14px;">
                                <div style="font-size: 12px; font-weight: 900; color: #111827;">${{ number_format((float)($user->available_balance ?? 0), 2) }}</div>
                            </td>
                            <td data-label="KYC Status" style="padding: 12px 14px;">
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
                            </td>
                            <td data-label="Joined" style="padding: 12px 14px; font-size: 11px; color: #6b7280;">{{ $user->created_at->format('M d, Y') }}</td>
                            <td data-label="Actions" style="padding: 12px 14px;">
                                <div class="action-buttons" style="display: flex; flex-wrap: wrap; gap: 6px;">
                                    <a href="{{ route('admin.users.show', $user) }}" style="padding: 6px 10px; border-radius: 8px; background: #111827; color: white; font-size: 11px; font-weight: 900; text-decoration: none;">View</a>
                                    <a href="{{ route('admin.users.edit', $user) }}" style="padding: 6px 10px; border-radius: 8px; background: #2563eb; color: white; font-size: 11px; font-weight: 900; text-decoration: none;">Edit</a>
                                    <button onclick="openBalanceModal({{ $user->id }}, '{{ $user->name }}', {{ $user->available_balance ?? 0 }})" 
                                        style="padding: 6px 10px; border-radius: 8px; background: #10b981; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">
                                        Balance
                                    </button>
                                    <button onclick="openEmailModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" 
                                        style="padding: 6px 10px; border-radius: 8px; background: #8b5cf6; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">
                                        Email
                                    </button>
                                    <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="padding: 6px 10px; border-radius: 8px; background: #f59e0b; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">Login As</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.delete', $user) }}" style="display: inline;" onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 10px; border-radius: 8px; background: #dc2626; color: white; font-size: 11px; font-weight: 900; cursor: pointer; border: none;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 40px; text-align: center; color: #6b7280; font-size: 13px;">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div style="padding: 14px; border-top: 1px solid rgba(0,0,0,.06);">
                {{ $users->links() }}
            </div>
        @endif
    </div>
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

document.getElementById('emailModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEmailModal();
    }
});
</script>

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
@endsection
