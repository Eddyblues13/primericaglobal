<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Primrica Global Capital</title>
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

        .email-content ul {
            margin-left: 20px;
            margin-bottom: 24px;
            color: #374151;
        }

        .email-content li {
            margin-bottom: 8px;
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

        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 32px 24px;
            }

            .email-content h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="email-logo">
                <img src="{{ url('images/logo.png') }}" alt="Primrica Global Capital Logo"
                    style="max-width: 300px; height: auto; display: block; margin: 0 auto;" />
            </div>
        </div>

        <div class="email-body">
            <div class="email-content">
                {!! nl2br(e($body)) !!}
            </div>
        </div>

        <div class="email-footer">
            <p><strong>Primrica Global Capital</strong></p>
            <p>Automated Investments • Stock Trading • Premium Vehicles</p>
            <p style="margin-top: 16px;">
                <a href="{{ config('app.url') }}">Visit Dashboard</a> |
                <a href="{{ config('app.url') }}/dashboard/support">Support</a>
            </p>
            <p style="margin-top: 16px; font-size: 12px; color: #9ca3af;">
                © {{ date('Y') }} Primrica Global Capital. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>