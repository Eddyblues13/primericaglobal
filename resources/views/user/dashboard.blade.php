@extends('layouts.dashboard')

@section('title', 'TESLA Dashboard')
@section('topTitle', 'Dashboard')

@push('styles')
<style>
    #dashboard .dashboard-video-wrap {
        margin-top: 16px;
        border-radius: 16px;
        overflow: hidden;
        background: #0f1116;
        border: 1px solid rgba(255,255,255,.08);
        box-shadow: 0 20px 40px rgba(0,0,0,.15);
    }
    #dashboard .dashboard-video-wrap video {
        width: 100%;
        display: block;
        max-height: 280px;
        object-fit: cover;
    }
    #dashboard .dashboard-charts-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 16px;
    }
    @media (max-width: 1024px) {
        #dashboard .dashboard-charts-grid { grid-template-columns: 1fr; }
    }
    #dashboard .dashboard-chart-card {
        background: #1a1c24;
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 18px;
        padding: 20px;
        min-height: 380px;
        color: #fff;
    }
    #dashboard .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    #dashboard .chart-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 900;
        color: #fff;
    }
    #dashboard .chart-live-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 800;
        color: #10b981;
        padding: 4px 8px;
        background: rgba(16, 185, 129, .1);
        border-radius: 6px;
    }
    #dashboard .chart-value-section {
        margin-bottom: 20px;
    }
    #dashboard .chart-main-value {
        font-size: 36px;
        font-weight: 900;
        color: #fff;
        margin: 0 0 8px 0;
        letter-spacing: -0.02em;
    }
    #dashboard .chart-change {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 800;
    }
    #dashboard .chart-change.positive {
        color: #10b981;
    }
    #dashboard .chart-change.negative {
        color: #ef4444;
    }
    #dashboard .chart-metrics {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
        padding-top: 16px;
        border-top: 1px solid rgba(255,255,255,.1);
    }
    #dashboard .chart-metric {
        font-size: 12px;
    }
    #dashboard .chart-metric-label {
        color: rgba(255,255,255,.6);
        font-weight: 700;
        margin-bottom: 4px;
    }
    #dashboard .chart-metric-value {
        color: #fff;
        font-weight: 900;
        font-size: 14px;
    }
    #dashboard .chart-period-selectors {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }
    #dashboard .chart-period-btn {
        padding: 6px 12px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: rgba(255,255,255,.7);
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s;
    }
    #dashboard .chart-period-btn:hover {
        background: rgba(255,255,255,.1);
        color: #fff;
    }
    #dashboard .chart-period-btn.active {
        background: #E31937;
        color: #fff;
    }
    #dashboard .dashboard-chart-canvas-wrap {
        position: relative;
        height: 180px;
        min-height: 180px;
        width: 100%;
    }
    #dashboard .dashboard-chart-canvas-wrap canvas {
        max-width: 100% !important;
    }
    @media (max-width: 480px) {
        #dashboard .dashboard-chart-card { padding: 16px; min-height: 360px; }
        #dashboard .dashboard-chart-canvas-wrap { height: 160px; }
        #dashboard .chart-main-value { font-size: 28px; }
    }
</style>
@endpush

