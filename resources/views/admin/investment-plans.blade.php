@extends('layouts.admin')

@section('title', 'Investment Plans - Admin')
@section('topTitle', 'Investment Plans')

@section('content')
<div class="wrap">
    <!-- Investment Plans Table -->
    <div class="whitePanel">
        <div class="panelHead">
            <div>
                <h5>Investment Plans</h5>
                <small>Total: {{ $plans->total() }} plans</small>
            </div>
            <a href="{{ route('admin.investment-plans.create') }}" style="padding: 8px 16px; border-radius: 8px; background: #111827; color: white; font-size: 12px; font-weight: 900; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add Plan
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
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Name</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Category</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Risk</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Min</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Max</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Profit %</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Duration</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Featured</th>
                        <th style="padding: 12px 14px; text-align: left; font-size: 11px; font-weight: 900; color: #6b7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,.06);">
                            <td style="padding: 12px 14px;">
                                <div style="font-size: 12px; font-weight: 900; color: #111827;">{{ $plan->name }}</div>
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ ucfirst($plan->category ?? 'N/A') }}</td>
                            <td style="padding: 12px 14px;">
                                @php
                                    $riskColors = [
                                        'low' => ['bg' => 'rgba(16, 185, 129, .14)', 'text' => 'rgba(16, 185, 129, .95)', 'border' => 'rgba(16, 185, 129, .25)'],
                                        'medium' => ['bg' => 'rgba(251, 191, 36, .14)', 'text' => 'rgba(217, 119, 6, .95)', 'border' => 'rgba(217, 119, 6, .25)'],
                                        'high' => ['bg' => 'rgba(239, 68, 68, .14)', 'text' => 'rgba(220, 38, 38, .95)', 'border' => 'rgba(220, 38, 38, .25)'],
                                    ];
                                    $risk = strtolower($plan->risk_level ?? 'medium');
                                    $color = $riskColors[$risk] ?? $riskColors['medium'];
                                @endphp
                                <span style="font-size: 10px; font-weight: 900; padding: 4px 8px; border-radius: 999px; background: {{ $color['bg'] }}; color: {{ $color['text'] }}; border: 1px solid {{ $color['border'] }};">
                                    {{ ucfirst($plan->risk_level ?? 'Medium') }}
                                </span>
                            </td>
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: #111827;">${{ number_format((float)($plan->min_investment ?? 0), 2) }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: #111827;">{{ $plan->max_investment === null ? 'Unlimited' : '$' . number_format((float)$plan->max_investment, 2) }}</td>
                            <td style="padding: 12px 14px; font-size: 12px; font-weight: 900; color: #10b981;">{{ number_format((float)($plan->profit_margin ?? 0), 2) }}%</td>
                            <td style="padding: 12px 14px; font-size: 12px; color: #111827;">{{ $plan->duration_label ?? $plan->duration_days . ' days' }}</td>
                            <td style="padding: 12px 14px;">
                                @if($plan->is_featured)
                                    <span class="status ok">Yes</span>
                                @else
                                    <span style="font-size: 11px; color: #9ca3af;">No</span>
                                @endif
                            </td>
                            <td style="padding: 12px 14px;">
                                <div style="display: flex; gap: 6px;">
                                    <a href="{{ route('admin.investment-plans.edit', $plan) }}" style="padding: 6px 12px; border-radius: 8px; background: #2563eb; color: white; font-size: 11px; font-weight: 900; text-decoration: none; cursor: pointer;">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.investment-plans.delete', $plan) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this investment plan?');">
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
                            <td colspan="9" style="padding: 40px; text-align: center; color: #6b7280; font-size: 13px;">
                                No investment plans found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($plans->hasPages())
            <div style="padding: 14px; border-top: 1px solid rgba(0,0,0,.06);">
                {{ $plans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
