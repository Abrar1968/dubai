<template>
    <section class="bg-[#eef8ef] py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <!-- Heading -->
            <div class="text-center">
                <p class="text-[#1b7f3a] text-xs sm:text-sm font-extrabold tracking-[0.22em] uppercase">
                    GET IN TOUCH
                </p>

                <h1 class="mt-3 text-3xl sm:text-5xl font-serif font-semibold text-slate-900">
                    Don't Hesitate to Contact Us
                </h1>

                <p class="mt-4 max-w-2xl mx-auto text-slate-600 text-sm sm:text-base leading-relaxed">
                    Lorem ipsum dolor sit amet, consecte adipiscing elit seddo eiusmod tempor
                    dolore magna aliqua inventore veritatis et quasi.
                </p>
            </div>

            <!-- Card -->
            <div
                class="mt-12 overflow-hidden rounded-2xl bg-white border border-slate-200 shadow-[0_20px_60px_rgba(0,0,0,0.10)]">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <!-- LEFT: Contact info (Dark) -->
                    <div class="bg-[#142a1a] text-white px-8 py-10 sm:px-12 sm:py-12">
                        <h2 class="text-2xl font-serif font-semibold">
                            Contact Information
                        </h2>
                        <p class="mt-2 text-white/70 text-sm">
                            Fill up the form and our team will get back you within 24 hours.
                        </p>

                        <!-- Office 1 -->
                        <div class="mt-8">
                            <h3 class="text-xl font-serif font-semibold">Bali Office</h3>

                            <ul class="mt-4 space-y-3 text-sm text-white/75">
                                <li class="flex gap-3">
                                    <span class="mt-[2px] text-[#55c06a]">📍</span>
                                    <span>Jl. Gatot Subroto VI/J No.30, Denpasar - Bali</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="mt-[2px] text-[#55c06a]">📞</span>
                                    <span>(+62) 8896-2220 &nbsp;|&nbsp; (+62) 81 574 6591</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="mt-[2px] text-[#55c06a]">✉️</span>
                                    <span>support@jumrah.com</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Office 2 -->
                        <div class="mt-10">
                            <h3 class="text-xl font-serif font-semibold">Surabaya Office</h3>

                            <ul class="mt-4 space-y-3 text-sm text-white/75">
                                <li class="flex gap-3">
                                    <span class="mt-[2px] text-[#55c06a]">📍</span>
                                    <span>Jl. Manyar Kertoadi No.1, Kec. Sukolilo, Surabaya</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="mt-[2px] text-[#55c06a]">📞</span>
                                    <span>(+62) 8896-2220 &nbsp;|&nbsp; (+62) 81 574 6591</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="mt-[2px] text-[#55c06a]">✉️</span>
                                    <span>support@jumrah.com</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Social -->
                        <!-- Social -->
                        <div class="mt-12">
                            <h3 class="text-xl font-serif font-semibold">Social Media</h3>

                            <div class="mt-4 flex items-center gap-3">
                                <!-- Facebook -->
                                <a href="#" aria-label="Facebook" class="h-10 w-10 rounded-full bg-[#1f8c3f] flex items-center justify-center
             text-white transition hover:brightness-110 active:scale-[0.95]">
                                    <Facebook class="w-5 h-5" />
                                </a>

                                <!-- Twitter -->
                                <a href="#" aria-label="Twitter" class="h-10 w-10 rounded-full bg-white/10 border border-white/15
             flex items-center justify-center text-white
             transition hover:bg-white/15 active:scale-[0.95]">
                                    <Twitter class="w-5 h-5" />
                                </a>

                                <!-- Instagram -->
                                <a href="#" aria-label="Instagram" class="h-10 w-10 rounded-full bg-white/10 border border-white/15
             flex items-center justify-center text-white
             transition hover:bg-white/15 active:scale-[0.95]">
                                    <Instagram class="w-5 h-5" />
                                </a>

                                <!-- LinkedIn -->
                                <a href="#" aria-label="LinkedIn" class="h-10 w-10 rounded-full bg-white/10 border border-white/15
             flex items-center justify-center text-white
             transition hover:bg-white/15 active:scale-[0.95]">
                                    <Linkedin class="w-5 h-5" />
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT: Form -->
                    <div class="px-8 py-10 sm:px-12 sm:py-12">
                        <form @submit.prevent="submit">
                            <div class="space-y-6">
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                                        Your Name
                                    </label>
                                    <input v-model="form.name" type="text" placeholder="Input your name" class="w-full rounded-full border border-slate-200 bg-white px-5 py-3.5 text-sm
                           outline-none transition focus:border-[#1b7f3a]/40 focus:ring-4 focus:ring-[#1b7f3a]/10" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                                        Your Email
                                    </label>
                                    <input v-model="form.email" type="email" placeholder="Input your email" class="w-full rounded-full border border-slate-200 bg-white px-5 py-3.5 text-sm
                           outline-none transition focus:border-[#1b7f3a]/40 focus:ring-4 focus:ring-[#1b7f3a]/10" />
                                </div>

                                <!-- Subject -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                                        Your Subject
                                    </label>
                                    <input v-model="form.subject" type="text" placeholder="Input your subject" class="w-full rounded-full border border-slate-200 bg-white px-5 py-3.5 text-sm
                           outline-none transition focus:border-[#1b7f3a]/40 focus:ring-4 focus:ring-[#1b7f3a]/10" />
                                </div>

                                <!-- Message -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                                        Your Message
                                    </label>
                                    <textarea v-model="form.message" rows="6" placeholder="Write your message"
                                        class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm resize-none
                           outline-none transition focus:border-[#1b7f3a]/40 focus:ring-4 focus:ring-[#1b7f3a]/10"></textarea>
                                </div>

                                <!-- Button -->
                                <div>
                                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#0f6a25] px-10 py-3.5
                           text-white font-semibold transition
                           hover:bg-[#0d5b20] active:scale-[0.98]">
                                        Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import HajjLayout from '@/layouts/HajjUmrahLayout.vue'
import { Facebook, Twitter, Instagram, Linkedin } from 'lucide-vue-next'

defineOptions({ layout: HajjLayout })

const form = reactive({
    name: '',
    email: '',
    subject: '',
    message: '',
})

const submit = () => {
    // Later: use Inertia form post
    // router.post(route('contact.send'), form)
    console.log('Contact submit:', { ...form })
}
</script>