@section('content')
<div class="wrap" id="dashboard">
    <!-- Hero / Welcome -->
    <div class="surface">
                    <div class="heroCard">
                        <div class="heroText">
                            <h3>Welcome back, {{ $user?->name ?? 'Investor' }}</h3>
                            <p>Track your investments, manage your portfolio, and explore opportunities.</p>
                        </div>

                        <div class="balanceBox">
                            <div class="balanceTop">
                                <span>Available Balance</span>
                                <span
                                    class="inline-flex w-8 h-8 rounded-xl border border-white/10 bg-[#E31937]/25 items-center justify-center">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        style="color:rgba(255,255,255,.85)" stroke-width="2">
                                        <path
                                            d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2" />
                                        <path d="M21 12h-7a2 2 0 0 0 0 4h7" />
                                    </svg>
                                </span>
                            </div>
                            <div class="balanceAmt">
                                ${{ number_format($dashboardStats['available_balance'] ?? 0, 2) }}
                            </div>
                            <div class="balanceBtns">
                                <a href="{{ route('dashboard.wallet.deposit') }}" class="sbtn deposit-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="flex-shrink: 0;"><path d="M12 5v14M5 12h14" /></svg> Deposit</a>
                                <a href="{{ route('dashboard.wallet.withdraw') }}" class="sbtn ghost withdraw-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="flex-shrink: 0;"><path d="M5 12h14" /></svg>Withdraw</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video -->
                <div class="dashboard-video-wrap">
                    <video id="dashboardVideo" controls autoplay muted loop playsinline preload="auto" poster="{{ asset('images/logo.png') }}">
                        <source src="{{ asset('videos/tesla2.mp4') }}" type="video/mp4">
                    </video>
                </div>


                <!-- Live charts: Balance, Profit, Investments -->
                <div class="dashboard-charts-grid">
                    <!-- Balance Chart -->
                    <div class="dashboard-chart-card">
                        <div class="chart-header">
                            <h4>Balance Overview</h4>
                            <span class="chart-live-badge">
                                <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%; display: inline-block;"></span>
                                Live
                            </span>
                        </div>
                        <div class="chart-value-section">
                            <div class="chart-main-value">${{ number_format($dashboardStats['available_balance'] ?? 0, 2) }}</div>
                            @php
                                $balanceChange = ($chartData['balance'][6] ?? 0) - ($chartData['balance'][0] ?? 0);
                                $balanceChangePercent = ($chartData['balance'][0] ?? 0) > 0 ? (($balanceChange / ($chartData['balance'][0] ?? 1)) * 100) : 0;
                                $isBalancePositive = $balanceChange >= 0;
                            @endphp
                            <div class="chart-change {{ $isBalancePositive ? 'positive' : 'negative' }}">
                                <span>{{ $isBalancePositive ? '↑' : '↓' }}</span>
                                <span>${{ number_format(abs($balanceChange), 2) }} ({{ $isBalancePositive ? '+' : '' }}{{ number_format($balanceChangePercent, 2) }}%)</span>
                            </div>
                        </div>
                        <div class="chart-metrics">
                            <div class="chart-metric">
                                <div class="chart-metric-label">Opening</div>
                                <div class="chart-metric-value">${{ number_format(($chartData['balance'][0] ?? 0), 2) }}</div>
                            </div>
                            <div class="chart-metric">
                                <div class="chart-metric-label">Current</div>
                                <div class="chart-metric-value">${{ number_format(($chartData['balance'][6] ?? ($chartData['balance'][count($chartData['balance'] ?? []) - 1] ?? 0)), 2) }}</div>
                            </div>
                            <div class="chart-metric">
                                <div class="chart-metric-label">Low</div>
                                <div class="chart-metric-value">${{ number_format((!empty($chartData['balance']) ? min($chartData['balance']) : 0), 2) }}</div>
                            </div>
                            <div class="chart-metric">
                                <div class="chart-metric-label">High</div>
                                <div class="chart-metric-value">${{ number_format((!empty($chartData['balance']) ? max($chartData['balance']) : 0), 2) }}</div>
                            </div>
                        </div>
                        <div class="chart-period-selectors">
                            <button class="chart-period-btn active" data-chart="balance" data-period="7d">7D</button>
                            <button class="chart-period-btn" data-chart="balance" data-period="30d">30D</button>
                            <button class="chart-period-btn" data-chart="balance" data-period="90d">90D</button>
                        </div>
                        <div class="dashboard-chart-canvas-wrap">
                            <canvas id="chartBalance"></canvas>
                        </div>
                    </div>

                    <!-- Profit Chart -->
                    <div class="dashboard-chart-card">
                        <div class="chart-header">
                            <h4>Profit Overview</h4>
                            <span class="chart-live-badge">
                                <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%; display: inline-block;"></span>
                                Live
                            </span>
                        </div>
                        <div class="chart-value-section">
                            @php
                                $currentProfit = $user->total_profit ?? 0;
                                $profitChange = $currentProfit - ($chartData['profit'][0] ?? 0);
                                $profitChangePercent = ($chartData['profit'][0] ?? 0) > 0 ? (($profitChange / ($chartData['profit'][0] ?? 1)) * 100) : 0;
                                $isProfitPositive = $profitChange >= 0;
                            @endphp
                            <div class="chart-main-value">${{ number_format($currentProfit, 2) }}</div>
                            <div class="chart-change {{ $isProfitPositive ? 'positive' : 'negative' }}">
                                <span>{{ $isProfitPositive ? '↑' : '↓' }}</span>
                                <span>${{ number_format(abs($profitChange), 2) }} ({{ $isProfitPositive ? '+' : '' }}{{ number_format($profitChangePercent, 2) }}%)</span>
                            </div>
                        </div>
                        <div class="chart-metrics">
                            <div class="chart-metric">
                                <div class="chart-metric-label">Opening</div>
                                <div class="chart-metric-value">${{ number_format($chartData['profit'][0] ?? 0, 2) }}</div>
                            </div>
                            <div class="chart-metric">
                                <div class="chart-metric-label">Current</div>
                                <div class="chart-metric-value">${{ number_format($user->total_profit ?? 0, 2) }}</div>
                            </div>
                            <div class="chart-metric">
                                <div class="chart-metric-label">Low</div>
                                <div class="chart-metric-value">${{ number_format((!empty($chartData['profit']) ? min($chartData['profit']) : 0), 2) }}</div>
                            </div>
                            <div class="chart-metric">
                                <div class="chart-metric-label">High</div>
                                <div class="chart-metric-value">${{ number_format((!empty($chartData['profit']) ? max($chartData['profit']) : 0), 2) }}</div>
                            </div>
                        </div>
                        <div class="chart-period-selectors">
                            <button class="chart-period-btn active" data-chart="profit" data-period="7d">7D</button>
                            <button class="chart-period-btn" data-chart="profit" data-period="30d">30D</button>
                            <button class="chart-period-btn" data-chart="profit" data-period="90d">90D</button>
                        </div>
                        <div class="dashboard-chart-canvas-wrap">
                            <canvas id="chartProfit"></canvas>
                        </div>
                    </div>

                    <!-- Investments Chart -->
                    <div class="dashboard-chart-card">
                        <div class="chart-header">
                            <h4>Investments Overview</h4>
                            <span class="chart-live-badge">
                                <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%; display: inline-block;"></span>
                                Live
                            </span>
                        </div>
                        <div class="chart-value-section">
                            @php
                                $currentInvestments = $chartData['investments'][6] ?? 0;
                                $investmentsChange = $currentInvestments - ($chartData['investments'][0] ?? 0);
                                $investmentsChangePercent = ($chartData['investments'][0] ?? 0) > 0 ? (($investmentsChange / ($chartData['investments'][0] ?? 1)) * 100) : 0;
                                $isInvestmentsPositive = $investmentsChange >= 0;
                            @endphp
                            <div class="chart-main-value">${{ number_format($currentInvestments, 2) }}</div>
                            <div class="chart-change {{ $isInvestmentsPositive ? 'positive' : 'negative' }}">
                                <span>{{ $isInvestmentsPositive ? '↑' : '↓' }}</span>
                                <span>${{ number_format(abs($investmentsChange), 2) }} ({{ $isInvestmentsPositive ? '+' : '' }}{{ number_format($investmentsChangePercent, 2) }}%)</span>
                            </div>
                        </div>
                        <div class="chart-metrics">
                            <div class="chart-metric">
                                <div class="chart-metric-label">Opening</div>
                                <div class="chart-metric-value">${{ number_format($chartData['investments'][0] ?? 0, 2) }}</div>
                            </div>
                            <div class="chart-metric">
                                <div class="chart-metric-label">Current</div>
                                <div class="chart-metric-value">${{ number_format(($chartData['investments'][6] ?? ($chartData['investments'][count($chartData['investments'] ?? []) - 1] ?? 0)), 2) }}</div>
                            </div>
                            <div class="chart-metric">
                                <div class="chart-metric-label">Low</div>
                                <div class="chart-metric-value">${{ number_format((!empty($chartData['investments']) ? min($chartData['investments']) : 0), 2) }}</div>
                            </div>
                            <div class="chart-metric">
                                <div class="chart-metric-label">High</div>
                                <div class="chart-metric-value">${{ number_format((!empty($chartData['investments']) ? max($chartData['investments']) : 0), 2) }}</div>
                            </div>
                        </div>
                        <div class="chart-period-selectors">
                            <button class="chart-period-btn active" data-chart="investments" data-period="7d">7D</button>
                            <button class="chart-period-btn" data-chart="investments" data-period="30d">30D</button>
                            <button class="chart-period-btn" data-chart="investments" data-period="90d">90D</button>
                        </div>
                        <div class="dashboard-chart-canvas-wrap">
                            <canvas id="chartInvestments"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="quickGrid">
                    <div class="quick">
                        <div>
                            <h4>Browse Cars</h4>
                            <p>Explore our inventory</p>
                            <a href="{{ route('dashboard.inventory') }}" style="color:#2563eb;">View Inventory </a>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"
                            style="opacity:.55">
                            <path d="M3 12h18" />
                            <path d="M6 12l3-7h6l3 7" />
                            <path d="M7 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                            <path d="M17 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                        </svg>
                    </div>

                    <div class="quick">
                        <div>
                            <h4>Investments</h4>
                            <p>Grow your wealth</p>
                            <a href="{{ route('dashboard.investments') }}" style="color:#10b981;">Start Investing </a>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"
                            style="opacity:.65">
                            <path d="M3 17l6-6 4 4 8-8" />
                            <path d="M21 7v6h-6" />
                        </svg>
                    </div>

                    <div class="quick">
                        <div>
                            <h4>Stocks</h4>
                            <p>Trade individual stocks</p>
                            <a href="{{ route('dashboard.stocks') }}" style="color:#7c3aed;">Trade Stocks </a>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2"
                            style="opacity:.65">
                            <path d="M4 19V5" />
                            <path d="M4 19h16" />
                            <path d="M8 15V9" />
                            <path d="M12 15V7" />
                            <path d="M16 15v-5" />
                        </svg>
                    </div>

                    <div class="quick">
                        <div>
                            <h4>Portfolio</h4>
                            <p>View your holdings</p>
                            <a href="{{ route('dashboard.portfolio') }}" style="color:#f59e0b;">View Portfolio </a>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"
                            style="opacity:.65">
                            <path d="M7 7h10v10H7z" />
                            <path d="M4 10V4h6" />
                            <path d="M20 14v6h-6" />
                        </svg>
                    </div>
                </div>

                <!-- Lower panels -->
                <div class="lowerGrid">
                    <!-- Recent Orders -->
                    <div class="whitePanel">
                        <div class="panelHead">
                            <div>
                                <h5>Recent Orders</h5>
                                <small>Your latest Tesla purchases</small>
                            </div>
                            <a class="viewAll" href="{{ route('dashboard.orders') }}">
                                View All 
                            </a>
                        </div>

                        @forelse ($recentOrders as $order)
                            <div class="orderRow">
                                <div class="orderLeft">
                                    <div class="thumb">
                                        @if($order->car && $order->car->image_url)
                                            <img src="{{ $order->car->image_url }}" alt="{{ $order->car->name }}" style="width:100%;height:100%;object-fit:cover;" />
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="orderTitle">{{ $order->car?->name ?? 'Tesla Vehicle' }}</div>
                                        <div class="orderMeta">
                                            {{ $order->car?->year }} {{ $order->car?->model }}
                                            @if($order->car?->variant)
                                                · {{ $order->car->variant }}
                                            @endif
                                            · {{ $order->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="orderRight">
                                    <div class="price">
                                        ${{ number_format($order->total_price, 2) }}
                                    </div>
                                    <span class="status ok">{{ $order->status }}</span>
                                </div>
                            </div>
                        @empty
                            <div style="padding: 20px; font-size: 13px; color: #6b7280;">
                                You have no recent orders yet.
                            </div>
                        @endforelse
                    </div>

                    <!-- Market Overview -->
                    <div class="whitePanel">
                        <div class="panelHead">
                            <div>
                                <h5>Market Overview</h5>
                                <small>Live market data</small>
                            </div>
                            <a class="viewAll" href="{{ route('dashboard.stocks') }}">
                                View All 
                            </a>
                        </div>

                        @forelse ($topStocks as $stock)
                            @php
                                $initials = strtoupper(substr($stock->ticker, 0, 2));
                                $colors = [
                                    ['bg' => '#fee2e2', 'text' => '#dc2626'],
                                    ['bg' => '#dcfce7', 'text' => '#16a34a'],
                                    ['bg' => '#dbeafe', 'text' => '#2563eb'],
                                    ['bg' => '#fef3c7', 'text' => '#d97706'],
                                    ['bg' => '#e9d5ff', 'text' => '#7c3aed'],
                                ];
                                $colorIndex = $loop->index % count($colors);
                                $color = $colors[$colorIndex];
                                $changeColor = $stock->change_percent >= 0 ? '#10b981' : '#ef4444';
                            @endphp
                            <div class="mItem">
                                <div class="mLeft">
                                    <div class="logo" style="background:{{ $color['bg'] }};color:{{ $color['text'] }};overflow:hidden;position:relative;padding:0;">
                                        <img src="{{ route('stock-logo', ['ticker' => $stock->ticker]) }}" 
                                             alt="{{ $stock->ticker }}" 
                                             style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:1;"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"
                                             loading="lazy">
                                        <span style="display:none;position:relative;z-index:0;">{{ $initials }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="mName">{{ $stock->name }}</div>
                                        <div class="mTicker">{{ $stock->ticker }}</div>
                                    </div>
                                </div>
                                <div class="mRight">
                                    <div class="mPrice">${{ number_format($stock->price, 2) }}</div>
                                    <div class="mChange" style="color:{{ $changeColor }};">
                                        {{ $stock->change_percent >= 0 ? '+' : '' }}{{ number_format($stock->change_percent, 2) }}%
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="padding: 20px; font-size: 13px; color: #6b7280;">
                                No market data available.
                            </div>
                        @endforelse
                    </div>
                </div>
                </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function() {
    @php
        $defaultChartData = [
            'labels' => [],
            'balance' => [],
            'investments' => [],
            'profit' => []
        ];
        $chartDataForJs = $chartData ?? $defaultChartData;
    @endphp
    var d = @json($chartDataForJs);
    var labels = d.labels || [];
    var balance = d.balance || [];
    var investments = d.investments || [];
    var profit = d.profit || [];

    function createGradient(ctx, color, opacity1, opacity2) {
        var gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color.replace(')', ', ' + opacity1 + ')').replace('rgb', 'rgba'));
        gradient.addColorStop(1, color.replace(')', ', ' + opacity2 + ')').replace('rgb', 'rgba'));
        return gradient;
    }

    function makeChart(id, data, color, label) {
        var el = document.getElementById(id);
        if (!el || !data.length) return;
        var ctx = el.getContext('2d');
        
        var gradient = createGradient(ctx, color, 0.4, 0.05);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: color,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: color,
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                    borderWidth: 2.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: color,
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { 
                            font: { size: 11, weight: '700' }, 
                            color: 'rgba(255,255,255,.6)',
                            maxRotation: 0
                        },
                        border: { display: false }
                    },
                    y: {
                        beginAtZero: false,
                        grid: { 
                            color: 'rgba(255,255,255,.08)',
                            drawBorder: false
                        },
                        ticks: { 
                            font: { size: 11, weight: '700' }, 
                            color: 'rgba(255,255,255,.6)',
                            callback: function(value) {
                                return '$' + value.toFixed(0);
                            }
                        },
                        border: { display: false }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    if (typeof Chart !== 'undefined') {
        makeChart('chartBalance', balance, 'rgb(16, 185, 129)', 'Balance');
        makeChart('chartProfit', profit, 'rgb(227, 25, 55)', 'Profit');
        makeChart('chartInvestments', investments, 'rgb(37, 99, 235)', 'Investments');
    }

    // Period selector functionality
    document.querySelectorAll('.chart-period-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var chartType = this.getAttribute('data-chart');
            var period = this.getAttribute('data-period');
            
            // Update active state
            this.parentElement.querySelectorAll('.chart-period-btn').forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
            
            // In a real implementation, you would fetch new data based on the period
            // For now, we'll just log it
            console.log('Period changed:', chartType, period);
        });
    });

    var v = document.getElementById('dashboardVideo');
    if (v) {
        v.muted = true;
        v.play().catch(function() {});
    }
})();
</script>
@endpush
@endsection