@extends('emails.layout')

@section('content')
<h1>Stock Purchase Confirmation</h1>

<p>Hello <strong>{{ $user->name }}</strong>,</p>

<p>Your stock purchase has been successfully processed. Here are the details of your transaction:</p>

<table class="details-table">
    <tr>
        <td>Stock</td>
        <td><strong>{{ $stock->name }}</strong> ({{ $stock->ticker }})</td>
    </tr>
    <tr>
        <td>Quantity</td>
        <td>{{ $quantity }} shares</td>
    </tr>
    <tr>
        <td>Price per Share</td>
        <td>${{ number_format($pricePerShare, 2) }}</td>
    </tr>
    <tr>
        <td>Total Amount</td>
        <td><strong class="highlight">${{ number_format($totalAmount, 2) }}</strong></td>
    </tr>
    <tr>
        <td>Order Type</td>
        <td style="text-transform: capitalize;">{{ $orderTypeText }}</td>
    </tr>
    @if(isset($limitPrice) && $limitPrice)
    <tr>
        <td>Limit Price</td>
        <td>${{ number_format($limitPrice, 2) }}</td>
    </tr>
    @endif
    <tr>
        <td>Status</td>
        <td><span class="status-badge status-success">{{ $statusText }}</span></td>
    </tr>
    <tr>
        <td>Transaction Date</td>
        <td>{{ now()->format('F d, Y \a\t g:i A') }}</td>
    </tr>
</table>

<div class="info-box">
    <strong>Your New Balance:</strong> ${{ number_format($newBalance, 2) }}
</div>

<p>Your shares have been added to your portfolio. You can view your stock holdings and track performance in your dashboard.</p>

<a href="{{ route('dashboard.stocks') }}" class="button">View Portfolio</a>

<div class="divider"></div>

<p>Thank you for trading with TESLA!</p>

<p>
    <strong>The TESLA Trading Team</strong>
</p>
@endsection
