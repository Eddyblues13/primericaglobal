@extends('layouts.app')

@section('title', 'Privacy & Legal - Primrica Global Capital')

@section('content')
<!-- Hero Section -->
<section class="bg-white py-16">
    <div class="wrap">
        <div class="max-w-3xl">
            <h1 class="text-[48px] md:text-[64px] font-[900] tracking-[-.04em] text-[#0f1115] mb-4">
                Privacy & Legal
            </h1>
            <p class="text-[15px] md:text-[16px] text-black/60">
                Last updated: January 18, 2025
            </p>
        </div>
    </div>
</section>

<!-- Privacy Content -->
<section class="bg-white pb-20">
    <div class="wrap">
        <div class="max-w-4xl">
            <div class="prose prose-lg max-w-none text-[15px] text-black/70 leading-relaxed">
                <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-4 mt-8">1. Information We Collect
                </h2>
                <p class="mb-6">
                    We collect information that you provide directly to us, including name, email address, phone number,
                    payment information, and other information you choose to provide when using our services.
                </p>

                <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-4 mt-8">2. How We Use Your
                    Information</h2>
                <p class="mb-4">We use the information we collect to:</p>
                <ul class="list-disc list-inside space-y-2 mb-6 ml-4">
                    <li>Provide, maintain, and improve our services</li>
                    <li>Process transactions and send related information</li>
                    <li>Send technical notices, updates, and support messages</li>
                    <li>Respond to your comments and questions</li>
                    <li>Monitor and analyze trends and usage</li>
                </ul>

                <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-4 mt-8">3. Information Sharing
                </h2>
                <p class="mb-6">
                    We do not sell, trade, or rent your personal information to third parties. We may share your
                    information only in the following circumstances:
                </p>
                <ul class="list-disc list-inside space-y-2 mb-6 ml-4">
                    <li>With your consent</li>
                    <li>To comply with legal obligations</li>
                    <li>To protect and defend our rights</li>
                    <li>With service providers who assist us in operating our platform</li>
                </ul>

                <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-4 mt-8">4. Data Security</h2>
                <p class="mb-6">
                    We implement appropriate technical and organizational measures to protect your personal information
                    against unauthorized access, alteration, disclosure, or destruction. However, no method of
                    transmission over the Internet is 100% secure.
                </p>

                <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-4 mt-8">5. Your Rights</h2>
                <p class="mb-4">You have the right to:</p>
                <ul class="list-disc list-inside space-y-2 mb-6 ml-4">
                    <li>Access and receive a copy of your personal data</li>
                    <li>Rectify inaccurate or incomplete data</li>
                    <li>Request deletion of your personal data</li>
                    <li>Object to processing of your personal data</li>
                    <li>Request restriction of processing</li>
                </ul>

                <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-4 mt-8">6. Cookies</h2>
                <p class="mb-6">
                    We use cookies and similar tracking technologies to track activity on our platform and hold certain
                    information. You can instruct your browser to refuse all cookies or to indicate when a cookie is
                    being sent.
                </p>

                <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-4 mt-8">7. Changes to This Policy
                </h2>
                <p class="mb-6">
                    We may update our Privacy Policy from time to time. We will notify you of any changes by posting the
                    new Privacy Policy on this page and updating the "Last updated" date.
                </p>

                <h2 class="text-[26px] font-[900] tracking-[-.02em] text-[#0f1115] mb-4 mt-8">8. Contact Us</h2>
                <p class="mb-6">
                    If you have any questions about this Privacy Policy, please contact us at <a
                        href="{{ route('contact') }}" class="text-[#0f1115] underline">our contact page</a>.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection