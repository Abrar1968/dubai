<template>
    <div class="bg-[#fbf6ef]">
        <!-- Header -->
        <section class="py-14 sm:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-orange-600 font-semibold text-sm tracking-wide">BLOG</p>
                    <h1 class="mt-2 text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">
                        Articles
                    </h1>
                    <p class="mt-4 text-slate-500 max-w-2xl mx-auto">
                        Read guides, tips and stories to make your Hajj & Umrah journey easier.
                    </p>
                </div>

                <!-- Cards -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <article v-for="post in posts" :key="post.id" class="group rounded-2xl bg-white border border-slate-200 overflow-hidden
                   shadow-[0_14px_40px_rgba(0,0,0,0.08)]
                   transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_22px_60px_rgba(0,0,0,0.12)]">
                        <!-- Image -->
                        <div class="relative h-[220px] overflow-hidden">
                            <img :src="post.image" :alt="post.title"
                                class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.06]"
                                loading="lazy" />
                        </div>

                        <!-- Content -->
                        <div class="p-7">
                            <p class="text-xs font-extrabold tracking-wide text-orange-500 uppercase">
                                {{ post.category }}
                            </p>

                            <h2 class="mt-3 text-xl font-extrabold text-slate-900 leading-snug">
                                {{ post.title }}
                            </h2>

                            <p class="mt-3 text-slate-500 text-sm leading-relaxed line-clamp-3">
                                {{ post.excerpt }}
                            </p>

                            <!-- Read More -->
                            <div class="mt-6">
                                <Link href="#"
                                    class="inline-flex items-center gap-2 text-slate-600 font-semibold hover:text-slate-900 transition">
                                    Read More
                                    <span
                                        class="inline-block transition-transform duration-300 group-hover:translate-x-1">→</span>
                                </Link>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import HajjUmrahLayout from '@/layouts/HajjUmrahLayout.vue'

defineOptions({ layout: HajjUmrahLayout })

// ✅ For now: demo data (later you will pass posts from controller)
const posts = [
    {
        id: 1,
        slug: 'essential-packing-tips-for-your-hajj',
        category: 'TRAVEL GUIDE',
        title: 'Essential Packing Tips for Your Hajj',
        excerpt:
            'A checklist of essentials, smart packing strategies, and what to avoid so you can travel light and stay prepared.',
        image: '/images/blog/b1.jpg',
    },
    {
        id: 2,
        slug: 'personal-stories-from-the-sacred-journey',
        category: 'TRAVEL GUIDE',
        title: 'Personal Stories from the Sacred Journey',
        excerpt:
            'Inspirational stories from pilgrims and the lessons they learned on their spiritual journey.',
        image: '/images/blog/b2.jpg',
    },
    {
        id: 3,
        slug: 'ultimate-guide-to-performing-umrah',
        category: 'TRAVEL GUIDE',
        title: 'The Ultimate Guide to Performing Umrah',
        excerpt:
            'Step-by-step Umrah guide: preparation, Ihram, Tawaf, Sa’i, and practical tips to make your Umrah easy.',
        image: '/images/blog/b3.jpg',
    },
]
</script>
