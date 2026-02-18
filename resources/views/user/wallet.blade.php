@extends('layouts.dashboard')

@section('title', 'Primrica Global Capital Wallet')

@section('topTitle', 'Wallet')

@push('styles')
<style>
    .walletSummaryGrid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .walletCard {
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, .06);
        background: #ffffff;
        padding: 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 110px;
    }

    .walletCardHead {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .walletCardTitle {
        font-size: 13px;
        font-weight: 900;
        color: #111827;
        margin: 0 0 2px 0;
    }

    .walletCardSubtitle {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        margin: 0;
    }

    .walletCardLink {
        font-size: 11px;
        font-weight: 900;
        color: #2563eb;
        text-decoration: none;
    }

    .walletCardLink:hover {
        text-decoration: underline;
    }

    .walletCardValue {
        font-size: 20px;
        font-weight: 900;
        color: #111827;
        margin-top: 6px;
    }

    .walletCardTag {
        font-size: 11px;
        font-weight: 700;
        margin-top: 4px;
    }

    .walletTagUp {
        color: #10b981;
    }

    .walletTagDown {
        color: #ef4444;
    }

    .walletTagClock {
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .walletRecentHeader {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .walletRecentSubtitle {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        margin-top: 3px;
    }

    .walletViewAll {
        font-size: 11px;
        font-weight: 900;
        color: #111827;
        opacity: .7;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .walletViewAll:hover {
        opacity: 1;
    }

    .walletTxRow {
        padding: 10px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .walletTxRow+.walletTxRow {
        border-top: 1px solid rgba(0, 0, 0, .06);
    }

    .walletTxLeft {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .walletTxIcon {
        width: 32px;
        height: 32px;
        border-radius: 999px;
        display: grid;
        place-items: center;
        background: #ecfdf5;
        color: #16a34a;
        font-size: 14px;
        flex: 0 0 auto;
    }

    .walletTxTitle {
        font-size: 12px;
        font-weight: 900;
        color: #111827;
    }

    .walletTxMeta {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        margin-top: 2px;
    }

    .walletTxRight {
        text-align: right;
        flex: 0 0 auto;
    }

    .walletTxAmount {
        font-size: 12px;
        font-weight: 900;
        color: #111827;
    }

    .walletTxAmountPositive {
        color: #10b981;
    }

    .walletTxStatus {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        margin-top: 2px;
    }

    @media (max-width: 1000px) {
        .walletSummaryGrid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .walletSummaryGrid {
            grid-template-columns: minmax(0, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="wrap" id="wallet">
    <!-- Wallet Header -->
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3>Wallet</h3>
                <p>Manage your funds and transactions.</p>
            </div>
        </div>
    </div>

    <!-- Deposit / Withdraw / Totals row -->
    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="walletSummaryGrid">
            <!-- Deposit Funds -->
            <div class="walletCard">
                <div class="walletCardHead">
                    <div>
                        <p class="walletCardTitle">Deposit Funds</p>
                        <p class="walletCardSubtitle">Add money to your wallet</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('dashboard.wallet.deposit') }}" class="walletCardLink">Add Funds →</a>
                </div>
            </div>

            <!-- Withdraw Funds -->
            <div class="walletCard">
                <div class="walletCardHead">
                    <div>
                        <p class="walletCardTitle">Withdraw Funds</p>
                        <p class="walletCardSubtitle">Transfer money to your bank</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('dashboard.wallet.withdraw') }}" class="walletCardLink"
                        style="color:#ef4444;">Withdraw →</a>
                </div>
            </div>

            <!-- Total Invested / Portfolio value -->
            <div class="walletCard">
                <div class="walletCardHead">
                    <div>
                        <p class="walletCardTitle">Total Invested</p>
                        <p class="walletCardSubtitle">Portfolio value</p>
                    </div>
                </div>
                <div>
                    <div class="walletCardValue">
                        ${{ number_format($walletSummary['total_invested'], 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Totals row -->
    <div class="lowerGrid" style="grid-template-columns: 1fr 1fr 1fr; margin-top: 12px;">
        <div class="whitePanel">
            <div class="walletCard" style="border:none; box-shadow:none; padding:14px;">
                <p class="walletCardTitle">Total Deposits</p>
                <div class="walletCardValue">
                    ${{ number_format($walletSummary['total_deposits'], 2) }}
                </div>
                <div class="walletCardTag walletTagUp">This month</div>
            </div>
        </div>
        <div class="whitePanel">
            <div class="walletCard" style="border:none; box-shadow:none; padding:14px;">
                <p class="walletCardTitle">Total Withdrawals</p>
                <div class="walletCardValue">
                    ${{ number_format($walletSummary['total_withdrawals'], 2) }}
                </div>
                <div class="walletCardTag walletTagDown">This month</div>
            </div>
        </div>
        <div class="whitePanel">
            <div class="walletCard" style="border:none; box-shadow:none; padding:14px;">
                <p class="walletCardTitle">Available Balance</p>
                <div class="walletCardValue">
                    ${{ number_format($walletSummary['current_balance'], 2) }}
                </div>
                <div class="walletCardTag walletTagClock">
                    <span>Last updated</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="whitePanel">
            <div class="panelHead walletRecentHeader">
                <div>
                    <h5>Recent Transactions</h5>
                    <p class="walletRecentSubtitle">Your latest wallet activity</p>
                </div>
                <a href="javascript:void(0)" class="walletViewAll">View All →</a>
            </div>
            <div>
                @forelse ($walletTransactions as $tx)
                <div class="walletTxRow">
                    <div class="walletTxLeft">
                        <div class="walletTxIcon">
                            {{ $tx->direction === 'credit' ? '+' : '−' }}
                        </div>
                        <div>
                            <div class="walletTxTitle">{{ $tx->title }}</div>
                            <div class="walletTxMeta">
                                {{ $tx->asset ?? 'Wallet' }} &middot;
                                {{ optional($tx->occurred_at)->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="walletTxRight">
                        @php
                        $isCredit = $tx->direction === 'credit';
                        @endphp
                        <div class="walletTxAmount {{ $isCredit ? 'walletTxAmountPositive' : '' }}">
                            {{ $isCredit ? '+' : '' }}${{ number_format($tx->amount, 2) }}
                        </div>
                        <div class="walletTxStatus">{{ $tx->status }}</div>
                    </div>
                </div>
                @empty
                <div style="padding: 32px 20px; text-align:center; font-size:13px; color:#6b7280;">
                    No transactions yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection