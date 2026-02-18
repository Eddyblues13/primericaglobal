<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin Dashboard - Primrica Global Capital')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap");

        :root {
            --bg: #0b0c10;
            --panel: #0f1116;
            --panel2: #141722;
            --card: #0e1016;
            --border: rgba(255, 255, 255, .10);
            --muted: rgba(255, 255, 255, .55);
            --muted2: rgba(255, 255, 255, .40);
            --text: #ffffff;
            --white: #ffffff;
            --sidebar: #07080b;
            --sidebar2: #0a0b10;
            --accent: #ffffff;
            --green: #34d399;
            --red: #fb7185;
            --blue: #60a5fa;
            --amber: #f59e0b;
            --violet: #a78bfa;
            --cyan: #22d3ee;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: linear-gradient(180deg, #07080b, #0b0c10 30%, #0b0c10);
            color: var(--text);
            overflow-x: hidden;
        }

        /* layout */
        .app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 260px 1fr;
        }

        @media (max-width: 1024px) {
            .app {
                grid-template-columns: 1fr;
            }
        }

        /* sidebar */
        .sidebar {
            background: radial-gradient(800px 500px at 50% 0%, rgba(255, 255, 255, .06), transparent 60%),
                linear-gradient(180deg, var(--sidebar), var(--sidebar2));
            border-right: 1px solid rgba(255, 255, 255, .08);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, .15) transparent;
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .12);
            border-radius: 10px;
        }

        .wordmark {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 18px 10px;
            user-select: none;
        }

        .wordmark img {
            height: 60px;
            width: auto;
            object-fit: contain;
        }

        .wordmark span {
            font-weight: 900;
            letter-spacing: .28em;
            font-size: 18px;
            color: #fff;
            opacity: .95;
        }

        .userBox {
            margin: 10px 14px 14px;
            padding: 12px;
            border-radius: 14px;
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .08);
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .10);
            display: grid;
            place-items: center;
            flex: 0 0 auto;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 800;
            padding: 5px 8px;
            border-radius: 999px;
            background: rgba(96, 165, 250, .10);
            border: 1px solid rgba(96, 165, 250, .25);
            color: rgba(96, 165, 250, .95);
            margin-top: 8px;
            width: max-content;
        }

        .nav {
            padding: 6px 10px 14px;
        }

        .nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: 10px;
            color: rgba(255, 255, 255, .62);
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
            transition: all .15s ease;
            -webkit-tap-highlight-color: transparent;
        }

        @media (max-width: 768px) {
            .nav a {
                padding: 14px 12px;
                font-size: 14px;
                min-height: 48px;
            }
        }

        .nav a:hover {
            background: rgba(255, 255, 255, .05);
            color: rgba(255, 255, 255, .82);
        }

        .nav a.active {
            background: #fff;
            color: #0f1116;
        }

        .nav a.active svg {
            color: #0f1116;
            opacity: 1;
        }

        .nav svg {
            width: 16px;
            height: 16px;
            opacity: .85;
        }

        .navSection {
            margin-top: 8px;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, .07);
        }

        .logout {
            margin-top: auto;
            padding: 14px 12px;
            border-top: 1px solid rgba(255, 255, 255, .07);
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: rgba(255, 255, 255, .55);
            font-size: 12px;
            font-weight: 800;
        }

        .logoutBtn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .08);
            color: rgba(255, 255, 255, .75);
            cursor: pointer;
            transition: all .15s ease;
        }

        .logoutBtn:hover {
            background: rgba(255, 255, 255, .06);
        }

        /* content */
        .content {
            min-height: 100vh;
            background: #f8f8f8;
        }

        .topbar {
            height: 58px;
            background: #fff;
            color: #0f1116;
            border-bottom: 1px solid rgba(0, 0, 0, .08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .topLeft {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .hamb {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, .10);
            background: #fff;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            -webkit-tap-highlight-color: transparent;
        }

        @media (max-width: 768px) {
            .hamb {
                min-width: 44px;
                min-height: 44px;
            }
        }

        .topTitle {
            font-weight: 900;
            font-size: 14px;
            color: #111827;
            letter-spacing: -0.01em;
        }

        .topRight {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profileIcon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e5e7eb;
            border: 1px solid rgba(0, 0, 0, .10);
            display: grid;
            place-items: center;
            cursor: pointer;
        }

        @media (max-width:1024px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                width: 260px;
                transform: translateX(-105%);
                transition: transform .2s ease;
                z-index: 60;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .hamb {
                display: flex;
            }

            .overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .55);
                z-index: 55;
                display: none;
            }

            .overlay.show {
                display: block;
            }
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .wrap {
                padding: 12px 12px 20px;
            }

            .topbar {
                padding: 0 12px;
                height: 54px;
            }

            .topTitle {
                font-size: 13px;
            }

            .statGrid {
                grid-template-columns: 1fr !important;
                gap: 10px;
            }

            .stat {
                padding: 10px;
                min-height: 60px;
            }

            .stat strong {
                font-size: 13px;
            }

            .stat small {
                font-size: 9px;
            }

            .stat .sub {
                font-size: 10px;
            }

            .quickGrid {
                grid-template-columns: 1fr !important;
                gap: 10px;
            }

            .quick {
                padding: 12px;
                min-height: auto;
            }

            .quick h4 {
                font-size: 11px;
            }

            .quick p {
                font-size: 10px;
            }

            .quick svg {
                width: 14px;
                height: 14px;
            }

            .whitePanel {
                border-radius: 12px;
            }

            .panelHead {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                padding: 10px 12px;
            }

            .panelHead h5 {
                font-size: 11px;
            }

            .panelHead small {
                font-size: 10px;
            }

            /* Tables - Convert to cards on mobile */
            .whitePanel table {
                display: block;
                width: 100%;
            }

            .whitePanel thead {
                display: none;
            }

            .whitePanel tbody {
                display: block;
                width: 100%;
            }

            .whitePanel tbody tr {
                display: block;
                margin-bottom: 12px;
                background: #fff;
                border: 1px solid rgba(0, 0, 0, .08);
                border-radius: 12px;
                padding: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
            }

            .whitePanel tbody td {
                display: block;
                padding: 8px 0;
                text-align: left !important;
                border: none;
                border-bottom: 1px solid rgba(0, 0, 0, .06);
            }

            .whitePanel tbody td:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .whitePanel tbody td:first-child {
                padding-top: 0;
            }

            .whitePanel tbody td[data-label]:before {
                content: attr(data-label) ": ";
                font-weight: 900;
                font-size: 10px;
                color: #6b7280;
                display: inline-block;
                margin-right: 6px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .whitePanel tbody td[data-label=""]:before {
                content: "";
            }

            /* Forms */
            .whitePanel form {
                padding: 12px !important;
            }

            .whitePanel input[type="text"],
            .whitePanel input[type="email"],
            .whitePanel input[type="number"],
            .whitePanel input[type="password"],
            .whitePanel select,
            .whitePanel textarea {
                font-size: 14px !important;
                padding: 10px 12px !important;
            }

            .whitePanel label {
                font-size: 11px !important;
            }

            /* Buttons */
            .whitePanel button,
            .whitePanel .btn,
            .whitePanel a[style*="padding"] {
                padding: 10px 16px !important;
                font-size: 12px !important;
                width: 100%;
                margin-bottom: 8px;
            }

            /* Action buttons in tables */
            .whitePanel .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 8px;
                margin-top: 8px;
            }

            .whitePanel .action-buttons a,
            .whitePanel .action-buttons button {
                width: 100%;
                text-align: center;
            }

            /* Search forms */
            .whitePanel form[method="GET"] {
                flex-direction: column;
            }

            .whitePanel form[method="GET"]>div {
                width: 100%;
            }

            /* Lower grid */
            .lowerGrid {
                grid-template-columns: 1fr !important;
            }

            /* Pagination */
            .pagination {
                flex-direction: column;
                gap: 8px;
                align-items: stretch;
            }

            .pagination a,
            .pagination span {
                width: 100%;
                text-align: center;
            }

            /* Modals */
            .modal {
                padding: 12px;
            }

            .modal-content {
                width: 95% !important;
                max-width: 95% !important;
                padding: 16px !important;
            }

            /* Search forms - stack vertically */
            form[method="GET"]>div[style*="display: flex"] {
                flex-direction: column !important;
            }

            form[method="GET"]>div[style*="display: flex"]>div {
                width: 100% !important;
                min-width: 100% !important;
            }

            /* Alerts */
            .alert {
                padding: 10px 12px !important;
                font-size: 12px !important;
            }

            /* Status badges */
            .status {
                font-size: 9px !important;
                padding: 3px 6px !important;
            }
        }

        @media (max-width: 480px) {
            .wrap {
                padding: 10px 10px 16px;
            }

            .stat {
                padding: 8px;
            }

            .quick {
                padding: 10px;
            }

            .panelHead {
                padding: 8px 10px;
            }

            .whitePanel form {
                padding: 10px !important;
            }

            /* Grid layouts in forms */
            .whitePanel form>div[style*="grid"] {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }

            /* Action buttons stack vertically */
            .action-buttons {
                flex-direction: column !important;
            }

            .action-buttons>* {
                width: 100% !important;
            }

            /* Panel head buttons */
            .panelHead a,
            .panelHead button {
                width: 100%;
                margin-top: 8px;
                text-align: center;
            }

            /* Touch-friendly buttons */
            button,
            .btn,
            a[style*="padding"] {
                min-height: 44px;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            }

            /* Input fields */
            input,
            select,
            textarea {
                min-height: 44px;
                font-size: 16px !important;
                /* Prevents zoom on iOS */
            }

            /* Wordmark and user box */
            .wordmark {
                padding: 14px 14px 8px;
            }

            .userBox {
                margin: 8px 12px 12px;
                padding: 10px;
            }

            /* Sidebar navigation */
            .nav {
                padding: 4px 8px 12px;
            }

            .navSection {
                margin-top: 12px;
                padding-top: 12px;
            }
        }

        /* inner layout */
        .wrap {
            padding: 18px 18px 30px;
        }

        .surface {
            border-radius: 18px;
            background: transparent;
            border: none;
            overflow: visible;
        }

        .statGrid {
            margin-top: 12px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        @media (max-width: 1100px) {
            .statGrid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 520px) {
            .statGrid {
                grid-template-columns: 1fr;
            }
        }

        .stat {
            border-radius: 14px;
            background: #fff;
            color: #0f1116;
            border: 1px solid rgba(0, 0, 0, .08);
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            min-height: 64px;
        }

        .stat small {
            display: block;
            font-size: 10px;
            font-weight: 900;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .stat strong {
            font-size: 14px;
            font-weight: 900;
            letter-spacing: -.01em;
        }

        .stat .sub {
            font-size: 11px;
            font-weight: 900;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .chip {
            width: 28px;
            height: 28px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(0, 0, 0, .10);
            background: #f9fafb;
        }

        .quickGrid {
            margin-top: 12px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        @media (max-width: 1100px) {
            .quickGrid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 520px) {
            .quickGrid {
                grid-template-columns: 1fr;
            }
        }

        .quick {
            border-radius: 14px;
            background: #fff;
            color: #0f1116;
            border: 1px solid rgba(0, 0, 0, .08);
            padding: 14px;
            min-height: 78px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .quick h4 {
            margin: 0;
            font-size: 12px;
            font-weight: 900;
            color: #111827;
        }

        .quick p {
            margin: 6px 0 0;
            font-size: 11px;
            color: #6b7280;
            font-weight: 700;
            line-height: 1.35;
        }

        .lowerGrid {
            margin-top: 14px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 12px;
        }

        @media (max-width: 1100px) {
            .lowerGrid {
                grid-template-columns: 1fr;
            }
        }

        .whitePanel {
            background: #fff;
            color: #0f1116;
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 14px;
            overflow: hidden;
        }

        .panelHead {
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
        }

        .panelHead h5 {
            margin: 0;
            font-size: 12px;
            font-weight: 900;
            color: #111827;
        }

        .panelHead small {
            display: block;
            margin-top: 3px;
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
        }

        .viewAll {
            font-size: 11px;
            font-weight: 900;
            color: #111827;
            opacity: .7;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .viewAll:hover {
            opacity: 1;
        }

        .orderRow {
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .orderRow+.orderRow {
            border-top: 1px solid rgba(0, 0, 0, .06);
        }

        .orderLeft {
            display: flex;
            gap: 10px;
            align-items: center;
            min-width: 0;
        }

        .thumb {
            width: 44px;
            height: 34px;
            border-radius: 10px;
            background: #f3f4f6;
            border: 1px solid rgba(0, 0, 0, .08);
            overflow: hidden;
            flex: 0 0 auto;
        }

        .orderTitle {
            font-size: 12px;
            font-weight: 900;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 280px;
        }

        .orderMeta {
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
            margin-top: 2px;
        }

        .orderRight {
            text-align: right;
            flex: 0 0 auto;
        }

        .price {
            font-size: 12px;
            font-weight: 900;
            color: #111827;
        }

        .status {
            margin-top: 4px;
            font-size: 10px;
            font-weight: 900;
            padding: 4px 8px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status.ok {
            background: rgba(52, 211, 153, .14);
            color: rgba(16, 185, 129, .95);
            border: 1px solid rgba(16, 185, 129, .25);
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="overlay" id="overlay"></div>

    <div class="app">
        <!-- ================= SIDEBAR ================= -->
        <aside class="sidebar" id="sidebar">
            <div class="wordmark">
                <img src="{{ asset('images/logo.png') }}" alt="Primrica Global Capital Logo" />
                <span style="font-size: 11px; opacity: .6; margin-left: 8px;">ADMIN</span>
            </div>

            <div class="userBox">
                <div class="avatar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        style="color:rgba(255,255,255,.85)" stroke-width="2">
                        <path d="M12 2v2" />
                        <path d="M8 4h8" />
                        <rect x="6" y="6" width="12" height="10" rx="3" />
                        <path d="M8 16v2" />
                        <path d="M16 16v2" />
                        <path d="M9 10h.01M15 10h.01" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <div class="text-[12px] font-[900] text-white/85 leading-tight truncate">
                        {{ auth()->guard('admin')->user()->name ?? 'Admin' }}
                    </div>
                    <div class="text-[11px] font-[700] text-white/45 truncate">
                        {{ auth()->guard('admin')->user()->email ?? '' }}
                    </div>
                    <div class="pill">
                        <span class="inline-block w-2 h-2 rounded-full" style="background:rgba(96,165,250,.95)"></span>
                        Administrator
                    </div>
                </div>
            </div>

            <nav class="nav" id="nav">
                <a href="{{ route('admin.dashboard') }}" @if(Route::currentRouteName()=='admin.dashboard' )
                    class="active" @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12l2-2 7-7 7 7 2 2" />
                        <path d="M5 10v10h14V10" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.users') }}" @if(Route::currentRouteName()=='admin.users' ) class="active"
                    @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21a8 8 0 1 0-16 0" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Users
                </a>

                <a href="{{ route('admin.orders') }}" @if(Route::currentRouteName()=='admin.orders' ) class="active"
                    @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 6h15l-1.5 9h-13z" />
                        <path d="M6 6l-2 0" />
                        <path d="M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                        <path d="M18 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                    </svg>
                    Orders
                </a>

                <a href="{{ route('admin.transactions') }}" @if(Route::currentRouteName()=='admin.transactions' )
                    class="active" @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2" />
                        <path d="M21 12h-7a2 2 0 0 0 0 4h7" />
                    </svg>
                    Transactions
                </a>

                <a href="{{ route('admin.payment-settings') }}" @if(Route::currentRouteName()=='admin.payment-settings'
                    ) class="active" @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                    Payment Settings
                </a>

                <a href="{{ route('admin.kyc') }}" @if(Route::currentRouteName()=='admin.kyc' ) class="active" @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3l8 4v6c0 5-3 8-8 8s-8-3-8-8V7z" />
                        <path d="M9 12l2 2 4-4" />
                    </svg>
                    KYC Submissions
                </a>

                <a href="{{ route('admin.support') }}" @if(Route::currentRouteName()=='admin.support' ) class="active"
                    @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                        <path d="M8 12h.01M12 12h.01M16 12h.01" />
                    </svg>
                    Support Tickets
                </a>

                <div class="navSection">
                    <a href="{{ route('admin.inventory') }}" @if(Route::currentRouteName()=='admin.inventory' )
                        class="active" @endif>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12h18" />
                            <path d="M6 12l3-7h6l3 7" />
                            <path d="M7 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                            <path d="M17 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                        </svg>
                        Inventory
                    </a>

                    <a href="{{ route('admin.investment-plans') }}"
                        @if(Route::currentRouteName()=='admin.investment-plans' ) class="active" @endif>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 17l6-6 4 4 8-8" />
                            <path d="M21 7v6h-6" />
                        </svg>
                        Investment Plans
                    </a>

                    <a href="{{ route('admin.stocks') }}" @if(Route::currentRouteName()=='admin.stocks' ) class="active"
                        @endif>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19V5" />
                            <path d="M4 19h16" />
                            <path d="M8 15V9" />
                            <path d="M12 15V7" />
                            <path d="M16 15v-5" />
                        </svg>
                        Stocks
                    </a>
                </div>
            </nav>

            <div class="logout">
                <span>Logout</span>
                <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logoutBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            style="opacity:.9" stroke-width="2">
                            <path d="M10 17l5-5-5-5" />
                            <path d="M15 12H3" />
                            <path d="M21 21V3" />
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ================= CONTENT ================= -->
        <main class="content">
            <div class="topbar">
                <div class="topLeft">
                    <button class="hamb" id="hamb" aria-label="Open sidebar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"
                            stroke-linecap="round">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="topTitle">@yield('topTitle', 'Admin Dashboard')</div>
                </div>

                <div class="topRight">
                    <div class="profileIcon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                            <path d="M20 21a8 8 0 1 0-16 0" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                </div>
            </div>

            @yield('content')

        </main>
    </div>

    <script>
        // Sidebar toggle (mobile)
        const sidebar = document.getElementById("sidebar");
        const hamb = document.getElementById("hamb");
        const overlay = document.getElementById("overlay");

        function openSidebar() {
            sidebar.classList.add("open");
            overlay.classList.add("show");
        }
        function closeSidebar() {
            sidebar.classList.remove("open");
            overlay.classList.remove("show");
        }
        hamb?.addEventListener("click", openSidebar);
        overlay?.addEventListener("click", closeSidebar);

        // Active nav state
        const nav = document.getElementById("nav");
        nav?.addEventListener("click", (e) => {
            const a = e.target.closest("a[data-page]");
            if (!a) return;
            nav.querySelectorAll("a").forEach(x => x.classList.remove("active"));
            a.classList.add("active");

            // mobile auto-close
            if (window.innerWidth <= 1024) closeSidebar();
        });

        // Close sidebar if resizing to desktop
        window.addEventListener("resize", () => {
            if (window.innerWidth > 1024) closeSidebar();
        });

        // Add data-label attributes to table cells from headers for mobile view
        document.addEventListener('DOMContentLoaded', function() {
            const tables = document.querySelectorAll('.whitePanel table');
            tables.forEach(table => {
                const headers = table.querySelectorAll('thead th');
                const rows = table.querySelectorAll('tbody tr');
                
                headers.forEach((header, index) => {
                    const label = header.textContent.trim();
                    rows.forEach(row => {
                        const cell = row.querySelectorAll('td')[index];
                        if (cell && !cell.hasAttribute('data-label')) {
                            cell.setAttribute('data-label', label);
                        }
                    });
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>