<script setup lang="ts">
import TourHeader from '@/components/tour/tourherader.vue'
import TourFooter from '@/components/tour/tourfooter.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <TourHeader />

    <main class="flex-1 w-full">
      <slot />
    </main>

    <TourFooter />
  </div>
</template>
