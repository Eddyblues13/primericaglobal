<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title ?? 'TESLA' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            line-height: 1.6;
            color: #111827;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        .email-header {
            background: linear-gradient(135deg, #0b0c10 0%, #1f2937 100%);
            padding: 32px 24px;
            text-align: center;
        }
        
        .email-logo {
            margin-bottom: 8px;
            text-align: center;
        }

        .email-logo img {
            max-height: 75px;
            width: auto;
            max-width: 300px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        
        .email-body {
            padding: 40px 32px;
        }
        
        .email-content {
            color: #374151;
            font-size: 15px;
            line-height: 1.7;
        }
        
        .email-content h1 {
            color: #0b0c10;
            font-size: 24px;
            font-weight: 900;
            margin-bottom: 16px;
            letter-spacing: -0.01em;
        }
        
        .email-content h2 {
            color: #0b0c10;
            font-size: 20px;
            font-weight: 800;
            margin-top: 24px;
            margin-bottom: 12px;
        }
        
        .email-content p {
            margin-bottom: 16px;
        }
        
        .info-box {
            background: #f9fafb;
            border-left: 4px solid #0b0c10;
            padding: 16px 20px;
            margin: 24px 0;
            border-radius: 6px;
        }
        
        .info-box strong {
            color: #0b0c10;
            font-weight: 800;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .details-table tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        .details-table tr:last-child {
            border-bottom: none;
        }
        
        .details-table td {
            padding: 14px 20px;
            font-size: 14px;
        }
        
        .details-table td:first-child {
            font-weight: 700;
            color: #6b7280;
            width: 40%;
        }
        
        .details-table td:last-child {
            color: #111827;
            font-weight: 600;
        }
        
        .button {
            display: inline-block;
            padding: 14px 28px;
            background: #0b0c10;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 800;
            font-size: 14px;
            text-align: center;
            margin: 24px 0;
            transition: background 0.2s;
        }
        
        .button:hover {
            background: #1f2937;
        }
        
        .button-success {
            background: #10b981;
        }
        
        .button-success:hover {
            background: #059669;
        }
        
        .email-footer {
            background: #f9fafb;
            padding: 32px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .email-footer p {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 8px;
        }
        
        .email-footer a {
            color: #0b0c10;
            text-decoration: none;
            font-weight: 700;
        }
        
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 32px 0;
        }
        
        .highlight {
            color: #0b0c10;
            font-weight: 800;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 32px 24px;
            }
            
            .email-content h1 {
                font-size: 22px;
            }
            
            .details-table td {
                padding: 12px 16px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="email-logo">
                <img src="{{ url('images/logo.png') }}" alt="TESLA Logo" style="max-width: 300px; height: auto; display: block; margin: 0 auto;" />
            </div>
        </div>
        
        <div class="email-body">
            <div class="email-content">
                @yield('content')
            </div>
        </div>
        
        <div class="email-footer">
            <p><strong>TESLA</strong></p>
            <p>Automated Investments • Stock Trading • Premium Vehicles</p>
            <p style="margin-top: 16px;">
                <a href="{{ config('app.url') }}">Visit Dashboard</a> | 
                <a href="{{ config('app.url') }}/dashboard/support">Support</a>
            </p>
            <p style="margin-top: 16px; font-size: 12px; color: #9ca3af;">
                © {{ date('Y') }} TESLA. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
