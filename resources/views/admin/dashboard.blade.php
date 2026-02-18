@extends('layouts.admin')

@section('title', 'Admin Dashboard - Primrica Global Capital')
@section('topTitle', 'Admin Dashboard')

@section('content')
<div class="wrap" id="adminDashboard">
    <!-- Stats Overview -->
    <div class="statGrid" style="margin-top: 0;">
        <div class="stat">
            <div>
                <small>Total Users</small>
                <strong>{{ number_format($stats['total_users']) }}</strong>
                <div class="sub" style="color:#2563eb;">
                    Active accounts
                </div>
            </div>
            <div class="chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                    <path d="M20 21a8 8 0 1 0-16 0" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </div>
        </div>

        <div class="stat">
            <div>
                <small>Total Revenue</small>
                <strong>${{ number_format((float)($stats['total_revenue'] ?? 0), 2) }}</strong>
                <div class="sub" style="color:#10b981;">
                    From deposits
                </div>
            </div>
            <div class="chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                    <path d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2" />
                    <path d="M21 12h-7a2 2 0 0 0 0 4h7" />
                </svg>
            </div>
        </div>

        <div class="stat">
            <div>
                <small>Pending Deposits</small>
                <strong>{{ $stats['pending_deposits'] }}</strong>
                <div class="sub" style="color:#f59e0b;">
                    Awaiting approval
                </div>
            </div>
            <div class="chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
            </div>
        </div>

        <div class="stat">
            <div>
                <small>Pending Withdrawals</small>
                <strong>{{ $stats['pending_withdrawals'] }}</strong>
                <div class="sub" style="color:#ef4444;">
                    Require action
                </div>
            </div>
            <div class="chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                    <path d="M12 2v20M6 5h5.5a3.5 3.5 0 0 1 0 7H6M18 12h-5.5a3.5 3.5 0 0 0 0 7H18" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="quickGrid" style="margin-top: 12px;">
        <a href="{{ route('admin.users') }}" class="quick" style="text-decoration: none; color: inherit;">
            <div>
                <h4>Manage Users</h4>
                <p>View and manage all users</p>
                <span
                    style="color:#2563eb; font-size: 11px; font-weight: 900; margin-top: 10px; display: inline-flex; align-items: center; gap: 4px;">
                    View Users <span>→</span>
                </span>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"
                style="opacity:.65">
                <path d="M20 21a8 8 0 1 0-16 0" />
                <circle cx="12" cy="7" r="4" />
            </svg>
        </a>

        <a href="{{ route('admin.orders') }}" class="quick" style="text-decoration: none; color: inherit;">
            <div>
                <h4>Manage Orders</h4>
                <p>View and update orders</p>
                <span
                    style="color:#10b981; font-size: 11px; font-weight: 900; margin-top: 10px; display: inline-flex; align-items: center; gap: 4px;">
                    View Orders <span>→</span>
                </span>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"
                style="opacity:.65">
                <path d="M6 6h15l-1.5 9h-13z" />
                <path d="M6 6l-2 0" />
                <path d="M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                <path d="M18 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
            </svg>
        </a>

        <a href="{{ route('admin.transactions') }}" class="quick" style="text-decoration: none; color: inherit;">
            <div>
                <h4>Wallet Transactions</h4>
                <p>Approve deposits & withdrawals</p>
                <span
                    style="color:#7c3aed; font-size: 11px; font-weight: 900; margin-top: 10px; display: inline-flex; align-items: center; gap: 4px;">
                    View Transactions <span>→</span>
                </span>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2"
                style="opacity:.65">
                <path d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2" />
                <path d="M21 12h-7a2 2 0 0 0 0 4h7" />
            </svg>
        </a>

        <a href="{{ route('admin.kyc') }}" class="quick" style="text-decoration: none; color: inherit;">
            <div>
                <h4>KYC Submissions</h4>
                <p>Review and approve KYC</p>
                <span
                    style="color:#f59e0b; font-size: 11px; font-weight: 900; margin-top: 10px; display: inline-flex; align-items: center; gap: 4px;">
                    Review KYC <span>→</span>
                </span>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"
                style="opacity:.65">
                <path d="M12 3l8 4v6c0 5-3 8-8 8s-8-3-8-8V7z" />
                <path d="M9 12l2 2 4-4" />
            </svg>
        </a>
    </div>

    <!-- Lower Grid: Recent Activity -->
    <div class="lowerGrid" style="margin-top: 14px;">
        <!-- Recent Users -->
        <div class="whitePanel">
            <div class="panelHead">
                <div>
                    <h5>Recent Users</h5>
                    <small>Latest registered users</small>
                </div>
                <a class="viewAll" href="{{ route('admin.users') }}">
                    View All <span>→</span>
                </a>
            </div>

            @forelse ($recentUsers as $user)
            <div class="orderRow">
                <div class="orderLeft">
                    <div class="thumb" style="background: #e5e7eb; display: grid; place-items: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                            <path d="M20 21a8 8 0 1 0-16 0" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="orderTitle">{{ $user->name }}</div>
                        <div class="orderMeta">
                            {{ $user->email }} · {{ $user->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
                <div class="orderRight">
                    <div class="price">${{ number_format((float)($user->available_balance ?? 0), 2) }}</div>
                    <span class="status ok">Active</span>
                </div>
            </div>
            @empty
            <div style="padding: 20px; font-size: 13px; color: #6b7280;">
                No users yet.
            </div>
            @endforelse
        </div>

        <!-- Recent Orders -->
        <div class="whitePanel">
            <div class="panelHead">
                <div>
                    <h5>Recent Orders</h5>
                    <small>Latest vehicle orders</small>
                </div>
                <a class="viewAll" href="{{ route('admin.orders') }}">
                    View All <span>→</span>
                </a>
            </div>

            @forelse ($recentOrders as $order)
            <div class="orderRow">
                <div class="orderLeft">
                    <div class="thumb">
                        @if($order->car && $order->car->image_url)
                        <img src="{{ $order->car->image_url }}" alt="{{ $order->car->name }}"
                            style="width:100%;height:100%;object-fit:cover;" />
                        @else
                        <div
                            style="background: #f3f4f6; width: 100%; height: 100%; display: grid; place-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"
                                stroke-width="2">
                                <path d="M3 12h18" />
                                <path d="M6 12l3-7h6l3 7" />
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="orderTitle">{{ $order->car?->name ?? 'Primrica Global Capital Vehicle' }}</div>
                        <div class="orderMeta">
                            {{ $order->user->name }} · {{ $order->created_at->format('M d') }}
                        </div>
                    </div>
                </div>
                <div class="orderRight">
                    <div class="price">${{ number_format((float)$order->total_price, 2) }}</div>
                    <span class="status {{ $order->status === 'Completed' ? 'ok' : '' }}">{{ $order->status }}</span>
                </div>
            </div>
            @empty
            <div style="padding: 20px; font-size: 13px; color: #6b7280;">
                No orders yet.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="whitePanel" style="margin-top: 12px;">
        <div class="panelHead">
            <div>
                <h5>Recent Transactions</h5>
                <small>Latest wallet activity</small>
            </div>
            <a class="viewAll" href="{{ route('admin.transactions') }}">
                View All <span>→</span>
            </a>
        </div>

        <div style="max-height: 400px; overflow-y: auto;">
            @forelse ($recentTransactions as $transaction)
            <div class="orderRow">
                <div class="orderLeft">
                    <div class="thumb"
                        style="background: {{ $transaction->direction === 'credit' ? '#d1fae5' : '#fee2e2' }}; display: grid; place-items: center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="{{ $transaction->direction === 'credit' ? '#10b981' : '#ef4444' }}"
                            stroke-width="2">
                            @if($transaction->direction === 'credit')
                            <path d="M12 5v14M19 12l-7-7-7 7" />
                            @else
                            <path d="M12 19V5M5 12l7 7 7-7" />
                            @endif
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="orderTitle">{{ $transaction->title }}</div>
                        <div class="orderMeta">
                            {{ $transaction->user->name }} · {{ $transaction->type }} · {{
                            $transaction->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
                <div class="orderRight">
                    <div class="price"
                        style="color: {{ $transaction->direction === 'credit' ? '#10b981' : '#ef4444' }};">
                        {{ $transaction->direction === 'credit' ? '+' : '-' }}${{
                        number_format((float)$transaction->amount, 2) }}
                    </div>
                    <span class="status {{ $transaction->status === 'Completed' ? 'ok' : '' }}">{{ $transaction->status
                        }}</span>
                </div>
            </div>
            @empty
            <div style="padding: 20px; font-size: 13px; color: #6b7280;">
                No transactions yet.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection