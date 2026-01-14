<template>
    <footer class="bg-[#efefef]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14 sm:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 md:gap-12">
                <!-- LEFT: Logo + About + Social -->
                <div>
                    <!-- Logo (replace with your image) -->
                    <a href="/" class="inline-flex items-center gap-3">
                        <!-- <img src="/assets/img/logo.png" class="h-14 w-auto" alt="Al Nahda Centre" /> -->
                        <div
                            class="h-14 w-14 rounded-xl bg-white/70 border border-slate-200 flex items-center justify-center">
                            <span class="text-xs font-bold text-slate-700">LOGO</span>
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs tracking-[0.22em] uppercase text-[#d39b4a] font-bold">Al Nahda</p>
                            <p class="text-lg font-extrabold text-slate-900">Centre</p>
                        </div>
                    </a>

                    <p class="mt-6 text-slate-700 leading-relaxed max-w-sm">
                        Providing exceptional government documentation services to individuals and organizations across
                        all industries and work sectors.
                    </p>

                    <!-- Social icons -->
                    <div class="mt-6 flex items-center gap-3">
                        <a href="#" class="h-9 w-9 rounded-full border border-slate-900/25 text-slate-900
                     flex items-center justify-center transition hover:bg-slate-900 hover:text-white"
                            aria-label="Facebook">
                            f
                        </a>
                        <a href="#" class="h-9 w-9 rounded-full border border-slate-900/25 text-slate-900
                     flex items-center justify-center transition hover:bg-slate-900 hover:text-white" aria-label="X">
                            x
                        </a>
                        <a href="#" class="h-9 w-9 rounded-full border border-slate-900/25 text-slate-900
                     flex items-center justify-center transition hover:bg-slate-900 hover:text-white"
                            aria-label="LinkedIn">
                            in
                        </a>
                        <a href="#" class="h-9 w-9 rounded-full border border-slate-900/25 text-slate-900
                     flex items-center justify-center transition hover:bg-slate-900 hover:text-white"
                            aria-label="Instagram">
                            ig
                        </a>
                        <a href="#" class="h-9 w-9 rounded-full border border-slate-900/25 text-slate-900
                     flex items-center justify-center transition hover:bg-slate-900 hover:text-white"
                            aria-label="TikTok">
                            tt
                        </a>
                        <a href="#" class="h-9 w-9 rounded-full border border-slate-900/25 text-slate-900
                     flex items-center justify-center transition hover:bg-slate-900 hover:text-white"
                            aria-label="YouTube">
                            yt
                        </a>
                    </div>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="text-slate-900 font-bold text-lg">Company</h3>
                    <div class="mt-2 h-[2px] w-10 bg-slate-900/60"></div>

                    <ul class="mt-6 space-y-4 text-slate-700">
                        <li><a href="/about" class="hover:text-slate-900 transition">About Us</a></li>
                        <li><a href="/services" class="hover:text-slate-900 transition">Our Services</a></li>
                        <li><a href="/news" class="hover:text-slate-900 transition">News &amp; Update</a></li>
                        <li><a href="/gallery" class="hover:text-slate-900 transition">Our Gallery</a></li>
                        <li><a href="/terms" class="hover:text-slate-900 transition">Terms And Conditions</a></li>
                        <li><a href="/contact" class="hover:text-slate-900 transition">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h3 class="text-slate-900 font-bold text-lg">Services</h3>
                    <div class="mt-2 h-[2px] w-10 bg-slate-900/60"></div>

                    <ul class="mt-6 space-y-4 text-slate-700">
                        <li><a href="#" class="hover:text-slate-900 transition">Al Nahda Medical Centre</a></li>
                        <li><a href="#" class="hover:text-slate-900 transition">Dubai Health Authority</a></li>
                        <li><a href="#" class="hover:text-slate-900 transition">Medical Fitness System</a></li>
                        <li><a href="#" class="hover:text-slate-900 transition">Medical Screening</a></li>
                        <li><a href="#" class="hover:text-slate-900 transition">Amer Centre Dubai</a></li>
                        <li><a href="#" class="hover:text-slate-900 transition">Taw-Jeeh &amp; Tas-Heel</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-slate-900 font-bold text-lg">Contact Info</h3>
                    <div class="mt-2 h-[2px] w-10 bg-slate-900/60"></div>

                    <div class="mt-6 space-y-4 text-slate-800">
                        <p class="font-extrabold">Toll Free - 800 62432</p>
                        <p class="text-slate-700">
                            10th street, Al Qusais 1st,<br />
                            Dubai, UAE
                        </p>

                        <div class="pt-2 space-y-3 text-slate-700">
                            <p class="flex items-center gap-3">
                                <span class="text-slate-900">✉</span>
                                helpdesk@alnahdacentre.com
                            </p>
                            <p class="flex items-center gap-3">
                                <span class="text-slate-900">☎</span>
                                04-256-9968
                            </p>
                        </div>

                        <a href="/contact" class="inline-flex mt-6 items-center justify-center rounded-md bg-[#0e3a1f] px-6 py-3
                     text-white font-semibold tracking-wide transition
                     hover:bg-[#0b2f19] active:scale-[0.98]">
                            CONTACT US
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom bar (optional) -->
        <div class="border-t border-black/10">
            <div
                class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-5 text-sm text-slate-600 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p>© {{ new Date().getFullYear() }} Al Nahda Centre. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="/privacy" class="hover:text-slate-900 transition">Privacy</a>
                    <a href="/terms" class="hover:text-slate-900 transition">Terms</a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
// No JS required
</script>
