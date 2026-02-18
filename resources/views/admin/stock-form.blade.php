@extends('layouts.admin')

@section('title', isset($stock) ? 'Edit Stock - Admin' : 'Create Stock - Admin')
@section('topTitle', isset($stock) ? 'Edit Stock' : 'Create Stock')

@section('content')
<div class="wrap">
    <div class="whitePanel" style="max-width: 800px; margin: 0 auto;">
        <div class="panelHead">
            <div>
                <h5>{{ isset($stock) ? 'Edit Stock' : 'Create New Stock' }}</h5>
                <small>{{ isset($stock) ? 'Update stock information' : 'Add a new stock to the system' }}</small>
            </div>
            <a href="{{ route('admin.stocks') }}" style="padding: 8px 16px; border-radius: 8px; background: #6b7280; color: white; font-size: 12px; font-weight: 900; text-decoration: none;">
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

        <form method="POST" action="{{ isset($stock) ? route('admin.stocks.update', $stock) : route('admin.stocks.store') }}" style="padding: 20px;">
            @csrf
            @if(isset($stock))
                @method('PUT')
            @endif

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Ticker Symbol *</label>
                    <input type="text" name="ticker" value="{{ old('ticker', $stock->ticker ?? '') }}" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g., TSLA" maxlength="10" />
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Company Name *</label>
                    <input type="text" name="name" value="{{ old('name', $stock->name ?? '') }}" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g., Tesla Inc." />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Current Price *</label>
                    <input type="number" name="price" value="{{ old('price', $stock->price ?? '') }}" step="0.01" min="0" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0.00" />
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Change</label>
                    <input type="number" name="change" value="{{ old('change', $stock->change ?? '') }}" step="0.01"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0.00" />
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Change %</label>
                    <input type="number" name="change_percent" value="{{ old('change_percent', $stock->change_percent ?? '') }}" step="0.01"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0.00" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Volume</label>
                    <input type="number" name="volume" value="{{ old('volume', $stock->volume ?? '') }}" min="0"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0" />
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Market Cap</label>
                    <input type="text" name="market_cap" value="{{ old('market_cap', $stock->market_cap ?? '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g., $1.2T" />
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Domain/Website</label>
                <input type="text" name="domain" value="{{ old('domain', $stock->domain ?? '') }}"
                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                    placeholder="e.g., tesla.com" />
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Description</label>
                <textarea name="description" rows="4"
                    style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; resize: vertical;"
                    placeholder="Stock description...">{{ old('description', $stock->description ?? '') }}</textarea>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" style="flex: 1; height: 44px; border-radius: 8px; background: #111827; color: white; font-size: 13px; font-weight: 900; cursor: pointer; border: none;">
                    {{ isset($stock) ? 'Update Stock' : 'Create Stock' }}
                </button>
                <a href="{{ route('admin.stocks') }}" style="flex: 1; height: 44px; border-radius: 8px; background: #ef4444; color: white; font-size: 13px; font-weight: 900; text-decoration: none; display: flex; align-items: center; justify-content: center;">
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
    form > div[style*="grid"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
