<template>
    <div class="bg-white">
        <!-- HERO / BREADCRUMB BANNER -->
        <section class="relative h-[280px] sm:h-[320px] w-full overflow-hidden">
            <!-- background image -->
            <img :src="headerBg" alt="Packages header" class="absolute inset-0 h-full w-full object-cover" />
            <!-- overlay -->
            <div class="absolute inset-0 bg-black/55"></div>

            <!-- content -->
            <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-full">
                <div class="h-full flex flex-col items-center justify-center text-center">
                    <p class="text-white/80 text-xs sm:text-sm tracking-wide">
                        YOUR DREAM TRIP
                    </p>

                    <h1 class="mt-2 text-white text-4xl sm:text-6xl font-extrabold tracking-tight">
                        Umrah Packages
                    </h1>

                    <p class="mt-4 max-w-xl text-white/80 text-sm sm:text-base">
                        Lorem ipsum dolor sit amet, consectetur dolore magna adipiscing elit.
                    </p>
                </div>
            </div>
        </section>

        <!-- PACKAGES GRID -->
        <section class="py-14 sm:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <article v-for="pkg in packages" :key="pkg.id"
                        class="group rounded-2xl border border-slate-200 bg-white shadow-[0_14px_40px_rgba(0,0,0,0.08)]
                   overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_60px_rgba(0,0,0,0.12)]">
                        <!-- image -->
                        <div class="relative h-[140px] overflow-hidden">
                            <img :src="pkg.image" :alt="pkg.title"
                                class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.06]" />

                            <!-- price tag -->
                            <div class="absolute top-3 right-3 rounded-full bg-[#f2e8dc] px-3 py-1 text-xs font-extrabold text-slate-800
                       shadow-[0_10px_20px_rgba(0,0,0,0.18)]">
                                ${{ pkg.price }}
                            </div>
                        </div>

                        <!-- body -->
                        <div class="p-5">
                            <h3 class="text-slate-900 font-extrabold text-base leading-snug">
                                {{ pkg.title }}
                            </h3>

                            <!-- features -->
                            <ul class="mt-4 space-y-2 text-sm text-slate-600">
                                <li v-for="(f, i) in pkg.features" :key="i" class="flex items-start gap-2">
                                    <span class="mt-[3px] text-slate-400">▢</span>
                                    <span>{{ f }}</span>
                                </li>
                            </ul>

                            <!-- CTA -->
                            <div class="mt-5">
                                <button class="w-full rounded-full border border-[#d8c2a6] bg-white px-4 py-3 text-xs font-extrabold
                         tracking-wide text-slate-800 transition-all duration-300
                         hover:bg-[#c89c6a] hover:text-white hover:border-[#c89c6a]
                         active:scale-[0.98]" @click="onLearnMore(pkg)">
                                    LEARN MORE
                                    <span
                                        class="ml-2 inline-block transition-transform duration-300 group-hover:translate-x-1">›</span>
                                </button>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import HajjUmrahLayout from '@/layouts/HajjUmrahLayout.vue'

defineOptions({ layout: HajjUmrahLayout })

/**
 * Put this image in: public/images/packages/header.jpg
 * Or change the path below.
 */
const headerBg = '/images/packages/header.jpg'

/**
 * Dynamic packages (replace later with API/DB)
 * Put images in: public/images/packages/
 */
const packages = [
    {
        id: 1,
        title: 'Premium Hajj',
        price: 590,
        image: '/images/packages/p1.jpg',
        features: ['Dec 10 - Dec 15', 'Document Guide', '5 Stars Hotel', 'Local Meals', 'Visa Included'],
    },
    {
        id: 2,
        title: 'Ramadan Umrah',
        price: 890,
        image: '/images/packages/p2.jpg',
        features: ['Jan 10 - Jan 15', 'Document Guide', 'Zam-Zam Hotel', 'Arabian Foods', 'Visa Included'],
    },
    {
        id: 3,
        title: 'Family Umrah',
        price: 199,
        image: '/images/packages/p3.jpg',
        features: ['Mar 10 - Mar 15', 'Document Guide', 'Abu Bakr Tower', 'Oriental Resto', 'Visa Included'],
    },
    {
        id: 4,
        title: 'Medina City Tour',
        price: 249,
        image: '/images/packages/p4.jpg',
        features: ['Oct 10 - Oct 15', 'Document Guide', 'Uthman Hotel', 'Asian Foods', 'Visa Included'],
    },
    {
        id: 5,
        title: 'Nabawi Tour',
        price: 590,
        image: '/images/packages/p5.jpg',
        features: ['Dec 10 - Dec 15', 'Document Guide', '5 Stars Hotel', 'Local Meals', 'Visa Included'],
    },
    {
        id: 6,
        title: 'Umrah Group',
        price: 890,
        image: '/images/packages/p6.jpg',
        features: ['Jan 10 - Jan 15', 'Document Guide', 'Zam-Zam Hotel', 'Arabian Foods', 'Visa Included'],
    },
    {
        id: 7,
        title: 'Saudi Tour',
        price: 199,
        image: '/images/packages/p7.jpg',
        features: ['Mar 10 - Mar 15', 'Document Guide', 'Abu Bakr Tower', 'Oriental Resto', 'Visa Included'],
    },
    {
        id: 8,
        title: 'Rawdha Package',
        price: 249,
        image: '/images/packages/p8.jpg',
        features: ['Oct 10 - Oct 15', 'Document Guide', 'Uthman Hotel', 'Asian Foods', 'Visa Included'],
    },
]

const onLearnMore = (pkg) => {
    // Later you can use Inertia route:
    // router.visit(route('packages.show', pkg.id))
    console.log('Learn more:', pkg)
}
</script>
