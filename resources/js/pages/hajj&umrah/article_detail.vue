<template>
    <div class="bg-[#fbf6ef] min-h-screen">
        <!-- Article Header -->
        <div class="relative h-[400px] w-full overflow-hidden">
            <img :src="displayArticle.featured_image || 'https://images.unsplash.com/photo-1585036156171-384164a8c675?q=80&w=2070'"
                 :alt="displayArticle.title"
                 class="absolute inset-0 h-full w-full object-cover" />
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-4">
                <span class="text-orange-400 text-sm font-semibold uppercase tracking-wide mb-4">
                    {{ displayArticle.category }}
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight max-w-4xl">
                    {{ displayArticle.title }}
                </h1>
                <p class="mt-4 text-white/80 text-sm">
                    {{ displayArticle.published_at ? formatDate(displayArticle.published_at) : 'Recently Published' }}
                    <span v-if="displayArticle.author" class="ml-2">by {{ displayArticle.author.name }}</span>
                </p>
            </div>
        </div>

        <!-- Article Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <article class="bg-white rounded-2xl shadow-lg p-8 sm:p-12">
                <div class="prose prose-lg max-w-none" v-html="displayArticle.content || defaultContent"></div>
            </article>

            <!-- Back Button -->
            <div class="mt-10 text-center">
                <Link href="/articles"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-orange-600 hover:bg-orange-700 transition shadow-lg hover:shadow-xl">
                    ← Back to Articles
                </Link>
            </div>
        </div>

        <!-- Related Articles -->
        <div v-if="displayRelatedArticles.length > 0" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <h2 class="text-2xl font-bold text-slate-900 mb-8">Related Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <article v-for="related in displayRelatedArticles" :key="related.id"
                    class="group rounded-2xl bg-white border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
                    <div class="relative h-[180px] overflow-hidden">
                        <img :src="related.featured_image || 'https://images.unsplash.com/photo-1585036156171-384164a8c675?q=80&w=2070'"
                             :alt="related.title"
                             class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    </div>
                    <div class="p-6">
                        <span class="text-xs font-bold text-orange-500 uppercase">{{ related.category }}</span>
                        <h3 class="mt-2 text-lg font-bold text-slate-900 group-hover:text-orange-600 transition-colors">
                            {{ related.title }}
                        </h3>
                        <Link :href="`/articles/${related.slug}`"
                            class="mt-4 inline-flex items-center text-sm font-semibold text-slate-600 hover:text-slate-900">
                            Read More →
                        </Link>
                    </div>
                </article>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import HajjUmrahLayout from '@/layouts/HajjUmrahLayout.vue'

defineOptions({ layout: HajjUmrahLayout })

// TypeScript interfaces
interface Author {
    name: string;
    avatar?: string;
}

interface Article {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    content?: string;
    category: string;
    featured_image: string | null;
    published_at: string | null;
    author?: Author;
}

const props = withDefaults(defineProps<{
    article?: Article;
    relatedArticles?: Article[];
    slug?: string;
}>(), {
    article: undefined,
    relatedArticles: () => [],
    slug: '',
});

const defaultContent = `
<p>This article is currently being written by our editors. Please check back soon for the full content.</p>
<p>In the meantime, explore our other articles about Hajj and Umrah to prepare for your spiritual journey.</p>
`;

// Fallback article data
const displayArticle = props.article || {
    id: 1,
    title: 'Article Detail',
    slug: props.slug || 'article',
    excerpt: 'Article content is being prepared.',
    content: defaultContent,
    category: 'TRAVEL GUIDE',
    featured_image: 'https://images.unsplash.com/photo-1585036156171-384164a8c675?q=80&w=2070',
    published_at: null,
    author: undefined,
};

// Fallback related articles
const displayRelatedArticles = props.relatedArticles.length > 0 ? props.relatedArticles : [
    {
        id: 2,
        slug: 'essential-packing-tips',
        category: 'TRAVEL GUIDE',
        title: 'Essential Packing Tips for Hajj',
        excerpt: 'What to pack for your spiritual journey.',
        featured_image: 'https://images.unsplash.com/photo-1606233282833-87bb161d9042?q=80&w=2148',
        published_at: null,
    },
    {
        id: 3,
        slug: 'umrah-guide',
        category: 'TRAVEL GUIDE',
        title: 'Complete Umrah Guide',
        excerpt: 'Step-by-step guide to performing Umrah.',
        featured_image: 'https://images.unsplash.com/photo-1551041777-cf9bd3048993?q=80&w=2006',
        published_at: null,
    },
];

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>
