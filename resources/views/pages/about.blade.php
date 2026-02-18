@extends('layouts.app')

@section('title', 'About Us - TESLA')

@section('content')
    <!-- Hero Section -->
    <section class="bg-white py-16">
        <div class="wrap">
            <div class="max-w-4xl">
                <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-[#0f1115] mb-4">
                    About TESLA
                </h1>
                <p class="text-[15px] md:text-[16px] text-black/60">
                    Investing, trading, and driving the future of sustainable transportation.
                </p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="bg-white pb-20">
        <div class="wrap">
            <div class="max-w-4xl">
                <div class="prose prose-lg max-w-none">
                    <h2 class="text-[32px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6">Our Mission</h2>
                    <p class="text-[15px] text-black/70 leading-relaxed mb-6">
                        TESLA is an all-in-one platform that combines investment opportunities, stock trading, and premium electric vehicle inventory. We're dedicated to making sustainable investing and transportation accessible to everyone.
                    </p>

                    <h2 class="text-[32px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6 mt-12">What We Do</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="p-6 rounded-lg border border-black/10 bg-white">
                            <div class="text-[18px] font-[900] text-[#0f1115] mb-2">Invest</div>
                            <div class="text-[13px] text-black/60">Automated investment plans with flexible contributions and smart portfolio management.</div>
                        </div>
                        <div class="p-6 rounded-lg border border-black/10 bg-white">
                            <div class="text-[18px] font-[900] text-[#0f1115] mb-2">Trade</div>
                            <div class="text-[13px] text-black/60">Real-time stock market data, analysis, and trading tools.</div>
                        </div>
                        <div class="p-6 rounded-lg border border-black/10 bg-white">
                            <div class="text-[18px] font-[900] text-[#0f1115] mb-2">Drive</div>
                            <div class="text-[13px] text-black/60">Curated selection of premium Tesla vehicles ready for delivery.</div>
                        </div>
                    </div>

                    <h2 class="text-[32px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6 mt-12">Our Values</h2>
                    <ul class="space-y-4 text-[15px] text-black/70 leading-relaxed">
                        <li><strong class="text-[#0f1115]">Innovation:</strong> Pioneering the future of sustainable finance and transportation.</li>
                        <li><strong class="text-[#0f1115]">Accessibility:</strong> Making investment and sustainable vehicles accessible to everyone.</li>
                        <li><strong class="text-[#0f1115]">Transparency:</strong> Clear, honest communication and straightforward pricing.</li>
                        <li><strong class="text-[#0f1115]">Sustainability:</strong> Committed to environmental responsibility and green energy solutions.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
