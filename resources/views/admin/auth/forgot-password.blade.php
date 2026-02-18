@extends('layouts.app')

@section('title', 'Admin Forgot Password - TESLA')

@section('content')
    <!-- Hero Section -->
    <section class="bg-white py-16">
        <div class="wrap">
            <div class="max-w-md mx-auto">
                <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-[#0f1115] mb-4 text-center">
                    Admin Forgot Password?
                </h1>
                <p class="text-[15px] md:text-[16px] text-black/60 text-center mb-8">
                    Enter your admin email to receive a reset link.
                </p>

                <!-- Forgot Password Form -->
                <div class="bg-white rounded-[18px] border border-black/10 p-8 shadow-[0_10px_30px_rgba(0,0,0,0.08)]">
                    @if (session('status'))
                        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200">
                            <p class="text-sm text-green-600">{{ session('status') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200">
                            <ul class="text-sm text-red-600 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.forgot-password.post') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[13px] font-[700] text-black/60 mb-2">Admin Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@email.com" class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20" required autofocus />
                        </div>

                        <button type="submit" class="w-full h-[44px] rounded-md bg-black text-white text-[13px] font-[900] hover:opacity-90 transition">
                            Send Reset Link
                        </button>
                    </form>

                    <!-- Back to Login Link -->
                    <p class="mt-6 text-center text-[13px] text-black/60">
                        Remember your password? <a href="{{ route('admin.login') }}" class="font-[700] text-black hover:opacity-80 transition">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
