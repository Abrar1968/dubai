<template>
    <div class="bg-white">
        <!-- HERO / BREADCRUMB BANNER -->
        <section class="relative h-[280px] sm:h-[320px] w-full overflow-hidden">
            <!-- background image -->
            <img :src="headerBg" alt="Packages header" loading="lazy" class="absolute inset-0 h-full w-full object-cover" />
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
                        Explore our exclusive Umrah packages available throughout the year.
                    </p>
                </div>
            </div>
        </section>

        <!-- PACKAGES GRID -->
        <section class="py-14 sm:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <article v-for="pkg in displayPackages" :key="pkg.id"
                        class="group rounded-2xl border border-slate-200 bg-white shadow-[0_14px_40px_rgba(0,0,0,0.08)]
                   overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_60px_rgba(0,0,0,0.12)]">
                        <!-- image -->
                        <div class="relative h-[140px] overflow-hidden">
                            <LazyImage
                                :src="pkg.image || '/assets/img/hajj/umrahh.jpg'"
                                :alt="pkg.title"
                                fallback="/assets/img/hajj/umrahh.jpg"
                                img-class="transition-transform duration-700 ease-out group-hover:scale-[1.06]"
                            />

                            <!-- price tag -->
                            <div class="absolute top-3 right-3 rounded-full bg-[#f2e8dc] px-3 py-1 text-xs font-extrabold text-slate-800
                       shadow-[0_10px_20px_rgba(0,0,0,0.18)] z-10">
                                <template v-if="pkg.discounted_price && pkg.discounted_price < pkg.price">
                                    <span class="line-through text-slate-400 mr-1">{{ formatPrice(pkg.price) }}</span>
                                    <span class="text-green-600">{{ formatPrice(pkg.discounted_price) }}</span>
                                </template>
                                <template v-else>
                                    {{ formatPrice(pkg.price) }}
                                </template>
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

<script setup lang="ts">
import HajjUmrahLayout from '@/layouts/HajjUmrahLayout.vue'
import LazyImage from '@/components/ui/LazyImage.vue'
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
}>(), {
    packages: () => [],
    settings: () => ({}),
});

const headerBg = '/assets/img/hajj/umrahh.jpg';

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
    target.src = '/assets/img/hajj/umrahh.jpg';
};
</script>
