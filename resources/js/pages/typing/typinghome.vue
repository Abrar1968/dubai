<template>
    <TypingLayout>
        <div class="bg-white">
            <!-- ✅ HERO (Banner only, no text) -->
            <section class="relative h-[260px] sm:h-[360px] lg:h-[460px] w-full overflow-hidden">
                <img :src="heroImage" alt="Hero Banner" class="absolute inset-0 h-full w-full object-cover" />
                <!-- optional overlay for readability (keep it light) -->
                <div class="absolute inset-0 bg-black/25"></div>
            </section>

            <!-- ✅ SERVICES GRID -->
            <section class="py-10 bg-white">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <h3 class="text-2xl font-semibold text-slate-900 mb-2">Our Typing Services</h3>
                    <p class="text-slate-600 mb-6 text-sm">Quick access to common services — click a card to learn more.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <article v-for="s in services" :key="s.url" class="rounded-lg border border-slate-200 p-5 bg-slate-50">
                            <h4 class="font-semibold text-slate-900 mb-2">{{ s.title }}</h4>
                            <p class="text-sm text-slate-700 mb-4">{{ s.desc }}</p>
                            <a :href="s.url" class="inline-flex items-center rounded-md bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm">Learn more</a>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ✅ WELCOME + INTRO -->
            <section class="py-14 sm:py-16">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <div
                            class="flex items-center justify-center gap-3 text-[11px] tracking-[0.25em] font-bold uppercase text-emerald-700">
                            <span class="h-[1px] w-10 bg-emerald-700/60"></span>
                            Welcome to SS Group Travels & Typing
                            <span class="h-[1px] w-10 bg-emerald-700/60"></span>
                        </div>

                        <h2 class="mt-4 text-3xl sm:text-5xl font-serif font-semibold text-slate-900">
                            Centre for all your documentation services
                        </h2>

                        <p class="mt-5 max-w-3xl mx-auto text-slate-600 text-sm sm:text-base leading-relaxed">
                            We believe in team work, discipline, innovation, customer satisfaction and most importantly
                            in
                            respecting everyone.
                            We are fortunate to have a disciplined team that work towards providing the best for our
                            customers.
                            Our team work together to provide fruitful results to our customers and make them continue
                            their
                            business relationship with us.
                        </p>
                    </div>

                    <!-- ✅ Mission / Vision / Values -->
                    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Mission -->
                        <article class="rounded-2xl bg-slate-50 border border-slate-200 p-7 shadow-[0_18px_50px_rgba(0,0,0,0.06)]
                   transition hover:-translate-y-1 hover:shadow-[0_24px_70px_rgba(0,0,0,0.10)]">
                            <h3 class="text-xl font-bold text-emerald-700">Our Mission</h3>
                            <p class="mt-4 text-slate-600 text-sm leading-relaxed">
                                We simplify government documentation with exceptional service, ensuring a seamless
                                process
                                for UAE residents.
                                Our commitment to excellence helps clients save time and move forward with confidence.
                            </p>
                        </article>

                        <!-- Vision -->
                        <article class="rounded-2xl bg-slate-50 border border-slate-200 p-7 shadow-[0_18px_50px_rgba(0,0,0,0.06)]
                   transition hover:-translate-y-1 hover:shadow-[0_24px_70px_rgba(0,0,0,0.10)]">
                            <h3 class="text-xl font-bold text-amber-500">Our Vision</h3>
                            <p class="mt-4 text-slate-600 text-sm leading-relaxed">
                                Our vision is to exceed expectations with outstanding service and minimal client effort.
                                With an efficient team, we foster lasting relationships built on trust and results.
                            </p>
                        </article>

                        <!-- Values -->
                        <article class="rounded-2xl bg-slate-50 border border-slate-200 p-7 shadow-[0_18px_50px_rgba(0,0,0,0.06)]
                   transition hover:-translate-y-1 hover:shadow-[0_24px_70px_rgba(0,0,0,0.10)]">
                            <h3 class="text-xl font-bold text-emerald-700">Our Values</h3>
                            <p class="mt-4 text-slate-600 text-sm leading-relaxed">
                                Teamwork, discipline, and customer satisfaction drive us to deliver exceptional results.
                                We go the extra mile to build enduring partnerships and ensure client success.
                            </p>
                        </article>
                    </div>
                </div>
            </section>
        </div>
    </TypingLayout>
</template>

<script setup>
import TypingLayout from '@/layouts/TypingLayout.vue';

// Hero image from site settings (public/storage)
const heroImage = '/storage/settings/hero/kabah_1768984201_PGeKumrh.webp'

// Services shown in the grid below the hero
const services = [
  { title: 'Immigration', desc: 'Emirates ID, residency and immigration document services.', url: '/typing/services/immigration' },
  { title: 'Labour Ministry', desc: 'MOHRE and labour-related transactions and queries.', url: '/typing/services/labour-ministry' },
  { title: 'Tasheel Services', desc: 'Tasheel centre transactions, contract typing and document submission.', url: '/typing/services/tasheel-services' },
  { title: 'Domestic Workers Visa', desc: 'Visa processing and documentation for domestic workers.', url: '/typing/services/domestic-workers-visa' },
  { title: 'Family Visa', desc: 'Family visa flow, renewals, newborn registration and cancellations.', url: '/typing/services/family-visa-process' },
  { title: 'Health Insurance', desc: 'Medical fitness and health insurance coordination for visa processing.', url: '/typing/services/health-insurance' },
  { title: 'Ministry of Interior', desc: 'MOI-related permits, Emirates ID and clearance services.', url: '/typing/services/ministry-of-interior' },
  { title: 'Certificate Attestation', desc: 'Document attestation and legalisation services.', url: '/typing/services/certificate-attestation' },
  { title: 'VAT Registration', desc: 'VAT registration and tax compliance assistance.', url: '/typing/services/vat-registration' },
  { title: 'CT Registration', desc: 'Corporate tax (CT) registration and support.', url: '/typing/services/ct-registration' },
  { title: 'Passport Renewal', desc: 'Passport renewal guidance and document handling.', url: '/typing/services/passport-renewal' },
  { title: 'Immigration Card', desc: 'Immigration card and Emirates ID assistance and replacement.', url: '/typing/services/immigration-card' },
]

</script>
