<template>
    <TypingLayout>
        <div class="bg-white">
            <!-- ✅ HERO (Banner only, no text) -->
            <section class="relative h-[260px] sm:h-[360px] lg:h-[460px] w-full overflow-hidden">
                <img :src="heroImage" alt="Hero Banner" class="absolute inset-0 h-full w-full object-cover" />
                <!-- optional overlay for readability (keep it light) -->
                <div class="absolute inset-0 bg-black/25"></div>
            </section>

            <!-- ✅ OFFICE LOCATIONS (Below Hero) -->
            <section v-if="offices && offices.length > 0" class="bg-teal-900 py-8">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="office in offices" :key="office.id" class="flex items-start gap-4 text-white">
                            <div class="flex-shrink-0 w-10 h-10 bg-teal-700 rounded-lg flex items-center justify-center">
                                <MapPin class="w-5 h-5" />
                            </div>
                            <div>
                                <h4 class="font-semibold text-white">{{ office.name }}</h4>
                                <p class="text-teal-100 text-sm mt-1" v-html="office.address.replace(/\n/g, '<br>')"></p>
                                <div class="mt-2 flex flex-col gap-1 text-sm">
                                    <a v-if="office.phone" :href="`tel:${office.phone}`" class="text-teal-200 hover:text-white transition flex items-center gap-2">
                                        <Phone class="w-3 h-3" />
                                        {{ office.phone }}
                                    </a>
                                    <a v-if="office.email" :href="`mailto:${office.email}`" class="text-teal-200 hover:text-white transition flex items-center gap-2">
                                        <Mail class="w-3 h-3" />
                                        {{ office.email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ✅ SERVICES GRID -->
            <section class="py-10 bg-white" id="services">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <h3 class="text-2xl font-semibold text-slate-900 mb-2">Our Typing Services</h3>
                    <p class="text-slate-600 mb-6 text-sm">Quick access to common services — click a card to learn more.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <article v-for="service in displayServices" :key="service.id" class="rounded-lg border border-slate-200 p-5 bg-slate-50 hover:shadow-lg transition-shadow relative">
                            <!-- Featured Star Badge -->
                            <div v-if="service.is_featured" class="absolute top-3 right-3 bg-amber-500 rounded-full p-1.5 shadow-lg">
                                <Star class="w-4 h-4 text-white fill-white" />
                            </div>
                            
                            <div v-if="service.image_url || service.image" class="mb-3 h-32 overflow-hidden rounded-md">
                                <img :src="service.image_url || getImageUrl(service.image)" :alt="service.title" class="w-full h-full object-cover" @error="handleImageError" />
                            </div>
                            <div v-else-if="service.icon" class="mb-3 text-3xl">
                                <span>{{ service.icon }}</span>
                            </div>
                            <h4 class="font-semibold text-slate-900 mb-2">{{ service.title }}</h4>
                            <p class="text-sm text-slate-700 mb-4 line-clamp-2">{{ service.short_description }}</p>
                            <a :href="service.url || `/typing/services/${service.slug}`" class="inline-flex items-center rounded-md bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm">
                                {{ service.cta_text || 'Learn more' }}
                            </a>
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
                            {{ companyTagline || 'Welcome to SS Group Travels & Typing' }}
                            <span class="h-[1px] w-10 bg-emerald-700/60"></span>
                        </div>

                        <h2 class="mt-4 text-3xl sm:text-5xl font-serif font-semibold text-slate-900">
                            Centre for all your documentation services
                        </h2>

                        <p v-if="companyDescription" class="mt-5 max-w-3xl mx-auto text-slate-600 text-sm sm:text-base leading-relaxed">
                            {{ companyDescription }}
                        </p>
                        <p v-else class="mt-5 max-w-3xl mx-auto text-slate-600 text-sm sm:text-base leading-relaxed">
                            We believe in team work, discipline, innovation, customer satisfaction and most importantly
                            in respecting everyone. We are fortunate to have a disciplined team that work towards providing the best for our
                            customers. Our team work together to provide fruitful results to our customers and make them continue
                            their business relationship with us.
                        </p>
                    </div>

                    <!-- ✅ Mission / Vision / Values -->
                    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Mission -->
                        <article class="rounded-2xl bg-slate-50 border border-slate-200 p-7 shadow-[0_18px_50px_rgba(0,0,0,0.06)]
                   transition hover:-translate-y-1 hover:shadow-[0_24px_70px_rgba(0,0,0,0.10)]">
                            <h3 class="text-xl font-bold text-emerald-700">Our Mission</h3>
                            <p class="mt-4 text-slate-600 text-sm leading-relaxed">
                                {{ companyMission || 'We simplify government documentation with exceptional service, ensuring a seamless process for UAE residents. Our commitment to excellence helps clients save time and move forward with confidence.' }}
                            </p>
                        </article>

                        <!-- Vision -->
                        <article class="rounded-2xl bg-slate-50 border border-slate-200 p-7 shadow-[0_18px_50px_rgba(0,0,0,0.06)]
                   transition hover:-translate-y-1 hover:shadow-[0_24px_70px_rgba(0,0,0,0.10)]">
                            <h3 class="text-xl font-bold text-amber-500">Our Vision</h3>
                            <p class="mt-4 text-slate-600 text-sm leading-relaxed">
                                {{ companyVision || 'Our vision is to exceed expectations with outstanding service and minimal client effort. With an efficient team, we foster lasting relationships built on trust and results.' }}
                            </p>
                        </article>

                        <!-- Values -->
                        <article class="rounded-2xl bg-slate-50 border border-slate-200 p-7 shadow-[0_18px_50px_rgba(0,0,0,0.06)]
                   transition hover:-translate-y-1 hover:shadow-[0_24px_70px_rgba(0,0,0,0.10)]">
                            <h3 class="text-xl font-bold text-emerald-700">Our Values</h3>
                            <p class="mt-4 text-slate-600 text-sm leading-relaxed">
                                {{ companyValues || 'Teamwork, discipline, and customer satisfaction drive us to deliver exceptional results. We go the extra mile to build enduring partnerships and ensure client success.' }}
                            </p>
                        </article>
                    </div>
                </div>
            </section>
        </div>
    </TypingLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import TypingLayout from '@/layouts/TypingLayout.vue';
import { MapPin, Phone, Mail, Star } from 'lucide-vue-next';

// Define interfaces for proper typing
interface TypingService {
    id: number;
    title: string;
    slug: string;
    short_description: string;
    long_description?: string;
    icon?: string;
    image?: string;
    cta_text?: string;
    cta_link?: string;
    url?: string;
    is_active: boolean;
    is_featured: boolean;
}

interface OfficeLocation {
    id: number;
    name: string;
    address: string;
    phone?: string;
    email?: string;
    section: string;
}

interface Settings {
    company?: {
        company_name?: string;
        company_tagline?: string;
        company_description?: string;
        company_mission?: string;
        company_vision?: string;
        company_values?: string;
        hero_image?: string;
        banner_image?: string;
    };
    seo?: Record<string, string>;
    social?: Record<string, string>;
    contact?: Record<string, string>;
}

// Props from backend controller
const props = withDefaults(defineProps<{
    services?: TypingService[];
    featuredServices?: TypingService[];
    settings?: Settings;
    offices?: OfficeLocation[];
}>(), {
    services: () => [],
    featuredServices: () => [],
    settings: () => ({}),
    offices: () => [],
});

// Computed properties for settings with defaults
const companySettings = computed(() => props.settings?.company || {});

const heroImage = computed(() => {
    const image = companySettings.value.hero_image || companySettings.value.banner_image;
    if (image) {
        return `/storage/${image}`;
    }
    return '/assets/img/typing/default-hero.jpg';
});

const companyTagline = computed(() => companySettings.value.company_tagline || '');
const companyDescription = computed(() => companySettings.value.company_description || '');
const companyMission = computed(() => companySettings.value.company_mission || '');
const companyVision = computed(() => companySettings.value.company_vision || '');
const companyValues = computed(() => companySettings.value.company_values || '');

// Use services from props, fallback to featured if available
const displayServices = computed(() => {
    if (props.services && props.services.length > 0) {
        return props.services;
    }
    if (props.featuredServices && props.featuredServices.length > 0) {
        return props.featuredServices;
    }
    return [];
});

// Helper to get image URL
const getImageUrl = (image: string | null | undefined): string => {
    if (!image) return '/assets/img/typing/default-service.jpg';
    if (image.startsWith('http')) return image;
    return `/storage/${image}`;
};

// Handle image errors
const handleImageError = (event: Event) => {
    const target = event.target as HTMLImageElement;
    target.src = '/assets/img/typing/default-service.jpg';
};
</script>
