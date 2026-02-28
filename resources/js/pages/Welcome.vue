<template>
  <div class="relative min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 overflow-hidden">
    <!-- ✅ Background Video -->
    <video class="absolute inset-0 h-full w-full object-cover opacity-30" autoplay muted loop playsinline preload="auto">
      <source :src="bgVideo" type="video/mp4" />
    </video>

    <!-- ✅ Gradient overlay for better text readability -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-black/70"></div>

    <!-- Content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 sm:px-6 py-8 sm:py-12">
      <div class="w-full max-w-4xl text-center space-y-8 sm:space-y-12">
        <!-- Header Section with Dynamic Logo -->
        <div class="space-y-4 sm:space-y-6">
          <!-- Dynamic Logo -->
          <div v-if="companyLogo" class="flex justify-center mb-4">
            <img
              :src="`/storage/${companyLogo}`"
              :alt="companyName"
              class="h-20 sm:h-28 md:h-36 w-auto object-contain drop-shadow-2xl"
            />
          </div>
          <h1 class="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-bold text-white leading-tight">
            SS Group <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Typing & Travels</span>
          </h1>
          <p class="text-lg sm:text-xl md:text-2xl text-white/80 max-w-3xl mx-auto leading-relaxed px-2">
            Your trusted partner for spiritual journeys and professional typing services in the UAE
          </p>
        </div>

        <!-- Services Grid -->
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
          <!-- Hajj & Umrah Card -->
          <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-500/20 to-orange-600/20 border border-amber-500/30 backdrop-blur-lg shadow-2xl hover:shadow-amber-500/25 transition-all duration-500 hover:scale-105 cursor-pointer"
               @click="goTo('hajj-umrah')">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-orange-600/5"></div>
            <div class="relative p-8 md:p-10">
              <!-- Icon -->
              <div class="w-16 h-16 mb-6 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
              </div>

              <!-- Content -->
              <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">
                Hajj & Umrah
              </h3>
              <p class="text-white/70 text-lg leading-relaxed mb-6">
                Experience the spiritual journey of a lifetime with our comprehensive pilgrimage packages, expert guidance, and premium services.
              </p>

              <!-- Features -->
              <ul class="space-y-2 mb-6">
                <li class="flex items-center text-white/60">
                  <svg class="w-4 h-4 mr-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  Premium accommodation
                </li>
                <li class="flex items-center text-white/60">
                  <svg class="w-4 h-4 mr-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  Expert religious guidance
                </li>
                <li class="flex items-center text-white/60">
                  <svg class="w-4 h-4 mr-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  24/7 support service
                </li>
              </ul>

              <!-- Action -->
              <div class="flex items-center justify-between">
                <span class="text-amber-400 font-semibold">Explore Packages</span>
                <div class="w-10 h-10 rounded-full bg-amber-400/20 flex items-center justify-center group-hover:bg-amber-400/30 transition-colors">
                  <svg class="w-5 h-5 text-amber-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Typing Services Card -->
          <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500/20 to-indigo-600/20 border border-blue-500/30 backdrop-blur-lg shadow-2xl hover:shadow-blue-500/25 transition-all duration-500 hover:scale-105 cursor-pointer"
               @click="goTo('typing')">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-600/5"></div>
            <div class="relative p-8 md:p-10">
              <!-- Icon -->
              <div class="w-16 h-16 mb-6 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H8v-2h4v2zm4-4H8v-2h8v2zm0-4H8V7h8v2z"/>
                </svg>
              </div>

              <!-- Content -->
              <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">
                Typing Services
              </h3>
              <p class="text-white/70 text-lg leading-relaxed mb-6">
                Professional document processing, visa applications, and government paperwork services with speed and accuracy.
              </p>

              <!-- Features -->
              <ul class="space-y-2 mb-6">
                <li class="flex items-center text-white/60">
                  <svg class="w-4 h-4 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  Visa applications
                </li>
                <li class="flex items-center text-white/60">
                  <svg class="w-4 h-4 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  Document translation
                </li>
                <li class="flex items-center text-white/60">
                  <svg class="w-4 h-4 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  Fast processing
                </li>
              </ul>

              <!-- Action -->
              <div class="flex items-center justify-between">
                <span class="text-blue-400 font-semibold">View Services</span>
                <div class="w-10 h-10 rounded-full bg-blue-400/20 flex items-center justify-center group-hover:bg-blue-400/30 transition-colors">
                  <svg class="w-5 h-5 text-blue-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Bottom CTA -->
        <div class="text-center space-y-4">
          <p class="text-white/60 text-base sm:text-lg">
            Trusted by thousands of customers across the UAE
          </p>
          <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8 text-white/40 text-sm sm:text-base">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Licensed & Certified</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>24/7 Support</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>Expert Team</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating elements for visual appeal -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-amber-400/10 rounded-full blur-xl"></div>
    <div class="absolute top-40 right-20 w-32 h-32 bg-blue-400/10 rounded-full blur-2xl"></div>
    <div class="absolute bottom-40 left-20 w-24 h-24 bg-indigo-400/10 rounded-full blur-xl"></div>
    <div class="absolute bottom-20 right-10 w-16 h-16 bg-orange-400/10 rounded-full blur-lg"></div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

defineProps<{
  canRegister?: boolean
  companyLogo?: string | null
  companyName?: string
}>()

const mounted = ref(false)

// ✅ Your public path: public/assets/img/bg video/bg.mp4
// In browser it becomes: /assets/img/bg%20video/bg.mp4
const bgVideo = '/assets/img/bg%20video/bg.mp4'

const goTo = (service) => {
  const paths = {
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
/* Responsive adjustments */
@media (max-width: 768px) {
  .grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  h1 {
    font-size: 2.5rem;
    line-height: 1.1;
  }

  .space-y-12 > * + * {
    margin-top: 2rem;
  }
}

@media (max-width: 640px) {
  h1 {
    font-size: 2rem;
  }

  .text-xl {
    font-size: 1.125rem;
  }

  .p-8 {
    padding: 1.5rem;
  }

  .space-y-12 > * + * {
    margin-top: 1.5rem;
  }
}

/* Floating animation */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

.absolute.blur-xl,
.absolute.blur-2xl,
.absolute.blur-lg {
  animation: float 6s ease-in-out infinite;
}

.absolute.blur-xl:nth-child(2) { animation-delay: 2s; }
.absolute.blur-2xl:nth-child(3) { animation-delay: 4s; }
.absolute.blur-lg:nth-child(4) { animation-delay: 1s; }
</style>
