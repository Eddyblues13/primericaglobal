@extends('layouts.dashboard')

@section('title', 'TESLA Wallet - Deposit Funds')
@section('topTitle', 'Deposit Funds')

@push('styles')
<style>
    .walletFormWrap {
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,.06);
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
        box-shadow: 0 18px 36px rgba(0,0,0,.25);
    }
    .walletHeroTitle { margin: 0 0 6px 0; font-size: 16px; font-weight: 900; }
    .walletHeroSubtitle { margin: 0; font-size: 12px; font-weight: 700; color: rgba(255,255,255,.82); }
    .walletBalanceCard {
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 12px;
        padding: 10px 12px;
        text-align: right;
        min-width: 180px;
    }
    .walletBalanceLabel { font-size: 11px; font-weight: 800; opacity: .85; margin: 0; }
    .walletBalanceValue { font-size: 16px; font-weight: 900; margin: 2px 0 0 0; }
    .formLabel { display:block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px; }
    .formInput { width:100%; height:44px; padding:0 12px; border:1px solid rgba(0,0,0,.12); border-radius:10px; font-size:14px; font-weight:800; color:#111827; }
    .methodCard {
        border:1px solid rgba(0,0,0,.08);
        border-radius:12px;
        padding:12px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        background:#fff;
        box-shadow: inset 0 0 0 1px transparent;
    }
    .methodCard + .methodCard { margin-top:10px; }
    .methodLeft { display:flex; align-items:center; gap:10px; }
    .methodIcon { width:32px; height:32px; border-radius:999px; display:grid; place-items:center; font-size:16px; }
    .methodTitle { font-size:13px; font-weight:900; color:#111827; margin:0; }
    .feeBox {
        margin-top:16px;
        border:1px solid rgba(16,185,129,.25);
        background: rgba(16,185,129,.08);
        border-radius:12px;
        padding:12px;
        font-size:12px;
        font-weight:800;
        color:#065f46;
    }
    .primaryBtn {
        width:100%;
        height:42px;
        border-radius:10px;
        background:#0b0c10;
        color:#fff;
        font-size:13px;
        font-weight:900;
        border:none;
        cursor:pointer;
        margin-top:16px;
    }
    .methodDetails {
        display: none;
        margin-top: 12px;
        padding: 14px;
        background: #f9fafb;
        border-radius: 10px;
        border: 1px solid rgba(0,0,0,.08);
        position: relative;
        user-select: text;
    }
    .methodDetails.show {
        display: block;
    }
    .copyBtn {
        background: #0b0c10;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 900;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .copyBtn:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }
    .copyBtn:active {
        opacity: 0.7;
    }
    .copyBtn.copied {
        background: #10b981;
    }
    .methodDetailsText {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .detailItem {
        padding: 10px;
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,.06);
    }
    .detailLabel {
        font-size: 10px;
        font-weight: 900;
        color: #6b7280;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .detailValue {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .detailValueText {
        flex: 1;
        font-size: 12px;
        font-weight: 800;
        color: #111827;
        font-family: 'Courier New', monospace;
        word-break: break-all;
        padding: 8px 10px;
        background: #f3f4f6;
        border-radius: 6px;
        border: 1px solid rgba(0,0,0,.06);
        min-height: 36px;
        display: flex;
        align-items: center;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.querySelector('input[name="amount"]');
    const methodRadios = document.querySelectorAll('input[name="payment_method_id"]');
    const methodDetails = document.querySelectorAll('.methodDetails');
    const feeDepositAmount = document.getElementById('feeDepositAmount');
    const feeProcessing = document.getElementById('feeProcessing');
    const feeTotalAmount = document.getElementById('feeTotalAmount');
    
    function updateDetailsVisibility() {
        const amount = parseFloat(amountInput.value) || 0;
        const selectedMethod = document.querySelector('input[name="payment_method_id"]:checked');
        
        // Hide all details first
        methodDetails.forEach(detail => {
            detail.classList.remove('show');
        });
        
        // Show details only if amount is entered and method is selected
        if (amount > 0 && selectedMethod) {
            const selectedDetails = document.querySelector(`.methodDetails[data-method-id="${selectedMethod.value}"]`);
            if (selectedDetails) {
                selectedDetails.classList.add('show');
            }
        }
    }

    function formatAmount(value) {
        return value.toFixed(2);
    }

    function updateFeeBreakdown() {
        if (!feeDepositAmount || !feeProcessing || !feeTotalAmount) return;

        const amount = parseFloat(amountInput.value) || 0;

        // Currently no processing fee; keep this logic simple but centralized
        const processingFee = 0;
        const total = amount + processingFee;

        feeDepositAmount.textContent = formatAmount(amount);
        feeProcessing.textContent = formatAmount(processingFee);
        feeTotalAmount.textContent = formatAmount(total);
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
    
    // Copy to clipboard functionality for individual items
    document.querySelectorAll('.copyBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            const originalText = this.textContent;
            
            // Copy to clipboard
            navigator.clipboard.writeText(textToCopy).then(() => {
                // Show feedback
                this.textContent = 'Copied!';
                this.classList.add('copied');
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    this.textContent = 'Copied!';
                    this.classList.add('copied');
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.classList.remove('copied');
                    }, 2000);
                } catch (err) {
                    alert('Failed to copy. Please select and copy manually.');
                }
                document.body.removeChild(textarea);
            });
        });
    });
});
</script>
@endpush

