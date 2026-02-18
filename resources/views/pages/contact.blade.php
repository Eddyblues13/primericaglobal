@extends('layouts.app')

@section('title', 'Contact Us - Primrica Global Capital')

@section('content')
<!-- Hero Section -->
<section class="bg-white py-16">
    <div class="wrap">
        <div class="max-w-3xl">
            <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-[#0f1115] mb-4">
                Contact Us
            </h1>
            <p class="text-[15px] md:text-[16px] text-black/60">
                Get in touch with our team. We're here to help with any questions.
            </p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="bg-white pb-20">
    <div class="wrap">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div>
                <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6">Send us a Message</h2>
                <form class="space-y-6">
                    <div>
                        <label class="block text-[13px] font-[700] text-black/60 mb-2">Name</label>
                        <input type="text"
                            class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20"
                            placeholder="Your name" />
                    </div>
                    <div>
                        <label class="block text-[13px] font-[700] text-black/60 mb-2">Email</label>
                        <input type="email"
                            class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20"
                            placeholder="your@email.com" />
                    </div>
                    <div>
                        <label class="block text-[13px] font-[700] text-black/60 mb-2">Subject</label>
                        <input type="text"
                            class="w-full h-12 px-4 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20"
                            placeholder="What can we help with?" />
                    </div>
                    <div>
                        <label class="block text-[13px] font-[700] text-black/60 mb-2">Message</label>
                        <textarea rows="6"
                            class="w-full px-4 py-3 rounded-lg border border-black/10 bg-white text-[#0f1115] text-[14px] focus:outline-none focus:ring-2 focus:ring-black/20"
                            placeholder="Your message..."></textarea>
                    </div>
                    <button
                        class="h-[44px] px-8 rounded-md bg-black text-white text-[13px] font-[900] hover:opacity-90 transition">
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div>
                <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-6">Get in Touch</h2>
                <div class="space-y-8">
                    <div>
                        <div class="text-[14px] font-[900] text-black/60 mb-2">Email</div>
                        <a href="mailto:support@Primrica Global Capital.com"
                            class="text-[15px] text-[#0f1115] hover:opacity-80 transition">support@Primrica Global
                            Capital.com</a>
                    </div>
                    <div>
                        <div class="text-[14px] font-[900] text-black/60 mb-2">Phone</div>
                        <a href="tel:+13024197620"
                            class="text-[15px] text-[#0f1115] hover:opacity-80 transition">+13024197620</a>
                    </div>
                    <div>
                        <div class="text-[14px] font-[900] text-black/60 mb-2">Address</div>
                        <div class="text-[15px] text-black/70">
                            1 Primrica Global Capital Road<br />
                            Austin, TX 78735<br />
                            United States
                        </div>
                    </div>
                    <div>
                        <div class="text-[14px] font-[900] text-black/60 mb-4">Business Hours</div>
                        <div class="space-y-2 text-[15px] text-black/70">
                            <div>Monday - Friday: 9:00 AM - 6:00 PM</div>
                            <div>Saturday: 10:00 AM - 4:00 PM</div>
                            <div>Sunday: Closed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection