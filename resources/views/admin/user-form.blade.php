@extends('layouts.admin')

@section('title', isset($user) ? 'Edit User - Admin' : 'Create User - Admin')
@section('topTitle', isset($user) ? 'Edit User' : 'Create User')

@section('content')
<div class="wrap">
    <div class="whitePanel" style="max-width: 700px; margin: 0 auto;">
        <div class="panelHead">
            <div>
                <h5>{{ isset($user) ? 'Edit User' : 'Create New User' }}</h5>
                <small>{{ isset($user) ? 'Update user information' : 'Add a new user to the system' }}</small>
            </div>
            <a href="{{ isset($user) ? route('admin.users.show', $user) : route('admin.users') }}" style="padding: 8px 16px; border-radius: 8px; background: #6b7280; color: white; font-size: 12px; font-weight: 900; text-decoration: none;">
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

        <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" style="padding: 20px;">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', isset($user) ? $user->first_name : '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;" />
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', isset($user) ? $user->last_name : '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;" />
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" required
                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                    placeholder="John Doe" />
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Email *</label>
                    <input type="email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="user@example.com" />
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Username</label>
                    <input type="text" name="username" value="{{ old('username', isset($user) ? $user->username : '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="johndoe" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Password {{ isset($user) ? '(leave blank to keep current)' : '*' }}</label>
                    <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;" />
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Confirm Password {{ isset($user) ? '(leave blank to keep current)' : '*' }}</label>
                    <input type="password" name="password_confirmation" {{ isset($user) ? '' : 'required' }}
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;" />
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Initial Balance</label>
                <input type="number" name="available_balance" value="{{ old('available_balance', isset($user) ? $user->available_balance : 0) }}" step="0.01" min="0"
                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                    placeholder="0.00" />
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" style="flex: 1; height: 44px; border-radius: 8px; background: #111827; color: white; font-size: 13px; font-weight: 900; cursor: pointer; border: none;">
                    {{ isset($user) ? 'Update User' : 'Create User' }}
                </button>
                <a href="{{ isset($user) ? route('admin.users.show', $user) : route('admin.users') }}" style="flex: 1; height: 44px; border-radius: 8px; background: #ef4444; color: white; font-size: 13px; font-weight: 900; text-decoration: none; display: flex; align-items: center; justify-content: center;">
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
