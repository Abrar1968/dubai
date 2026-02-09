<template>
    <div class="bg-[#fbf6ef] min-h-screen">
        <!-- Article Header -->
        <div class="relative h-[400px] w-full overflow-hidden">
            <img :src="displayArticle.image || 'https://images.unsplash.com/photo-1585036156171-384164a8c675?q=80&w=2070'"
                 :alt="displayArticle.title"
                 loading="lazy"
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
                <!-- Tags Display -->
                <div v-if="displayArticle.tags && displayArticle.tags.length > 0" class="mb-6 flex flex-wrap gap-2">
                    <span v-for="tag in displayArticle.tags" :key="tag"
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 hover:bg-orange-200 transition-colors">
                        #{{ tag }}
                    </span>
                </div>

                <div class="prose prose-lg max-w-none" v-html="displayArticle.content || defaultContent"></div>

                <!-- Views Counter -->
                <div v-if="displayArticle.views_count" class="mt-8 pt-6 border-t border-slate-200 text-sm text-slate-500">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ displayArticle.views_count.toLocaleString() }} views
                    </span>
                </div>
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
                        <img :src="related.image || 'https://images.unsplash.com/photo-1585036156171-384164a8c675?q=80&w=2070'"
                             :alt="related.title"
                             loading="lazy"
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
import { computed } from 'vue';
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
    image: string | null;
    published_at: string | null;
    author?: Author;
    views_count?: number;
    tags?: string[];
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

// Reactive article data using computed
const displayArticle = computed(() => props.article || {
    id: 1,
    title: 'Article Detail',
    slug: props.slug || 'article',
    excerpt: 'Article content is being prepared.',
    content: defaultContent,
    category: 'TRAVEL GUIDE',
    image: 'https://images.unsplash.com/photo-1585036156171-384164a8c675?q=80&w=2070',
    published_at: null,
    author: undefined,
});

// Reactive related articles - no mock data
const displayRelatedArticles = computed(() => {
    if (props.relatedArticles && props.relatedArticles.length > 0) {
        return props.relatedArticles;
    }
    return [];
});

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>
