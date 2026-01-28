<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import HajjHeader from '@/components/hajj/hajjheader.vue';
import HajjFooter from '@/components/hajj/hajjfooter.vue';
import {
    MapPin, Calendar, Users, Star, ArrowRight, ShieldCheck,
    Clock, Heart, Menu, Phone, Mail, CheckCircle, ArrowUpRight, MessageCircle, ChevronDown, ChevronUp
} from 'lucide-vue-next';

// Props from backend
interface Package {
    id: number;
    title: string;
    slug: string;
    price: number;
    currency: string;
    duration_days: number;
    image: string;
    features: string[];
    type: string;
}

interface Article {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    category: string;
    image: string;
}

interface Testimonial {
    id: number;
    name: string;
    location: string;
    content: string;
    rating: number;
    avatar: string | null;
}

interface Faq {
    id: number;
    question: string;
    answer: string;
}

interface OfficeLocation {
    id: number;
    name: string;
    address: string;
    phone?: string;
    email?: string;
    section: string;
}

const props = withDefaults(defineProps<{
    packages?: Package[];
    articles?: Article[];
    testimonials?: Testimonial[];
    faqs?: Faq[];
    settings?: Record<string, string>;
    offices?: OfficeLocation[];
}>(), {
    packages: () => [],
    articles: () => [],
    testimonials: () => [],
    faqs: () => [],
    settings: () => ({}),
    offices: () => [],
});

// Use props directly - data comes from backend
const displayPackages = props.packages;
const displayArticles = props.articles;
const displayTestimonial = props.testimonials.length > 0 ? props.testimonials[0] : null;
const displayFaqs = ref(props.faqs.map((f, i) => ({ ...f, open: i === 0 })));

const toggleFaq = (i: number) => {
    displayFaqs.value[i].open = !displayFaqs.value[i].open;
};

const features = [
    { title: 'Tawaf', desc: 'Perform Tawaf with ease and guidance.', icon: Clock },
    { title: 'Ihram', desc: 'Learn the proper state of Ihram.', icon: Users },
    { title: 'Mina', desc: 'Stay in Mina with comfort and peace.', icon: MapPin },
    { title: 'Jamarat', desc: 'Safe stoning rituals guidance.', icon: ShieldCheck },
    { title: 'Zam-Zam', desc: 'Unlimited Zam-Zam water supply.', icon: Heart },
    { title: 'Prayer Mat', desc: 'Premium prayer mats provided.', icon: Star },
];

// Format price display
const formatPrice = (price: number, currency: string) => {
    return `Start From $${price.toLocaleString()}`;
};

// Smooth scroll to packages
const scrollToPackages = () => {
    const el = document.getElementById('packages')
    if (!el) return

    const y = el.getBoundingClientRect().top + window.pageYOffset - 80 // header offset
    window.scrollTo({
        top: y,
        behavior: 'smooth',
    })
}

const handleImageError = (event: Event) => {
    const target = event.target as HTMLImageElement;
    target.src = '/assets/img/hajj/hajjbg.jpg';
}

// Get hero background image
const heroImage = props.settings.hero_image ? `/storage/${props.settings.hero_image}` : '/assets/img/hajj/hajjbg.jpg';

// Get SEO meta tags
const pageTitle = props.settings.meta_title || 'Dubai Hajj & Umrah Services | Premium Pilgrimage Packages';
const pageDescription = props.settings.meta_description || 'Book premium Hajj and Umrah packages from Dubai. 5-star hotels, expert guides, all-inclusive services.';

// WhatsApp link
const whatsappUrl = props.settings.company_whatsapp ? `https://wa.me/${props.settings.company_whatsapp.replace(/[^0-9]/g, '')}` : null;

</script>

