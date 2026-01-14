<script setup lang="ts">
// import AppLayout from '@/layouts/AppLayout.vue'
import HajjHeader from '@/components/hajj/hajjheader.vue'
import HajjFooter from '@/components/hajj/hajjfooter.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <HajjHeader />

    <main class="flex-1 w-full">
      <slot />
    </main>

    <HajjFooter />
  </div>
</template>
