<template>
    <div class="bg-white">
        <!-- HERO / BREADCRUMB BANNER -->
        <section class="relative h-[280px] sm:h-[320px] w-full overflow-hidden">
            <!-- background image -->
            <img :src="headerBg" alt="Tour Packages header" class="absolute inset-0 h-full w-full object-cover" />
            <!-- overlay -->
            <div class="absolute inset-0 bg-black/55"></div>

            <!-- content -->
            <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-full">
                <div class="h-full flex flex-col items-center justify-center text-center">
                    <p class="text-white/80 text-xs sm:text-sm tracking-wide">
                        EXPLORE THE WORLD
                    </p>

                    <h1 class="mt-2 text-white text-4xl sm:text-6xl font-extrabold tracking-tight">
                        Tour Packages
                    </h1>

                    <p class="mt-4 max-w-xl text-white/80 text-sm sm:text-base">
                        Discover amazing destinations with our exclusive tour packages.
                    </p>
                </div>
            </div>
        </section>

        <!-- PACKAGES GRID -->
        <section class="py-14 sm:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Empty State -->
                <div v-if="displayPackages.length === 0" class="text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">No Tour Packages Available</h3>
                    <p class="text-slate-600 max-w-md mx-auto">
                        We're currently preparing exciting tour packages for you. Please check back soon or contact us for custom tour arrangements.
                    </p>
                    <a href="/contactus" class="mt-6 inline-flex items-center justify-center rounded-full bg-[#D3A762] hover:bg-[#c29652] px-6 py-3 text-sm font-bold text-white transition">
                        Contact Us
                    </a>
                </div>

                <!-- Packages Grid -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <article v-for="pkg in displayPackages" :key="pkg.id"
                        class="group rounded-2xl border border-slate-200 bg-white shadow-[0_14px_40px_rgba(0,0,0,0.08)]
                   overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_60px_rgba(0,0,0,0.12)]">
                        <!-- image -->
                        <div class="relative h-[140px] overflow-hidden">
                            <img :src="pkg.image || '/assets/img/tour/tour-bg.jpg'" :alt="pkg.title"
                                @error="handleImageError"
                                class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.06]" />

                            <!-- price tag -->
                            <div class="absolute top-3 right-3 rounded-full bg-[#f2e8dc] px-3 py-1 text-xs font-extrabold text-slate-800
                       shadow-[0_10px_20px_rgba(0,0,0,0.18)]">
                                <template v-if="pkg.discounted_price && pkg.discounted_price < pkg.price">
                                    <span class="line-through text-slate-400 mr-1">{{ formatPrice(pkg.price) }}</span>
                                    <span class="text-green-600">{{ formatPrice(pkg.discounted_price) }}</span>
                                </template>
                                <template v-else>
                                    {{ formatPrice(pkg.price) }}
                                </template>
                            </div>

                            <!-- Tour Badge -->
                            <div class="absolute top-3 left-3 rounded-full bg-blue-500 px-2 py-0.5 text-[10px] font-bold text-white uppercase tracking-wide">
                                Tour
                            </div>
                        </div>

                        <!-- body -->
                        <div class="p-5">
                            <h3 class="text-slate-900 font-extrabold text-base leading-snug">
                                {{ pkg.title }}
                            </h3>

                            <!-- Duration -->
                            <p class="mt-2 text-sm text-slate-500">
                                {{ pkg.duration_days }} Days {{ pkg.duration_nights ? `/ ${pkg.duration_nights} Nights` : '' }}
                            </p>

                            <!-- features -->
                            <ul class="mt-4 space-y-2 text-sm text-slate-600">
                                <li v-for="(f, i) in pkg.features?.slice(0, 3)" :key="i" class="flex items-start gap-2">
                                    <span class="mt-[3px] text-blue-400">✓</span>
                                    <span>{{ f }}</span>
                                </li>
                            </ul>

                            <!-- CTA -->
                            <div class="mt-5">
                                <button class="w-full rounded-full border border-[#d8c2a6] bg-white px-4 py-3 text-xs font-extrabold
                         tracking-wide text-slate-800 transition-all duration-300
                         hover:bg-blue-500 hover:text-white hover:border-blue-500
                         active:scale-[0.98]" @click="onLearnMore(pkg)">
                                    VIEW DETAILS
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

<script setup lang="ts">
import HajjUmrahLayout from '@/layouts/HajjUmrahLayout.vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: HajjUmrahLayout })

// TypeScript interfaces
interface Package {
    id: number;
    title: string;
    slug: string;
    price: number;
    discounted_price?: number | null;
    currency: string;
    duration_days: number;
    duration_nights?: number;
    image: string;
    features: string[];
    type: string;
    departure_date?: string;
}

interface Settings {
    page_title?: string;
    page_subtitle?: string;
    page_description?: string;
}

const props = withDefaults(defineProps<{
    packages?: Package[];
    settings?: Settings;
    headerBg?: string;
}>(), {
    packages: () => [],
    settings: () => ({}),
    headerBg: '/assets/img/tour/tour-bg.jpg',
});

// Use props directly - data comes from backend
const displayPackages = props.packages;

const onLearnMore = (pkg: Package) => {
    router.visit(`/packages/${pkg.slug}`);
};

const formatPrice = (price: number) => {
    return `AED ${price.toLocaleString()}`;
};

const handleImageError = (event: Event) => {
    const target = event.target as HTMLImageElement;
    target.src = '/assets/img/tour/tour-bg.jpg';
};
</script>
