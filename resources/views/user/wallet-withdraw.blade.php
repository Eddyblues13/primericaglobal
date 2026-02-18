@extends('layouts.dashboard')

@section('title', 'Primrica Global Capital Wallet - Withdraw Funds')
@section('topTitle', 'Withdraw Funds')

@push('styles')
<style>
    .walletFormWrap {
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, .06);
        background: #fff;
        padding: 16px;
    }

    .walletHero {
        background: linear-gradient(120deg, #0b0c10 0%, #111827 70%);
        color: #fff;
        border-radius: 16px;
        padding: 18px 18px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        box-shadow: 0 18px 36px rgba(0, 0, 0, .25);
    }

    .walletHeroTitle {
        margin: 0 0 6px 0;
        font-size: 16px;
        font-weight: 900;
    }

    .walletHeroSubtitle {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        color: rgba(255, 255, 255, .82);
    }

    .walletBalanceCard {
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .15);
        border-radius: 12px;
        padding: 10px 12px;
        text-align: right;
        min-width: 180px;
    }

    .walletBalanceLabel {
        font-size: 11px;
        font-weight: 800;
        opacity: .85;
        margin: 0;
    }

    .walletBalanceValue {
        font-size: 16px;
        font-weight: 900;
        margin: 2px 0 0 0;
    }

    .formLabel {
        display: block;
        font-size: 11px;
        font-weight: 900;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .formInput {
        width: 100%;
        height: 44px;
        padding: 0 12px;
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 800;
        color: #111827;
    }

    .hintText {
        font-size: 11px;
        font-weight: 700;
        color: #9ca3af;
        margin: 6px 0 0 0;
    }

    .methodCard {
        border: 1px solid rgba(0, 0, 0, .08);
        border-radius: 12px;
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: #fff;
        box-shadow: inset 0 0 0 1px transparent;
    }

    .methodCard+.methodCard {
        margin-top: 10px;
    }

    .methodLeft {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .methodIcon {
        width: 32px;
        height: 32px;
        border-radius: 999px;
        display: grid;
        place-items: center;
        font-size: 16px;
    }

    .methodTitle {
        font-size: 13px;
        font-weight: 900;
        color: #111827;
        margin: 0;
    }

    .feeBox {
        margin-top: 16px;
        border: 1px solid rgba(239, 68, 68, .25);
        background: rgba(239, 68, 68, .08);
        border-radius: 12px;
        padding: 12px;
        font-size: 12px;
        font-weight: 800;
        color: #991b1b;
    }

    .primaryBtn {
        width: 100%;
        height: 42px;
        border-radius: 10px;
        background: #0b0c10;
        color: #fff;
        font-size: 13px;
        font-weight: 900;
        border: none;
        cursor: pointer;
        margin-top: 16px;
    }

    .withdrawalDetailsSection {
        display: none;
        margin-top: 16px;
        padding: 14px;
        background: #f9fafb;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, .08);
    }

    .withdrawalDetailsSection.show {
        display: block;
    }

    .formInput:disabled {
        background: #f3f4f6;
        opacity: 0.6;
        cursor: not-allowed;
    }

    .formTextarea {
        width: 100%;
        min-height: 80px;
        padding: 10px 12px;
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        font-family: 'Courier New', monospace;
        resize: vertical;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.querySelector('input[name="amount"]');
    const methodRadios = document.querySelectorAll('input[name="payment_method_id"]');
    const withdrawalDetailsSections = document.querySelectorAll('.withdrawalDetailsSection');
    const feeWithdrawAmount = document.getElementById('feeWithdrawAmount');
    const feeProcessing = document.getElementById('feeWithdrawProcessing');
    const feeReceiveAmount = document.getElementById('feeReceiveAmount');
    
    const methodCategories = {
        @foreach($withdrawMethods as $method)
        '{{ $method->id }}': '{{ $method->category }}',
        @endforeach
    };
    
    function updateDetailsVisibility() {
        const amount = parseFloat(amountInput.value) || 0;
        const selectedMethod = document.querySelector('input[name="payment_method_id"]:checked');
        
        // Hide all details sections first and disable/remove required from all fields
        withdrawalDetailsSections.forEach(section => {
            section.classList.remove('show');
            // Disable and remove required from all fields in hidden sections
            const inputs = section.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.disabled = true;
                input.removeAttribute('required');
            });
        });
        
        // Show details section only if amount is entered and method is selected
        if (amount > 0 && selectedMethod) {
            const methodId = selectedMethod.value;
            const category = methodCategories[methodId];
            const detailsSection = document.querySelector(`.withdrawalDetailsSection[data-method-id="${methodId}"]`);
            
            if (detailsSection) {
                detailsSection.classList.add('show');
                // Enable and add required to fields in visible section
                const allInputs = detailsSection.querySelectorAll('input, textarea');
                allInputs.forEach(input => {
                    input.disabled = false;
                    if (input.hasAttribute('data-required')) {
                        input.setAttribute('required', 'required');
                    }
                });
            }
        }
    }

    function formatAmount(value) {
        return value.toFixed(2);
    }

    function updateFeeBreakdown() {
        if (!feeWithdrawAmount || !feeProcessing || !feeReceiveAmount) return;

        const amount = parseFloat(amountInput.value) || 0;

        // Currently no withdrawal fee; keep this centralised for future changes
        const processingFee = 0;
        const receive = Math.max(amount - processingFee, 0);

        feeWithdrawAmount.textContent = formatAmount(amount);
        feeProcessing.textContent = formatAmount(processingFee);
        feeReceiveAmount.textContent = formatAmount(receive);
    }
    
    // Listen for amount changes
    amountInput.addEventListener('input', function () {
        updateDetailsVisibility();
        updateFeeBreakdown();
    });
    amountInput.addEventListener('change', function () {
        updateDetailsVisibility();
        updateFeeBreakdown();
    });
    
    // Listen for method selection changes
    methodRadios.forEach(radio => {
        radio.addEventListener('change', updateDetailsVisibility);
    });
    
    // Initial check (in case form has old values)
    updateDetailsVisibility();
    updateFeeBreakdown();
    
    // Before form submission, ensure all visible fields are enabled
    const form = document.querySelector('form[action="{{ route('dashboard.wallet.withdraw.submit') }}"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Enable all fields in visible sections before submission
            withdrawalDetailsSections.forEach(section => {
                if (section.classList.contains('show')) {
                    const inputs = section.querySelectorAll('input, textarea');
                    inputs.forEach(input => {
                        input.disabled = false;
                    });
                }
            });
        });
    }
});
</script>
@endpush

