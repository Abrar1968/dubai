<template>
  <TypingLayout>
    <section class="py-14 bg-white">
      <div class="mx-auto max-w-3xl px-4">
        <h1 class="text-3xl font-bold mb-4 text-slate-900">★ Cancellation</h1>
        <h2 v-if="displayEmirate" class="text-xl font-semibold text-slate-900 mt-2 mb-4">{{ displayEmirate }}</h2>
        <p class="text-slate-700 mb-6">Assist with visa cancellations and related clearance if the sponsor requests it. We manage exit formalities, clearance letters and coordination with authorities to close cases quickly.</p>

        <h3 class="text-lg font-semibold mt-6 text-slate-900">Cancellation Services</h3>
        <ul class="list-disc pl-6 mt-2 text-slate-700">
          <li>Exit permit and clearance coordination</li>
          <li>Cancellation paperwork and timelines</li>
          <li>Assistance with related document updates</li>
        </ul>

        <div class="mt-8">
          <a :href="contactLink" class="inline-flex items-center rounded-md bg-[#D3A762] hover:bg-[#c29652] px-4 py-2 text-white font-semibold">Apply</a>
        </div>
      </div>
    </section>
  </TypingLayout>
</template>

<script setup>
import TypingLayout from '@/layouts/TypingLayout.vue';
import { computed } from 'vue';
const params = new URLSearchParams(window.location.search);
const emirate = params.get('emirate') || '';
const displayEmirate = computed(() => {
  if (!emirate) return '';
  const map = { 'abu-dhabi': 'Abu Dhabi', 'ras-al-khaimah': 'Ras Al Khaimah', 'umm-al-quwain': 'Umm Al Quwain' };
  return map[emirate] ?? emirate.replace(/-/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase());
});
const contactLink = `/contactus?service=family-visa&type=cancellation${emirate ? '&emirate='+encodeURIComponent(emirate) : ''}`
</script>
