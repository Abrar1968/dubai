<template>
    <div class="bg-[#fbf6ef] min-h-screen py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block p-4 rounded-full bg-orange-100 text-orange-600 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </span>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight sm:text-5xl mb-4">
                Article Detail
            </h1>
            <p class="text-lg text-slate-600 mb-8">
                You are viewing the article with slug: <code
                    class="bg-white px-2 py-1 rounded border border-slate-200 font-mono text-orange-600">{{ slug }}</code>
            </p>
            <p class="text-slate-500 max-w-xl mx-auto mb-10">
                This content is currently being written by our editors. Please check back soon for the full travel
                guide.
            </p>

            <Link href="/articles"
                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-orange-600 hover:bg-orange-700 transition shadow-lg hover:shadow-xl">
                ← Back to Articles
            </Link>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import HajjUmrahLayout from '@/layouts/HajjUmrahLayout.vue'

defineOptions({ layout: HajjUmrahLayout })

defineProps<{
    slug?: string
}>()
</script>
