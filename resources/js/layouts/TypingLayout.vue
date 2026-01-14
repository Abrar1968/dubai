<script setup lang="ts">
import typingheader from '@/components/typing/typingheader.vue'
import typingfooter from '@/components/typing/typingfooter.vue'
</script>

<template>
  <div>
    <typingheader />
    <main class="w-full">
      <slot />
    </main>
    <typingfooter />
  </div>
</template>
