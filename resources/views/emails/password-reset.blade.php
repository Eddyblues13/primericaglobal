@extends('emails.layout')

@section('content')
<h1 style="font-size: 28px; font-weight: 900; color: #0f1115; margin: 0 0 16px 0; letter-spacing: -0.02em;">
    Reset Your Password
</h1>

<p style="font-size: 16px; line-height: 1.6; color: #0f1115; margin: 0 0 24px 0;">
    Hello {{ $user->name }},
</p>

<p style="font-size: 16px; line-height: 1.6; color: #0f1115; margin: 0 0 24px 0;">
    We received a request to reset your password for your Primrica Global Capital account. Click the button below to
    create a new password:
</p>

<table width="100%" cellpadding="0" cellspacing="0" style="margin: 32px 0;">
    <tr>
        <td align="center">
            <a href="{{ $resetUrl }}"
                style="display: inline-block; padding: 14px 32px; background-color: #0f1115; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 900; letter-spacing: -0.01em;">
                Reset Password
            </a>
        </td>
    </tr>
</table>

<p style="font-size: 14px; line-height: 1.6; color: #666666; margin: 0 0 16px 0;">
    Or copy and paste this link into your browser:
</p>

<p style="font-size: 14px; line-height: 1.6; color: #0066cc; margin: 0 0 24px 0; word-break: break-all;">
    {{ $resetUrl }}
</p>

<div style="border-top: 1px solid #e5e5e5; padding-top: 24px; margin-top: 32px;">
    <p style="font-size: 14px; line-height: 1.6; color: #666666; margin: 0 0 16px 0;">
        <strong>Important:</strong> This password reset link will expire in 60 minutes for security reasons.
    </p>

    <p style="font-size: 14px; line-height: 1.6; color: #666666; margin: 0;">
        If you didn't request a password reset, you can safely ignore this email. Your password will remain unchanged.
    </p>
</div>
@endsection