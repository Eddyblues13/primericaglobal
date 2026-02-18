@extends('emails.layout')

@section('content')
<h1>Verify Your Email Address</h1>

<p>Hello <strong>{{ $user->name }}</strong>,</p>

<p>Thank you for registering on Primrica Global Capital! To complete your registration, please verify your email address
    using the code below.</p>

<div class="info-box"
    style="background: #f3f4f6; border-left: 4px solid #E31937; padding: 20px; margin: 24px 0; text-align: center;">
    <p style="margin: 0 0 8px 0; font-size: 14px; color: #6b7280;">Your Verification Code</p>
    <div
        style="font-size: 48px; font-weight: 900; letter-spacing: 12px; color: #0f1115; font-family: 'Courier New', monospace;">
        {{ $code }}
    </div>
    <p style="margin: 12px 0 0 0; font-size: 13px; color: #9ca3af;">This code will expire in 10 minutes</p>
</div>

<p>Please enter this code on the verification page to activate your account and start exploring Primrica Global Capital.
</p>

<div class="divider"></div>

<p style="font-size: 13px; color: #6b7280;">
    <strong>Security Note:</strong> If you didn't create an account on Primrica Global Capital, please ignore this
    email. This code will expire automatically.
</p>

<p>
    <strong>The Primrica Global Capital Team</strong>
</p>
@endsection