@extends('layouts.app')

@section('title', 'Verify Email - TESLA')

@section('content')
    <!-- Verification Section -->
    <section class="bg-white py-16">
        <div class="wrap">
            <div class="max-w-md mx-auto">
                <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-[#0f1115] mb-4 text-center">
                    Verify Your Email
                </h1>
                <p class="text-[15px] md:text-[16px] text-black/60 text-center mb-8">
                    We've sent a 4-digit verification code to <strong class="text-black">{{ $email }}</strong>
                </p>

                <!-- Verification Form -->
                <div class="bg-white rounded-[18px] border border-black/10 p-8 shadow-[0_10px_30px_rgba(0,0,0,0.08)]">
                    @if (session('success'))
                        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200">
                            <p class="text-sm text-green-600">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200">
                            <p class="text-sm text-red-600">{{ session('error') }}</p>
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

                    <form method="POST" action="{{ route('verify-email.post') }}" id="verification-form">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-[13px] font-[700] text-black/60 mb-3 text-center">Enter Verification Code</label>
                            
                            <!-- 4-Digit Code Input -->
                            <div class="flex justify-center gap-3 mb-2">
                                <input type="text" maxlength="1" class="code-input w-16 h-16 text-center text-[28px] font-[900] rounded-lg border-2 border-black/20 bg-white text-[#0f1115] focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition" data-index="0" />
                                <input type="text" maxlength="1" class="code-input w-16 h-16 text-center text-[28px] font-[900] rounded-lg border-2 border-black/20 bg-white text-[#0f1115] focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition" data-index="1" />
                                <input type="text" maxlength="1" class="code-input w-16 h-16 text-center text-[28px] font-[900] rounded-lg border-2 border-black/20 bg-white text-[#0f1115] focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition" data-index="2" />
                                <input type="text" maxlength="1" class="code-input w-16 h-16 text-center text-[28px] font-[900] rounded-lg border-2 border-black/20 bg-white text-[#0f1115] focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition" data-index="3" />
                            </div>
                            
                            <!-- Hidden input to submit the full code -->
                            <input type="hidden" name="code" id="verification-code" value="" />
                        </div>

                        <button type="submit" class="w-full h-[44px] rounded-md bg-black text-white text-[13px] font-[900] hover:opacity-90 transition">
                            Verify Email
                        </button>
                    </form>

                    <!-- Resend Code -->
                    <div class="mt-6">
                        <p class="text-center text-[13px] text-black/60 mb-2">
                            Didn't receive the code?
                        </p>
                        <form method="POST" action="{{ route('resend-verification-code') }}">
                            @csrf
                            <button type="submit" class="w-full h-[44px] rounded-md border border-black/15 bg-white text-[#0f1115] text-[13px] font-[900] hover:bg-black/5 transition">
                                Resend Code
                            </button>
                        </form>
                    </div>

                    <!-- Help Text -->
                    <p class="mt-6 text-center text-[12px] text-black/40">
                        The verification code expires in 10 minutes for security purposes.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Code input auto-advance and backspace functionality
        const codeInputs = document.querySelectorAll('.code-input');
        const hiddenInput = document.getElementById('verification-code');
        const form = document.getElementById('verification-form');

        codeInputs.forEach((input, index) => {
            // Auto-focus first input on page load
            if (index === 0) {
                input.focus();
            }

            // Handle input
            input.addEventListener('input', function(e) {
                // Only allow digits
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if (this.value) {
                    // Auto-advance to next input
                    if (index < codeInputs.length - 1) {
                        codeInputs[index + 1].focus();
                    }
                }
                
                updateHiddenInput();
            });

            // Handle backspace
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    codeInputs[index - 1].focus();
                    codeInputs[index - 1].value = '';
                    updateHiddenInput();
                }
            });

            // Handle paste
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                
                if (pasteData.length === 4) {
                    codeInputs.forEach((input, i) => {
                        input.value = pasteData[i] || '';
                    });
                    codeInputs[3].focus();
                    updateHiddenInput();
                }
            });
        });

        // Update hidden input with combined code
        function updateHiddenInput() {
            const code = Array.from(codeInputs).map(input => input.value).join('');
            hiddenInput.value = code;

            // Auto-submit if all 4 digits are entered
            if (code.length === 4) {
                form.submit();
            }
        }
    </script>
@endsection
