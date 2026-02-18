@extends('layouts.app')

@section('title', 'Live Stock Trading - Real-Time Market Data | TESLA Market Hub')
@section('meta_title', 'Live Stock Trading - Real-Time Market Data | TESLA Market Hub')
@section('meta_description', 'Trade stocks with real-time market data. Access top gainers, top losers, and most active stocks. Start building your investment portfolio with live stock prices.')
@section('meta_keywords', 'stock trading, real-time stocks, stock market, top gainers, stock prices, trading platform, investment portfolio, market data')

@section('og_title', 'Real-Time Stock Trading Platform | TESLA Market Hub')
@section('og_description', 'Access live stock market data, track top gainers and losers, and trade with confidence.')

@section('content')
    <!-- Hero Section -->
    <section class="bg-[#07090c] py-16">
        <div class="wrap">
            <div class="max-w-3xl">
                <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-white mb-4">
                    Stock Markets <span class="text-[#E31937]">•</span>
                </h1>
                <p class="text-[15px] md:text-[16px] text-white/60">
                    Featured picks, top gainers, losers, and <span class="text-[#E31937] font-[600]">most active</span> stocks.
                </p>
            </div>
        </div>
    </section>

    <!-- Stock Markets Grid -->
    <section class="bg-[#07090c] pb-20">
        <div class="wrap">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
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
                                <div class="w-[30px] h-[30px] rounded-[10px] border border-[#E31937]/30 bg-black/20 grid place-items-center text-[#E31937] text-[12px] font-[900]">
                                    {{ substr($symbol, 0, 1) }}
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
                                <div class="w-[30px] h-[30px] rounded-[10px] border border-[#E31937]/30 bg-black/20 grid place-items-center">
                                    <span class="text-[12px] font-[900] text-[#E31937]">{{ substr($stock['symbol'] ?? '?', 0, 2) }}</span>
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
                                <div class="w-[30px] h-[30px] rounded-[10px] border border-[#E31937]/30 bg-black/20 grid place-items-center">
                                    <span class="text-[12px] font-[900] text-[#E31937]">{{ substr($stock['symbol'] ?? '?', 0, 2) }}</span>
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
                                <div class="w-[30px] h-[30px] rounded-[10px] border border-[#E31937]/30 bg-black/20 grid place-items-center">
                                    <span class="text-[12px] font-[900] text-[#E31937]">{{ substr($stock['symbol'] ?? '?', 0, 2) }}</span>
                                </div>
                                <div class="text-[13px] font-[900] text-white/90">{{ $stock['symbol'] ?? 'N/A' }}</div>
                            </div>
                            <div class="text-[12px] font-[800] text-[#E31937]/80">Vol {{ $volumeFormatted }}</div>
                        </div>
                    @empty
                        <div class="px-5 py-4 border-t border-white/7 text-[12px] text-white/50">Loading data...</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
