@extends('emails.layout')

@section('content')
<h1>Investment Confirmation</h1>

<p>Hello <strong>{{ $user->name }}</strong>,</p>

<p>Your investment has been successfully processed. We're excited to help you grow your wealth!</p>

<table class="details-table">
    <tr>
        <td>Investment Plan</td>
        <td><strong>{{ $plan->name }}</strong></td>
    </tr>
    <tr>
        <td>Strategy</td>
        <td>{{ $plan->strategy }}</td>
    </tr>
    <tr>
        <td>Investment Amount</td>
        <td><strong class="highlight">${{ number_format($amount, 2) }}</strong></td>
    </tr>
    <tr>
        <td>Expected Return</td>
        <td>{{ number_format($plan->one_year_return, 2) }}% per year</td>
    </tr>
    <tr>
        <td>Minimum Investment</td>
        <td>${{ number_format($plan->min_investment, 2) }}</td>
    </tr>
    <tr>
        <td>Status</td>
        <td><span class="status-badge status-success">Active</span></td>
    </tr>
    <tr>
        <td>Investment Date</td>
        <td>{{ now()->format('F d, Y \a\t g:i A') }}</td>
    </tr>
</table>

<div class="info-box">
    <strong>Your New Balance:</strong> ${{ number_format($newBalance, 2) }}
</div>

<p>Your investment is now active and will start generating returns according to the plan's strategy. You can track your
    investment performance in your dashboard.</p>

<a href="{{ route('dashboard.investment-dashboard') }}" class="button button-success">View Investment Dashboard</a>

<div class="divider"></div>

<p>Thank you for investing with Primrica Global Capital. We're committed to helping you achieve your financial goals!
</p>

<p>
    <strong>The Primrica Global Capital Investment Team</strong>
</p>
@endsection