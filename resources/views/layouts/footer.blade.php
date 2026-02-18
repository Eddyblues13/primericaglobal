<!-- FOOTER -->
<footer class="bg-black text-white">
    <div class="wrap py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 items-start">

            <!-- Left: Tesla logo only -->
            <div>
                <div class="text-[22px] font-[900] tracking-[.35em]">
                    TESLA
                </div>
            </div>

            <!-- Company -->
            <div>
                <div class="text-[14px] font-[900] mb-4">Company</div>
                <ul class="space-y-2 text-[13px] text-white/55">
                    <li>
                        <a href="{{ route('about') }}" class="hover:text-white transition">About</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <div class="text-[14px] font-[900] mb-4">Support</div>
                <ul class="space-y-2 text-[13px] text-white/55">
                    <li>
                        <a href="{{ route('help') }}" class="hover:text-white transition">Help Center</a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="hover:text-white transition">Terms</a>
                    </li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <div class="text-[14px] font-[900] mb-4">Legal</div>
                <ul class="space-y-2 text-[13px] text-white/55">
                    <li>
                        <a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy & Legal</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- subtle bottom divider -->
    <div class="border-t border-white/10"></div>
</footer>
