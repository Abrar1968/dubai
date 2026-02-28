<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import typingheader from '@/components/typing/typingheader.vue'
import typingfooter from '@/components/typing/typingfooter.vue'

// Get settings from page props (passed from controller)
const page = usePage();
const settings = computed(() => {
    const pageSettings = page.props.settings || {};
    // Flatten company/social settings if grouped
    return {
        ...pageSettings.company,
        ...pageSettings.social,
        ...pageSettings.contact,
        ...pageSettings.seo,
    };
});
const auth = computed(() => page.props.auth || {});
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <typingheader :settings="settings" :auth="auth" />
    <main class="flex-1 w-full">
      <slot />
    </main>
    <typingfooter :settings="settings" />
  </div>
</template>
