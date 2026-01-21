<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import HajjHeader from '@/components/hajj/hajjheader.vue'
import HajjFooter from '@/components/hajj/hajjfooter.vue'

// Get settings from page props (passed from controller)
const page = usePage();
const settings = computed(() => page.props.settings || {});
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <HajjHeader :settings="settings" />

    <main class="flex-1 w-full">
      <slot />
    </main>

    <HajjFooter :settings="settings" />
  </div>
</template>