@section('content')
<div class="wrap" id="wallet-withdraw">
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3>Withdraw Funds</h3>
                <p>Transfer money to your bank or preferred method.</p>
            </div>
        </div>
    </div>

    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="walletFormWrap">
            @if ($errors->any())
            <div
                style="margin-bottom: 12px; padding: 10px 12px; border-radius: 10px; background:#fee2e2; border:1px solid #fecaca; font-size:12px; color:#991b1b;">
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif
            @if (session('error'))
            <div
                style="margin-bottom: 12px; padding: 10px 12px; border-radius: 10px; background:#fee2e2; border:1px solid #fecaca; font-size:12px; color:#991b1b;">
                {{ session('error') }}
            </div>
            @endif
            @if (session('success'))
            <div
                style="margin-bottom: 12px; padding: 10px 12px; border-radius: 10px; background:#dcfce7; border:1px solid #bbf7d0; font-size:12px; color:#166534;">
                {{ session('success') }}
            </div>
            @endif
            <div class="walletHero">
                <div>
                    <h4 class="walletHeroTitle">Withdraw Funds</h4>
                    <p class="walletHeroSubtitle">Choose method and enter amount to withdraw</p>
                </div>
                <div class="walletBalanceCard">
                    <p class="walletBalanceLabel">Current Balance</p>
                    <p class="walletBalanceValue">${{ number_format($currentBalance, 2) }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('dashboard.wallet.withdraw.submit') }}"
                style="display:flex; flex-direction:column; gap:16px;">
                @csrf
                <div>
                    <label class="formLabel">Withdrawal Amount</label>
                    <input type="number" step="0.01" min="1" name="amount" class="formInput" placeholder="$ 0.00"
                        value="{{ old('amount') }}" />
                    <p class="hintText">Maximum withdrawal: ${{ number_format($currentBalance, 2) }}</p>
                </div>

                <div>
                    <label class="formLabel">Withdrawal Method</label>

                    @foreach($withdrawMethods as $index => $method)
                    <div class="methodCard" @if($index===1) style="background:#f9fafb;" @endif>
                        <div class="methodLeft">
                            <input type="radio" name="payment_method_id" value="{{ $method->id }}"
                                @checked(old('payment_method_id', $withdrawMethods->first()->id ?? null) == $method->id)
                            >
                            <div class="methodIcon" style="background:#f3f4f6;">
                                @if($method->logo_url)
                                <img src="{{ $method->logo_url }}" alt="{{ $method->name }}"
                                    style="width:24px; height:24px; object-fit:contain;">
                                @else
                                <span style="font-size:12px; font-weight:900; color:#111827;">{{
                                    strtoupper(substr($method->name,0,2)) }}</span>
                                @endif
                            </div>
                            <div>
                                <p class="methodTitle">{{ $method->name }}</p>
                            </div>
                        </div>
                        <span style="color:#9ca3af;">➜</span>
                    </div>

                    @if($method->category === 'crypto')
                    <div class="withdrawalDetailsSection" data-method-id="{{ $method->id }}">
                        <label class="formLabel">Your {{ $method->name }} Wallet Address</label>
                        <input type="text" name="wallet_address" class="formInput"
                            placeholder="Enter your {{ $method->name }} wallet address"
                            value="{{ old('wallet_address') }}" data-required="true" disabled />
                        <p class="hintText">Make sure to enter the correct wallet address. Funds cannot be recovered if
                            sent to the wrong address.</p>
                    </div>
                    @elseif($method->category === 'bank')
                    <div class="withdrawalDetailsSection" data-method-id="{{ $method->id }}">
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            <div>
                                <label class="formLabel">Bank Name</label>
                                <input type="text" name="bank_name" class="formInput" placeholder="Enter your bank name"
                                    value="{{ old('bank_name') }}" data-required="true" disabled />
                            </div>
                            <div>
                                <label class="formLabel">Account Name</label>
                                <input type="text" name="account_name" class="formInput"
                                    placeholder="Enter account holder name" value="{{ old('account_name') }}"
                                    data-required="true" disabled />
                            </div>
                            <div>
                                <label class="formLabel">Account Number</label>
                                <input type="text" name="account_number" class="formInput"
                                    placeholder="Enter your account number" value="{{ old('account_number') }}"
                                    data-required="true" disabled />
                            </div>
                            <div>
                                <label class="formLabel">IBAN (Optional)</label>
                                <input type="text" name="iban" class="formInput" placeholder="Enter IBAN if applicable"
                                    value="{{ old('iban') }}" disabled />
                            </div>
                            <div>
                                <label class="formLabel">SWIFT/BIC (Optional)</label>
                                <input type="text" name="swift_bic" class="formInput"
                                    placeholder="Enter SWIFT/BIC code if applicable" value="{{ old('swift_bic') }}"
                                    disabled />
                            </div>
                        </div>
                    </div>
                    @elseif($method->category === 'wallet')
                    <div class="withdrawalDetailsSection" data-method-id="{{ $method->id }}">
                        <label class="formLabel">Your {{ $method->name }} Email</label>
                        <input type="email" name="wallet_email" class="formInput"
                            placeholder="Enter your {{ $method->name }} email address" value="{{ old('wallet_email') }}"
                            data-required="true" disabled />
                        <p class="hintText">Enter the email address associated with your {{ $method->name }} account.
                        </p>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="feeBox">
                    Fee Breakdown<br>
                    Withdrawal Amount: $<span id="feeWithdrawAmount">{{ old('amount', '0.00') }}</span><br>
                    Processing Fee: $<span id="feeWithdrawProcessing">0.00</span><br>
                    You’ll Receive: $<span id="feeReceiveAmount">{{ old('amount', '0.00') }}</span>
                </div>

                <button class="primaryBtn" type="submit">Withdraw</button>
            </form>
        </div>
    </div>
</div>
@endsection