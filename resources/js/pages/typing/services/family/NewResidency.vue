<template>
  <TypingLayout>
    <section class="py-14 bg-white">
      <div class="mx-auto max-w-3xl px-4">
        <h1 class="text-3xl font-bold mb-4 text-slate-900">★ Apply for New Residency / Entry Permit</h1>
        <h2 v-if="displayEmirate" class="text-xl font-semibold text-slate-900 mt-2 mb-4">{{ displayEmirate }}</h2>
        <p class="text-slate-700 mb-6">Start a new family residency application with sponsor documents, entry permits and medical fitness tests. We guide you through each step and coordinate appointments for Emirates ID and medical fitness tests to minimize delays.</p>

        <h3 class="text-lg font-semibold mt-6 text-slate-900">What we do</h3>
        <ul class="list-disc pl-6 mt-2 text-slate-700">
          <li>Document checklist and verification</li>
          <li>Entry permit submission and follow-up</li>
          <li>Medical fitness and Emirates ID coordination</li>
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
const contactLink = `/contactus?service=family-visa&type=new_residency${emirate ? '&emirate='+encodeURIComponent(emirate) : ''}`
</script>
