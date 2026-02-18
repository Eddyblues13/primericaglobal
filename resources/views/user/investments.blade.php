@extends('layouts.dashboard')

@section('title', 'TESLA Investments')

@section('topTitle', 'Investments')

@push('styles')
<style>
    /* Investment page layout */
    .investHeaderTitle {
        font-size: 18px;
        font-weight: 900;
        color: #111827;
        margin: 0 0 4px 0;
    }

    .investHeaderSubtitle {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
    }

    .investFilterBar {
        margin-top: 12px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .06);
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .investFilterLeft {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 900;
        color: #6b7280;
    }

    .investFilterChip {
        padding: 6px 10px;
        border-radius: 999px;
        border: 1px solid rgba(0, 0, 0, .08);
        background: #f9fafb;
        font-size: 11px;
        font-weight: 800;
        color: #374151;
    }

    .investFilterRight {
        font-size: 11px;
        font-weight: 900;
        color: #111827;
        opacity: .7;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .investFilterRight:hover {
        opacity: 1;
    }

    .plansGrid {
        padding: 16px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .planCard {
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, .06);
        background: #fff;
        padding: 14px 14px 12px;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 150px;
    }

    .planCardHeader {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .planName {
        font-size: 13px;
        font-weight: 900;
        color: #111827;
        margin: 0 0 3px 0;
    }

    .planStrategy {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        margin: 0;
    }

    .planRiskBadge {
        font-size: 10px;
        font-weight: 900;
        padding: 4px 10px;
        border-radius: 999px;
        border-width: 1px;
        border-style: solid;
    }

    .riskHigh {
        background: rgba(248, 113, 113, .12);
        color: #b91c1c;
        border-color: rgba(239, 68, 68, .45);
    }

    .riskMedium {
        background: rgba(252, 211, 77, .14);
        color: #92400e;
        border-color: rgba(245, 158, 11, .45);
    }

    .riskLow {
        background: rgba(52, 211, 153, .14);
        color: #047857;
        border-color: rgba(16, 185, 129, .45);
    }

    .planMetaRow {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin: 8px 0 10px;
    }

    .planMetaBlock small {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: #9ca3af;
        margin-bottom: 2px;
    }

    .planMetaBlock strong {
        font-size: 13px;
        font-weight: 900;
        color: #111827;
    }

    .planReturnPositive {
        color: #10b981;
    }

    .planFooter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
    }

    .planDetailsLink {
        font-size: 11px;
        font-weight: 900;
        color: #2563eb;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .planDetailsLink:hover {
        text-decoration: underline;
    }

    .planPrimaryBtn {
        height: 32px;
        padding: 0 18px;
        border-radius: 10px;
        background: #E31937;
        color: #fff;
        font-size: 11px;
        font-weight: 900;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .planPrimaryBtn:hover {
        opacity: .9;
    }

    .sectionTitleRow {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sectionSubtitle {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        margin-top: 3px;
    }

    .plansCountText {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
    }

    /* Plan details modal */
    .planModalOverlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 80;
    }

    .planModalOverlay.is-visible {
        display: flex;
    }

    .planModal {
        width: 480px;
        max-width: 95vw;
        max-height: 90vh;
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid rgba(0, 0, 0, .06);
        box-shadow: 0 24px 50px rgba(15, 23, 42, 0.25);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .planModalHeader {
        padding: 16px 18px 10px;
        border-bottom: 1px solid rgba(0, 0, 0, .06);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .planModalTitle {
        font-size: 15px;
        font-weight: 900;
        color: #111827;
        margin: 0 0 3px 0;
    }

    .planModalSubtitle {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        margin: 0;
    }

    .planModalClose {
        border: none;
        background: transparent;
        color: #9ca3af;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
    }

    .planModalBody {
        padding: 14px 18px 16px;
        overflow-y: auto;
    }

    .planModalStatsRow {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 14px;
    }

    .planModalStat small {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: #9ca3af;
        margin-bottom: 2px;
    }

    .planModalStat strong {
        font-size: 14px;
        font-weight: 900;
        color: #111827;
    }

    .planModalMeta {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .planModalFooter {
        padding: 12px 18px 16px;
        border-top: 1px solid rgba(0, 0, 0, .06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .planModalCta {
        height: 36px;
        padding: 0 20px;
        border-radius: 10px;
        background: #E31937;
        color: #ffffff;
        font-size: 12px;
        font-weight: 900;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .planModalHelper {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
    }

    @media (max-width: 1100px) {
        .plansGrid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .investFilterBar {
            flex-direction: column;
            align-items: flex-start;
        }

        .plansGrid {
            grid-template-columns: minmax(0, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="wrap" id="investments">
    <!-- Success/Error Messages -->
    @if ($errors->any())
        <div class="surface" style="margin-bottom: 12px;">
            <div style="padding: 12px 16px; border-radius: 12px; background:#fee2e2; border:1px solid #fecaca; font-size:12px; color:#991b1b;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif
    @if (session('success'))
        <div class="surface" style="margin-bottom: 12px;">
            <div style="padding: 12px 16px; border-radius: 12px; background:#dcfce7; border:1px solid #bbf7d0; font-size:12px; color:#166534;">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3 class="investHeaderTitle">Investments</h3>
                <p class="investHeaderSubtitle">Browse curated investment plans and start investing.</p>
            </div>
        </div>
    </div>

    <!-- Search & Filters bar -->
    <div class="surface">
        <div class="investFilterBar">
            <div class="investFilterLeft">
                <span>Search &amp; Filters</span>
                <span class="investFilterChip">All Plans</span>
            </div>
            <div class="investFilterRight">
                <span>View All Featured</span>
                <span>➜</span>
            </div>
        </div>
    </div>

    <!-- Featured Plans -->
    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="whitePanel">
            <div class="panelHead">
                <div>
                    <h5>Featured Plans</h5>
                    <small>Highlighted opportunities picked for you</small>
                </div>
            </div>
            <div class="plansGrid" id="featuredPlansContainer"></div>
        </div>
    </div>

    <!-- All Investment Plans -->
    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="whitePanel">
            <div class="panelHead sectionTitleRow">
                <div>
                    <h5>All Investment Plans</h5>
                    <small class="sectionSubtitle">
                        Showing <span id="plansShowingCount"></span> of <span id="plansTotalCount"></span> plans
                    </small>
                </div>
            </div>
            <div class="plansGrid" id="allPlansContainer"></div>
        </div>
    </div>

    <!-- Plan Details Modal -->
    <div class="planModalOverlay" id="planDetailsOverlay">
        <div class="planModal" role="dialog" aria-modal="true" aria-labelledby="planDetailsTitle">
            <div class="planModalHeader">
                <div>
                    <h4 class="planModalTitle" id="planDetailsTitle"></h4>
                    <p class="planModalSubtitle" id="planDetailsStrategy"></p>
                </div>
                <div style="display:flex; align-items:center; gap:8px;">
                    <span class="planRiskBadge" id="planDetailsRiskBadge"></span>
                    <button type="button" class="planModalClose" id="planDetailsCloseBtn" aria-label="Close">×</button>
                </div>
            </div>
            <div class="planModalBody">
                <div class="planModalStatsRow">
                    <div class="planModalStat">
                        <small>Profit Margin</small>
                        <strong id="planDetailsProfit"></strong>
                    </div>
                    <div class="planModalStat">
                        <small>Duration</small>
                        <strong id="planDetailsDuration"></strong>
                    </div>
                    <div class="planModalStat">
                        <small>Min</small>
                        <strong id="planDetailsMin"></strong>
                    </div>
                    <div class="planModalStat">
                        <small>Max</small>
                        <strong id="planDetailsMax"></strong>
                    </div>
                </div>
                <div class="planModalMeta" id="planDetailsMeta"></div>
            </div>
            <div class="planModalFooter">
                <p class="planModalHelper">Review this plan’s details carefully before investing.</p>
                <button type="button" class="planModalCta" id="planModalInvestBtn">Invest in this Plan</button>
            </div>
        </div>
    </div>

    <!-- Investment Form Modal -->
    <div class="planModalOverlay" id="investFormOverlay">
        <div class="planModal" role="dialog" aria-modal="true">
            <div class="planModalHeader">
                <div>
                    <h4 class="planModalTitle" id="investFormTitle">Invest in Plan</h4>
                    <p class="planModalSubtitle" id="investFormSubtitle">Enter your investment amount</p>
                </div>
                <button type="button" class="planModalClose" id="investFormCloseBtn" aria-label="Close">×</button>
            </div>
            <form method="POST" action="{{ route('dashboard.investments.submit') }}" id="investForm">
                @csrf
                <input type="hidden" name="investment_plan_id" id="investFormPlanId">
                <div class="planModalBody">
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Investment Amount</label>
                        <input
                            type="number"
                            step="0.01"
                            min="1"
                            name="amount"
                            id="investFormAmount"
                            class="formInput"
                            placeholder="$ 0.00"
                            required
                            style="width:100%; height:44px; padding:0 12px; border:1px solid rgba(0,0,0,.12); border-radius:10px; font-size:14px; font-weight:800; color:#111827;"
                        />
                        <p style="font-size: 11px; font-weight: 700; color: #9ca3af; margin: 6px 0 0 0;">
                            Minimum: $<span id="investFormMinAmount">0.00</span>
                            <span id="investFormMaxWrap" style="display: none;"> · Maximum: <span id="investFormMaxAmount">—</span></span>
                        </p>
                        <p style="font-size: 11px; font-weight: 700; color: #9ca3af; margin: 4px 0 0 0;">
                            Available Balance: ${{ number_format($currentBalance ?? 0, 2) }}
                        </p>
                    </div>
                </div>
                <div class="planModalFooter">
                    <p class="planModalHelper">Your balance will be deducted immediately upon confirmation.</p>
                    <button type="submit" class="planModalCta">Confirm Investment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Investment plans data from database (pre-formatted)
    const investmentPlansData = @json($investmentPlansForJs);

    function formatCurrency(value) {
        return value.toLocaleString(undefined, {
            minimumFractionDigits: 4,
            maximumFractionDigits: 4
        });
    }

    function formatMoney(value) {
        return value.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function getRiskBadgeClass(riskLevel) {
        if (!riskLevel) return '';
        const level = riskLevel.toLowerCase();
        if (level === 'high') return 'riskHigh';
        if (level === 'medium') return 'riskMedium';
        return 'riskLow';
    }

    function renderPlans() {
        const featuredContainer = document.getElementById('featuredPlansContainer');
        const allContainer = document.getElementById('allPlansContainer');
        const totalCountEl = document.getElementById('plansTotalCount');
        const showingCountEl = document.getElementById('plansShowingCount');

        const featuredPlans = investmentPlansData.filter(p => p.isFeatured);

        totalCountEl.textContent = investmentPlansData.length;
        showingCountEl.textContent = investmentPlansData.length;

        featuredContainer.innerHTML = featuredPlans.map(plan => planCardHtml(plan, true)).join('');
        allContainer.innerHTML = investmentPlansData.map(plan => planCardHtml(plan, false)).join('');

        attachPlanEvents();
    }

    function planCardHtml(plan, isFeaturedSection) {
        const riskClass = getRiskBadgeClass(plan.riskLevel);
        const profit = plan.profitMargin != null ? plan.profitMargin : plan.oneYearReturn;
        const maxLabel = plan.maxInvestment == null ? 'Unlimited' : '$' + formatMoney(plan.maxInvestment);
        const primaryCta = isFeaturedSection ? 'Invest Now' : 'Invest';

        return `
            <div class="planCard" data-plan-slug="${plan.slug}" data-plan-id="${plan.id}">
                <div>
                    <div class="planCardHeader">
                        <div>
                            <h5 class="planName">${plan.name}</h5>
                            <p class="planStrategy">${plan.strategy}</p>
                        </div>
                        <span class="planRiskBadge ${riskClass}">${plan.riskLevel}</span>
                    </div>
                    <div class="planMetaRow">
                        <div class="planMetaBlock">
                            <small>${isFeaturedSection ? 'Profit' : 'Profit'}</small>
                            <strong class="planReturnPositive">${Number(profit).toFixed(0)}%</strong>
                        </div>
                        <div class="planMetaBlock">
                            <small>${isFeaturedSection ? 'Duration' : 'Duration'}</small>
                            <strong>${plan.durationLabel || (plan.durationDays + ' days')}</strong>
                        </div>
                        <div class="planMetaBlock">
                            <small>${isFeaturedSection ? 'Min' : 'Min'}</small>
                            <strong>$${formatMoney(plan.minInvestment)}</strong>
                        </div>
                        <div class="planMetaBlock">
                            <small>Max</small>
                            <strong>${maxLabel}</strong>
                        </div>
                    </div>
                </div>
                <div class="planFooter">
                    <a href="javascript:void(0)" class="planDetailsLink" data-plan-slug="${plan.slug}">View Details <span>➜</span></a>
                    <button type="button" class="planPrimaryBtn">${primaryCta}</button>
                </div>
            </div>
        `;
    }

    function attachPlanEvents() {
        const detailLinks = document.querySelectorAll('.planDetailsLink');
        detailLinks.forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const slug = link.getAttribute('data-plan-slug') ||
                    (link.closest('.planCard')?.getAttribute('data-plan-slug') || '');
                const plan = investmentPlansData.find(p => p.slug === slug);
                if (plan) {
                    openPlanDetailsModal(plan);
                }
            });
        });

        // Invest button clicks on plan cards
        const investButtons = document.querySelectorAll('.planPrimaryBtn');
        investButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const card = button.closest('.planCard');
                const slug = card?.getAttribute('data-plan-slug') || '';
                const plan = investmentPlansData.find(p => p.slug === slug);
                if (plan) {
                    openInvestFormModal(plan);
                }
            });
        });

        // Invest button in details modal
        const planModalInvestBtn = document.getElementById('planModalInvestBtn');
        if (planModalInvestBtn) {
            planModalInvestBtn.addEventListener('click', () => {
                const overlay = document.getElementById('planDetailsOverlay');
                if (overlay) {
                    const slug = overlay.getAttribute('data-current-plan-slug') || '';
                    const plan = investmentPlansData.find(p => p.slug === slug);
                    if (plan) {
                        closePlanDetailsModal();
                        openInvestFormModal(plan);
                    }
                }
            });
        }

        const overlay = document.getElementById('planDetailsOverlay');
        const closeBtn = document.getElementById('planDetailsCloseBtn');

        if (overlay) {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    closePlanDetailsModal();
                }
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closePlanDetailsModal);
        }

        // Investment form modal events
        const investFormOverlay = document.getElementById('investFormOverlay');
        const investFormCloseBtn = document.getElementById('investFormCloseBtn');

        if (investFormOverlay) {
            investFormOverlay.addEventListener('click', (e) => {
                if (e.target === investFormOverlay) {
                    closeInvestFormModal();
                }
            });
        }

        if (investFormCloseBtn) {
            investFormCloseBtn.addEventListener('click', closeInvestFormModal);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closePlanDetailsModal();
                closeInvestFormModal();
            }
        });
    }

    function openPlanDetailsModal(plan) {
        const overlay = document.getElementById('planDetailsOverlay');
        if (!overlay) return;

        const titleEl = document.getElementById('planDetailsTitle');
        const strategyEl = document.getElementById('planDetailsStrategy');
        const profitEl = document.getElementById('planDetailsProfit');
        const durationEl = document.getElementById('planDetailsDuration');
        const minEl = document.getElementById('planDetailsMin');
        const maxEl = document.getElementById('planDetailsMax');
        const metaEl = document.getElementById('planDetailsMeta');
        const riskBadgeEl = document.getElementById('planDetailsRiskBadge');

        const profit = plan.profitMargin != null ? plan.profitMargin : plan.oneYearReturn;
        const maxLabel = plan.maxInvestment == null ? 'Unlimited' : '$' + formatMoney(plan.maxInvestment);

        if (titleEl) titleEl.textContent = plan.name;
        if (strategyEl) strategyEl.textContent = plan.strategy;
        if (profitEl) profitEl.textContent = `${Number(profit).toFixed(0)}%`;
        if (durationEl) durationEl.textContent = plan.durationLabel || (plan.durationDays + ' days');
        if (minEl) minEl.textContent = `$${formatMoney(plan.minInvestment)}`;
        if (maxEl) maxEl.textContent = maxLabel;

        if (metaEl) {
            metaEl.textContent = `${plan.category} • ${plan.riskLevel} risk • Min $${formatMoney(plan.minInvestment)} • Max ${maxLabel}`;
        }

        if (riskBadgeEl) {
            riskBadgeEl.textContent = plan.riskLevel;
            riskBadgeEl.className = 'planRiskBadge ' + getRiskBadgeClass(plan.riskLevel);
        }

        overlay.setAttribute('data-current-plan-slug', plan.slug);
        overlay.classList.add('is-visible');
    }

    function closePlanDetailsModal() {
        const overlay = document.getElementById('planDetailsOverlay');
        if (!overlay) return;
        overlay.classList.remove('is-visible');
        overlay.removeAttribute('data-current-plan-slug');
    }

    function openInvestFormModal(plan) {
        const overlay = document.getElementById('investFormOverlay');
        if (!overlay) return;

        if (!plan || !plan.id) {
            console.error('Plan ID is missing:', plan);
            alert('Error: Investment plan information is incomplete. Please refresh the page and try again.');
            return;
        }

        const titleEl = document.getElementById('investFormTitle');
        const subtitleEl = document.getElementById('investFormSubtitle');
        const planIdInput = document.getElementById('investFormPlanId');
        const amountInput = document.getElementById('investFormAmount');
        const minAmountEl = document.getElementById('investFormMinAmount');
        const maxWrapEl = document.getElementById('investFormMaxWrap');
        const maxAmountEl = document.getElementById('investFormMaxAmount');

        if (titleEl) titleEl.textContent = `Invest in ${plan.name}`;
        if (subtitleEl) subtitleEl.textContent = plan.strategy;
        if (planIdInput) planIdInput.value = plan.id;
        if (amountInput) {
            amountInput.value = '';
            amountInput.min = plan.minInvestment;
            amountInput.removeAttribute('max');
            if (plan.maxInvestment != null) {
                amountInput.max = plan.maxInvestment;
            }
        }
        if (minAmountEl) minAmountEl.textContent = formatMoney(plan.minInvestment);
        if (maxWrapEl && maxAmountEl) {
            if (plan.maxInvestment != null) {
                maxWrapEl.style.display = '';
                maxAmountEl.textContent = '$' + formatMoney(plan.maxInvestment);
            } else {
                maxWrapEl.style.display = 'none';
            }
        }

        overlay.classList.add('is-visible');
    }

    function closeInvestFormModal() {
        const overlay = document.getElementById('investFormOverlay');
        if (!overlay) return;
        overlay.classList.remove('is-visible');
    }

    document.addEventListener('DOMContentLoaded', renderPlans);
</script>
@endpush
