@extends('layouts.admin')

@section('title', 'Payment Settings - Admin')
@section('topTitle', 'Payment Settings')

@section('content')
<div class="wrap">
    @if(session('success'))
        <div class="alert alert-success" style="background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #10b981;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ef4444;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="whitePanel" style="margin-bottom: 20px;">
        <div class="panelHead">
            <div>
                <h5>Payment Methods Configuration</h5>
                <small>Configure wallet addresses, PayPal, and bank details for deposits and withdrawals</small>
            </div>
        </div>
    </div>

    <!-- Crypto Wallet Settings -->
    <div class="whitePanel" style="margin-bottom: 20px;">
        <div class="panelHead">
            <div>
                <h5>Crypto Wallet</h5>
                <small>Set up cryptocurrency wallet address for deposits</small>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 11px; color: #6b7280; font-weight: 700;">
                    Status: 
                    <span style="color: {{ $cryptoWallet->is_active ? '#10b981' : '#ef4444' }};">
                        {{ $cryptoWallet->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.payment-settings.update') }}" style="padding: 14px;">
            @csrf
            <input type="hidden" name="payment_type" value="crypto">

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Wallet Address <span style="color: #ef4444;">*</span>
                </label>
                <input 
                    type="text" 
                    name="crypto_wallet_address" 
                    value="{{ $cryptoDetails['wallet_address'] ?? '' }}"
                    placeholder="Enter cryptocurrency wallet address"
                    required
                    style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Network (e.g., ERC-20, TRC-20, BEP-20)
                </label>
                <input 
                    type="text" 
                    name="crypto_network" 
                    value="{{ $cryptoDetails['network'] ?? '' }}"
                    placeholder="Enter network type"
                    style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    QR Code URL (optional)
                </label>
                <input 
                    type="text" 
                    name="crypto_qr_code" 
                    value="{{ $cryptoDetails['qr_code'] ?? '' }}"
                    placeholder="Enter QR code image URL"
                    style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
            </div>

            <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <input 
                    type="checkbox" 
                    name="crypto_active" 
                    id="crypto_active"
                    {{ $cryptoWallet->is_active ? 'checked' : '' }}
                    style="width: 16px; height: 16px; cursor: pointer;">
                <label for="crypto_active" style="font-size: 12px; font-weight: 700; color: #111827; cursor: pointer;">
                    Enable this payment method
                </label>
            </div>

            <button type="submit" style="padding: 10px 20px; background: #0b0c10; color: #fff; border: none; border-radius: 8px; font-size: 12px; font-weight: 900; cursor: pointer; transition: opacity 0.2s;">
                Update Crypto Wallet
            </button>
        </form>
    </div>

    <!-- PayPal Settings -->
    <div class="whitePanel" style="margin-bottom: 20px;">
        <div class="panelHead">
            <div>
                <h5>PayPal</h5>
                <small>Configure PayPal account details for payments</small>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 11px; color: #6b7280; font-weight: 700;">
                    Status: 
                    <span style="color: {{ $paypal->is_active ? '#10b981' : '#ef4444' }};">
                        {{ $paypal->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.payment-settings.update') }}" style="padding: 14px;">
            @csrf
            <input type="hidden" name="payment_type" value="paypal">

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    PayPal Email <span style="color: #ef4444;">*</span>
                </label>
                <input 
                    type="email" 
                    name="paypal_email" 
                    value="{{ $paypalDetails['email'] ?? '' }}"
                    placeholder="Enter PayPal email address"
                    required
                    style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Business Name (optional)
                </label>
                <input 
                    type="text" 
                    name="paypal_business_name" 
                    value="{{ $paypalDetails['business_name'] ?? '' }}"
                    placeholder="Enter business name"
                    style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Account Type (optional)
                </label>
                <select 
                    name="paypal_account_type"
                    style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
                    <option value="">Select account type</option>
                    <option value="personal" {{ ($paypalDetails['account_type'] ?? '') === 'personal' ? 'selected' : '' }}>Personal</option>
                    <option value="business" {{ ($paypalDetails['account_type'] ?? '') === 'business' ? 'selected' : '' }}>Business</option>
                </select>
            </div>

            <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <input 
                    type="checkbox" 
                    name="paypal_active" 
                    id="paypal_active"
                    {{ $paypal->is_active ? 'checked' : '' }}
                    style="width: 16px; height: 16px; cursor: pointer;">
                <label for="paypal_active" style="font-size: 12px; font-weight: 700; color: #111827; cursor: pointer;">
                    Enable this payment method
                </label>
            </div>

            <button type="submit" style="padding: 10px 20px; background: #0b0c10; color: #fff; border: none; border-radius: 8px; font-size: 12px; font-weight: 900; cursor: pointer; transition: opacity 0.2s;">
                Update PayPal
            </button>
        </form>
    </div>

    <!-- Bank Transfer Settings -->
    <div class="whitePanel" style="margin-bottom: 20px;">
        <div class="panelHead">
            <div>
                <h5>Bank Transfer</h5>
                <small>Configure bank account details for wire transfers</small>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 11px; color: #6b7280; font-weight: 700;">
                    Status: 
                    <span style="color: {{ $bankTransfer->is_active ? '#10b981' : '#ef4444' }};">
                        {{ $bankTransfer->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.payment-settings.update') }}" style="padding: 14px;">
            @csrf
            <input type="hidden" name="payment_type" value="bank">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Bank Name <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="bank_name" 
                        value="{{ $bankDetails['bank_name'] ?? '' }}"
                        placeholder="Enter bank name"
                        required
                        style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Account Name <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="account_name" 
                        value="{{ $bankDetails['account_name'] ?? '' }}"
                        placeholder="Enter account holder name"
                        required
                        style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Account Number <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="account_number" 
                        value="{{ $bankDetails['account_number'] ?? '' }}"
                        placeholder="Enter account number"
                        required
                        style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        Routing Number (US) / Sort Code (UK)
                    </label>
                    <input 
                        type="text" 
                        name="routing_number" 
                        value="{{ $bankDetails['routing_number'] ?? '' }}"
                        placeholder="Enter routing/sort code"
                        style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        SWIFT Code / BIC
                    </label>
                    <input 
                        type="text" 
                        name="swift_code" 
                        value="{{ $bankDetails['swift_code'] ?? '' }}"
                        placeholder="Enter SWIFT/BIC code"
                        style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                        IBAN (International)
                    </label>
                    <input 
                        type="text" 
                        name="iban" 
                        value="{{ $bankDetails['iban'] ?? '' }}"
                        placeholder="Enter IBAN"
                        style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff;">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 6px;">
                    Bank Address
                </label>
                <textarea 
                    name="bank_address" 
                    rows="3"
                    placeholder="Enter bank address"
                    style="width: 100%; padding: 10px 12px; border: 1px solid rgba(0,0,0,.10); border-radius: 8px; font-size: 13px; background: #fff; resize: vertical;">{{ $bankDetails['bank_address'] ?? '' }}</textarea>
            </div>

            <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <input 
                    type="checkbox" 
                    name="bank_active" 
                    id="bank_active"
                    {{ $bankTransfer->is_active ? 'checked' : '' }}
                    style="width: 16px; height: 16px; cursor: pointer;">
                <label for="bank_active" style="font-size: 12px; font-weight: 700; color: #111827; cursor: pointer;">
                    Enable this payment method
                </label>
            </div>

            <button type="submit" style="padding: 10px 20px; background: #0b0c10; color: #fff; border: none; border-radius: 8px; font-size: 12px; font-weight: 900; cursor: pointer; transition: opacity 0.2s;">
                Update Bank Details
            </button>
        </form>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .whitePanel form > div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }

        .panelHead {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 8px !important;
        }

        .panelHead > div:last-child {
            width: 100%;
        }

        .alert {
            padding: 10px 12px !important;
            font-size: 12px !important;
        }

        .alert ul {
            padding-left: 16px !important;
        }
    }

    @media (max-width: 480px) {
        .whitePanel {
            margin-bottom: 16px !important;
        }

        .whitePanel form {
            padding: 10px !important;
        }

        input[type="text"],
        input[type="email"],
        select,
        textarea {
            font-size: 14px !important;
        }
    }
</style>
@endsection