<template>
    <div class="font-sans text-slate-800">
        <!-- SEO Meta Tags -->
        <Head>
            <title>{{ pageTitle }}</title>
            <meta name="description" :content="pageDescription" />
            <meta property="og:title" :content="pageTitle" />
            <meta property="og:description" :content="pageDescription" />
            <meta property="og:type" content="website" />
        </Head>

        <HajjHeader :settings="settings" :auth="$page.props.auth" />

        <main>
            <!-- Hero Section -->
            <section class="relative h-[85vh] w-full bg-slate-900 group overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0">
                    <img :src="heroImage" alt="Makkah" class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform
                    duration-1000" @error="handleImageError" />
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent">
                    </div>
                </div>

                <!-- Content -->
                <div class="relative z-10 h-full flex flex-col justify-center items-center text-center px-4">
                    <span
                        class="text-[#D3A762] text-lg font-medium tracking-widest uppercase mb-4 animate-in fade-in slide-in-from-bottom-4 duration-700">Call
                        of Allah</span>
                    <h1
                        class="text-4xl md:text-6xl lg:text-7xl font-serif text-white mb-8 leading-tight max-w-4xl animate-in fade-in slide-in-from-bottom-8 duration-700 delay-100">
                        {{ settings.company_tagline || 'Hajj & Umrah with Ease, Faith with Peace' }}
                    </h1>
                    <button @click="scrollToPackages"
                        class="bg-[#D3A762] hover:bg-[#c29652] text-white px-8 py-4 rounded-md font-semibold tracking-wide transition-all transform hover:scale-105 animate-in fade-in slide-in-from-bottom-8 duration-700 delay-200">
                        DISCOVER MORE
                    </button>
                </div>

                <!-- Bottom Feature Strip -->
                <div
                    class="absolute bottom-10 left-0 right-0 max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-white/90 border-t border-white/20 pt-8">
                    <div class="flex items-start gap-4">
                        <ShieldCheck class="w-8 h-8 text-[#D3A762]" />
                        <div>
                            <h4 class="font-bold text-lg">Safety First</h4>
                            <p class="text-sm text-gray-300">Comprehensive insurance and medical support.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <Star class="w-8 h-8 text-[#D3A762]" />
                        <div>
                            <h4 class="font-bold text-lg">Premium Service</h4>
                            <p class="text-sm text-gray-300">Luxury accommodation and VIP transport.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <Clock class="w-8 h-8 text-[#D3A762]" />
                        <div>
                            <h4 class="font-bold text-lg">24/7 Support</h4>
                            <p class="text-sm text-gray-300">Always here to guide you through your journey.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Office Locations (Below Hero) -->
            <section v-if="offices && offices.length > 0" class="bg-slate-900 py-8">
                <div class="mx-auto max-w-7xl px-4 md:px-16">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="office in offices" :key="office.id" class="flex items-start gap-4 text-white">
                            <div class="flex-shrink-0 w-10 h-10 bg-[#D3A762]/20 rounded-lg flex items-center justify-center">
                                <MapPin class="w-5 h-5 text-[#D3A762]" />
                            </div>
                            <div>
                                <h4 class="font-semibold text-white">{{ office.name }}</h4>
                                <p class="text-gray-400 text-sm mt-1" v-html="office.address.replace(/\n/g, '<br>')"></p>
                                <div class="mt-2 flex flex-col gap-1 text-sm">
                                    <a v-if="office.phone" :href="`tel:${office.phone}`" class="text-gray-400 hover:text-[#D3A762] transition flex items-center gap-2">
                                        <Phone class="w-3 h-3" />
                                        {{ office.phone }}
                                    </a>
                                    <a v-if="office.email" :href="`mailto:${office.email}`" class="text-gray-400 hover:text-[#D3A762] transition flex items-center gap-2">
                                        <Mail class="w-3 h-3" />
                                        {{ office.email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Choose Your Package -->
            <section id="packages" class="py-20 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 md:px-16">
                    <div class="text-center mb-16">
                        <span class="text-[#D3A762] uppercase tracking-wider text-sm font-semibold">Packages</span>
                        <h2 class="text-4xl font-serif mt-2 text-slate-900">Choose Your Package</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div v-for="(pkg, index) in displayPackages" :key="pkg.id"
                            class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow group">
                            <div class="relative h-48 overflow-hidden">
                                <img :src="pkg.image" :alt="pkg.title"
                                    @error="handleImageError"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div
                                    class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-slate-900">
                                    {{ pkg.duration_days }} Days
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ pkg.title }}</h3>
                                <p class="text-[#D3A762] font-semibold text-lg mb-4">{{ formatPrice(pkg.price, pkg.currency) }}</p>
                                <ul class="space-y-2 mb-6">
                                    <li v-for="feat in pkg.features" :key="feat"
                                        class="flex items-center gap-2 text-slate-600 text-sm">
                                        <CheckCircle class="w-4 h-4 text-green-500" /> {{ feat }}
                                    </li>
                                </ul>
                                <a :href="`/packages/${pkg.slug}`"
                                    class="w-full block text-center border border-slate-200 py-3 rounded-lg text-slate-700 font-medium hover:bg-slate-900 hover:text-white transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Your Spiritual Voyage -->
            <section id="services" class="py-20 bg-white">
                <div class="max-w-7xl mx-auto px-4 md:px-16">
                    <div class="mb-12 flex items-end justify-between">
                        <div>
                            <span class="text-[#D3A762] uppercase tracking-wider text-sm font-semibold">Services</span>
                            <h2 class="text-4xl font-serif mt-2 text-slate-900">Your Spiritual Voyage</h2>
                        </div>
                        <!-- <a href="#" class="text-[#D3A762] font-medium hidden md:flex items-center gap-2 hover:gap-4 transition-all">
                            View All <ArrowRight class="w-4 h-4" />
                        </a> -->
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 h-96">
                        <!-- Card 1 -->
                        <a href="/umrahpackage" class="block h-full">
                            <div class="relative rounded-2xl overflow-hidden group h-full">
                                <img src="/assets/img/hajj/umrahh.jpg"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    alt="Umrah" />

                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent">
                                </div>

                                <div class="absolute bottom-8 left-8 text-white">
                                    <h3 class="text-2xl font-serif mb-2">Umrah</h3>

                                    <p
                                        class="text-sm text-gray-300 mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        Spiritual journey available throughout the year.
                                    </p>

                                    <span class="bg-white/20 backdrop-blur p-2 rounded-full inline-block
               group-hover:bg-[#D3A762] transition-colors">
                                        <ArrowUpRight class="w-5 h-5" />
                                    </span>
                                </div>
                            </div>
                        </a>

                        <!-- Card 2 -->
                        <a href="/hajjpackage" class="block h-full">
                            <div class="relative rounded-2xl overflow-hidden group h-full md:-mt-8">
                                <img src="/assets/img/hajj/hajjj.jpg"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    alt="Hajj" />

                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent">
                                </div>

                                <div class="absolute bottom-8 left-8 text-white">
                                    <h3 class="text-2xl font-serif mb-2">Hajj</h3>

                                    <p
                                        class="text-sm text-gray-300 mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        The obligatory pilgrimage for every Muslim.
                                    </p>

                                    <span class="bg-white/20 backdrop-blur p-2 rounded-full inline-block
               group-hover:bg-[#D3A762] transition-colors">
                                        <ArrowUpRight class="w-5 h-5" />
                                    </span>
                                </div>
                            </div>
                        </a>

                        <!-- Card 3 -->
                        <div class="relative rounded-2xl overflow-hidden group h-full">
                            <img src="/assets/img/hajj/islamictour.png"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                alt="Tour" />

                            <!-- dark gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent">
                            </div>

                            <!-- ✅ Coming Soon overlay (center) -->
                            <div class="absolute inset-0 z-10 flex items-center justify-center">
                                <span class="rounded-full bg-black/60 backdrop-blur-md
             px-6 py-2 text-sm font-semibold tracking-widest uppercase
             text-[#D3A762] border border-white/20
             shadow-[0_10px_30px_rgba(0,0,0,0.35)]">
                                    Coming Soon
                                </span>
                            </div>

                            <!-- bottom content -->
                            <div class="absolute bottom-8 left-8 text-white z-20">
                                <h3 class="text-2xl font-serif mb-2">Islamic Tour</h3>

                                <p
                                    class="text-sm text-gray-300 mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    Explore the history of Islamic civilization.
                                </p>

                                <span class="bg-white/20 backdrop-blur p-2 rounded-full inline-block
             group-hover:bg-[#D3A762] transition-colors">
                                    <ArrowUpRight class="w-5 h-5" />
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Elevate Your Faith -->
            <section id="features" class="py-20 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 md:px-16 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="relative h-[600px] rounded-2xl overflow-hidden shadow-2xl">
                        <img src="/assets/img/hajj/whyus.jpg" class="w-full h-full object-cover"
                            alt="Makkah Clock Tower" />
                    </div>

                    <div>
                        <span class="text-[#D3A762] uppercase tracking-wider text-sm font-semibold">Features</span>
                        <h2 class="text-4xl font-serif mt-2 mb-8 text-slate-900 leading-tight">Elevate Your Faith with
                            Our Guidance</h2>
                        <p class="text-slate-600 mb-12 leading-relaxed">
                            We provide comprehensive guidance and support throughout your spiritual journey, ensuring
                            you can focus entirely on your worship and connection with the Divine.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-12">
                            <div v-for="(feat, idx) in features" :key="idx" class="flex gap-4">
                                <div class="shrink-0">
                                    <component :is="feat.icon" class="w-10 h-10 text-slate-400" stroke-width="1.5" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-1">{{ feat.title }}</h4>
                                    <p class="text-sm text-slate-500 leading-relaxed">{{ feat.desc }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonial Section -->
            <section id="testimonials" v-if="displayTestimonial" class="relative py-24 bg-slate-900 overflow-hidden">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1627441584288-51b660c6d9c6?q=80&w=2070&auto=format&fit=crop"
                        class="w-full h-full object-cover opacity-20" alt="Background" />
                </div>
                <div class="relative z-10 max-w-7xl mx-auto px-4 md:px-16 flex items-center">
                    <div class="bg-white p-10 rounded-xl shadow-xl max-w-lg">
                        <div class="flex gap-1 mb-6">
                            <Star v-for="i in displayTestimonial.rating" :key="i" class="w-5 h-5 text-yellow-500 fill-yellow-500" />
                        </div>
                        <p class="text-slate-700 text-lg italic leading-relaxed mb-8">
                            "{{ displayTestimonial.content }}"
                        </p>
                        <div class="flex items-center gap-4">
                            <img :src="displayTestimonial.avatar || '/assets/img/hajj/hajjbg.jpg'"
                                class="w-12 h-12 rounded-full object-cover" alt="User" />
                            <div>
                                <h5 class="font-bold text-slate-900">{{ displayTestimonial.name }}</h5>
                                <span class="text-sm text-slate-500">{{ displayTestimonial.location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Easy Process -->
            <section class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 md:px-16 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <span class="text-[#D3A762] uppercase tracking-wider text-sm font-semibold">Workflow</span>
                        <h2 class="text-4xl font-serif mt-2 mb-12 text-slate-900">Easy Process for Your Journey</h2>

                        <div class="space-y-12">
                            <div class="flex gap-6 group">
                                <span
                                    class="text-6xl font-serif text-slate-200 font-bold group-hover:text-[#D3A762] transition-colors">01</span>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900 mb-2">Choose Package</h4>
                                    <p class="text-slate-600">Select the package that best suits your schedule and
                                        budget preferences.</p>
                                </div>
                            </div>
                            <div class="flex gap-6 group">
                                <span
                                    class="text-6xl font-serif text-slate-200 font-bold group-hover:text-[#D3A762] transition-colors">02</span>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900 mb-2">Document Submission</h4>
                                    <p class="text-slate-600">Submit your passport and necessary documents for swift
                                        visa processing.</p>
                                </div>
                            </div>
                            <div class="flex gap-6 group">
                                <span
                                    class="text-6xl font-serif text-slate-200 font-bold group-hover:text-[#D3A762] transition-colors">03</span>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900 mb-2">Enjoy Your Travel</h4>
                                    <p class="text-slate-600">Embark on your spiritual journey with complete peace of
                                        mind.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <img src="/assets/img/hajj/kabah.jpg" class="rounded-2xl shadow-2xl w-full" alt="Kaaba" />
                    </div>
                </div>
            </section>

            <!-- Haramain News -->
            <section class="py-20 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 md:px-16">
                    <div class="text-center mb-16 relative">
                        <span class="text-[#D3A762] uppercase tracking-wider text-sm font-semibold">Articles</span>
                        <h2 class="text-4xl font-serif mt-2 text-slate-900">Haramain News</h2>

                        <a href="/articles"
                            class="absolute right-0 bottom-0 text-[#D3A762] font-medium hidden md:flex items-center gap-2 hover:gap-4 transition-all">
                            View All
                            <ArrowRight class="w-4 h-4" />
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div v-for="article in displayArticles" :key="article.id"
                            class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow group">
                            <div class="h-64 overflow-hidden">
                                <img :src="article.image || 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?q=80&w=2070'"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    :alt="article.title">
                            </div>
                            <div class="p-8">
                                <span class="text-xs font-bold text-[#D3A762] uppercase mb-2 block">{{ article.category || 'Travel Guide' }}</span>
                                <h3
                                    class="text-xl font-bold text-slate-900 mb-4 group-hover:text-[#D3A762] transition-colors cursor-pointer">
                                    {{ article.title }}</h3>
                                <a :href="`/articles/${article.slug}`"
                                    class="text-sm font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-2">Read
                                    More
                                    <ArrowRight class="w-4 h-4" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section v-if="displayFaqs.length > 0" class="py-20 bg-white">
                <div class="max-w-4xl mx-auto px-4 md:px-16">
                    <div class="text-center mb-12">
                        <span class="text-[#D3A762] uppercase tracking-wider text-sm font-semibold">FAQs</span>
                        <h2 class="text-4xl font-serif mt-2 text-slate-900">Frequently Asked Questions</h2>
                        <p class="mt-4 text-slate-600">Find answers to common questions about our Hajj & Umrah packages</p>
                    </div>

                    <div class="space-y-4">
                        <div v-for="(faq, i) in displayFaqs" :key="faq.id"
                             class="bg-white border border-slate-200 rounded-xl overflow-hidden transition-all hover:shadow-md">
                            <button
                                @click="toggleFaq(i)"
                                class="w-full px-6 py-5 flex items-center justify-between text-left group">
                                <span class="font-semibold text-slate-900 group-hover:text-[#D3A762] transition text-lg pr-4">
                                    {{ faq.question }}
                                </span>
                                <span class="flex-shrink-0 text-slate-500 transition-transform duration-300"
                                      :class="{ 'rotate-180': faq.open }">
                                    <ChevronDown class="w-5 h-5" />
                                </span>
                            </button>
                            <div v-show="faq.open" class="px-6 pb-5 text-slate-600 leading-relaxed">
                                {{ faq.answer }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Banner Section -->
            <section class="w-full bg-[#D3A762]">
                <img v-if="settings.banner_image"
                     :src="`/storage/${settings.banner_image}`"
                     alt="Banner"
                     class="w-full h-[200px] object-cover" />
                <img v-else
                     src="https://images.unsplash.com/photo-1596726284620-830238e8f643?q=80&w=2684&auto=format&fit=crop"
                     alt="Kaaba"
                     class="w-full h-[200px] object-cover" />
            </section>
        </main>

        <HajjFooter :settings="settings" />

        <!-- WhatsApp Floating Button -->
        <a v-if="whatsappUrl" :href="whatsappUrl" target="_blank" rel="noopener noreferrer"
           class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-2xl transition-all hover:scale-110 z-50 flex items-center gap-2 group"
           aria-label="Chat on WhatsApp">
            <MessageCircle class="w-6 h-6" />
            <span class="hidden group-hover:inline-block text-sm font-semibold pr-2">Chat with us</span>
        </a>
    </div>
</template>
