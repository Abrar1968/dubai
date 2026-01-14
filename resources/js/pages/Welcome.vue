<template>
  <div class="relative min-h-screen bg-black flex items-center justify-center px-6 py-12 overflow-hidden">
    <!-- ✅ Background Video -->
    <video class="absolute inset-0 h-full w-full object-cover" autoplay muted loop playsinline preload="auto">
      <source :src="bgVideo" type="video/mp4" />
    </video>

    <!-- ✅ Dark overlay for readability -->
    <div class="absolute inset-0 bg-black/60"></div>

    <!-- Content -->
    <div class="relative z-10 w-full max-w-md space-y-5">
      <button v-for="(item, i) in services" :key="item.key" class="group w-full rounded-2xl border border-white/10 bg-white/5 px-8 py-6 text-left
               backdrop-blur-md shadow-[0_0_0_1px_rgba(255,255,255,0.02)]
               transition-all duration-300 ease-out
               hover:border-white/25 hover:bg-white/10 hover:shadow-[0_0_30px_rgba(255,255,255,0.08)]
               active:scale-[0.98]" :style="{
                animationDelay: `${i * 120}ms`,
                animationPlayState: mounted ? 'running' : 'paused'
              }" @click="goTo(item.key)">
        <div class="flex items-center justify-between">
          <h2 class="text-white text-xl md:text-2xl font-semibold tracking-tight">
            {{ item.label }}
          </h2>

          <span class="text-white/50 transition-all duration-300 group-hover:text-white/80 group-hover:translate-x-1"
            aria-hidden="true">
            →
          </span>
        </div>

        <p class="mt-2 text-sm text-white/50 group-hover:text-white/60 transition-colors duration-300">
          Tap to continue
        </p>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const mounted = ref(false)

// ✅ Your public path: public/assets/img/bg video/bg.mp4
// In browser it becomes: /assets/img/bg%20video/bg.mp4
// Better: rename folder to "bg-video" to avoid spaces.
// But this will still work if the folder name truly has a space.
const bgVideo = '/assets/img/bg%20video/bg.mp4'

const services = [
  { key: 'tour-travel', label: 'Tour & Travel' },
  { key: 'hajj-umrah', label: 'Hajj & Umrah' },
  { key: 'typing', label: 'Typing' },
]

const goTo = (service) => {
  const paths = {
    'tour-travel': '/tour-travel',
    'hajj-umrah': '/hajj-umrah',
    'typing': '/typing',
  }

  const target = paths[service]
  if (target) router.visit(target)
}

onMounted(() => {
  mounted.value = true
})
</script>

<style scoped>
/* Load animation: fade + slide up */
button {
  opacity: 0;
  transform: translateY(14px);
  animation: riseIn 520ms cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
}

@keyframes riseIn {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
