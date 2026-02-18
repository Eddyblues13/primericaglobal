@extends('emails.layout')

@section('content')
<h1>New Login to Your Account</h1>

<p>Hello <strong>{{ $user->name }}</strong>,</p>

<p>We detected a new login to your Primrica Global Capital account. If this was you, you can safely ignore this email.
    If you don't recognize this activity, please secure your account immediately.</p>

<div class="info-box" style="background: #f3f4f6; border-left: 4px solid #E31937; padding: 20px; margin: 24px 0;">
    <h2 style="margin: 0 0 16px 0; font-size: 16px; color: #0f1115;">Login Details</h2>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 12px 8px 0; font-weight: 600; color: #6b7280; width: 140px;">
                <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 6px;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Time
            </td>
            <td style="padding: 8px 0; color: #0f1115; font-weight: 500;">
                {{ $loginData['time'] }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 12px 8px 0; font-weight: 600; color: #6b7280;">
                <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 6px;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                IP Address
            </td>
            <td style="padding: 8px 0; color: #0f1115; font-weight: 500; font-family: 'Courier New', monospace;">
                {{ $loginData['ip'] }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 12px 8px 0; font-weight: 600; color: #6b7280;">
                <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 6px;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Location
            </td>
            <td style="padding: 8px 0; color: #0f1115; font-weight: 500;">
                {{ $loginData['location']['city'] }}, {{ $loginData['location']['region'] }}, {{
                $loginData['location']['country'] }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 12px 8px 0; font-weight: 600; color: #6b7280;">
                <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 6px;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Browser
            </td>
            <td style="padding: 8px 0; color: #0f1115; font-weight: 500;">
                {{ $loginData['browser'] }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 12px 8px 0; font-weight: 600; color: #6b7280;">
                <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 6px;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
                Operating System
            </td>
            <td style="padding: 8px 0; color: #0f1115; font-weight: 500;">
                {{ $loginData['os'] }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 12px 8px 0; font-weight: 600; color: #6b7280;">
                <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 6px;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Device Type
            </td>
            <td style="padding: 8px 0; color: #0f1115; font-weight: 500;">
                {{ $loginData['device'] }}
            </td>
        </tr>
    </table>
</div>

<div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; margin: 24px 0; border-radius: 4px;">
    <p style="margin: 0; font-size: 14px; color: #991b1b;">
        <strong>⚠️ Didn't recognize this login?</strong><br>
        <span style="color: #7f1d1d;">If you did not perform this login, your account may have been compromised. Please
            change your password immediately and contact our support team.</span>
    </p>
</div>

<a href="{{ route('dashboard.index') }}" class="button" style="display: inline-block; margin: 16px 0;">Go to
    Dashboard</a>

<div class="divider"></div>

<p style="font-size: 13px; color: #6b7280;">
    This is an automated security notification to help protect your account. We send these emails every time there's a
    login to your Primrica Global Capital account.
</p>

<p>
    <strong>The Primrica Global Capital Team</strong>
</p>
@endsection