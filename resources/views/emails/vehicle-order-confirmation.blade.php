@extends('emails.layout')

@section('content')
<h1>Vehicle Order Confirmation</h1>

<p>Hello <strong>{{ $user->name }}</strong>,</p>

<p>Your vehicle order has been successfully placed! We're excited to help you get behind the wheel of your new Primrica
    Global Capital.</p>

<table class="details-table">
    <tr>
        <td>Order Number</td>
        <td><strong>{{ $order->order_number }}</strong></td>
    </tr>
    <tr>
        <td>Vehicle</td>
        <td><strong>{{ $car->name }}</strong></td>
    </tr>
    @if($car->year)
    <tr>
        <td>Year</td>
        <td>{{ $car->year }}</td>
    </tr>
    @endif
    @if($car->model)
    <tr>
        <td>Model</td>
        <td>{{ $car->model }}</td>
    </tr>
    @endif
    @if($car->variant)
    <tr>
        <td>Variant</td>
        <td>{{ $car->variant }}</td>
    </tr>
    @endif
    <tr>
        <td>Quantity</td>
        <td>{{ $quantity }}</td>
    </tr>
    <tr>
        <td>Price per Vehicle</td>
        <td>${{ number_format($car->price, 2) }}</td>
    </tr>
    <tr>
        <td>Total Amount</td>
        <td><strong class="highlight">${{ number_format($total, 2) }}</strong></td>
    </tr>
    <tr>
        <td>Status</td>
        <td><span class="status-badge status-success">{{ $order->status }}</span></td>
    </tr>
    <tr>
        <td>Order Date</td>
        <td>{{ $order->created_at->format('F d, Y \a\t g:i A') }}</td>
    </tr>
</table>

<div class="info-box">
    <strong>Your New Balance:</strong> ${{ number_format($newBalance, 2) }}
</div>

<p>Your order has been confirmed and will be processed shortly. You'll receive updates on the delivery status as soon as
    they're available.</p>

<a href="{{ route('dashboard.orders') }}" class="button">View Order Details</a>

<div class="divider"></div>

<p>Thank you for choosing Primrica Global Capital! We're honored to be part of your journey.</p>

<p>
    <strong>The Primrica Global Capital Sales Team</strong>
</p>
@endsection