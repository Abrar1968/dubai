<script setup lang="ts">
import HajjHeader from '@/components/hajj/hajjheader.vue';
import HajjFooter from '@/components/hajj/hajjfooter.vue';
import {
    MapPin, Calendar, Users, Star, ArrowRight, ShieldCheck,
    Clock, Heart, Menu, Phone, Mail, CheckCircle, ArrowUpRight
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

const props = withDefaults(defineProps<{
    packages?: Package[];
    articles?: Article[];
    testimonials?: Testimonial[];
    settings?: Record<string, string>;
}>(), {
    packages: () => [],
    articles: () => [],
    testimonials: () => [],
    settings: () => ({}),
});

// Fallback data if no data from backend
const displayPackages = props.packages.length > 0 ? props.packages : [
    {
        id: 1,
        title: 'Premium Hajj',
        slug: 'premium-hajj',
        price: 12500,
        currency: 'USD',
        duration_days: 7,
        image: '/assets/img/hajj/hajjbg.jpg',
        features: ['5 Star Hotel', 'Direct Flight', 'Visa Included', 'Full Board'],
        type: 'hajj',
    },
    {
        id: 2,
        title: 'Ramadan Umrah',
        slug: 'ramadan-umrah',
        price: 2250,
        currency: 'USD',
        duration_days: 7,
        image: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?q=80&w=2070&auto=format&fit=crop',
        features: ['4 Star Hotel', 'Direct Flight', 'Visa Included', 'Breakfast'],
        type: 'umrah',
    },
    {
        id: 3,
        title: 'Family Umrah',
        slug: 'family-umrah',
        price: 1850,
        currency: 'USD',
        duration_days: 7,
        image: '/assets/img/hajj/family.jpg',
        features: ['Family Room', 'City Tour', 'Visa Included', 'Guide'],
        type: 'umrah',
    },
    {
        id: 4,
        title: 'Medina City Tour',
        slug: 'medina-city-tour',
        price: 850,
        currency: 'USD',
        duration_days: 3,
        image: '/assets/img/hajj/madina.jpg',
        features: ['Local Guide', 'Transport', 'Lunch Included', 'Museums'],
        type: 'tour',
    },
];

const displayArticles = props.articles.length > 0 ? props.articles : [
    {
        id: 1,
        slug: 'essential-packing-tips',
        title: 'Essential Packing Tips for Your Hajj',
        category: 'Travel Guide',
        excerpt: '',
        image: 'https://images.unsplash.com/photo-1585036156171-384164a8c675?q=80&w=2070&auto=format&fit=crop',
    },
    {
        id: 2,
        slug: 'personal-stories',
        title: 'Personal Stories from the Sacred Journey',
        category: 'Travel Guide',
        excerpt: '',
        image: 'https://images.unsplash.com/photo-1606233282833-87bb161d9042?q=80&w=2148&auto=format&fit=crop',
    },
    {
        id: 3,
        slug: 'ultimate-guide-umrah',
        title: 'The Ultimate Guide to Performing Umrah',
        category: 'Travel Guide',
        excerpt: '',
        image: 'https://images.unsplash.com/photo-1551041777-cf9bd3048993?q=80&w=2006&auto=format&fit=crop',
    }
];

const displayTestimonial = props.testimonials.length > 0 ? props.testimonials[0] : {
    id: 1,
    name: 'Ahmed Hassan',
    location: 'Pilgrim from UK',
    content: 'The experience was absolutely spiritually uplifting. The team took care of every detail, from the visa process to the accommodations near the Haram. I felt completely at peace.',
    rating: 5,
    avatar: 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?q=80&w=1780&auto=format&fit=crop',
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

</script>

<template>
    <div class="font-sans text-slate-800">
        <HajjHeader />

        <main>
            <!-- Hero Section -->
            <section class="relative h-[85vh] w-full bg-slate-900 group overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0">
                    <img src="/assets/img/hajj/hajjbg.jpg" alt="Makkah" class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform
                    duration-1000" />
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
                        Hajj & Umrah with Ease,<br>Faith with Peace
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
            <section class="py-20 bg-white">
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
            <section class="py-20 bg-gray-50">
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
            <section class="relative py-24 bg-slate-900 overflow-hidden">
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
                            <img :src="displayTestimonial.avatar || 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?q=80&w=1780&auto=format&fit=crop'"
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
                                <img :src="article.featured_image || 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?q=80&w=2070'"
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

            <!-- Newsletter CTA (Extra nice touch) -->
            <section class="py-20 bg-[#D3A762]">
                <!-- <div class="max-w-4xl mx-auto px-4 text-center text-white">
                    <h2 class="text-3xl font-serif font-bold mb-4">Subscribe for Updates</h2>
                    <p class="mb-8 text-white/90">Join our community to get the latest news and special offers for Hajj
                        & Umrah packages.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <input type="email" placeholder="Your email address"
                            class="px-6 py-4 rounded-full text-slate-900 w-full sm:w-96 focus:outline-none shadow-lg">
                        <button
                            class="bg-slate-900 text-white px-8 py-4 rounded-full font-bold hover:bg-slate-800 transition-colors shadow-lg">Subscribe</button>
                    </div>
                </div> -->

                <img src="https://images.unsplash.com/photo-1596726284620-830238e8f643?q=80&w=2684&auto=format&fit=crop"
                    class="rounded-2xl shadow-2xl w-full" alt="Kaaba" />
            </section>
        </main>

        <HajjFooter />
    </div>
</template>
