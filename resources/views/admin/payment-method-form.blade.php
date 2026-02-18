@extends('layouts.admin')

@section('title', isset($method) ? 'Edit Payment Method - Admin' : 'Create Payment Method - Admin')
@section('topTitle', isset($method) ? 'Edit Payment Method' : 'Create Payment Method')

@section('content')
<div class="wrap">
    <div class="whitePanel" style="max-width: 800px; margin: 0 auto;">
        <div class="panelHead">
            <div>
                <h5>{{ isset($method) ? 'Edit Payment Method' : 'Create New Payment Method' }}</h5>
                <small>{{ isset($method) ? 'Update payment method properties and visibility' : 'Add a new deposit/withdrawal method to the system' }}</small>
            </div>
            <a href="{{ route('admin.payment-methods') }}" style="padding: 8px 16px; border-radius: 8px; background: #6b7280; color: white; font-size: 12px; font-weight: 900; text-decoration: none;">
                ← Back
            </a>
        </div>

        @if($errors->any())
            <div style="margin: 12px 14px; padding: 12px; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px;">
                <ul style="margin: 0; padding-left: 20px; color: #dc2626; font-size: 12px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ isset($method) ? route('admin.payment-methods.update', $method) : route('admin.payment-methods.store') }}" style="padding: 20px;">
            @csrf
            @if(isset($method))
                @method('PUT')
            @endif

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Name *</label>
                <input type="text" name="name" value="{{ old('name', $method->name ?? '') }}" required
                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                    placeholder="e.g., Bitcoin, Bank Transfer, PayPal" />
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $method->slug ?? '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="auto-generated if empty" />
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $method->display_order ?? 0) }}" min="0"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Type *</label>
                    <select name="type" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                        @php
                            $type = old('type', $method->type ?? 'both');
                        @endphp
                        <option value="deposit" {{ $type === 'deposit' ? 'selected' : '' }}>Deposit only</option>
                        <option value="withdrawal" {{ $type === 'withdrawal' ? 'selected' : '' }}>Withdrawal only</option>
                        <option value="both" {{ $type === 'both' ? 'selected' : '' }}>Deposit & Withdrawal</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Category</label>
                    @php
                        $category = old('category', $method->category ?? 'other');
                    @endphp
                    <select name="category"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                        <option value="crypto" {{ $category === 'crypto' ? 'selected' : '' }}>Crypto</option>
                        <option value="bank" {{ $category === 'bank' ? 'selected' : '' }}>Bank</option>
                        <option value="wallet" {{ $category === 'wallet' ? 'selected' : '' }}>Wallet (PayPal, etc.)</option>
                        <option value="other" {{ $category === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Logo URL (optional)</label>
                <input type="text" name="logo_url" value="{{ old('logo_url', $method->logo_url ?? '') }}"
                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                    placeholder="https://example.com/logo.png" />
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">
                    Details (visible to users on deposit/withdraw pages)
                </label>
                <textarea name="details" rows="5"
                    style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; resize: vertical;"
                    placeholder="Example for crypto:
Network: Bitcoin (BTC)
Wallet address:
your_btc_address_here&#10;
Example for bank:
Bank name: ...
Account name: ...
Account number: ...
IBAN: ...
SWIFT/BIC: ...">{{ old('details', $method->details ?? '') }}</textarea>
                <p style="margin-top: 6px; font-size: 11px; color: #9ca3af;">
                    Tip: Use labels like <strong>Wallet address:</strong>, <strong>Bank name:</strong>, <strong>Account number:</strong>, <strong>IBAN:</strong>, <strong>SWIFT/BIC:</strong>.
                    The deposit page will automatically highlight and add copy buttons for these fields.
                </p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 700; color: #111827;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $method->is_active ?? true) ? 'checked' : '' }}
                        style="width: 18px; height: 18px; cursor: pointer;" />
                    Active (visible to users)
                </label>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" style="flex: 1; height: 44px; border-radius: 8px; background: #111827; color: white; font-size: 13px; font-weight: 900; cursor: pointer; border: none;">
                    {{ isset($method) ? 'Update Method' : 'Create Method' }}
                </button>
                <a href="{{ route('admin.payment-methods') }}" style="flex: 1; height: 44px; border-radius: 8px; background: #ef4444; color: white; font-size: 13px; font-weight: 900; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .wrap > div {
        margin: 0 !important;
    }
    form > div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

