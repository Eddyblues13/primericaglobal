@extends('emails.layout')

@section('content')
<h1>Welcome to TESLA!</h1>

<p>Hello <strong>{{ $user->name }}</strong>,</p>

<p>We're thrilled to have you join the TESLA platform! Your account has been successfully created and you're all set to start your journey with us.</p>

<div class="info-box">
    <strong>What's Next?</strong><br>
    Start exploring our platform and discover amazing opportunities to grow your wealth.
</div>

<h2>Get Started</h2>
<p>Here's what you can do on TESLA:</p>

<ul style="margin-left: 20px; margin-bottom: 24px; color: #374151;">
    <li style="margin-bottom: 8px;"><strong>Explore Investment Plans</strong> - Grow your wealth with automated investment strategies</li>
    <li style="margin-bottom: 8px;"><strong>Trade Stocks</strong> - Buy and sell stocks in real-time with live market data</li>
    <li style="margin-bottom: 8px;"><strong>Browse Vehicle Inventory</strong> - Discover premium Tesla vehicles</li>
    <li style="margin-bottom: 8px;"><strong>Manage Your Wallet</strong> - Deposit, withdraw, and track all your transactions</li>
</ul>

<a href="{{ route('dashboard.index') }}" class="button">Go to Dashboard</a>

<div class="divider"></div>

<p>If you have any questions or need assistance, our support team is here to help. Simply visit the Support page in your dashboard or reply to this email.</p>

<p>Welcome aboard!</p>

<p>
    <strong>The TESLA Team</strong>
</p>
@endsection
