@extends('layouts.dashboard')

@section('title', 'Primrica Global Capital Stocks')

@section('topTitle', 'Stocks')

@section('content')
<div class="wrap" id="stocks">
    <!-- Success/Error Messages -->
    @if ($errors->any())
    <div class="surface" style="margin-bottom: 12px;">
        <div
            style="padding: 12px 16px; border-radius: 12px; background:#fee2e2; border:1px solid #fecaca; font-size:12px; color:#991b1b;">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
    @endif
    @if (session('success'))
    <div class="surface" style="margin-bottom: 12px;">
        <div
            style="padding: 12px 16px; border-radius: 12px; background:#dcfce7; border:1px solid #bbf7d0; font-size:12px; color:#166534;">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Stocks Header -->
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3>Stock Trading</h3>
                <p>Buy and sell stocks on the market.</p>
            </div>
        </div>
    </div>

    <!-- Stocks List -->
    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="whitePanel">
            <div class="panelHead">
                <div>
                    <h5>Available Stocks</h5>
                    <small>Browse and trade stocks</small>
                </div>
            </div>
            <div id="stocksContainer" style="padding: 0;">
                <!-- Stocks will be dynamically loaded here -->
            </div>

            <!-- Pagination -->
            <div class="paginationWrapper">
                <div class="paginationInfo" id="paginationInfo">
                    Loading...
                </div>
                <div class="pagination" id="pagination">
                    <!-- Pagination buttons will be dynamically generated -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Details Modal -->
<div id="stockDetailsModal" class="modal">
    <div class="modalOverlay"></div>
    <div class="modalContent">
        <button class="modalClose" id="closeDetailsModal">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12" />
            </svg>
        </button>
        <div class="modalHeader">
            <div class="modalStockLogo" id="modalStockLogo">
                <img id="modalLogoImg" src="" alt=""
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                <span id="modalLogoFallback" style="display:none;"></span>
            </div>
            <div class="modalStockInfo">
                <h3 id="modalStockName">-</h3>
                <p id="modalStockTicker">-</p>
            </div>
        </div>
        <div class="modalBody">
            <div class="modalStats">
                <div class="modalStat">
                    <label>Current Price</label>
                    <strong id="modalPrice">-</strong>
                </div>
                <div class="modalStat">
                    <label>Change</label>
                    <strong id="modalChange">-</strong>
                </div>
                <div class="modalStat">
                    <label>Volume</label>
                    <strong id="modalVolume">-</strong>
                </div>
                <div class="modalStat">
                    <label>Market Cap</label>
                    <strong id="modalMarketCap">-</strong>
                </div>
            </div>
            <div class="modalDescription">
                <h4>About</h4>
                <p id="modalDescription">Loading company information...</p>
            </div>
        </div>
        <div class="modalFooter">
            <button class="modalBtn secondary" id="addToWatchlistFromModal">Add to Watchlist</button>
            <button class="modalBtn primary" id="addToPortfolioFromModal">Add to Portfolio</button>
        </div>
    </div>
</div>

