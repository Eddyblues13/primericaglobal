@extends('layouts.app')

@section('title', 'Account - TESLA')

@section('content')
    <!-- Hero Section -->
    <section class="bg-white py-16">
        <div class="wrap">
            <div class="max-w-3xl">
                <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-[#0f1115] mb-4">
                    Account Settings
                </h1>
                <p class="text-[15px] md:text-[16px] text-black/60">
                    Manage your profile, preferences, and account security.
                </p>
            </div>
        </div>
    </section>

    <!-- Account Settings -->
    <section class="bg-white pb-20">
        <div class="wrap">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <nav class="space-y-2">
                            <a href="#profile" class="block px-4 py-3 rounded-lg text-[13px] font-[700] text-black/60 hover:bg-black/5 hover:text-black transition">Profile</a>
                            <a href="#security" class="block px-4 py-3 rounded-lg text-[13px] font-[700] text-black/60 hover:bg-black/5 hover:text-black transition">Security</a>
                            <a href="#notifications" class="block px-4 py-3 rounded-lg text-[13px] font-[700] text-black/60 hover:bg-black/5 hover:text-black transition">Notifications</a>
                            <a href="#billing" class="block px-4 py-3 rounded-lg text-[13px] font-[700] text-black/60 hover:bg-black/5 hover:text-black transition">Billing</a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Profile Section -->
                    <div id="profile" class="mb-12">
                        <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6">Profile Information</h2>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[13px] font-[700] text-black/60 mb-2">Full Name</label>
                                <input type="text" value="John Doe" class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20" />
                            </div>
                            <div>
                                <label class="block text-[13px] font-[700] text-black/60 mb-2">Email</label>
                                <input type="email" value="john@example.com" class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20" />
                            </div>
                            <div>
                                <label class="block text-[13px] font-[700] text-black/60 mb-2">Phone</label>
                                <input type="tel" value="+13024197620" class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20" />
                            </div>
                            <button class="h-[44px] px-8 rounded-md bg-black text-white text-[13px] font-[900] hover:opacity-90 transition">
                                Save Changes
                            </button>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div id="security" class="mb-12">
                        <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6">Security</h2>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[13px] font-[700] text-black/60 mb-2">Current Password</label>
                                <input type="password" class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20" />
                            </div>
                            <div>
                                <label class="block text-[13px] font-[700] text-black/60 mb-2">New Password</label>
                                <input type="password" class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20" />
                            </div>
                            <div>
                                <label class="block text-[13px] font-[700] text-black/60 mb-2">Confirm New Password</label>
                                <input type="password" class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20" />
                            </div>
                            <button class="h-[44px] px-8 rounded-md bg-black text-white text-[13px] font-[900] hover:opacity-90 transition">
                                Update Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
