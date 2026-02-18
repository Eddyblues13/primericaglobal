@extends('layouts.admin')

@section('title', isset($plan) ? 'Edit Investment Plan - Admin' : 'Create Investment Plan - Admin')
@section('topTitle', isset($plan) ? 'Edit Investment Plan' : 'Create Investment Plan')

@section('content')
<div class="wrap">
    <div class="whitePanel" style="max-width: 800px; margin: 0 auto;">
        <div class="panelHead">
            <div>
                <h5>{{ isset($plan) ? 'Edit Investment Plan' : 'Create New Investment Plan' }}</h5>
                <small>{{ isset($plan) ? 'Update investment plan information' : 'Add a new investment plan to the
                    system' }}</small>
            </div>
            <a href="{{ route('admin.investment-plans') }}"
                style="padding: 8px 16px; border-radius: 8px; background: #6b7280; color: white; font-size: 12px; font-weight: 900; text-decoration: none;">
                ← Back
            </a>
        </div>

        @if($errors->any())
        <div
            style="margin: 12px 14px; padding: 12px; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px;">
            <ul style="margin: 0; padding-left: 20px; color: #dc2626; font-size: 12px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST"
            action="{{ isset($plan) ? route('admin.investment-plans.update', $plan) : route('admin.investment-plans.store') }}"
            style="padding: 20px;">
            @csrf
            @if(isset($plan))
            @method('PUT')
            @endif

            <div style="margin-bottom: 16px;">
                <label
                    style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Plan
                    Name *</label>
                <input type="text" name="name" value="{{ old('name', optional($plan)->name ?? '') }}" required
                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                    placeholder="e.g., STARTER PLAN" />
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', optional($plan)->slug ?? '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="auto-generated if empty" />
                </div>

                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Category</label>
                    <input type="text" name="category" value="{{ old('category', optional($plan)->category ?? '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g., Primrica Global Capital" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Strategy</label>
                    <input type="text" name="strategy" value="{{ old('strategy', optional($plan)->strategy ?? '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g., Primrica Global Capital Investment" />
                </div>

                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Risk
                        Level *</label>
                    <select name="risk_level" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;">
                        <option value="low" {{ old('risk_level', $plan->risk_level ?? '') == 'low' ? 'selected' : ''
                            }}>Low</option>
                        <option value="medium" {{ old('risk_level', $plan->risk_level ?? '') == 'medium' ? 'selected' :
                            '' }}>Medium</option>
                        <option value="high" {{ old('risk_level', $plan->risk_level ?? '') == 'high' ? 'selected' : ''
                            }}>High</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">NAV
                        (optional)</label>
                    <input type="number" name="nav" value="{{ old('nav', optional($plan)->nav ?? 0) }}" step="0.0001"
                        min="0"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0" />
                </div>

                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">1
                        Year Return % (optional)</label>
                    <input type="number" name="one_year_return"
                        value="{{ old('one_year_return', $plan->one_year_return ?? '') }}" step="0.01"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0" />
                </div>

                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Min
                        Investment *</label>
                    <input type="number" name="min_investment"
                        value="{{ old('min_investment', optional($plan)->min_investment ?? '') }}" step="0.01" min="0"
                        required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0.00" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Max
                        Investment (leave empty = Unlimited)</label>
                    <input type="number" name="max_investment"
                        value="{{ old('max_investment', $plan->max_investment ?? '') }}" step="0.01" min="0"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="Unlimited" />
                </div>

                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Profit
                        Margin % *</label>
                    <input type="number" name="profit_margin"
                        value="{{ old('profit_margin', $plan->profit_margin ?? '') }}" step="0.01" min="0" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g. 20" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Duration
                        (days) *</label>
                    <input type="number" name="duration_days"
                        value="{{ old('duration_days', optional($plan)->duration_days ?? '') }}" min="0" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g. 2, 7, 30" />
                </div>

                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Duration
                        Label</label>
                    <input type="text" name="duration_label"
                        value="{{ old('duration_label', optional($plan)->duration_label ?? '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g. 2 days, 1 month" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 20px;">
                <div>
                    <label
                        style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Display
                        Order</label>
                    <input type="number" name="display_order"
                        value="{{ old('display_order', $plan->display_order ?? '') }}" min="0"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0" />
                </div>

                <div style="display: flex; align-items: end;">
                    <label
                        style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 700; color: #111827;">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured',
                            optional($plan)->is_featured ?? false) ? 'checked' : '' }}
                        style="width: 18px; height: 18px; cursor: pointer;" />
                        Featured Plan
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit"
                    style="flex: 1; height: 44px; border-radius: 8px; background: #111827; color: white; font-size: 13px; font-weight: 900; cursor: pointer; border: none;">
                    {{ isset($plan) ? 'Update Plan' : 'Create Plan' }}
                </button>
                <a href="{{ route('admin.investment-plans') }}"
                    style="flex: 1; height: 44px; border-radius: 8px; background: #ef4444; color: white; font-size: 13px; font-weight: 900; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .wrap>div {
            margin: 0 !important;
        }

        form>div[style*="grid"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection