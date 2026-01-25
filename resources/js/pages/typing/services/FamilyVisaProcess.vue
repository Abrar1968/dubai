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
              <button v-for="em in emirates" :key="em.slug" @click="selectEmirate(em.slug)"
                :class="['w-full text-left px-4 py-4 rounded-md border-2 transition', selectedEmirate === em.slug ? 'bg-white border-yellow-400 shadow-sm' : 'bg-white/50 border-slate-200']">
                <span class="font-bold text-slate-800">{{ em.name }}</span>
              </button>
            </div>
          </aside>

          <!-- Right: Actions + Details -->
          <div class="md:col-span-2">
            <div class="flex flex-col items-end gap-4 mb-6">
              <a :href="applyLink('new_residency')" class="inline-flex items-center justify-center rounded-md bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 font-semibold">Apply for New Residency / Entry Permit</a>
              <div class="flex flex-col items-end gap-3 w-full md:w-auto">
                <a :href="applyLink('renewal')" class="inline-flex items-center justify-center rounded-md bg-blue-500 hover:bg-blue-600 text-white px-5 py-2">Residency Renewal</a>
                <a :href="applyLink('new_born')" class="inline-flex items-center justify-center rounded-md bg-blue-500 hover:bg-blue-600 text-white px-5 py-2">New Born Baby</a>
                <a :href="applyLink('cancellation')" class="inline-flex items-center justify-center rounded-md bg-blue-500 hover:bg-blue-600 text-white px-5 py-2">Cancellation</a>
              </div>
            </div>

            <div class="space-y-8">
              <div v-for="em in emirates" :key="em.slug" v-show="selectedEmirate === em.slug">
                <!-- Visa type cards removed per request -->
              </div>
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

const emirates = [
  {
    slug: 'sharjah',
    name: 'Sharjah',
    description: 'Sharjah processes family visas in accordance with federal immigration rules and local authority procedures. Processing times vary and some emirate-specific requirements may apply.',
    services: [
      { key: 'new_residency', title: 'Apply for New Residency / Entry Permit', desc: 'Start a new family residency application with sponsor documents, entry permits and medical fitness tests.' },
      { key: 'renewal', title: 'Residency Renewal', desc: 'Renew existing family residency permits before expiry to avoid penalties.' },
      { key: 'new_born', title: 'New Born Baby', desc: 'Register and sponsor newborns with required birth documentation and medical certificates.' },
      { key: 'cancellation', title: 'Cancellation', desc: 'Assist with visa cancellations and related clearance if the sponsor requests it.' },
    ],
  },
  {
    slug: 'dubai',
    name: 'Dubai',
    description: 'Dubai follows federal visa rules and has specific timelines for medical testing and Emirates ID issuance. We coordinate appointments and document submissions to comply with Dubai-specific procedures.',
    services: [
      { key: 'new_residency', title: 'Apply for New Residency / Entry Permit', desc: 'Employer- or sponsor-led entry permits and follow-up for stamping and Emirates ID.' },
      { key: 'renewal', title: 'Residency Renewal', desc: 'Fast-track renewal assistance and reminders for renewals.' },
      { key: 'new_born', title: 'New Born Baby', desc: 'Assistance with adding a newborn to your residency visa and medical records.' },
      { key: 'cancellation', title: 'Cancellation', desc: 'Complete cancellation processing and guidance for exit permits where necessary.' },
    ],
  },
  {
    slug: 'ajman',
    name: 'Ajman',
    description: 'Ajman processes follow the federal guidelines with local administrative steps; we assist with document submission and local office visits.',
    services: [
      { key: 'new_residency', title: 'Apply for New Residency / Entry Permit', desc: 'Support with initial application, document checks and follow-up.' },
      { key: 'renewal', title: 'Residency Renewal', desc: 'Assistance to renew residency and ensure compliance with local rules.' },
      { key: 'new_born', title: 'New Born Baby', desc: 'Register and sponsor newborns and update family records.' },
      { key: 'cancellation', title: 'Cancellation', desc: 'Handle cancellations with minimal delay and manage documentation.' },
    ],
  },
  {
    slug: 'abu-dhabi',
    name: 'Abu Dhabi',
    description: 'Large-scale processing hub with specific health insurance and medical checks; we ensure all steps are completed accurately for Abu Dhabi authorities.',
    services: [
      { key: 'new_residency', title: 'Apply for New Residency / Entry Permit', desc: 'Full application management including health insurance alignment required by Abu Dhabi.' },
      { key: 'renewal', title: 'Residency Renewal', desc: 'Renewal management and coordination with health insurers.' },
      { key: 'new_born', title: 'New Born Baby', desc: 'Newborn registration and documentation assistance for residency addition.' },
      { key: 'cancellation', title: 'Cancellation', desc: 'Process cancellations and help with exit formalities.' },
    ],
  },
  {
    slug: 'ras-al-khaimah',
    name: 'Ras Al Khaimah',
    description: 'Ras Al Khaimah follows federal immigration processes and we offer end-to-end support for family visa matters in the emirate.',
    services: [
      { key: 'new_residency', title: 'Apply for New Residency / Entry Permit', desc: 'Local doc preparation and submission service.' },
      { key: 'renewal', title: 'Residency Renewal', desc: 'Renewal assistance and local office liaison.' },
      { key: 'new_born', title: 'New Born Baby', desc: 'Support to sponsor newborns and prepare birth documentation.' },
      { key: 'cancellation', title: 'Cancellation', desc: 'We handle cancellation paperwork and related updates.' },
    ],
  },
  {
    slug: 'umm-al-quwain',
    name: 'Umm Al Quwain',
    description: 'We assist with family visas in Umm Al Quwain, including coordination of medical tests and Emirates ID steps.',
    services: [
      { key: 'new_residency', title: 'Apply for New Residency / Entry Permit', desc: 'Application submission and follow-up on permit issuance.' },
      { key: 'renewal', title: 'Residency Renewal', desc: 'Renewal support and records management.' },
      { key: 'new_born', title: 'New Born Baby', desc: 'Help with newborn sponsorship and required documents.' },
      { key: 'cancellation', title: 'Cancellation', desc: 'Visa cancellation handling and notifications.' },
    ],
  },
  {
    slug: 'fujairah',
    name: 'Fujairah',
    description: 'Fujairah family visa processing support with local knowledge to speed up document handling and approvals.',
    services: [
      { key: 'new_residency', title: 'Apply for New Residency / Entry Permit', desc: 'Document checks and filing for entry permits.' },
      { key: 'renewal', title: 'Residency Renewal', desc: 'Assistance with renewals and coordinating medical checks.' },
      { key: 'new_born', title: 'New Born Baby', desc: 'Support for adding newborns to family residence records.' },
      { key: 'cancellation', title: 'Cancellation', desc: 'Manage cancellations and related clearance processes.' },
    ],
  },
]

const selectedEmirate = ref('sharjah')

const selectEmirate = (slug) => {
  selectedEmirate.value = slug
}

const selected = computed(() => emirates.find(e => e.slug === selectedEmirate.value))

const applyLink = (type) => {
  return `/contactus?service=family-visa&emirate=${selectedEmirate.value}&type=${encodeURIComponent(type)}`
}
</script>
