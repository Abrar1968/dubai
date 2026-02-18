<template>
  <TypingLayout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-12">
      <div class="mx-auto max-w-7xl px-4">
        <!-- Breadcrumb -->
        <nav class="mb-4 text-sm">
          <ol class="flex items-center space-x-2">
            <li><a href="/typing" class="hover:underline opacity-80">Home</a></li>
            <li><span class="opacity-60">/</span></li>
            <li><a href="/typing/services/family-visa-process" class="hover:underline opacity-80">Family Visa</a></li>
            <li><span class="opacity-60">/</span></li>
            <li><span class="opacity-80">{{ emirate.name }}</span></li>
            <li><span class="opacity-60">/</span></li>
            <li class="font-semibold">{{ visaType.name }}</li>
          </ol>
        </nav>

        <h1 class="text-3xl md:text-4xl font-bold">{{ visaType.name }}</h1>
        <p class="mt-2 text-lg opacity-90">{{ emirate.name }} - Family Visa Services</p>

        <div v-if="visaType.processing_time || visaType.price_range" class="mt-4 flex flex-wrap gap-4">
          <span v-if="visaType.processing_time" class="inline-flex items-center bg-white/20 rounded-full px-4 py-2">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ visaType.processing_time }}
          </span>
          <span v-if="visaType.price_range" class="inline-flex items-center bg-white/20 rounded-full px-4 py-2">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ visaType.price_range }}
          </span>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-slate-50">
      <div class="mx-auto max-w-7xl px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Main Content Column -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Short Description -->
            <div v-if="visaType.short_description" class="bg-white rounded-lg shadow-sm p-6">
              <p class="text-lg text-slate-700 leading-relaxed">{{ visaType.short_description }}</p>
            </div>

            <!-- Long Description -->
            <div v-if="visaType.long_description" class="bg-white rounded-lg shadow-sm p-6">
              <h2 class="text-xl font-bold text-slate-900 mb-4">Overview</h2>
              <div class="prose prose-slate max-w-none" v-html="visaType.long_description"></div>
            </div>

            <!-- Requirements Section -->
            <div v-if="visaType.requirements && visaType.requirements.length > 0" class="bg-white rounded-lg shadow-sm p-6">
              <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center">
                <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
                Requirements
              </h2>
              <ul class="space-y-3">
                <li v-for="(req, index) in visaType.requirements" :key="index" class="flex items-start">
                  <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                  </svg>
                  <span class="text-slate-700">{{ req }}</span>
                </li>
              </ul>
            </div>

            <!-- Documents Section -->
            <div v-if="visaType.documents && visaType.documents.length > 0" class="bg-white rounded-lg shadow-sm p-6">
              <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center">
                <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mr-3">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                  </svg>
                </span>
                Required Documents
              </h2>
              <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <li v-for="(doc, index) in visaType.documents" :key="index" class="flex items-start bg-slate-50 rounded-md p-3">
                  <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                  </svg>
                  <span class="text-slate-700">{{ doc }}</span>
                </li>
              </ul>
            </div>

            <!-- Process Steps Section -->
            <div v-if="visaType.process_steps && visaType.process_steps.length > 0" class="bg-white rounded-lg shadow-sm p-6">
              <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mr-3">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                  </svg>
                </span>
                Process Steps
              </h2>
              <div class="relative">
                <!-- Timeline line -->
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-200"></div>

                <div class="space-y-6">
                  <div v-for="(step, index) in visaType.process_steps" :key="index" class="relative pl-12">
                    <!-- Step number -->
                    <div class="absolute left-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                      {{ index + 1 }}
                    </div>
                    <div class="bg-slate-50 rounded-md p-4">
                      <p class="text-slate-700">{{ step }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="lg:col-span-1 space-y-6">
            <!-- CTA Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
              <h3 class="text-lg font-bold text-slate-900 mb-4">Ready to Apply?</h3>
              <p class="text-slate-600 mb-6">Our team is here to assist you with your {{ visaType.name }} application in {{ emirate.name }}.</p>

              <a v-if="visaType.cta_link" :href="visaType.cta_link" class="block w-full text-center bg-[#D3A762] hover:bg-[#c29652] text-white font-semibold py-3 px-6 rounded-md transition">
                {{ visaType.cta_text || 'Apply Now' }}
              </a>
              <a v-else href="/typing/contact" class="block w-full text-center bg-[#D3A762] hover:bg-[#c29652] text-white font-semibold py-3 px-6 rounded-md transition">
                {{ visaType.cta_text || 'Contact Us to Apply' }}
              </a>

              <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="tel:+97165631444" class="flex items-center text-slate-600 hover:text-blue-600 mb-2">
                  <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                  </svg>
                  +971 6 563 1444
                </a>
                <a href="mailto:info@almushantyping.com" class="flex items-center text-slate-600 hover:text-blue-600">
                  <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                  </svg>
                  info@almushantyping.com
                </a>
              </div>
            </div>

            <!-- Other Services in this Emirate -->
            <div v-if="otherTypes && otherTypes.length > 0" class="bg-white rounded-lg shadow-sm p-6">
              <h3 class="text-lg font-bold text-slate-900 mb-4">Other Services in {{ emirate.name }}</h3>
              <ul class="space-y-2">
                <li v-for="type in otherTypes" :key="type.id">
                  <a :href="`/typing/services/family-visa/${emirate.slug}/${type.slug}`"
                     class="flex items-center text-slate-600 hover:text-blue-600 py-2 border-b border-slate-100 last:border-0">
                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                    {{ type.name }}
                  </a>
                </li>
              </ul>
            </div>

            <!-- Back to Family Visa -->
            <div class="bg-slate-100 rounded-lg p-4">
              <a href="/typing/services/family-visa-process" class="flex items-center text-slate-700 hover:text-blue-600 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to All Emirates
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </TypingLayout>
</template>

<script setup>
import TypingLayout from '@/layouts/TypingLayout.vue';

const props = defineProps({
  settings: {
    type: Object,
    default: () => ({})
  },
  visaType: {
    type: Object,
    required: true
  },
  emirate: {
    type: Object,
    required: true
  },
  otherTypes: {
    type: Array,
    default: () => []
  }
})
</script>
