@extends('layouts.dashboard')

@section('title', 'TESLA Account')
@section('topTitle', 'Account')

@push('styles')
<style>
    .accountSection {
        margin-bottom: 20px;
    }
    .formGroup {
        margin-bottom: 16px;
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
        height: 42px;
        padding: 0 14px;
        border-radius: 10px;
        border: 1px solid rgba(0,0,0,.10);
        background: #fff;
        font-size: 13px;
        color: #111827;
        transition: all 0.2s;
    }
    .formInput:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .passwordWrapper {
        position: relative;
    }
    .passwordToggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        transition: color 0.2s;
    }
    .passwordToggle:hover {
        color: #111827;
    }
    .passwordToggle svg {
        width: 18px;
        height: 18px;
    }
    .sectionDivider {
        margin: 24px 0;
        border: none;
        border-top: 1px solid rgba(0,0,0,.06);
    }
    .sectionTitle {
        font-size: 13px;
        font-weight: 900;
        color: #111827;
        margin: 0 0 16px 0;
    }
    .infoBox {
        padding: 12px;
        border-radius: 10px;
        background: #f9fafb;
        border: 1px solid rgba(0,0,0,.06);
        font-size: 11px;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 16px;
    }
    .submitBtn {
        height: 42px;
        padding: 0 24px;
        border-radius: 10px;
        background: #E31937;
        color: #fff;
        font-size: 12px;
        font-weight: 900;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .submitBtn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    .accountStats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .statCard {
        padding: 16px;
        border-radius: 12px;
        background: #f9fafb;
        border: 1px solid rgba(0,0,0,.06);
    }
    .statLabel {
        font-size: 10px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .statValue {
        font-size: 18px;
        font-weight: 900;
        color: #111827;
    }
</style>
@endpush

@section('content')
<div class="wrap" id="account">
    <!-- Hero / Welcome -->
    <div class="surface">
        <div class="heroCard">
            <div class="heroText">
                <h3>Account Settings</h3>
                <p>Manage your account information, preferences, and security settings.</p>
            </div>
        </div>
    </div>

    <!-- Account Statistics -->
    <div class="accountStats" style="margin-top: 12px;">
        <div class="statCard">
            <div class="statLabel">Account Status</div>
            <div class="statValue" style="color: #10b981;">Active</div>
        </div>
        <div class="statCard">
            <div class="statLabel">Member Since</div>
            <div class="statValue">{{ $user->created_at->format('M Y') }}</div>
        </div>
        <div class="statCard">
            <div class="statLabel">Email Verified</div>
            <div class="statValue" style="color: {{ $user->email_verified_at ? '#10b981' : '#ef4444' }};">
                {{ $user->email_verified_at ? 'Yes' : 'No' }}
            </div>
        </div>
    </div>

    <!-- Account Settings Form -->
    <div class="lowerGrid" style="grid-template-columns: 1fr; margin-top: 12px;">
        <div class="whitePanel">
            <div class="panelHead">
                <div>
                    <h5>Personal Information</h5>
                    <small>Update your account details and security settings</small>
                </div>
            </div>

            <div style="padding: 20px;">
                @if ($errors->any())
                    <div style="padding: 12px; margin-bottom: 16px; border-radius: 10px; background: #fee2e2; border: 1px solid #fecaca;">
                        <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #991b1b;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div style="padding: 12px; margin-bottom: 16px; border-radius: 10px; background: #d1fae5; border: 1px solid #86efac; font-size: 12px; color: #065f46;">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('dashboard.account.update') }}" style="display: flex; flex-direction: column; gap: 20px;">
                    @csrf
                    @method('PUT')
                    
                    <!-- Personal Information Section -->
                    <div class="accountSection">
                        <h5 class="sectionTitle">Personal Information</h5>
                        
                        <div class="formGroup">
                            <label class="formLabel">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required class="formInput" placeholder="Enter your full name" />
                        </div>

                        <div class="formGroup">
                            <label class="formLabel">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="formInput" placeholder="Enter your email address" />
                        </div>
                    </div>

                    <hr class="sectionDivider" />

                    <!-- Password Change Section -->
                    <div class="accountSection">
                        <h5 class="sectionTitle">Change Password</h5>
                        
                        <div class="infoBox">
                            <strong>Note:</strong> Leave password fields empty if you don't want to change your password. All three fields are required only when changing your password.
                        </div>
                        
                        <div class="formGroup">
                            <label class="formLabel">Current Password</label>
                            <div class="passwordWrapper">
                                <input type="password" name="current_password" id="current_password" class="formInput" placeholder="Enter your current password" />
                                <button type="button" class="passwordToggle" onclick="togglePassword('current_password', 'current_password_toggle')" id="current_password_toggle" aria-label="Toggle password visibility">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="formGroup">
                            <label class="formLabel">New Password</label>
                            <div class="passwordWrapper">
                                <input type="password" name="password" id="password" class="formInput" placeholder="Enter your new password (min. 8 characters)" />
                                <button type="button" class="passwordToggle" onclick="togglePassword('password', 'password_toggle')" id="password_toggle" aria-label="Toggle password visibility">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="formGroup">
                            <label class="formLabel">Confirm New Password</label>
                            <div class="passwordWrapper">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="formInput" placeholder="Confirm your new password" />
                                <button type="button" class="passwordToggle" onclick="togglePassword('password_confirmation', 'password_confirmation_toggle')" id="password_confirmation_toggle" aria-label="Toggle password visibility">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr class="sectionDivider" />

                    <!-- Submit Button -->
                    <div style="display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="submit" class="submitBtn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 6L9 17l-5-5" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId, toggleId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        
        if (input.type === 'password') {
            input.type = 'text';
            toggle.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                    <line x1="1" y1="1" x2="23" y2="23" />
                </svg>
            `;
        } else {
            input.type = 'password';
            toggle.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            `;
        }
    }
</script>
@endpush
@endsection
