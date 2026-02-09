<script setup lang="ts">
import { ref, computed } from 'vue';

interface Props {
    src: string;
    alt?: string;
    fallback?: string;
    imgClass?: string;
    skeletonClass?: string;
}

const props = withDefaults(defineProps<Props>(), {
    alt: 'Image',
    fallback: '/assets/img/hajj/hajjbg.jpg',
    imgClass: '',
    skeletonClass: '',
});

const hasError = ref(false);
const isLoaded = ref(false);

const imageSrc = computed(() => {
    if (hasError.value || !props.src) {
        return props.fallback;
    }
    return props.src;
});

const handleError = () => {
    hasError.value = true;
    isLoaded.value = true; // Hide skeleton on error too
};

const handleLoad = () => {
    isLoaded.value = true;
};
</script>

<template>
    <div class="relative overflow-hidden w-full h-full">
        <!-- Skeleton Shimmer Loader -->
        <div
            v-show="!isLoaded"
            class="absolute inset-0 bg-slate-200"
            :class="skeletonClass"
        >
            <!-- Shimmer Effect -->
            <div class="absolute inset-0 -translate-x-full animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/60 to-transparent"></div>

            <!-- Image Icon Placeholder -->
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <!-- Actual Image -->
        <img
            :src="imageSrc"
            :alt="alt"
            loading="lazy"
            decoding="async"
            class="w-full h-full object-cover transition-opacity duration-500 ease-out"
            :class="[imgClass, { 'opacity-0': !isLoaded, 'opacity-100': isLoaded }]"
            @error="handleError"
            @load="handleLoad"
        />
    </div>
</template>

<style scoped>
@keyframes shimmer {
    100% {
        transform: translateX(100%);
    }
}
</style>
