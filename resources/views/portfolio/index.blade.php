@extends('layouts.app')

@section('title', 'Portfolio - Primrica Global Capital')

@section('content')
<!-- Hero Section -->
<section class="bg-[#07090c] py-16">
    <div class="wrap">
        <div class="max-w-3xl">
            <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-white mb-4">
                Your Portfolio <span class="text-[#E31937]">•</span>
            </h1>
            <p class="text-[15px] md:text-[16px] text-white/60">
                Track performance, analyze returns, and manage your investments.
            </p>
        </div>
    </div>
</section>

<!-- Portfolio Overview -->
<section class="bg-[#07090c] pb-20">
    <div class="wrap">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="glass bigCard hover:border-[#E31937]/30 transition-colors">
                <div class="label text-[#E31937]">Total Value</div>
                <div class="big"><span class="text-[#E31937]">$125,430.00</span></div>
                <div class="desc">+$5,234.50 (+4.35%)</div>
            </div>

            <div class="glass bigCard hover:border-[#E31937]/30 transition-colors">
                <div class="label text-[#E31937]">Invested</div>
                <div class="big"><span class="text-[#E31937]">$120,195.50</span></div>
                <div class="desc">Principal amount</div>
            </div>

            <div class="glass bigCard hover:border-[#E31937]/30 transition-colors">
                <div class="label text-[#E31937]">Returns</div>
                <div class="big"><span class="text-[#E31937]">+$5,234.50</span></div>
                <div class="desc">4.35% gain this month</div>
            </div>
        </div>

        <!-- Holdings -->
        <div class="mb-12">
            <h2 class="text-[28px] font-[900] tracking-[-.02em] text-white mb-6">Holdings <span
                    class="text-[#E31937]">•</span></h2>
            <div class="glass overflow-hidden hover:border-[#E31937]/30 transition-colors">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#E31937]/30">
                                <th class="px-5 py-4 text-left text-[13px] font-[900] text-[#E31937]">Symbol</th>
                                <th class="px-5 py-4 text-left text-[13px] font-[900] text-[#E31937]">Name</th>
                                <th class="px-5 py-4 text-right text-[13px] font-[900] text-[#E31937]">Shares</th>
                                <th class="px-5 py-4 text-right text-[13px] font-[900] text-[#E31937]">Price</th>
                                <th class="px-5 py-4 text-right text-[13px] font-[900] text-[#E31937]">Value</th>
                                <th class="px-5 py-4 text-right text-[13px] font-[900] text-[#E31937]">Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-white/7">
                                <td class="px-5 py-4 text-[13px] font-[900] text-white/90">AAPL</td>
                                <td class="px-5 py-4 text-[13px] text-white/70">Apple Inc.</td>
                                <td class="px-5 py-4 text-right text-[13px] font-[700] text-white/90">25</td>
                                <td class="px-5 py-4 text-right text-[13px] font-[700] text-white/90">$229.35</td>
                                <td class="px-5 py-4 text-right text-[14px] font-[900] text-white/90">$5,733.75</td>
                                <td class="px-5 py-4 text-right text-[12px] font-[900] text-emerald-400">+1.27%</td>
                            </tr>
                            <tr class="border-b border-white/7">
                                <td class="px-5 py-4 text-[13px] font-[900] text-white/90">MSFT</td>
                                <td class="px-5 py-4 text-[13px] text-white/70">Microsoft Corporation</td>
                                <td class="px-5 py-4 text-right text-[13px] font-[700] text-white/90">15</td>
                                <td class="px-5 py-4 text-right text-[13px] font-[700] text-white/90">$522.04</td>
                                <td class="px-5 py-4 text-right text-[14px] font-[900] text-white/90">$7,830.60</td>
                                <td class="px-5 py-4 text-right text-[12px] font-[900] text-rose-400">-2.09%</td>
                            </tr>
                            <tr class="border-b border-white/7">
                                <td class="px-5 py-4 text-[13px] font-[900] text-white/90">GOOGL</td>
                                <td class="px-5 py-4 text-[13px] text-white/70">Alphabet Inc.</td>
                                <td class="px-5 py-4 text-right text-[13px] font-[700] text-white/90">30</td>
                                <td class="px-5 py-4 text-right text-[13px] font-[700] text-white/90">$201.42</td>
                                <td class="px-5 py-4 text-right text-[14px] font-[900] text-white/90">$6,042.60</td>
                                <td class="px-5 py-4 text-right text-[12px] font-[900] text-rose-400">-1.76%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Performance Chart Area -->
        <div class="glass bigCardTall hover:border-[#E31937]/30 transition-colors">
            <div class="label text-[#E31937]">Performance</div>
            <div class="big">Last 30 Days <span class="text-[#E31937]">•</span></div>
            <div class="desc">Portfolio value over time</div>
            <div class="mt-6 h-48 bg-black/20 rounded-lg flex items-center justify-center border border-[#E31937]/20">
                <div class="text-[#E31937]/60 text-[13px]">Chart visualization would appear here</div>
            </div>
        </div>
    </div>
</section>
@endsection