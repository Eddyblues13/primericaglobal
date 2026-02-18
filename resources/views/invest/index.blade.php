@extends('layouts.app')

@section('title', 'Investment Plans - Automated Wealth Building | TESLA Market Hub')
@section('meta_title', 'Investment Plans - Automated Wealth Building | TESLA Market Hub')
@section('meta_description', 'Explore our automated investment plans with daily returns. Choose from Starter, Silver, Gold, and Platinum plans with flexible terms and guaranteed profit margins.')
@section('meta_keywords', 'tesla investment plans, automated investing, investment returns, wealth building, passive income, investment portfolio, financial planning')

@section('og_title', 'Tesla Investment Plans - Start Growing Your Wealth Today')
@section('og_description', 'Choose from 4 automated investment plans with guaranteed returns. Minimum investment from $100 to unlimited.')

@section('content')
    <!-- Hero Section -->
    <section class="bg-[#07090c] py-16">
        <div class="wrap">
            <div class="max-w-3xl">
                <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-white mb-4">
                    Automated Investments <span class="text-[#E31937]">•</span>
                </h1>
                <p class="text-[15px] md:text-[16px] text-white/60">
                    Flexible plans, recurring contributions, and smart portfolio management.
                </p>
            </div>
        </div>
    </section>

    <!-- Investment Plans -->
    <section class="bg-[#07090c] pb-20">
        <div class="wrap">
            <div class="max-w-4xl mb-12">
                <h2 class="text-[32px] md:text-[40px] font-[900] tracking-[-.02em] text-white mb-4">
                    Tesla Investment Plans
                </h2>
                <p class="text-[15px] text-white/60">
                    Tesla offers four investment plans with varying profit margins and investment limits.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($investmentPlans ?? [] as $index => $plan)
                    @php
                        $min = (float) $plan->min_investment;
                        $max = $plan->max_investment;
                        $range = $max === null
                            ? '$' . number_format($min, 0) . '+'
                            : '$' . number_format($min, 0) . ' - $' . number_format((float) $max, 0);
                        $maxLabel = $max === null ? 'Unlimited' : '$' . number_format((float) $max, 0);
                        $isVip = $plan->slug === 'vip';
                    @endphp
                    <div class="glass bigCard hover:border-[#E31937]/30 transition-colors">
                        <div class="label">Plan {{ $index + 1 }}</div>
                        <div class="big">{{ $plan->name }} <span class="text-[#E31937]">•</span></div>
                        <div class="desc mt-3">
                            <div class="mb-2"><strong>Investment Range:</strong> <span class="text-[#E31937]">{{ $range }}</span></div>
                            <div class="mb-2"><strong>Profit Margin:</strong> <span class="text-[#E31937]">{{ number_format((float) ($plan->profit_margin ?? 0), 0) }}%</span></div>
                            <div><strong>Duration:</strong> <span class="text-[#E31937]">{{ $plan->duration_label ?? '—' }}</span></div>
                        </div>
                        <div class="mt-4">
                            <div class="text-[14px] font-[900] text-white/90">Minimum: <span class="text-[#E31937]">${{ number_format($min, 0) }}</span></div>
                            <div class="text-[12px] text-white/45 mt-1">Maximum: <span class="text-[#E31937]">{{ $maxLabel }}</span></div>
                        </div>
                        @auth
                            <a href="{{ route('dashboard.investments') }}" class="{{ $isVip ? 'btn primary' : 'btn outline' }} mt-6 w-full block text-center {{ !$isVip ? 'hover:border-[#E31937] hover:text-[#E31937] transition-colors' : '' }}" @if($isVip) style="background: #E31937; border-color: #E31937;" @endif>Start Plan</a>
                        @else
                            <a href="{{ route('login') }}" class="{{ $isVip ? 'btn primary' : 'btn outline' }} mt-6 w-full block text-center {{ !$isVip ? 'hover:border-[#E31937] hover:text-[#E31937] transition-colors' : '' }}" @if($isVip) style="background: #E31937; border-color: #E31937;" @endif>Start Plan</a>
                        @endauth
                    </div>
                @endforeach
            </div>

            <!-- Features -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl border border-[#E31937]/30 bg-black/20 grid place-items-center text-[#E31937]">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 17l6-6 4 4 8-8" />
                            <path d="M21 7v6h-6" />
                        </svg>
                    </div>
                    <div class="text-[16px] font-[900] text-white mb-2">Automated</div>
                    <div class="text-[13px] text-white/60">Set it and forget it. Automatic rebalancing and contributions.</div>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl border border-[#E31937]/30 bg-black/20 grid place-items-center text-[#E31937]">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2" />
                            <path d="M21 12h-7a2 2 0 0 0 0 4h7" />
                        </svg>
                    </div>
                    <div class="text-[16px] font-[900] text-white mb-2">Flexible</div>
                    <div class="text-[13px] text-white/60">Adjust contributions, pause, or withdraw anytime.</div>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl border border-[#E31937]/30 bg-black/20 grid place-items-center text-[#E31937]">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21.2 15.9A9 9 0 1 1 12 3v9l9.2 3.9Z" />
                        </svg>
                    </div>
                    <div class="text-[16px] font-[900] text-white mb-2">Diversified</div>
                    <div class="text-[13px] text-white/60">Portfolio spread across multiple asset classes and sectors.</div>
                </div>
            </div>
        </div>
    </section>
@endsection
