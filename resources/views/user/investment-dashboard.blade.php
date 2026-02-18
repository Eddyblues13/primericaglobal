@extends('layouts.dashboard')

@section('title', 'Primrica Global Capital Investment Dashboard')
@section('topTitle', 'Investment Dashboard')

@section('content')
<div class="wrap" id="investment-dashboard">
    <!-- Investment Dashboard Header -->
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3>Investment Dashboard</h3>
                <p>Track your investment history and performance.</p>
            </div>
        </div>
    </div>

    <!-- Investment Statistics -->
    <div class="statGrid" style="margin-top: 12px;">
        <div class="stat">
            <div>
                <small>Total Invested</small>
                <strong>${{ number_format($totalInvested ?? 0, 2) }}</strong>
                <div class="sub" style="color:#2563eb;">
                    All time investments
                </div>
            </div>
            <div class="chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                    <path d="M3 17l6-6 4 4 8-8" />
                    <path d="M21 7v6h-6" />
                </svg>
            </div>
        </div>

        <div class="stat">
            <div>
                <small>Active Investments</small>
                <strong>${{ number_format($activeInvestments ?? 0, 2) }}</strong>
                <div class="sub" style="color:#10b981;">
                    {{ $investments->where('status', 'Active')->count() }} active plans
                </div>
            </div>
            <div class="chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                    <path d="M12 8v4l3 3" />
                    <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>

        <div class="stat">
            <div>
                <small>Total Earnings</small>
                <strong style="color: #10b981;">${{ number_format($totalEarnings ?? 0, 2) }}</strong>
                <div class="sub" style="color:#10b981;">
                    From active investments
                </div>
            </div>
            <div class="chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                    <path d="M3 17l6-6 4 4 8-8" />
                    <path d="M21 7v6h-6" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Investment History Table -->
    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="whitePanel">
            <div class="panelHead">
                <div>
                    <h5>Investment History & Earnings</h5>
                    <small>All your investments and calculated earnings</small>
                </div>
            </div>

            <div style="padding: 0; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 1px solid rgba(0,0,0,.08);">
                            <th
                                style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Plan Name</th>
                            <th
                                style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Strategy</th>
                            <th
                                style="padding: 12px 14px; text-align: right; font-size: 11px; font-weight: 900; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Invested</th>
                            <th
                                style="padding: 12px 14px; text-align: right; font-size: 11px; font-weight: 900; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Return Rate</th>
                            <th
                                style="padding: 12px 14px; text-align: right; font-size: 11px; font-weight: 900; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Earnings</th>
                            <th
                                style="padding: 12px 14px; text-align: right; font-size: 11px; font-weight: 900; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Total Value</th>
                            <th
                                style="padding: 12px 14px; text-align: center; font-size: 11px; font-weight: 900; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Status</th>
                            <th
                                style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($investments ?? [] as $investment)
                        @php
                        $plan = $investment->investmentPlan;
                        $returnRate = $plan ? (float) ($plan->profit_margin ?? $plan->one_year_return ?? 0) : 0;
                        $earnings = $investment->calculated_earnings ?? ($investment->status === 'Active' && $plan ?
                        ($investment->amount * ($returnRate / 100)) : 0);
                        $totalValue = $investment->amount + $earnings;
                        @endphp
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 14px; font-size: 13px; font-weight: 900; color: #111827;">
                                {{ $plan->name ?? 'N/A' }}
                            </td>
                            <td style="padding: 14px; font-size: 12px; font-weight: 700; color: #6b7280;">
                                {{ $plan->strategy ?? 'N/A' }}
                            </td>
                            <td
                                style="padding: 14px; text-align: right; font-size: 13px; font-weight: 900; color: #111827;">
                                ${{ number_format($investment->amount, 2) }}
                            </td>
                            <td
                                style="padding: 14px; text-align: right; font-size: 12px; font-weight: 800; color: {{ $returnRate >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ $returnRate >= 0 ? '+' : '' }}{{ number_format($returnRate, 2) }}%
                            </td>
                            <td
                                style="padding: 14px; text-align: right; font-size: 13px; font-weight: 900; color: {{ $earnings >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ $earnings >= 0 ? '+' : '' }}${{ number_format($earnings, 2) }}
                            </td>
                            <td
                                style="padding: 14px; text-align: right; font-size: 13px; font-weight: 900; color: #111827;">
                                ${{ number_format($totalValue, 2) }}
                            </td>
                            <td style="padding: 14px; text-align: center;">
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 10px; font-weight: 900; 
                                        @if($investment->status === 'Active')
                                            background: rgba(16, 185, 129, .14); color: #047857; border: 1px solid rgba(16, 185, 129, .45);
                                        @elseif($investment->status === 'Completed')
                                            background: rgba(107, 114, 128, .14); color: #374151; border: 1px solid rgba(107, 114, 128, .45);
                                        @else
                                            background: rgba(239, 68, 68, .14); color: #991b1b; border: 1px solid rgba(239, 68, 68, .45);
                                        @endif">
                                    {{ $investment->status }}
                                </span>
                            </td>
                            <td style="padding: 14px; font-size: 11px; font-weight: 700; color: #9ca3af;">
                                {{ $investment->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="padding: 40px 20px; text-align: center;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"
                                    stroke-width="2" style="margin: 0 auto 12px; opacity: 0.5;">
                                    <path d="M3 17l6-6 4 4 8-8" />
                                    <path d="M21 7v6h-6" />
                                </svg>
                                <p style="font-size: 13px; color: #6b7280; font-weight: 700; margin: 0;">No investments
                                    yet</p>
                                <p style="font-size: 11px; color: #9ca3af; margin: 4px 0 0 0;">Start investing to see
                                    your history here</p>
                                <a href="{{ route('dashboard.investments') }}"
                                    style="display: inline-block; margin-top: 16px; padding: 8px 16px; border-radius: 8px; background: #E31937; color: #fff; font-size: 11px; font-weight: 900; text-decoration: none;">
                                    Browse Investment Plans →
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(($investments ?? collect())->isNotEmpty())
                    <tfoot style="background: #f9fafb; border-top: 2px solid rgba(0,0,0,.1);">
                        <tr>
                            <td colspan="2"
                                style="padding: 14px; font-size: 12px; font-weight: 900; color: #111827; text-align: right;">
                                <strong>Totals:</strong>
                            </td>
                            <td
                                style="padding: 14px; text-align: right; font-size: 13px; font-weight: 900; color: #111827;">
                                ${{ number_format($totalInvested ?? 0, 2) }}
                            </td>
                            <td style="padding: 14px;"></td>
                            <td
                                style="padding: 14px; text-align: right; font-size: 13px; font-weight: 900; color: #10b981;">
                                ${{ number_format($totalEarnings ?? 0, 2) }}
                            </td>
                            <td
                                style="padding: 14px; text-align: right; font-size: 13px; font-weight: 900; color: #111827;">
                                ${{ number_format(($totalInvested ?? 0) + ($totalEarnings ?? 0), 2) }}
                            </td>
                            <td colspan="2" style="padding: 14px;"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection