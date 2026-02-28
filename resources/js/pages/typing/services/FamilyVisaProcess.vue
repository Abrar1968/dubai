<template>
  <TypingLayout>
    <section class="py-14 bg-white">
      <div class="mx-auto max-w-7xl px-4">
        <!-- Intro box (reference style) -->
        <div class="rounded-lg bg-blue-600 text-white p-8 shadow-md">
          <h1 class="text-xl md:text-2xl font-extrabold text-center">Welcome to Al Mushan Typing &amp; Emirates ID Center – Your Trusted Partner for Family Visa Services</h1>
          <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="font-semibold">Your Gateway to Reuniting Families</h3>
              <p class="mt-2 text-[15px]">We understand the significance of family and the joy that comes with being together. Our mission is to simplify the visa application process, making it easier for families to reunite and create lasting memories. With our expert Family Visa Services, you can navigate the complex immigration procedures with confidence and peace of mind.</p>
            </div>

            <div>
              <h3 class="font-semibold">Why Choose Us?</h3>
              <p class="mt-2 text-[15px]">We offer personalised guidance tailored to your situation and keep you informed throughout the process. Our team coordinates paperwork, medical requirements and Emirates ID steps to minimise delays.</p>
            </div>
          </div>
        </div>

        <!-- CTA banner -->
        <div class="mt-8 rounded-md bg-blue-500/90 text-white border-4 border-blue-300 p-6 text-center">
          <strong class="uppercase tracking-widest text-lg">Click on which emirate your visa belongs to</strong>
        </div>

        <!-- Main two-column area -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Left: Emirates list -->
          <aside class="md:col-span-1">
            <div class="space-y-4">
              <button v-for="em in displayEmirates" :key="em.slug" @click="selectEmirate(em.slug)"
                :class="['w-full text-left px-4 py-4 rounded-md border-2 transition', selectedEmirate === em.slug ? 'bg-white border-yellow-400 shadow-sm' : 'bg-white/50 border-slate-200']">
                <span class="font-bold text-slate-800">{{ em.name }}</span>
                <span v-if="em.visa_types && em.visa_types.length" class="ml-2 text-sm text-slate-500">({{ em.visa_types.length }} services)</span>
              </button>
            </div>
          </aside>

          <!-- Right: Visa Types -->
          <div class="md:col-span-2">
            <div v-if="selectedEmirateData" class="space-y-6">
              <div>
                <h2 class="text-2xl font-bold text-slate-900">{{ selectedEmirateData.name }}</h2>
                <p v-if="selectedEmirateData.description" class="mt-2 text-slate-700">{{ selectedEmirateData.description }}</p>
              </div>

              <!-- Visa Types Grid -->
              <div v-if="selectedEmirateData.visa_types && selectedEmirateData.visa_types.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a v-for="visaType in selectedEmirateData.visa_types"
                   :key="visaType.id"
                   :href="`/typing/services/family-visa/${selectedEmirateData.slug}/${visaType.slug}`"
                   class="rounded-md p-4 border border-slate-100 bg-slate-50 hover:bg-slate-100 hover:border-blue-300 transition group">
                  <h3 class="font-bold text-slate-900 group-hover:text-blue-600">{{ visaType.name }}</h3>
                  <p v-if="visaType.short_description" class="mt-2 text-slate-700 text-sm">{{ visaType.short_description }}</p>
                  <div class="mt-3">
                    <span class="inline-flex items-center rounded-md bg-[#D3A762] group-hover:bg-[#c29652] px-4 py-2 text-white font-semibold text-sm">
                      Learn More
                      <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                      </svg>
                    </span>
                  </div>
                </a>
              </div>

              <!-- No visa types message -->
              <div v-else class="text-center py-8 text-slate-500 bg-slate-50 rounded-lg">
                <p>No visa types available for this emirate yet.</p>
                <p class="mt-2 text-sm">Please check back later or contact us for assistance.</p>
                <a href="/typing/contact" class="mt-4 inline-flex items-center rounded-md bg-blue-600 hover:bg-blue-700 px-6 py-3 text-white font-semibold">
                  Contact Us
                </a>
              </div>
            </div>

            <!-- No emirate selected -->
            <div v-else class="text-center py-12 text-slate-500">
              <p class="text-lg">Please select an emirate from the list</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </TypingLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import TypingLayout from '@/layouts/TypingLayout.vue';

const props = defineProps({
  settings: {
    type: Object,
    default: () => ({})
  },
  emirates: {
    type: Array,
    default: () => []
  }
})

// Use database emirates if available, otherwise use fallback static data
const fallbackEmirates = [
  { slug: 'sharjah', name: 'Sharjah', description: 'Family visa services for Sharjah.', visa_types: [] },
  { slug: 'dubai', name: 'Dubai', description: 'Family visa services for Dubai.', visa_types: [] },
  { slug: 'ajman', name: 'Ajman', description: 'Family visa services for Ajman.', visa_types: [] },
  { slug: 'abu-dhabi', name: 'Abu Dhabi', description: 'Family visa services for Abu Dhabi.', visa_types: [] },
  { slug: 'ras-al-khaimah', name: 'Ras Al Khaimah', description: 'Family visa services for Ras Al Khaimah.', visa_types: [] },
  { slug: 'umm-al-quwain', name: 'Umm Al Quwain', description: 'Family visa services for Umm Al Quwain.', visa_types: [] },
  { slug: 'fujairah', name: 'Fujairah', description: 'Family visa services for Fujairah.', visa_types: [] },
]

const displayEmirates = computed(() => {
  return props.emirates && props.emirates.length > 0 ? props.emirates : fallbackEmirates
})

const selectedEmirate = ref(displayEmirates.value.length > 0 ? displayEmirates.value[0].slug : 'sharjah')

const selectEmirate = (slug) => {
  selectedEmirate.value = slug
}

const selectedEmirateData = computed(() => {
  return displayEmirates.value.find(e => e.slug === selectedEmirate.value)
})
</script>
