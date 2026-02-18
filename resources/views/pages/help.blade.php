@extends('layouts.app')

@section('title', 'Help Center - Primrica Global Capital')

@section('content')
<!-- Hero Section -->
<section class="bg-white py-16">
    <div class="wrap">
        <div class="max-w-3xl">
            <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-[#0f1115] mb-4">
                Help Center
            </h1>
            <p class="text-[15px] md:text-[16px] text-black/60">
                Find answers to frequently asked questions and get support.
            </p>
        </div>
    </div>
</section>

<!-- Help Center Section -->
<section class="bg-white pb-20">
    <div class="wrap">
        <!-- Search -->
        <div class="max-w-2xl mb-12">
            <input type="search" placeholder="Search for help..."
                class="w-full h-14 px-6 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20" />
        </div>

        <!-- FAQ Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div>
                <h2 class="text-[24px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6">Getting Started</h2>
                <div class="space-y-4">
                    <div class="p-4 rounded-lg border border-black/10 bg-white">
                        <div class="text-[15px] font-[900] text-[#0f1115] mb-2">How do I create an account?</div>
                        <div class="text-[13px] text-black/60">Click "Sign In" in the header, then select "Create
                            Account" to get started with your email address.</div>
                    </div>
                    <div class="p-4 rounded-lg border border-black/10 bg-white">
                        <div class="text-[15px] font-[900] text-[#0f1115] mb-2">How do I start investing?</div>
                        <div class="text-[13px] text-black/60">Navigate to the Invest page and choose an investment plan
                            that matches your risk tolerance.</div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-[24px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6">Trading & Stocks</h2>
                <div class="space-y-4">
                    <div class="p-4 rounded-lg border border-black/10 bg-white">
                        <div class="text-[15px] font-[900] text-[#0f1115] mb-2">How do I buy stocks?</div>
                        <div class="text-[13px] text-black/60">Visit the Stocks page to view market data, then select
                            stocks to add to your portfolio.</div>
                    </div>
                    <div class="p-4 rounded-lg border border-black/10 bg-white">
                        <div class="text-[15px] font-[900] text-[#0f1115] mb-2">Are the stock prices real-time?</div>
                        <div class="text-[13px] text-black/60">Yes, we provide real-time stock market data updated
                            throughout trading hours.</div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-[24px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6">Inventory</h2>
                <div class="space-y-4">
                    <div class="p-4 rounded-lg border border-black/10 bg-white">
                        <div class="text-[15px] font-[900] text-[#0f1115] mb-2">How do I order a vehicle?</div>
                        <div class="text-[13px] text-black/60">Browse our Inventory page, select your preferred model,
                            and click "Order" to begin the process.</div>
                    </div>
                    <div class="p-4 rounded-lg border border-black/10 bg-white">
                        <div class="text-[15px] font-[900] text-[#0f1115] mb-2">What is the delivery time?</div>
                        <div class="text-[13px] text-black/60">Delivery times vary by model and location. Contact us for
                            specific delivery estimates.</div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-[24px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6">Account & Billing</h2>
                <div class="space-y-4">
                    <div class="p-4 rounded-lg border border-black/10 bg-white">
                        <div class="text-[15px] font-[900] text-[#0f1115] mb-2">How do I update my profile?</div>
                        <div class="text-[13px] text-black/60">Go to the Account page and update your information in the
                            Profile section.</div>
                    </div>
                    <div class="p-4 rounded-lg border border-black/10 bg-white">
                        <div class="text-[15px] font-[900] text-[#0f1115] mb-2">How do I change my password?</div>
                        <div class="text-[13px] text-black/60">Visit Account → Security to update your password.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="border-t border-black/10 pt-12">
            <h2 class="text-[24px] font-[900] tracking-[-.02em] text-[#0f1115] mb-4">Still Need Help?</h2>
            <p class="text-[15px] text-black/60 mb-6">Can't find what you're looking for? Our support team is ready to
                help.</p>
            <a href="{{ route('contact') }}"
                class="inline-block h-[44px] px-8 rounded-md bg-black text-white text-[13px] font-[900] hover:opacity-90 transition">
                Contact Support
            </a>
        </div>
    </div>
</section>
@endsection