@section('content')
<div class="wrap" id="wallet-deposit">
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3>Deposit Funds</h3>
                <p>Add money to your wallet using your preferred payment method.</p>
            </div>
        </div>
    </div>

    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="walletFormWrap">
            @if ($errors->any())
                <div style="margin-bottom: 12px; padding: 10px 12px; border-radius: 10px; background:#fee2e2; border:1px solid #fecaca; font-size:12px; color:#991b1b;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            @if (session('error'))
                <div style="margin-bottom: 12px; padding: 10px 12px; border-radius: 10px; background:#fee2e2; border:1px solid #fecaca; font-size:12px; color:#991b1b;">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div style="margin-bottom: 12px; padding: 10px 12px; border-radius: 10px; background:#dcfce7; border:1px solid #bbf7d0; font-size:12px; color:#166534;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="walletHero">
                <div>
                    <h4 class="walletHeroTitle">Deposit Funds</h4>
                    <p class="walletHeroSubtitle">Add money to your wallet using your preferred payment method</p>
                </div>
                <div class="walletBalanceCard">
                    <p class="walletBalanceLabel">Current Balance</p>
                    <p class="walletBalanceValue">${{ number_format($currentBalance, 2) }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('dashboard.wallet.deposit.submit') }}" style="display:flex; flex-direction:column; gap:16px;">
                @csrf
                <div>
                    <label class="formLabel">Deposit Amount</label>
                    <input
                        type="number"
                        step="0.01"
                        min="1"
                        name="amount"
                        class="formInput"
                        placeholder="$ 0.00"
                        value="{{ old('amount') }}"
                    />
                </div>

                <div>
                    <label class="formLabel">Payment Method</label>

                    @foreach($depositMethods as $index => $method)
                        <div class="methodCard" @if($index === 1) style="background:#f9fafb;" @endif>
                            <div class="methodLeft">
                                <input
                                    type="radio"
                                    name="payment_method_id"
                                    value="{{ $method->id }}"
                                    @checked(old('payment_method_id', $depositMethods->first()->id ?? null) == $method->id)
                                >
                                <div class="methodIcon" style="background:#f3f4f6;">
                                    @if($method->logo_url)
                                        <img src="{{ $method->logo_url }}" alt="{{ $method->name }}" style="width:24px; height:24px; object-fit:contain;">
                                    @else
                                        <span style="font-size:12px; font-weight:900; color:#111827;">{{ strtoupper(substr($method->name,0,2)) }}</span>
                                    @endif
                                </div>
                                <div style="flex: 1;">
                                    <p class="methodTitle">{{ $method->name }}</p>
                                    @if($method->details)
                                        <div class="methodDetails" data-method-id="{{ $method->id }}">
                                            <div class="methodDetailsText">
                                                @php
                                                    $lines = explode("\n", $method->details);
                                                    $importantParts = [];
                                                    $lineCount = count($lines);
                                                    
                                                    for ($i = 0; $i < $lineCount; $i++) {
                                                        $line = trim($lines[$i]);
                                                        if (empty($line)) continue;
                                                        
                                                        // Check for wallet address (most common patterns)
                                                        if (stripos($line, 'address:') !== false || stripos($line, 'wallet address:') !== false) {
                                                            $parts = explode(':', $line, 2);
                                                            $value = '';
                                                            
                                                            // If value is on same line
                                                            if (count($parts) > 1 && !empty(trim($parts[1]))) {
                                                                $value = trim($parts[1]);
                                                            }
                                                            // If value is on next line
                                                            elseif ($i + 1 < $lineCount && !empty(trim($lines[$i + 1]))) {
                                                                $nextLine = trim($lines[$i + 1]);
                                                                // Check if next line doesn't contain a colon (not a label)
                                                                if (stripos($nextLine, ':') === false) {
                                                                    $value = $nextLine;
                                                                    $i++; // Skip next line since we used it
                                                                }
                                                            }
                                                            
                                                            if (!empty($value)) {
                                                                $importantParts[] = [
                                                                    'label' => trim($parts[0]),
                                                                    'value' => $value,
                                                                    'type' => 'address'
                                                                ];
                                                            }
                                                        }
                                                        // Check for email
                                                        elseif (stripos($line, '@') !== false && (stripos($line, 'email') !== false || stripos($line, 'paypal') !== false)) {
                                                            $parts = explode(':', $line, 2);
                                                            if (count($parts) > 1 && !empty(trim($parts[1]))) {
                                                                $importantParts[] = [
                                                                    'label' => trim($parts[0]),
                                                                    'value' => trim($parts[1]),
                                                                    'type' => 'email'
                                                                ];
                                                            }
                                                        }
                                                        // Check for account number
                                                        elseif (stripos($line, 'account number:') !== false) {
                                                            $parts = explode(':', $line, 2);
                                                            if (count($parts) > 1 && !empty(trim($parts[1]))) {
                                                                $importantParts[] = [
                                                                    'label' => trim($parts[0]),
                                                                    'value' => trim($parts[1]),
                                                                    'type' => 'account'
                                                                ];
                                                            }
                                                        }
                                                        // Check for IBAN
                                                        elseif (stripos($line, 'IBAN:') !== false) {
                                                            $parts = explode(':', $line, 2);
                                                            if (count($parts) > 1 && !empty(trim($parts[1]))) {
                                                                $importantParts[] = [
                                                                    'label' => trim($parts[0]),
                                                                    'value' => trim($parts[1]),
                                                                    'type' => 'iban'
                                                                ];
                                                            }
                                                        }
                                                        // Check for SWIFT/BIC
                                                        elseif (stripos($line, 'SWIFT') !== false || stripos($line, 'BIC') !== false) {
                                                            $parts = explode(':', $line, 2);
                                                            if (count($parts) > 1 && !empty(trim($parts[1]))) {
                                                                $importantParts[] = [
                                                                    'label' => trim($parts[0]),
                                                                    'value' => trim($parts[1]),
                                                                    'type' => 'swift'
                                                                ];
                                                            }
                                                        }
                                                        // Check for bank name
                                                        elseif (stripos($line, 'bank name:') !== false) {
                                                            $parts = explode(':', $line, 2);
                                                            if (count($parts) > 1 && !empty(trim($parts[1]))) {
                                                                $importantParts[] = [
                                                                    'label' => trim($parts[0]),
                                                                    'value' => trim($parts[1]),
                                                                    'type' => 'bank'
                                                                ];
                                                            }
                                                        }
                                                        // Check for account name
                                                        elseif (stripos($line, 'account name:') !== false) {
                                                            $parts = explode(':', $line, 2);
                                                            if (count($parts) > 1 && !empty(trim($parts[1]))) {
                                                                $importantParts[] = [
                                                                    'label' => trim($parts[0]),
                                                                    'value' => trim($parts[1]),
                                                                    'type' => 'account_name'
                                                                ];
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if(!empty($importantParts))
                                                    @foreach($importantParts as $part)
                                                        @if(!empty($part['value']))
                                                            <div class="detailItem">
                                                                <div class="detailLabel">{{ $part['label'] }}</div>
                                                                <div class="detailValue">
                                                                    <span class="detailValueText" id="detail-{{ $method->id }}-{{ $loop->index }}">{{ $part['value'] }}</span>
                                                                    <button 
                                                                        type="button" 
                                                                        class="copyBtn" 
                                                                        data-copy="{{ $part['value'] }}"
                                                                        title="Copy {{ $part['label'] }}"
                                                                    >
                                                                        Copy
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="detailItem">
                                                        <div class="detailValueText" style="font-family: inherit; white-space: pre-line;">{{ $method->details }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <span style="color:#9ca3af;">➜</span>
                        </div>
                    @endforeach
                </div>

                <div class="feeBox">
                    Fee Breakdown<br>
                    Deposit Amount: $<span id="feeDepositAmount">{{ old('amount', '0.00') }}</span><br>
                    Processing Fee: $<span id="feeProcessing">0.00</span><br>
                    Total Amount: $<span id="feeTotalAmount">{{ old('amount', '0.00') }}</span>
                </div>

                <button class="primaryBtn" type="submit">Add Funds</button>
            </form>
        </div>
    </div>
</div>
@endsection

