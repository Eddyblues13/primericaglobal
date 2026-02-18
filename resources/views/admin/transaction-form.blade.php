@extends('layouts.admin')

@section('title', isset($transaction) ? 'Edit Transaction - Admin' : 'Create Transaction - Admin')
@section('topTitle', isset($transaction) ? 'Edit Transaction' : 'Create Transaction')

@section('content')
<div class="wrap">
    <div class="whitePanel" style="max-width: 700px; margin: 0 auto;">
        <div class="panelHead">
            <div>
                <h5>{{ isset($transaction) ? 'Edit Transaction' : 'Create New Transaction' }}</h5>
                <small>{{ isset($transaction) ? 'Update transaction details' : 'Add a new wallet transaction' }}</small>
            </div>
            <a href="{{ route('admin.transactions') }}" style="padding: 8px 16px; border-radius: 8px; background: #6b7280; color: white; font-size: 12px; font-weight: 900; text-decoration: none;">
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

        <form method="POST" action="{{ isset($transaction) ? route('admin.transactions.update', $transaction) : route('admin.transactions.store') }}" style="padding: 20px;">
            @csrf
            @if(isset($transaction))
                @method('PUT')
            @endif

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">User *</label>
                <select name="user_id" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                    <option value="">Select user</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id', isset($transaction) ? $transaction->user_id : '') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Type *</label>
                    <select name="type" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                        <option value="deposit" {{ old('type', isset($transaction) ? $transaction->type : '') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                        <option value="withdrawal" {{ old('type', isset($transaction) ? $transaction->type : '') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                        <option value="investment" {{ old('type', isset($transaction) ? $transaction->type : '') == 'investment' ? 'selected' : '' }}>Investment</option>
                        <option value="admin_adjustment" {{ old('type', isset($transaction) ? $transaction->type : '') == 'admin_adjustment' ? 'selected' : '' }}>Admin Adjustment</option>
                        <option value="other" {{ old('type', isset($transaction) ? $transaction->type : '') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Direction *</label>
                    <select name="direction" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                        <option value="credit" {{ old('direction', isset($transaction) ? $transaction->direction : '') == 'credit' ? 'selected' : '' }}>Credit</option>
                        <option value="debit" {{ old('direction', isset($transaction) ? $transaction->direction : '') == 'debit' ? 'selected' : '' }}>Debit</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Title *</label>
                    <input type="text" name="title" value="{{ old('title', isset($transaction) ? $transaction->title : '') }}" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g. Deposit via Bank" />
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Asset</label>
                    <input type="text" name="asset" value="{{ old('asset', isset($transaction) ? $transaction->asset : 'USD') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="USD" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Amount *</label>
                    <input type="number" name="amount" value="{{ old('amount', isset($transaction) ? $transaction->amount : '') }}" step="0.01" min="0.01" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0.00" />
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Status *</label>
                    <select name="status" required style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                        <option value="Pending" {{ old('status', isset($transaction) ? $transaction->status : '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ old('status', isset($transaction) ? $transaction->status : '') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Rejected" {{ old('status', isset($transaction) ? $transaction->status : '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Withdrawal details (optional)</label>
                <textarea name="withdrawal_details" rows="3" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px; resize: vertical;">{{ old('withdrawal_details', isset($transaction) ? $transaction->withdrawal_details : '') }}</textarea>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" style="flex: 1; height: 44px; border-radius: 8px; background: #111827; color: white; font-size: 13px; font-weight: 900; cursor: pointer; border: none;">
                    {{ isset($transaction) ? 'Update Transaction' : 'Create Transaction' }}
                </button>
                <a href="{{ route('admin.transactions') }}" style="flex: 1; height: 44px; border-radius: 8px; background: #ef4444; color: white; font-size: 13px; font-weight: 900; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .wrap > div { margin: 0 !important; }
    form > div[style*="grid"] { grid-template-columns: 1fr !important; }
}
</style>
@endsection
