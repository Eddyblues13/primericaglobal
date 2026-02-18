<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Too Many Requests | TESLA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background: linear-gradient(180deg, #07080b, #0b0c10 30%, #0b0c10);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="text-center px-4" style="max-width: 600px; width: 100%;">
        <div style="font-size: 120px; font-weight: 900; line-height: 1; color: #fff; margin-bottom: 20px; letter-spacing: -0.02em;">
            429
        </div>
        <h1 style="font-size: 32px; font-weight: 900; color: #fff; margin-bottom: 12px; letter-spacing: -0.01em;">
            Too Many Requests
        </h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.66); margin-bottom: 32px; line-height: 1.6;">
            You've made too many requests. Please wait a moment and try again.
        </p>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <button onclick="setTimeout(() => window.location.reload(), 2000)" style="padding: 12px 24px; background: #111827; color: white; border-radius: 10px; font-size: 14px; font-weight: 900; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                </svg>
                Wait & Retry
            </button>
            <a href="{{ route('home') }}" style="padding: 12px 24px; background: rgba(255, 255, 255, 0.1); color: white; border-radius: 10px; font-size: 14px; font-weight: 900; text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.2); display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <path d="M9 22V12h6v10" />
                </svg>
                Go Home
            </a>
        </div>
    </div>
</body>
</html>
