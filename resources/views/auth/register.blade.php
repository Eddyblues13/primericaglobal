@extends('layouts.app')

@section('title', 'Register - Primrica Global Capital')

@section('content')
<!-- Hero Section -->
<section class="bg-white py-16">
    <div class="wrap">
        <div class="max-w-md mx-auto">
            <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-[#0f1115] mb-4 text-center">
                Create Account
            </h1>
            <p class="text-[15px] md:text-[16px] text-black/60 text-center mb-8">
                Start your journey with automated investments, stock trading, and premium vehicles.
            </p>

            <!-- Register Form -->
            <div class="bg-white rounded-[18px] border border-black/10 p-8 shadow-[0_10px_30px_rgba(0,0,0,0.08)]">
                @if ($errors->any())
                <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[13px] font-[700] text-black/60 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe"
                            class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20"
                            required autofocus />
                    </div>

                    <div>
                        <label class="block text-[13px] font-[700] text-black/60 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com"
                            class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20"
                            required />
                    </div>

                    <div>
                        <label class="block text-[13px] font-[700] text-black/60 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="register-password" name="password"
                                placeholder="Create a strong password"
                                class="w-full h-12 px-4 pr-12 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20"
                                required />
                            <button type="button" onclick="togglePassword('register-password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-black/40 hover:text-black/60 transition">
                                <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-[700] text-black/60 mb-2">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="register-password-confirm" name="password_confirmation"
                                placeholder="Confirm your password"
                                class="w-full h-12 px-4 pr-12 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20"
                                required />
                            <button type="button" onclick="togglePassword('register-password-confirm', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-black/40 hover:text-black/60 transition">
                                <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <label class="flex items-start gap-2 cursor-pointer">
                        <input type="checkbox"
                            class="mt-1 w-4 h-4 rounded border-black/20 text-black focus:ring-2 focus:ring-black/20"
                            required />
                        <span class="text-[13px] text-black/60">I agree to the <a href="{{ route('terms') }}"
                                class="font-[700] text-black hover:opacity-80 transition">Terms of Service</a> and <a
                                href="{{ route('privacy') }}"
                                class="font-[700] text-black hover:opacity-80 transition">Privacy Policy</a></span>
                    </label>

                    <button type="submit"
                        class="w-full h-[44px] rounded-md bg-black text-white text-[13px] font-[900] hover:opacity-90 transition">
                        Create Account
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-black/10"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-black/40 text-[13px]">Or continue with</span>
                    </div>
                </div>

                <!-- Google Login -->
                <button type="button"
                    class="w-full h-[44px] rounded-md border border-black/15 bg-white text-[#0f1115] text-[13px] font-[900] hover:bg-black/5 transition flex items-center justify-center gap-3">
                    <svg width="20" height="20" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853"
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05"
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path fill="#EA4335"
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    Continue with Google
                </button>

                <!-- Sign In Link -->
                <p class="mt-6 text-center text-[13px] text-black/60">
                    Already have an account? <a href="{{ route('login') }}"
                        class="font-[700] text-black hover:opacity-80 transition">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</section>

<script>
    function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
</script>
@endsection