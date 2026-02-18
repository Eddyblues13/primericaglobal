@extends('layouts.app')

@section('title', 'TESLA Market Hub - Invest, Trade Stocks & Buy Premium Tesla Vehicles')
@section('meta_title', 'TESLA Market Hub - Invest, Trade Stocks & Buy Premium Vehicles')
@section('meta_description', 'Invest in automated plans with daily returns, trade stocks in real-time, and purchase premium Tesla vehicles. Your all-in-one platform for wealth building and luxury electric cars.')
@section('meta_keywords', 'tesla investment, stock trading, tesla vehicles, automated investing, wealth building, tesla cars, stock market, investment plans, electric vehicles, tesla model s, model 3, model x, model y')

@section('og_title', 'TESLA Market Hub - Invest, Trade & Drive Electric')
@section('og_description', 'Your all-in-one platform for automated investments, real-time stock trading, and premium Tesla vehicle purchases.')
@section('og_type', 'website')

@section('content')

    <!-- HERO (THIS PART IS THE "FIRST TOP" YOU POINTED AT) -->
    <section class="hero">
        <div class="wrap heroInner">
            <div class="grid grid-cols-12 gap-6 items-start">
                <!-- LEFT -->
                <div class="col-span-12 lg:col-span-7">
                    <h1 class="h1">Invest. <span class="text-[#E31937]">Trade.</span> Drive.</h1>
                    <p class="lead">
                        All-in-one platform for crypto wallet funding, automated investments, <span class="text-[#E31937] font-[700]">live stocks</span>, and premium
                        EV inventory.
                    </p>

                    <div class="btnRow">
                        @auth
                            <a href="{{ route('dashboard.investments') }}" class="btn primary" style="background: #E31937; border-color: #E31937;">
                        @else
                            <a href="{{ route('invest') }}" class="btn primary" style="background: #E31937; border-color: #E31937;">
                        @endauth
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 17l6-6 4 4 8-8" />
                                <path d="M21 7v6h-6" />
                            </svg>
                            Start Investing
                        </a>

                        <a href="{{ route('stocks') }}" class="btn outline hover:border-[#E31937] hover:text-[#E31937] transition-colors">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19V5" />
                                <path d="M4 19h16" />
                                <path d="M8 15l3-3 3 3 6-6" />
                            </svg>
                            Explore Stocks
                        </a>

                        <a href="{{ route('inventory') }}" class="btn outline hover:border-[#E31937] hover:text-[#E31937] transition-colors">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 12h18" />
                                <path d="M6 12l3-7h6l3 7" />
                                <path d="M7 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                <path d="M17 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                            </svg>
                            View Inventory
                        </a>
                    </div>

                    <!-- small 3 cards row -->
                    <div class="miniGrid">
                        <div class="glass soft miniCard hover:border-[#E31937]/50 transition-colors">
                            <div class="miniIcon" style="color: #E31937;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M4 19V5" />
                                    <path d="M4 19h16" />
                                    <path d="M7 14l3-3 3 3 6-6" />
                                </svg>
                            </div>
                            <div class="miniTitle">Live Stocks</div>
                            <div class="miniWord" style="color: #E31937;">Realtime</div>
                        </div>

                        <div class="glass soft miniCard hover:border-[#E31937]/50 transition-colors">
                            <div class="miniIcon" style="color: #E31937;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M20 7H4" />
                                    <path d="M20 12H4" />
                                    <path d="M20 17H4" />
                                </svg>
                            </div>
                            <div class="miniTitle">Wallet</div>
                            <div class="miniWord" style="color: #E31937;">Crypto</div>
                        </div>

                        <div class="glass soft miniCard hover:border-[#E31937]/50 transition-colors">
                            <div class="miniIcon" style="color: #E31937;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M3 13l2-2 4 4L19 5l2 2-12 12-6-6Z" />
                                </svg>
                            </div>
                            <div class="miniTitle">EV Inventory</div>
                            <div class="miniWord" style="color: #E31937;">Premium</div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-span-12 lg:col-span-5">
                    <div class="glass p-4 rounded-[18px] border border-[#E31937]/20">
                        <!-- Elon Musk Video -->
                        <div class="relative w-full rounded-[12px] overflow-hidden mb-4 ring-2 ring-[#E31937]/30" style="aspect-ratio: 16/9; background: #000;">
                            <video 
                                id="heroVideo"
                                class="w-full h-full object-cover" 
                                controls 
                                autoplay 
                                muted 
                                loop
                                playsinline
                                preload="auto"
                                poster="{{ asset('images/logo.png') }}">
                                <source src="{{ asset('videos/tesla1.mp4') }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const video = document.getElementById('heroVideo');
                                if (video) {
                                    // Set video to play automatically
                                    video.muted = true;
                                    
                                    // Try to play immediately
                                    const tryPlay = () => {
                                        const playPromise = video.play();
                                        if (playPromise !== undefined) {
                                            playPromise
                                                .then(() => {
                                                    console.log('Video is playing');
                                                })
                                                .catch(error => {
                                                    console.log('Autoplay prevented, will try on user interaction:', error);
                                                    // Try to play when user interacts with page
                                                    const playOnInteraction = () => {
                                                        video.play().catch(e => console.log('Play failed:', e));
                                                    };
                                                    document.addEventListener('click', playOnInteraction, { once: true });
                                                    document.addEventListener('touchstart', playOnInteraction, { once: true });
                                                });
                                        }
                                    };
                                    
                                    // Try to play when video can play
                                    if (video.readyState >= 2) {
                                        tryPlay();
                                    } else {
                                        video.addEventListener('canplay', tryPlay, { once: true });
                                        video.addEventListener('loadeddata', tryPlay, { once: true });
                                    }
                                    
                                    // Also try on load
                                    video.addEventListener('loadstart', tryPlay);
                                }
                            });
                        </script>
                        
                        <!-- Write-up under video -->
                        <div class="text-white/90">
                            <h3 class="text-[20px] font-[900] mb-3 text-white">Vision for the Future <span class="text-[#E31937]">•</span></h3>
                            <p class="text-[14px] leading-relaxed text-white/80">
                                "Our mission is to accelerate the world's transition to sustainable energy. Through innovative technology, 
                                we're not just building electric vehicles—we're creating a sustainable energy ecosystem that will 
                                transform how we power our world. Every Tesla vehicle, every solar panel, every battery we produce 
                                brings us closer to a zero-emission future."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick actions strip (dark, same glass style) -->
        <div class="wrap pt-20 pb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Wallet -->
                <div class="glass soft p-6 flex items-center justify-between min-h-[86px] hover:border-[#E31937]/30 transition-colors">
                    <div>
                        <div class="text-[12px] font-[700] text-white/55 mb-1">Wallet</div>
                        <div class="text-[14px] font-[900] text-white/90">Fund or withdraw</div>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl border border-[#E31937]/30 bg-black/20 grid place-items-center text-[#E31937]">
                        <!-- wallet icon -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2" />
                            <path d="M21 12h-7a2 2 0 0 0 0 4h7" />
                        </svg>
                    </div>
                </div>

                <!-- Investments -->
                <div class="glass soft p-6 flex items-center justify-between min-h-[86px] hover:border-[#E31937]/30 transition-colors">
                    <div>
                        <div class="text-[12px] font-[700] text-white/55 mb-1">Investments</div>
                        <div class="text-[14px] font-[900] text-white/90">Create a plan</div>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl border border-[#E31937]/30 bg-black/20 grid place-items-center text-[#E31937]">
                        <!-- trend icon -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 17l6-6 4 4 8-8" />
                            <path d="M21 7v6h-6" />
                        </svg>
                    </div>
                </div>

                <!-- Stocks -->
                <div class="glass soft p-6 flex items-center justify-between min-h-[86px] hover:border-[#E31937]/30 transition-colors">
                    <div>
                        <div class="text-[12px] font-[700] text-white/55 mb-1">Stocks</div>
                        <div class="text-[14px] font-[900] text-white/90">Market overview</div>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl border border-[#E31937]/30 bg-black/20 grid place-items-center text-[#E31937]">
                        <!-- chart icon -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M4 19V5" />
                            <path d="M4 19h16" />
                            <path d="M8 15V9" />
                            <path d="M12 15V7" />
                            <path d="M16 15v-5" />
                        </svg>
                    </div>
                </div>

                <!-- Portfolio -->
                <div class="glass soft p-6 flex items-center justify-between min-h-[86px] hover:border-[#E31937]/30 transition-colors">
                    <div>
                        <div class="text-[12px] font-[700] text-white/55 mb-1">Portfolio</div>
                        <div class="text-[14px] font-[900] text-white/90">Track performance</div>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl border border-[#E31937]/30 bg-black/20 grid place-items-center text-[#E31937]">
                        <!-- pie icon -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M21.2 15.9A9 9 0 1 1 12 3v9l9.2 3.9Z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tesla Investment Plans section -->
    <section class="bg-[#07090c] border-t border-[#E31937]/20">
        <div class="wrap py-14">
            <div class="flex items-start justify-between gap-6 mb-6">
                <div>
                    <h2 class="text-[26px] md:text-[32px] font-[900] tracking-[-.02em] text-white">
                        Tesla Investment Plans <span class="text-[#E31937]">•</span>
                    </h2>
                    <p class="mt-2 text-[13px] md:text-[14px] text-white/55 max-w-xl">
                        Choose from four Tesla investment plans with fixed profit margins and clear minimum and maximum investment limits.
                    </p>
                </div>
                <div class="mt-2">
                    <a href="{{ route('invest') }}" class="inline-flex items-center gap-2 text-[13px] font-[800] text-[#E31937] hover:opacity-80 transition-colors">
                        View all plans
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14" />
                            <path d="M13 6l6 6-6 6" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                @forelse(($investmentPlans ?? [])->take(4) as $plan)
                    <div class="rounded-[18px] border border-white/10 bg-white/5 px-5 py-5 flex flex-col justify-between hover:border-[#E31937]/40 hover:bg-white/[0.06] transition-colors">
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <h3 class="text-[15px] font-[900] tracking-[-.02em] text-white uppercase">
                                    {{ $plan->name }}
                                </h3>
                                @if($plan->profit_margin)
                                    <span class="inline-flex items-center rounded-full bg-[#E31937]/15 px-3 py-1 text-[11px] font-[800] text-[#E31937]">
                                        {{ rtrim(rtrim(number_format((float)$plan->profit_margin, 2), '0'), '.') }}% Profit
                                    </span>
                                @endif
                            </div>
                            <p class="text-[12px] text-white/45 mb-4">
                                {{ $plan->duration_label ? $plan->duration_label : ($plan->duration_days ? $plan->duration_days . ' days' : 'Flexible duration') }}
                                @if($plan->category)
                                    · <span class="text-white/35">{{ $plan->category }}</span>
                                @endif
                            </p>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <div class="text-[11px] font-[800] text-white/45 uppercase tracking-[0.08em]">Minimum</div>
                                    <div class="mt-1 text-[14px] font-[900] text-white">
                                        ${{ number_format((float)$plan->min_investment, 0) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[11px] font-[800] text-white/45 uppercase tracking-[0.08em]">Maximum</div>
                                    <div class="mt-1 text-[14px] font-[900] text-white">
                                        @if(is_null($plan->max_investment))
                                            Unlimited
                                        @else
                                            ${{ number_format((float)$plan->max_investment, 0) }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-[12px] text-white/50">
                                <div>
                                    <span class="font-[800] text-white/70">
                                        @if($plan->duration_label)
                                            {{ $plan->duration_label }}
                                        @elseif($plan->duration_days)
                                            {{ $plan->duration_days }} days
                                        @else
                                            Fixed term
                                        @endif
                                    </span>
                                    @if($plan->risk_level)
                                        <span class="ml-1 text-white/35">· {{ ucfirst($plan->risk_level) }} risk</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-between gap-3">
                            @auth
                                <a href="{{ route('dashboard.investments') }}"
                                   class="flex-1 h-[38px] inline-flex items-center justify-center rounded-md text-[12px] font-[900] text-white hover:opacity-90 transition cursor-pointer"
                                   style="background: #E31937;">
                                    Invest now
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                   class="flex-1 h-[38px] inline-flex items-center justify-center rounded-md text-[12px] font-[900] text-white hover:opacity-90 transition cursor-pointer"
                                   style="background: #E31937;">
                                    Get started
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-8 text-[13px] text-white/50">
                        Investment plans will appear here once configured.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- White inventory section (exact structure like screenshot) -->
    <section class="bg-white">
        <div class="wrap py-14">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <h3 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115]">
                        Available Inventory <span class="text-[#E31937]">•</span>
                    </h3>
                    <p class="mt-2 text-[13px] text-black/50">
                        Explore a curated selection ready for delivery.
                    </p>
                </div>

                <a href="{{ route('inventory') }}" class="text-[13px] font-[700] text-[#E31937] hover:opacity-80 mt-2 transition-colors">
                    View all
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-7">
                @forelse($featuredCars->take(2) as $car)
                    <div class="rounded-[18px] border border-black/10 bg-white overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:shadow-[0_15px_40px_rgba(227,25,55,0.2)] transition">
                        <!-- Image -->
                        <div class="h-[300px] bg-[#f3f4f6] relative overflow-hidden">
                            <a href="{{ route('inventory.show', $car) }}">
                                <img src="{{ asset($car->getPrimaryImage()) }}"
                                    alt="{{ $car->name }}" class="w-full h-full object-cover" loading="lazy" />
                            </a>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <div class="text-[18px] font-[900] tracking-[-.01em] text-[#0f1115]">
                                {{ $car->name }}
                            </div>

                            <!-- specs line -->
                            <div class="mt-3 grid grid-cols-3 gap-3">
                                @if($car->range_miles)
                                <div>
                                    <div class="text-[14px] font-[900] text-[#0f1115]">{{ $car->range_miles }}mi</div>
                                    <div class="text-[11px] text-black/45">Range</div>
                                </div>
                                @endif
                                @if($car->zero_to_sixty)
                                <div>
                                    <div class="text-[14px] font-[900] text-[#0f1115]">{{ number_format($car->zero_to_sixty, 1) }}s</div>
                                    <div class="text-[11px] text-black/45">0-60 mph</div>
                                </div>
                                @endif
                                @if($car->top_speed_mph)
                                <div>
                                    <div class="text-[14px] font-[900] text-[#0f1115]">{{ $car->top_speed_mph }}mph</div>
                                    <div class="text-[11px] text-black/45">Top Speed</div>
                                </div>
                                @endif
                            </div>

                            <!-- price + buttons -->
                            <div class="mt-5 flex items-end justify-between gap-4">
                                <div>
                                    <div class="text-[14px] font-[900] text-[#0f1115]">
                                        Starting at <span class="text-[#E31937]">${{ number_format($car->price, 2) }}*</span>
                                    </div>
                                    <div class="text-[12px] text-black/45">
                                        After Est. Gas Savings
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="{{ route('inventory.show', $car) }}"
                                        class="h-[34px] px-4 rounded-md border border-black/15 bg-white text-[#0f1115] text-[12px] font-[800] hover:bg-black/5 hover:border-[#E31937] hover:text-[#E31937] transition cursor-pointer inline-flex items-center">
                                        View Details
                                    </a>
                                    @auth
                                        <a href="{{ route('inventory.show', $car) }}"
                                            class="h-[34px] px-4 rounded-md text-white text-[12px] font-[800] hover:opacity-90 transition cursor-pointer inline-flex items-center justify-center" style="background: #E31937;">
                                            Order
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="h-[34px] px-4 rounded-md text-white text-[12px] font-[800] hover:opacity-90 transition cursor-pointer inline-flex items-center justify-center" style="background: #E31937;">
                                            Order
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12 text-[14px] text-black/50">
                        No vehicles available at the moment.
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- ===================================================== -->
    <!-- BIG INVENTORY CARD STRIP (like your screenshot) -->
    <!-- Put this RIGHT UNDER the "Available Inventory" section -->
    <!-- ===================================================== -->

    <section class="bg-white">
        <div class="wrap pb-16">
            <!-- Cards row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @forelse($featuredCars->skip(2)->take(2) as $car)
                    <div class="rounded-[18px] border border-black/10 bg-white overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:shadow-[0_15px_40px_rgba(227,25,55,0.2)] transition">
                        <!-- Image -->
                        <div class="h-[300px] bg-[#f3f4f6] relative overflow-hidden">
                            <a href="{{ route('inventory.show', $car) }}">
                                <img src="{{ asset($car->getPrimaryImage()) }}"
                                    alt="{{ $car->name }}" class="w-full h-full object-cover" loading="lazy" />
                            </a>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <div class="text-[18px] font-[900] tracking-[-.01em] text-[#0f1115]">
                                {{ $car->name }}
                            </div>

                            <!-- specs line -->
                            <div class="mt-3 grid grid-cols-3 gap-3">
                                @if($car->range_miles)
                                <div>
                                    <div class="text-[14px] font-[900] text-[#0f1115]">{{ $car->range_miles }}mi</div>
                                    <div class="text-[11px] text-black/45">Range</div>
                                </div>
                                @endif
                                @if($car->zero_to_sixty)
                                <div>
                                    <div class="text-[14px] font-[900] text-[#0f1115]">{{ number_format($car->zero_to_sixty, 1) }}s</div>
                                    <div class="text-[11px] text-black/45">0-60 mph</div>
                                </div>
                                @endif
                                @if($car->top_speed_mph)
                                <div>
                                    <div class="text-[14px] font-[900] text-[#0f1115]">{{ $car->top_speed_mph }}mph</div>
                                    <div class="text-[11px] text-black/45">Top Speed</div>
                                </div>
                                @endif
                            </div>

                            <!-- price + buttons -->
                            <div class="mt-5 flex items-end justify-between gap-4">
                                <div>
                                    <div class="text-[14px] font-[900] text-[#0f1115]">
                                        Starting at <span class="text-[#E31937]">${{ number_format($car->price, 2) }}*</span>
                                    </div>
                                    <div class="text-[12px] text-black/45">
                                        After Est. Gas Savings
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="{{ route('inventory.show', $car) }}"
                                        class="h-[34px] px-4 rounded-md border border-black/15 bg-white text-[#0f1115] text-[12px] font-[800] hover:bg-black/5 hover:border-[#E31937] hover:text-[#E31937] transition cursor-pointer inline-flex items-center">
                                        View Details
                                    </a>
                                    @auth
                                        <a href="{{ route('inventory.show', $car) }}"
                                            class="h-[34px] px-4 rounded-md text-white text-[12px] font-[800] hover:opacity-90 transition cursor-pointer inline-flex items-center justify-center" style="background: #E31937;">
                                            Order
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="h-[34px] px-4 rounded-md text-white text-[12px] font-[800] hover:opacity-90 transition cursor-pointer inline-flex items-center justify-center" style="background: #E31937;">
                                            Order
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Show placeholder if no more cars -->
                @endforelse
            </div>
        </div>
    </section>

    <!-- Tesla News Section -->
    <section class="bg-white py-16">
        <div class="wrap">
            <div class="flex items-start justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-[32px] md:text-[40px] font-[900] tracking-[-.02em] text-[#0f1115]">
                        Tesla News <span class="text-[#E31937]">•</span>
                    </h2>
                    <p class="mt-2 text-[15px] text-black/60">
                        Stay updated with the latest news, updates, and announcements from Tesla.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- News Card 1 -->
                <div class="rounded-[18px] border border-black/10 bg-white overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:shadow-[0_15px_40px_rgba(227,25,55,0.15)] hover:border-[#E31937]/30 transition">
                    <div class="h-[200px] bg-[#f3f4f6] relative">
                        <div class="absolute top-3 left-3 w-2 h-2 rounded-full bg-[#E31937]"></div>
                        <img src="{{ asset('images/news-1.jpg') }}" alt="Tesla News" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                    <div class="p-5">
                        <div class="text-[12px] font-[700] text-[#E31937] mb-2">January 26, 2026</div>
                        <h3 class="text-[18px] font-[900] tracking-[-.01em] text-[#0f1115] mb-3">
                            Tesla Announces Record-Breaking Q4 Deliveries
                        </h3>
                        <p class="text-[14px] text-black/60 leading-relaxed">
                            Tesla delivered over 1.8 million vehicles in Q4 2025, marking another record quarter and demonstrating strong global demand for electric vehicles.
                        </p>
                    </div>
                </div>

                <!-- News Card 2 -->
                <div class="rounded-[18px] border border-black/10 bg-white overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:shadow-[0_15px_40px_rgba(227,25,55,0.15)] hover:border-[#E31937]/30 transition">
                    <div class="h-[200px] bg-[#f3f4f6] relative">
                        <div class="absolute top-3 left-3 w-2 h-2 rounded-full bg-[#E31937]"></div>
                        <img src="{{ asset('images/news-2.jpg') }}" alt="Tesla News" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                    <div class="p-5">
                        <div class="text-[12px] font-[700] text-[#E31937] mb-2">January 20, 2026</div>
                        <h3 class="text-[18px] font-[900] tracking-[-.01em] text-[#0f1115] mb-3">
                            New Supercharger Network Expansion
                        </h3>
                        <p class="text-[14px] text-black/60 leading-relaxed">
                            Tesla continues to expand its Supercharger network globally, adding 500 new locations to make long-distance travel even more convenient for EV owners.
                        </p>
                    </div>
                </div>

                <!-- News Card 3 -->
                <div class="rounded-[18px] border border-black/10 bg-white overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:shadow-[0_15px_40px_rgba(227,25,55,0.15)] hover:border-[#E31937]/30 transition">
                    <div class="h-[200px] bg-[#f3f4f6] relative">
                        <div class="absolute top-3 left-3 w-2 h-2 rounded-full bg-[#E31937]"></div>
                        <img src="{{ asset('images/news-3.jpg') }}" alt="Tesla News" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                    <div class="p-5">
                        <div class="text-[12px] font-[700] text-[#E31937] mb-2">January 15, 2026</div>
                        <h3 class="text-[18px] font-[900] tracking-[-.01em] text-[#0f1115] mb-3">
                            Full Self-Driving Beta Reaches New Milestone
                        </h3>
                        <p class="text-[14px] text-black/60 leading-relaxed">
                            Tesla's Full Self-Driving Beta v12.5 now available to over 500,000 users, showcasing significant improvements in autonomous driving capabilities.
                        </p>
                    </div>
                </div>

                <!-- News Card 4 -->
                <div class="rounded-[18px] border border-black/10 bg-white overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:shadow-[0_15px_40px_rgba(227,25,55,0.15)] hover:border-[#E31937]/30 transition">
                    <div class="h-[200px] bg-[#f3f4f6] relative">
                        <div class="absolute top-3 left-3 w-2 h-2 rounded-full bg-[#E31937]"></div>
                        <img src="{{ asset('images/news-4.jpg') }}" alt="Tesla News" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                    <div class="p-5">
                        <div class="text-[12px] font-[700] text-[#E31937] mb-2">January 10, 2026</div>
                        <h3 class="text-[18px] font-[900] tracking-[-.01em] text-[#0f1115] mb-3">
                            Cybertruck Production Ramps Up
                        </h3>
                        <p class="text-[14px] text-black/60 leading-relaxed">
                            Tesla's revolutionary Cybertruck production reaches new heights with increased manufacturing capacity at Giga Texas facility.
                        </p>
                    </div>
                </div>

                <!-- News Card 5 -->
                <div class="rounded-[18px] border border-black/10 bg-white overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:shadow-[0_15px_40px_rgba(227,25,55,0.15)] hover:border-[#E31937]/30 transition">
                    <div class="h-[200px] bg-[#f3f4f6] relative">
                        <div class="absolute top-3 left-3 w-2 h-2 rounded-full bg-[#E31937]"></div>
                        <img src="{{ asset('images/news-1.jpg') }}" alt="Tesla News" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                    <div class="p-5">
                        <div class="text-[12px] font-[700] text-[#E31937] mb-2">January 5, 2026</div>
                        <h3 class="text-[18px] font-[900] tracking-[-.01em] text-[#0f1115] mb-3">
                            Energy Storage Solutions Expand Globally
                        </h3>
                        <p class="text-[14px] text-black/60 leading-relaxed">
                            Tesla Energy announces major expansion of Megapack installations worldwide, supporting the transition to renewable energy infrastructure.
                        </p>
                    </div>
                </div>

                <!-- News Card 6 -->
                <div class="rounded-[18px] border border-black/10 bg-white overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:shadow-[0_15px_40px_rgba(227,25,55,0.15)] hover:border-[#E31937]/30 transition">
                    <div class="h-[200px] bg-[#f3f4f6] relative">
                        <div class="absolute top-3 left-3 w-2 h-2 rounded-full bg-[#E31937]"></div>
                        <img src="{{ asset('images/news-2.jpg') }}" alt="Tesla News" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                    <div class="p-5">
                        <div class="text-[12px] font-[700] text-[#E31937] mb-2">December 30, 2025</div>
                        <h3 class="text-[18px] font-[900] tracking-[-.01em] text-[#0f1115] mb-3">
                            Model Y Becomes World's Best-Selling EV
                        </h3>
                        <p class="text-[14px] text-black/60 leading-relaxed">
                            Tesla Model Y maintains its position as the world's best-selling electric vehicle, with over 1.2 million units delivered in 2025.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================================== -->
    <!-- STOCK MARKETS (exact block like your screenshot) -->
    <!-- Put this RIGHT UNDER the big inventory cards section -->
    <!-- ===================================================== -->

    <section id="stocks" class="bg-[#07090c] py-16 scroll-mt-20 border-t border-[#E31937]/20">
        <div class="wrap">
            <!-- Header -->
            <div class="flex items-start justify-between gap-6">
                <div>
                    <h2 class="text-[28px] font-[900] tracking-[-.02em] text-white">
                        Stock Markets <span class="text-[#E31937]">•</span>
                    </h2>
                    <p class="mt-2 text-[13px] text-white/45">
                        Featured picks, top gainers, losers, and most active.
                    </p>
                </div>
                <a href="{{ route('stocks') }}" class="text-[13px] font-[700] text-[#E31937] hover:opacity-80 mt-2 transition-colors">
                    Open markets
                </a>
            </div>

            <!-- Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-8">
                <!-- Featured -->
                <div class="rounded-[18px] border border-white/10 bg-white/5 overflow-hidden hover:border-[#E31937]/50 transition-colors">
                    <div class="flex items-center justify-between px-5 py-4 text-[13px] font-[900] text-white/75 border-b border-[#E31937]/20">
                        <span class="text-[#E31937]">Featured</span>
                        <span class="text-[#E31937] font-[800] hover:opacity-80 cursor-pointer">See all</span>
                    </div>

                    @php
                        $stockNames = [
                            'AAPL' => 'Apple Inc.',
                            'MSFT' => 'Microsoft Corporation',
                            'GOOGL' => 'Alphabet Inc.',
                            'AMZN' => 'Amazon.com Inc.',
                            'NVDA' => 'NVIDIA Corporation',
                            'META' => 'Meta Platforms Inc.',
                            'TSLA' => 'Tesla Inc.',
                            'JPM' => 'JPMorgan Chase & Co.',
                            'V' => 'Visa Inc.',
                            'JNJ' => 'Johnson & Johnson',
                        ];
                    @endphp

                    @forelse($featuredStocks ?? [] as $symbol => $stock)
                        <div class="flex items-center justify-between gap-3 px-5 py-4 border-t border-white/7">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-[30px] h-[30px] rounded-[10px] border border-white/10 bg-black/20 overflow-hidden flex-shrink-0">
                                    <img src="{{ route('stock-logo', ['ticker' => $symbol]) }}" 
                                         alt="{{ $symbol }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"
                                         loading="lazy">
                                    <div class="w-full h-full grid place-items-center text-white/90 text-[12px] font-[900]" style="display:none;">
                                        {{ substr($symbol, 0, 1) }}
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[13px] font-[900] text-white/90 truncate">
                                        {{ $symbol }} <span class="text-white/40 font-[700]">· {{ $stockNames[$symbol] ?? 'Stock' }}</span>
                                    </div>
                                    <div class="text-[12px] text-white/40">Technology</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-[14px] font-[900] text-white/90">${{ number_format($stock['price'] ?? 0, 2) }}</div>
                                @php
                                    $change = $stock['change_percent'] ?? 0;
                                    $changeColor = $change >= 0 ? 'text-emerald-400' : 'text-rose-400';
                                    $changeSign = $change >= 0 ? '+' : '';
                                @endphp
                                <div class="text-[12px] font-[900] {{ $changeColor }}">{{ $changeSign }}{{ number_format($change, 2) }}%</div>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-4 border-t border-white/7 text-[12px] text-white/50">Loading stock data...</div>
                    @endforelse
                </div>

                <!-- Top Gainers -->
                <div class="rounded-[18px] border border-white/10 bg-white/5 overflow-hidden hover:border-[#E31937]/50 transition-colors">
                    <div class="px-5 py-4 text-[13px] font-[900] text-[#E31937] border-b border-[#E31937]/20">
                        Top Gainers
                    </div>

                    @forelse($topGainers ?? [] as $stock)
                        <div class="flex items-center justify-between px-5 py-4 border-t border-white/7">
                            <div class="flex items-center gap-3">
                                <div class="w-[30px] h-[30px] rounded-[10px] border border-white/10 bg-black/20 overflow-hidden flex-shrink-0">
                                    <img src="{{ route('stock-logo', ['ticker' => $stock['symbol'] ?? '']) }}" 
                                         alt="{{ $stock['symbol'] ?? 'N/A' }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"
                                         loading="lazy">
                                    <div class="w-full h-full grid place-items-center text-white/85 text-[12px] font-[900]" style="display:none;">
                                        {{ substr($stock['symbol'] ?? '?', 0, 2) }}
                                    </div>
                                </div>
                                <div class="text-[13px] font-[900] text-white/90">{{ $stock['symbol'] ?? 'N/A' }}</div>
                            </div>
                            <div class="text-[12px] font-[900] text-emerald-400">+{{ number_format($stock['change_percent'] ?? 0, 2) }}%</div>
                        </div>
                    @empty
                        <div class="px-5 py-4 border-t border-white/7 text-[12px] text-white/50">Loading data...</div>
                    @endforelse
                </div>

                <!-- Top Losers -->
                <div class="rounded-[18px] border border-white/10 bg-white/5 overflow-hidden hover:border-[#E31937]/50 transition-colors">
                    <div class="px-5 py-4 text-[13px] font-[900] text-[#E31937] border-b border-[#E31937]/20">
                        Top Losers
                    </div>

                    @forelse($topLosers ?? [] as $stock)
                        <div class="flex items-center justify-between px-5 py-4 border-t border-white/7">
                            <div class="flex items-center gap-3">
                                <div class="w-[30px] h-[30px] rounded-[10px] border border-white/10 bg-black/20 overflow-hidden flex-shrink-0">
                                    <img src="{{ route('stock-logo', ['ticker' => $stock['symbol'] ?? '']) }}" 
                                         alt="{{ $stock['symbol'] ?? 'N/A' }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"
                                         loading="lazy">
                                    <div class="w-full h-full grid place-items-center text-white/85 text-[12px] font-[900]" style="display:none;">
                                        {{ substr($stock['symbol'] ?? '?', 0, 2) }}
                                    </div>
                                </div>
                                <div class="text-[13px] font-[900] text-white/90">{{ $stock['symbol'] ?? 'N/A' }}</div>
                            </div>
                            <div class="text-[12px] font-[900] text-rose-400">{{ number_format($stock['change_percent'] ?? 0, 2) }}%</div>
                        </div>
                    @empty
                        <div class="px-5 py-4 border-t border-white/7 text-[12px] text-white/50">Loading data...</div>
                    @endforelse
                </div>

                <!-- Most Active -->
                <div class="rounded-[18px] border border-white/10 bg-white/5 overflow-hidden hover:border-[#E31937]/50 transition-colors">
                    <div class="px-5 py-4 text-[13px] font-[900] text-[#E31937] border-b border-[#E31937]/20">
                        Most Active
                    </div>

                    @forelse($mostActive ?? [] as $stock)
                        @php
                            $volume = $stock['volume'] ?? 0;
                            $volumeFormatted = $volume >= 1000000 
                                ? number_format($volume / 1000000, 1) . 'M' 
                                : ($volume >= 1000 
                                    ? number_format($volume / 1000, 1) . 'K' 
                                    : number_format($volume));
                        @endphp
                        <div class="flex items-center justify-between px-5 py-4 border-t border-white/7">
                            <div class="flex items-center gap-3">
                                <div class="w-[30px] h-[30px] rounded-[10px] border border-white/10 bg-black/20 overflow-hidden flex-shrink-0">
                                    <img src="{{ route('stock-logo', ['ticker' => $stock['symbol'] ?? '']) }}" 
                                         alt="{{ $stock['symbol'] ?? 'N/A' }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"
                                         loading="lazy">
                                    <div class="w-full h-full grid place-items-center text-white/85 text-[12px] font-[900]" style="display:none;">
                                        {{ substr($stock['symbol'] ?? '?', 0, 2) }}
                                    </div>
                                </div>
                                <div class="text-[13px] font-[900] text-white/90">{{ $stock['symbol'] ?? 'N/A' }}</div>
                            </div>
                            <div class="text-[12px] font-[800] text-white/55">Vol {{ $volumeFormatted }}</div>
                        </div>
                    @empty
                        <div class="px-5 py-4 border-t border-white/7 text-[12px] text-white/50">Loading data...</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================================== -->
    <!-- MARKET NEWS (matches your screenshot layout) -->
    <!-- Put this RIGHT UNDER the Stock Markets section -->
    <!-- ===================================================== -->

    <section id="portfolio" class="bg-[#0a0f17] py-16 scroll-mt-20">
        <div class="wrap">
            <!-- Header -->
            <div class="flex items-start justify-between gap-6">
                <div>
                    <h2 class="text-[28px] font-[900] tracking-[-.02em] text-white">
                        Market News <span class="text-[#E31937]">•</span>
                    </h2>
                    <p class="mt-2 text-[13px] text-white/45">
                        Latest headlines impacting your watchlist.
                    </p>
                </div>
                <a href="#" class="text-[13px] font-[700] text-[#E31937] hover:opacity-80 mt-2 transition-colors">
                    View stocks
                </a>
            </div>

            <!-- News cards grid (2 rows x 3 columns on desktop) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @php
                    $newsImages = ['news-1.jpg', 'news-2.jpg', 'news-3.jpg', 'news-4.jpg'];
                    $newsColors = ['bg-orange-500/90', 'bg-purple-500/20', 'bg-blue-500/20', 'bg-green-500/20'];
                @endphp
                
                @forelse($marketNews ?? [] as $index => $news)
                    @php
                        $ticker = $news['ticker'] ?? 'STK';
                        $source = $news['source'] ?? 'News';
                        $publishedAt = $news['published_at'] ?? now()->toIso8601String();
                        $timeAgo = \Carbon\Carbon::parse($publishedAt)->diffForHumans();
                        $sentiment = $news['sentiment'] ?? 'Neutral';
                        $sentimentColor = str_contains(strtolower($sentiment), 'positive') ? 'text-emerald-400' : (str_contains(strtolower($sentiment), 'negative') ? 'text-rose-400' : 'text-white/35');
                        $imageIndex = $index % count($newsImages);
                        $hasImage = !empty($news['image']);
                    @endphp
                    <div class="rounded-[18px] border border-white/10 bg-white/5 p-5">
                        <div class="flex items-start gap-4">
                            @if($hasImage)
                                <div class="w-[52px] h-[52px] rounded-[14px] border border-white/10 bg-white/10 overflow-hidden">
                                    <img src="{{ $news['image'] }}" alt="" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';" />
                                    <div class="w-[52px] h-[52px] rounded-[14px] border border-white/10 {{ $newsColors[$imageIndex] }} grid place-items-center overflow-hidden" style="display: none;">
                                        <span class="text-white font-[900] text-[12px]">{{ substr($source, 0, 2) }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="w-[52px] h-[52px] rounded-[14px] border border-white/10 {{ $newsColors[$imageIndex] }} grid place-items-center overflow-hidden">
                                    <span class="text-white font-[900] text-[12px]">{{ substr($source, 0, 2) }}</span>
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 text-[12px] font-[800] text-white/55">
                                    @if($ticker)
                                        <span class="text-white/80 font-[900]">{{ $ticker }}</span>
                                    @endif
                                    <span>{{ $source }}</span>
                                    <span class="text-white/35">•</span>
                                    <span>{{ $timeAgo }}</span>
                                </div>

                                <div class="mt-2 text-[14px] font-[900] text-white/90 leading-snug line-clamp-2">
                                    <a href="{{ $news['url'] ?? '#' }}" target="_blank" class="hover:underline">
                                        {{ $news['title'] ?? 'No title available' }}
                                    </a>
                                </div>

                                @if(!empty($news['description']))
                                    <div class="mt-2 text-[12.5px] text-white/45 leading-relaxed line-clamp-2">
                                        {{ $news['description'] }}
                                    </div>
                                @endif

                                <div class="mt-4 text-[12px] font-[900] {{ $sentimentColor }}">
                                    Sentiment: {{ $sentiment }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <div class="text-white/50 text-[14px]">Loading market news...</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- ===================================================== -->
    <!-- CTA BANNER: "Ready to build your portfolio?" (like screenshot) -->
    <!-- Put this RIGHT UNDER the Market News section -->
    <!-- ===================================================== -->

    <section class="bg-white">
        <!-- Top white spacing band like screenshot -->
        <div class="h-20 bg-white"></div>

        <!-- Dark CTA strip -->
        <div class="bg-[#0a0f17]">
            <div class="wrap py-20">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-[28px] md:text-[32px] font-[900] tracking-[-.02em] text-white">
                        Ready to build your <span class="text-[#E31937]">portfolio</span>?
                    </h2>

                    <p class="mt-3 text-[14px] md:text-[15px] text-white/55">
                        Create an investment plan, follow stocks, and shop inventory in one place.
                    </p>

                    <div class="mt-8 flex items-center justify-center gap-4">
                        <a href="{{ route('register') }}" class="h-[44px] px-8 rounded-md text-white text-[13px] font-[900]
                     border hover:opacity-90 transition cursor-pointer flex items-center justify-center" style="background: #E31937; border-color: #E31937;">
                            Get Started
                        </a>

                        <a href="{{ route('login') }}" class="h-[44px] px-8 rounded-md bg-transparent text-white text-[13px] font-[900]
                     border border-[#E31937]/50 hover:bg-[#E31937]/10 hover:border-[#E31937] transition cursor-pointer flex items-center justify-center">
                            Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom white spacing band like screenshot -->
        <div class="h-20 bg-white"></div>
    </section>
@endsection
