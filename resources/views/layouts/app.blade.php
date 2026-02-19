<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Primary Meta Tags -->
    <title>@yield('title', 'Primrica Global Capital Market Hub - Invest, Trade Stocks & Buy Premium Vehicles')</title>
    <meta name="title"
        content="@yield('meta_title', 'Primrica Global Capital Market Hub - Invest, Trade Stocks & Buy Premium Vehicles')" />
    <meta name="description"
        content="@yield('meta_description', 'Invest in automated plans, trade stocks in real-time, and purchase premium Primrica Global Capital vehicles. Your all-in-one platform for wealth building and luxury cars.')" />
    <meta name="keywords"
        content="@yield('meta_keywords', 'Primrica Global Capital investment, stock trading, premium vehicles, automated investing, wealth building, Primrica Global Capital cars, stock market, investment plans')" />
    <meta name="author" content="Primrica Global Capital Market Hub" />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title"
        content="@yield('og_title', 'Primrica Global Capital Market Hub - Invest, Trade Stocks & Buy Premium Vehicles')" />
    <meta property="og:description"
        content="@yield('og_description', 'Invest in automated plans, trade stocks in real-time, and purchase premium Primrica Global Capital vehicles. Your all-in-one platform for wealth building and luxury cars.')" />
    <meta property="og:image" content="@yield('og_image', asset('/images/og-default.jpg'))" />
    <meta property="og:site_name" content="Primrica Global Capital Market Hub" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title"
        content="@yield('twitter_title', 'Primrica Global Capital Market Hub - Invest, Trade Stocks & Buy Premium Vehicles')" />
    <meta property="twitter:description"
        content="@yield('twitter_description', 'Invest in automated plans, trade stocks in real-time, and purchase premium Primrica Global Capital vehicles. Your all-in-one platform for wealth building and luxury cars.')" />
    <meta property="twitter:image" content="@yield('twitter_image', asset('/images/og-default.jpg'))" />

    <!-- Theme Color -->
    <meta name="theme-color" content="#E31937" />
    <meta name="msapplication-TileColor" content="#E31937" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}" />

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')

    <style>
        /* Homepage popup banner (like your screenshot) */
        .sitePopup {
            position: fixed;
            left: 50%;
            bottom: 16px;
            transform: translateX(-50%);
            z-index: 9999;
            max-width: 340px;
            width: calc(100vw - 32px);
            background: #0b0c10;
            color: #fff;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .14);
            box-shadow: 0 18px 45px rgba(0, 0, 0, .35);
            padding: 12px 12px;
            display: none;
        }

        .sitePopup.show {
            display: block;
        }

        .sitePopupTop {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: start;
        }

        .sitePopupTitle {
            font-size: 13px;
            font-weight: 900;
            margin: 0;
        }

        .sitePopupMsg {
            margin-top: 2px;
            font-size: 13px;
            font-weight: 700;
            color: rgba(255, 255, 255, .82);
            line-height: 1.35;
        }

        .sitePopupAmt {
            font-size: 15px;
            font-weight: 900;
            color: #ffffff;
        }

        .sitePopupClose {
            width: 28px;
            height: 28px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(255, 255, 255, .06);
            color: #fff;
            cursor: pointer;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .sitePopupClose:hover {
            background: rgba(255, 255, 255, .10);
        }

        @media (max-width: 480px) {
            .sitePopup {
                left: 50%;
                bottom: 12px;
                transform: translateX(-50%);
                width: calc(100vw - 24px);
            }
        }
    </style>
    <!-- Smartsupp Live Chat script -->
    <script type="text/javascript">
        var _smartsupp = _smartsupp || {};
_smartsupp.key = 'e3123adb720afedc9fbb65a9a66935ee975f2d0f';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
    </script>
    <noscript>Powered by <a href="https://www.smartsupp.com" target="_blank">Smartsupp</a></noscript>


</head>

<body>
    @include('layouts.header')

    @include('layouts.mobile-menu')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    @include('layouts.scripts')

    <!-- Homepage popup banner -->
    <div class="sitePopup" id="sitePopup" aria-live="polite" aria-atomic="true">
        <div class="sitePopupTop">
            <div style="min-width:0;">
                <div class="sitePopupTitle" id="sitePopupTitle">Earning</div>
                <div class="sitePopupMsg" id="sitePopupMsg">Someone just earned $100</div>
            </div>
            <button type="button" class="sitePopupClose" id="sitePopupClose" aria-label="Close">✕</button>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Homepage rotating popup (fake activity like screenshot)
        (function () {
            const popup = document.getElementById('sitePopup');
            const titleEl = document.getElementById('sitePopupTitle');
            const msgEl = document.getElementById('sitePopupMsg');
            const closeBtn = document.getElementById('sitePopupClose');
            if (!popup || !titleEl || !msgEl || !closeBtn) return;

            const names = ['William', 'Sophia', 'Daniel', 'Amina', 'Noah', 'Olivia', 'Ethan', 'Mia', 'James', 'Fatima'];
            const countries = ['USA', 'CANADA', 'UK', 'GERMANY', 'NIGERIA', 'PAKISTAN', 'UAE', 'SOUTH AFRICA', 'INDIA', 'FRANCE'];

            function rand(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }
            function money(min, max) { return '$' + rand(min, max).toLocaleString('en-US'); }

            const templates = [
                () => ({ title: 'Earning', msg: `${names[rand(0, names.length-1)]} from ${countries[rand(0, countries.length-1)]} has just earned ${money(120, 8200)}` }),
                () => ({ title: 'Deposit', msg: `A user just deposited ${money(50, 5000)}` }),
                () => ({ title: 'Withdrawal', msg: `A user just requested a withdrawal of ${money(50, 7000)}` }),
                () => ({ title: 'Purchase', msg: `A user just purchased an item worth ${money(200, 12000)}` }),
            ];

            let closed = false;
            closeBtn.addEventListener('click', function () {
                closed = true;
                popup.classList.remove('show');
            });

            function showOnce() {
                if (closed) return;
                const t = templates[rand(0, templates.length-1)]();
                titleEl.textContent = t.title;
                // emphasize the amount (first $... pattern)
                msgEl.innerHTML = String(t.msg).replace(/(\$[0-9][0-9,]*)/, '<span class="sitePopupAmt">$1</span>');
                popup.classList.add('show');
                setTimeout(() => popup.classList.remove('show'), 9000);
            }

            // first popup after a short delay, then rotate
            setTimeout(showOnce, 2500);
            setInterval(showOnce, 20000);
        })();
    </script>

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Primrica Global Capital Market Hub",
        "url": "{{ config('app.url') }}",
        "logo": "{{ asset('/images/logo.png') }}",
        "description": "Invest in automated plans, trade stocks in real-time, and purchase premium Primrica Global Capital vehicles.",
        "sameAs": [
            "https://www.facebook.com/Primrica Global Capitalmarkethub",
            "https://www.twitter.com/Primrica Global Capitalmarkethub",
            "https://www.instagram.com/Primrica Global Capitalmarkethub"
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+1-800-Primrica Global Capital-HUB",
            "contactType": "Customer Service"
        }
    }
    </script>
</body>

</html>