<!-- Add to Portfolio Modal -->
<div id="addPortfolioModal" class="modal">
    <div class="modalOverlay"></div>
    <div class="modalContent portfolioModal">
        <button class="modalClose" id="closePortfolioModal">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12" />
            </svg>
        </button>
        <div class="modalHeader">
            <div class="modalStockLogo" id="portfolioModalLogo">
                <img id="portfolioLogoImg" src="" alt=""
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                <span id="portfolioLogoFallback" style="display:none;"></span>
            </div>
            <div class="modalStockInfo">
                <h3 id="portfolioStockName">-</h3>
                <p id="portfolioStockTicker">-</p>
            </div>
        </div>
        <div class="modalBody">
            <div class="portfolioPriceInfo">
                <div class="priceDisplay">
                    <label>Current Price</label>
                    <strong id="portfolioCurrentPrice">$0.00</strong>
                </div>
                <div class="priceChangeDisplay" id="portfolioPriceChange">
                    <span>+0.00</span>
                    <span>+0.00%</span>
                </div>
            </div>
            <form id="portfolioForm" class="portfolioForm" method="POST"
                action="{{ route('dashboard.stocks.purchase.submit') }}">
                @csrf
                <input type="hidden" name="stock_id" id="portfolioStockId">
                <div class="formGroup">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" required>
                    <small>Enter the number of shares you want to purchase</small>
                </div>
                <div class="formGroup">
                    <label for="orderType">Order Type</label>
                    <select id="orderType" name="order_type" required>
                        <option value="market">Market Order</option>
                        <option value="limit">Limit Order</option>
                    </select>
                </div>
                <div class="formGroup" id="limitPriceGroup" style="display:none;">
                    <label for="limitPrice">Limit Price</label>
                    <input type="number" id="limitPrice" name="limit_price" step="0.01" min="0.01">
                    <small>Maximum price you're willing to pay per share</small>
                </div>
                <div class="totalCost">
                    <label>Total Cost</label>
                    <strong id="totalCost">$0.00</strong>
                </div>
                <div
                    style="margin-top: 12px; padding: 10px; background: #f9fafb; border-radius: 8px; font-size: 11px; color: #6b7280;">
                    <strong>Available Balance:</strong> ${{ number_format($currentBalance ?? 0, 2) }}
                </div>
            </form>
        </div>
        <div class="modalFooter">
            <button class="modalBtn secondary" id="cancelPortfolio">Cancel</button>
            <button class="modalBtn primary" id="confirmPurchase">Confirm Purchase</button>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Stock Row Styles */
    .stockRow {
        padding: 14px 18px;
        display: grid;
        grid-template-columns: 2fr 1.5fr 1fr 120px;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid rgba(0, 0, 0, .06);
        transition: background-color 0.15s ease;
    }

    .stockRow:hover {
        background-color: #f9fafb;
    }

    .stockRow:last-child {
        border-bottom: none;
    }

    .stockLeft {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .stockLogo {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: 12px;
        font-weight: 900;
        flex: 0 0 auto;
        border: 1px solid rgba(0, 0, 0, .08);
        background: #fff;
        overflow: hidden;
        position: relative;
    }

    .stockLogo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 6px;
    }

    .stockInfo {
        min-width: 0;
        flex: 1;
    }

    .stockName {
        font-size: 13px;
        font-weight: 900;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }

    .stockTicker {
        font-size: 11px;
        font-weight: 800;
        color: #6b7280;
    }

    .stockPrice {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .stockPriceValue {
        font-size: 14px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -0.01em;
    }

    .stockChange {
        display: flex;
        flex-direction: column;
        gap: 2px;
        font-size: 11px;
        font-weight: 900;
    }

    .stockChange span:first-child {
        font-size: 12px;
    }

    .stockMarket {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 11px;
        font-weight: 800;
        color: #6b7280;
        text-align: right;
    }

    .stockActions {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-end;
    }

    .stockActionBtn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid rgba(0, 0, 0, .10);
        background: #fff;
        color: #6b7280;
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: all 0.15s ease;
        padding: 0;
    }

    .stockActionBtn:hover {
        background: #f3f4f6;
        color: #111827;
        border-color: rgba(0, 0, 0, .15);
        transform: translateY(-1px);
    }

    .stockActionBtn:active {
        transform: translateY(0);
    }

    .stockActionBtn svg {
        width: 16px;
        height: 16px;
    }

    /* Pagination Styles */
    .paginationWrapper {
        padding: 16px 18px;
        border-top: 1px solid rgba(0, 0, 0, .06);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f9fafb;
    }

    .paginationInfo {
        font-size: 12px;
        font-weight: 800;
        color: #6b7280;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .paginationBtn {
        min-width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid rgba(0, 0, 0, .10);
        background: #fff;
        color: #6b7280;
        font-size: 12px;
        font-weight: 900;
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: all 0.15s ease;
        padding: 0 8px;
    }

    .paginationBtn:hover:not(:disabled) {
        background: #f3f4f6;
        color: #111827;
        border-color: rgba(0, 0, 0, .15);
    }

    .paginationBtn.active {
        background: #E31937;
        color: #fff;
        border-color: #0b0c10;
    }

    .paginationBtn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .paginationBtn svg {
        width: 14px;
        height: 14px;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
        animation: fadeIn 0.2s ease;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modalOverlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
    }

    .modalContent {
        position: relative;
        background: #fff;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        z-index: 1001;
        animation: slideUp 0.3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .portfolioModal {
        max-width: 500px;
    }

    .modalClose {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        background: #f3f4f6;
        color: #6b7280;
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: all 0.15s ease;
        z-index: 10;
    }

    .modalClose:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .modalHeader {
        padding: 24px 24px 20px;
        border-bottom: 1px solid rgba(0, 0, 0, .06);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .modalStockLogo {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        border: 1px solid rgba(0, 0, 0, .08);
        background: #f9fafb;
        overflow: hidden;
        flex: 0 0 auto;
    }

    .modalStockLogo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }

    .modalStockInfo h3 {
        font-size: 18px;
        font-weight: 900;
        color: #111827;
        margin: 0 0 4px;
    }

    .modalStockInfo p {
        font-size: 13px;
        font-weight: 800;
        color: #6b7280;
        margin: 0;
    }

    .modalBody {
        padding: 24px;
    }

    .modalStats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .modalStat {
        padding: 16px;
        background: #f9fafb;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, .06);
    }

    .modalStat label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .modalStat strong {
        display: block;
        font-size: 16px;
        font-weight: 900;
        color: #111827;
    }

    .modalDescription h4 {
        font-size: 14px;
        font-weight: 900;
        color: #111827;
        margin: 0 0 8px;
    }

    .modalDescription p {
        font-size: 13px;
        line-height: 1.6;
        color: #6b7280;
        margin: 0;
    }

    .modalFooter {
        padding: 20px 24px;
        border-top: 1px solid rgba(0, 0, 0, .06);
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .modalBtn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 900;
        cursor: pointer;
        transition: all 0.15s ease;
        border: none;
    }

    .modalBtn.primary {
        background: #E31937;
        color: #fff;
    }

    .modalBtn.primary:hover {
        background: #1a1c24;
        transform: translateY(-1px);
    }

    .modalBtn.secondary {
        background: #f3f4f6;
        color: #6b7280;
    }

    .modalBtn.secondary:hover {
        background: #e5e7eb;
        color: #111827;
    }

    /* Portfolio Modal Specific Styles */
    .portfolioPriceInfo {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        border: 1px solid rgba(0, 0, 0, .06);
    }

    .priceDisplay {
        margin-bottom: 12px;
    }

    .priceDisplay label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .priceDisplay strong {
        display: block;
        font-size: 24px;
        font-weight: 900;
        color: #111827;
    }

    .priceChangeDisplay {
        display: flex;
        gap: 12px;
        font-size: 13px;
        font-weight: 900;
    }

    .portfolioForm {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .formGroup {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .formGroup label {
        font-size: 12px;
        font-weight: 900;
        color: #111827;
    }

    .formGroup input,
    .formGroup select {
        padding: 12px;
        border: 1px solid rgba(0, 0, 0, .10);
        border-radius: 8px;
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        background: #fff;
        transition: all 0.15s ease;
    }

    .formGroup input:focus,
    .formGroup select:focus {
        outline: none;
        border-color: #0b0c10;
        box-shadow: 0 0 0 3px rgba(11, 12, 16, 0.1);
    }

    .formGroup small {
        font-size: 11px;
        color: #6b7280;
        font-weight: 600;
    }

    .totalCost {
        padding: 16px;
        background: #f9fafb;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, .06);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .totalCost label {
        font-size: 13px;
        font-weight: 900;
        color: #6b7280;
    }

    .totalCost strong {
        font-size: 18px;
        font-weight: 900;
        color: #111827;
    }

    @media (max-width: 1100px) {
        .stockRow {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .stockPrice,
        .stockMarket,
        .stockActions {
            justify-content: flex-start;
            text-align: left;
        }

        .paginationWrapper {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }

        .modalContent {
            width: 95%;
            max-height: 95vh;
        }

        .modalStats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Function to get logo URL with multiple fallbacks
    function getStockLogo(ticker, companyDomain = '') {
        // Use multiple reliable sources - try IEX first as it's most reliable
        return `https://storage.googleapis.com/iex/api/logos/${ticker}.png`;
    }

    // Stock data from database (pre-formatted in controller)
    const stocksData = @json($stocksForJs);

    const itemsPerPage = 10;
    let currentPage = 1;

    // Logo error handler with fallbacks
    function handleLogoError(img, ticker, domain = '') {
        const fallback = img.nextElementSibling;
        // If backend proxy failed, show fallback text
        img.style.display = 'none';
        if (fallback && fallback.classList.contains('logoFallback')) {
            fallback.style.display = 'grid';
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        renderStocks();
        setupPagination();
        setupModals();
    });

    // Render stocks for current page
    function renderStocks() {
        const container = document.getElementById('stocksContainer');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pageStocks = stocksData.slice(start, end);

        container.innerHTML = pageStocks.map(stock => {
            const isPositive = stock.change >= 0;
            const changeColor = isPositive ? '#10b981' : '#ef4444';
            const changeSign = isPositive ? '+' : '';
            // Use backend proxy to avoid CORS issues
            const logoBaseUrl = '{{ route("dashboard.stock-logo", ["ticker" => "TICKER"]) }}'.replace('TICKER', '');
            const logoUrl = logoBaseUrl + stock.ticker;

            return `
                <div class="stockRow" data-ticker="${stock.ticker}">
                    <div class="stockLeft">
                        <div class="stockLogo" style="background:#f9fafb;">
                            <img src="${logoUrl}" alt="${stock.name}" loading="lazy" onerror="handleLogoError(this, '${stock.ticker}', '${stock.domain || ''}')">
                            <span class="logoFallback" style="display:none; font-size:11px; font-weight:900; color:#6b7280;">${stock.ticker}</span>
                        </div>
                        <div class="stockInfo">
                            <div class="stockName">${stock.name}</div>
                            <div class="stockTicker">${stock.ticker}</div>
                        </div>
                    </div>
                    <div class="stockPrice">
                        <div class="stockPriceValue">$${stock.price.toFixed(2)}</div>
                        <div class="stockChange" style="color:${changeColor};">
                            <span>${changeSign}${stock.change.toFixed(2)}</span>
                            <span>${changeSign}${stock.changePercent.toFixed(2)}%</span>
                        </div>
                    </div>
                    <div class="stockMarket">
                        <div>${stock.volume}</div>
                        <div>${stock.marketCap}</div>
                    </div>
                    <div class="stockActions">
                        <button class="stockActionBtn viewDetails" data-ticker="${stock.ticker}" title="View Details">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                        <button class="stockActionBtn addPortfolio" data-ticker="${stock.ticker}" title="Add to Portfolio">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                        </button>
                        <button class="stockActionBtn addWatchlist" data-ticker="${stock.ticker}" title="Add to Watchlist">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        }).join('');

        // Re-attach event listeners
        attachEventListeners();
    }

    // Setup pagination
    function setupPagination() {
        const totalPages = Math.ceil(stocksData.length / itemsPerPage);
        const pagination = document.getElementById('pagination');
        const info = document.getElementById('paginationInfo');
        
        const start = (currentPage - 1) * itemsPerPage + 1;
        const end = Math.min(currentPage * itemsPerPage, stocksData.length);
        info.textContent = `Showing ${start} to ${end} of ${stocksData.length} results`;

        let paginationHTML = '';

        // Previous button
        paginationHTML += `
            <button class="paginationBtn" ${currentPage === 1 ? 'disabled' : ''} data-page="prev">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>
        `;

        // Page numbers
        const maxVisible = 7;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        if (startPage > 1) {
            paginationHTML += `<button class="paginationBtn" data-page="1">1</button>`;
            if (startPage > 2) {
                paginationHTML += `<button class="paginationBtn" disabled>...</button>`;
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <button class="paginationBtn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>
            `;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHTML += `<button class="paginationBtn" disabled>...</button>`;
            }
            paginationHTML += `<button class="paginationBtn" data-page="${totalPages}">${totalPages}</button>`;
        }

        // Next button
        paginationHTML += `
            <button class="paginationBtn" ${currentPage === totalPages ? 'disabled' : ''} data-page="next">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>
        `;

        pagination.innerHTML = paginationHTML;

        // Attach pagination event listeners
        pagination.querySelectorAll('.paginationBtn:not(:disabled)').forEach(btn => {
            btn.addEventListener('click', function() {
                const page = this.getAttribute('data-page');
                if (page === 'prev' && currentPage > 1) {
                    currentPage--;
                } else if (page === 'next' && currentPage < totalPages) {
                    currentPage++;
                } else if (!isNaN(page)) {
                    currentPage = parseInt(page);
                }
                renderStocks();
                setupPagination();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    }

    // Setup modals
    function setupModals() {
        const detailsModal = document.getElementById('stockDetailsModal');
        const portfolioModal = document.getElementById('addPortfolioModal');
        
        // Close modals
        document.getElementById('closeDetailsModal').addEventListener('click', () => {
            detailsModal.classList.remove('active');
        });
        
        document.getElementById('closePortfolioModal').addEventListener('click', () => {
            portfolioModal.classList.remove('active');
        });
        
        document.getElementById('cancelPortfolio').addEventListener('click', () => {
            portfolioModal.classList.remove('active');
        });

        // Close on overlay click
        detailsModal.querySelector('.modalOverlay').addEventListener('click', () => {
            detailsModal.classList.remove('active');
        });
        
        portfolioModal.querySelector('.modalOverlay').addEventListener('click', () => {
            portfolioModal.classList.remove('active');
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                detailsModal.classList.remove('active');
                portfolioModal.classList.remove('active');
            }
        });

        // Order type change
        document.getElementById('orderType').addEventListener('change', function() {
            const limitGroup = document.getElementById('limitPriceGroup');
            if (this.value === 'limit') {
                limitGroup.style.display = 'block';
            } else {
                limitGroup.style.display = 'none';
            }
        });

        // Calculate total cost
        const quantityInput = document.getElementById('quantity');
        quantityInput.addEventListener('input', calculateTotal);
        
        function calculateTotal() {
            const quantity = parseInt(quantityInput.value) || 0;
            const stock = getCurrentPortfolioStock();
            if (stock) {
                const total = quantity * stock.price;
                document.getElementById('totalCost').textContent = `$${total.toFixed(2)}`;
            }
        }

        // Confirm purchase - submit form
        document.getElementById('confirmPurchase').addEventListener('click', function(e) {
            e.preventDefault();
            const stock = getCurrentPortfolioStock();
            const quantity = parseInt(quantityInput.value) || 0;
            const orderTypeSelect = document.getElementById('orderType');
            const orderType = orderTypeSelect ? orderTypeSelect.value : 'market';
            const form = document.getElementById('portfolioForm');
            
            if (!stock || !stock.id) {
                alert('Error: Stock information is incomplete. Please refresh the page and try again.');
                return;
            }
            
            if (quantity <= 0) {
                alert('Please enter a valid quantity (minimum 1 share).');
                return;
            }
            
            // Validate limit price if limit order
            if (orderType === 'limit') {
                const limitPriceInput = document.getElementById('limitPrice');
                const limitPrice = parseFloat(limitPriceInput ? limitPriceInput.value : 0) || 0;
                if (!limitPrice || limitPrice < stock.price) {
                    alert('Limit price must be greater than or equal to the current market price ($' + stock.price.toFixed(2) + ').');
                    return;
                }
            }
            
            // Submit the form
            form.submit();
        });
    }

    let currentPortfolioStock = null;

    function getCurrentPortfolioStock() {
        return currentPortfolioStock;
    }

    // Attach event listeners to action buttons
    function attachEventListeners() {
        // View Details
        document.querySelectorAll('.viewDetails').forEach(btn => {
            btn.addEventListener('click', function() {
                const ticker = this.getAttribute('data-ticker');
                const stock = stocksData.find(s => s.ticker === ticker);
                if (stock) {
                    openDetailsModal(stock);
                }
            });
        });

        // Add to Portfolio
        document.querySelectorAll('.addPortfolio').forEach(btn => {
            btn.addEventListener('click', function() {
                const ticker = this.getAttribute('data-ticker');
                const stock = stocksData.find(s => s.ticker === ticker);
                if (stock) {
                    openPortfolioModal(stock);
                }
            });
        });

        // Add to Watchlist
        document.querySelectorAll('.addWatchlist').forEach(btn => {
            btn.addEventListener('click', function() {
                const ticker = this.getAttribute('data-ticker');
                const stock = stocksData.find(s => s.ticker === ticker);
                if (stock) {
                    // Here you would make an AJAX call to add to watchlist
                    alert(`${stock.name} (${stock.ticker}) has been added to your watchlist!`);
                }
            });
        });
    }

    // Open details modal
    function openDetailsModal(stock) {
        const modal = document.getElementById('stockDetailsModal');
        const isPositive = stock.change >= 0;
        const changeColor = isPositive ? '#10b981' : '#ef4444';
        const changeSign = isPositive ? '+' : '';

        document.getElementById('modalStockName').textContent = stock.name;
        document.getElementById('modalStockTicker').textContent = stock.ticker;
        document.getElementById('modalPrice').textContent = `$${stock.price.toFixed(2)}`;
        document.getElementById('modalChange').innerHTML = `<span style="color:${changeColor};">${changeSign}${stock.change.toFixed(2)} (${changeSign}${stock.changePercent.toFixed(2)}%)</span>`;
        document.getElementById('modalVolume').textContent = stock.volume;
        document.getElementById('modalMarketCap').textContent = stock.marketCap;
        document.getElementById('modalDescription').textContent = stock.description;

        const logoImg = document.getElementById('modalLogoImg');
        const logoFallback = document.getElementById('modalLogoFallback');
        // Use backend proxy
        const logoBaseUrl = '{{ route("dashboard.stock-logo", ["ticker" => "TICKER"]) }}'.replace('TICKER', '');
        logoImg.src = logoBaseUrl + stock.ticker;
        logoImg.style.display = 'block';
        logoImg.onerror = function() {
            this.style.display = 'none';
            logoFallback.textContent = stock.ticker;
            logoFallback.style.display = 'grid';
        };
        logoFallback.textContent = stock.ticker;
        logoFallback.style.display = 'none';

        // Update action buttons in modal
        document.getElementById('addToWatchlistFromModal').setAttribute('data-ticker', stock.ticker);
        document.getElementById('addToPortfolioFromModal').setAttribute('data-ticker', stock.ticker);

        modal.classList.add('active');
    }

    // Open portfolio modal
    function openPortfolioModal(stock) {
        currentPortfolioStock = stock;
        const modal = document.getElementById('addPortfolioModal');
        const isPositive = stock.change >= 0;
        const changeColor = isPositive ? '#10b981' : '#ef4444';
        const changeSign = isPositive ? '+' : '';

        // Set the stock ID in the hidden input field
        document.getElementById('portfolioStockId').value = stock.id;

        document.getElementById('portfolioStockName').textContent = stock.name;
        document.getElementById('portfolioStockTicker').textContent = stock.ticker;
        document.getElementById('portfolioCurrentPrice').textContent = `$${stock.price.toFixed(2)}`;
        
        const priceChange = document.getElementById('portfolioPriceChange');
        priceChange.innerHTML = `
            <span style="color:${changeColor};">${changeSign}${stock.change.toFixed(2)}</span>
            <span style="color:${changeColor};">${changeSign}${stock.changePercent.toFixed(2)}%</span>
        `;
        priceChange.style.color = changeColor;

        const logoImg = document.getElementById('portfolioLogoImg');
        const logoFallback = document.getElementById('portfolioLogoFallback');
        // Use backend proxy
        const logoBaseUrl = '{{ route("dashboard.stock-logo", ["ticker" => "TICKER"]) }}'.replace('TICKER', '');
        logoImg.src = logoBaseUrl + stock.ticker;
        logoImg.style.display = 'block';
        logoImg.onerror = function() {
            this.style.display = 'none';
            logoFallback.textContent = stock.ticker;
            logoFallback.style.display = 'grid';
        };
        logoFallback.textContent = stock.ticker;
        logoFallback.style.display = 'none';

        // Reset form
        const quantityInput = document.getElementById('quantity');
        const orderTypeSelect = document.getElementById('orderType');
        if (quantityInput) quantityInput.value = 1;
        if (orderTypeSelect) orderTypeSelect.value = 'market';
        document.getElementById('limitPriceGroup').style.display = 'none';
        document.getElementById('totalCost').textContent = `$${stock.price.toFixed(2)}`;

        modal.classList.add('active');
    }

    // Modal action buttons
    document.getElementById('addToWatchlistFromModal').addEventListener('click', function() {
        const ticker = this.getAttribute('data-ticker');
        const stock = stocksData.find(s => s.ticker === ticker);
        if (stock) {
            alert(`${stock.name} (${stock.ticker}) has been added to your watchlist!`);
            document.getElementById('stockDetailsModal').classList.remove('active');
        }
    });

    document.getElementById('addToPortfolioFromModal').addEventListener('click', function() {
        const ticker = this.getAttribute('data-ticker');
        const stock = stocksData.find(s => s.ticker === ticker);
        if (stock) {
            document.getElementById('stockDetailsModal').classList.remove('active');
            setTimeout(() => openPortfolioModal(stock), 200);
        }
    });
</script>
@endpush
@endsection