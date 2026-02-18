@extends('emails.layout')

@section('content')
<h1>Support Ticket Created</h1>

<p>Hello <strong>{{ $user->name }}</strong>,</p>

<p>Your support ticket has been successfully created. Our team will review your request and get back to you within 24 hours.</p>

<table class="details-table">
    <tr>
        <td>Ticket Number</td>
        <td><strong>{{ $ticket->ticket_number }}</strong></td>
    </tr>
    <tr>
        <td>Subject</td>
        <td>{{ $ticket->subject }}</td>
    </tr>
    <tr>
        <td>Category</td>
        <td style="text-transform: capitalize;">{{ $ticket->category }}</td>
    </tr>
    <tr>
        <td>Status</td>
        <td><span class="status-badge status-pending">{{ $ticket->status }}</span></td>
    </tr>
    <tr>
        <td>Created</td>
        <td>{{ $ticket->created_at->format('F d, Y \a\t g:i A') }}</td>
    </tr>
</table>

<div class="info-box">
    <strong>Your Message:</strong><br>
    {{ $ticket->message }}
</div>

<p>We'll review your request and respond as soon as possible. You can track the status of your ticket in your dashboard.</p>

<a href="{{ route('dashboard.support') }}" class="button">View Support Tickets</a>

<div class="divider"></div>

<p>Thank you for contacting TESLA Support. We're here to help!</p>

<p>
    <strong>The TESLA Support Team</strong>
</p>
@endsection
