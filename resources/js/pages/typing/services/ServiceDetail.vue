<template>
  <TypingLayout>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-teal-900 via-teal-800 to-slate-900 py-16">
      <div class="absolute inset-0 bg-[url('/assets/pattern.svg')] opacity-5"></div>
      <div class="relative mx-auto max-w-6xl px-4">
        <div class="flex items-center gap-4 mb-4">
          <span v-if="service.icon" class="text-4xl">{{ service.icon }}</span>
          <nav class="flex items-center gap-2 text-sm text-teal-200">
            <Link href="/typing" class="hover:text-white">Home</Link>
            <span>/</span>
            <Link href="/typing" class="hover:text-white">Services</Link>
            <span>/</span>
            <span class="text-white">{{ service.title }}</span>
          </nav>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ service.title }}</h1>
        <p v-if="service.short_description" class="text-lg text-teal-100 max-w-3xl">{{ service.short_description }}</p>
      </div>
    </section>

    <!-- Main Content -->
    <section class="py-14 bg-white">
      <div class="mx-auto max-w-6xl px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
          <!-- Main Content Area -->
          <div class="lg:col-span-2">
            <!-- Long Description (from database) -->
            <div v-if="service.long_description" class="prose prose-slate prose-lg max-w-none"
                 v-html="formattedDescription"></div>

            <!-- Sub-Services (if available) -->
            <div v-if="service.sub_services && service.sub_services.length > 0" class="mt-10">
              <h2 class="text-2xl font-bold text-slate-900 mb-6">Our Services Include</h2>
              <div class="space-y-6">
                <div v-for="(subService, index) in service.sub_services" :key="index"
                     class="bg-slate-50 rounded-xl p-6 border border-slate-100">
                  <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center">
                      <span class="text-teal-600 font-bold">{{ index + 1 }}</span>
                    </div>
                    <div>
                      <h3 class="text-lg font-semibold text-slate-900">{{ subService.name }}</h3>
                      <p v-if="subService.description" class="mt-2 text-slate-600">{{ subService.description }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- CTA Section -->
            <div class="mt-10 bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl p-8 text-center">
              <h3 class="text-xl font-bold text-white mb-3">Ready to Get Started?</h3>
              <p class="text-teal-100 mb-6">Contact us today for professional assistance with {{ service.title.toLowerCase() }}.</p>
              <Link :href="service.cta_link || '/typing/contact'"
                    class="inline-flex items-center justify-center rounded-xl bg-white text-teal-700 px-8 py-3 text-sm font-semibold hover:bg-teal-50 transition">
                {{ service.cta_text || 'Contact Us' }}
              </Link>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <!-- Featured Image -->
            <div v-if="service.featured_image_url" class="mb-6">
              <img :src="service.featured_image_url" :alt="service.title"
                   class="w-full rounded-xl shadow-lg object-cover aspect-video">
            </div>
            <!-- Fallback to old image_url for backwards compatibility -->
            <div v-else-if="service.image_url" class="mb-6">
              <img :src="service.image_url" :alt="service.title"
                   class="w-full rounded-xl shadow-lg object-cover aspect-video">
            </div>

            <!-- Gallery Images (Government Documents) -->
            <div v-if="service.gallery_urls && service.gallery_urls.length > 0" class="mb-6">
              <h3 class="text-lg font-semibold text-slate-900 mb-3">Reference Documents</h3>
              <div class="grid grid-cols-2 gap-2">
                <div v-for="(imgUrl, index) in service.gallery_urls" :key="index"
                     class="cursor-pointer overflow-hidden rounded-lg border border-slate-200 hover:border-teal-400 transition"
                     @click="openGallery(index)">
                  <img :src="imgUrl" :alt="`Document ${index + 1}`"
                       class="w-full h-24 object-cover hover:scale-105 transition-transform">
                </div>
              </div>
            </div>

            <!-- Quick Contact Card -->
            <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 mb-6">
              <h3 class="text-lg font-semibold text-slate-900 mb-4">Need Assistance?</h3>
              <p class="text-slate-600 text-sm mb-4">Our team is ready to help you with {{ service.title.toLowerCase() }}.</p>
              <Link href="/typing/contact"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-teal-600 px-4 py-3 text-sm font-semibold text-white hover:bg-teal-700 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                </svg>
                Contact Us Now
              </Link>
            </div>

            <!-- Other Services -->
            <div v-if="otherServices && otherServices.length > 0" class="bg-white rounded-xl p-6 border border-slate-200">
              <h3 class="text-lg font-semibold text-slate-900 mb-4">Other Services</h3>
              <ul class="space-y-3">
                <li v-for="other in otherServices" :key="other.id">
                  <Link :href="other.url"
                        class="flex items-center gap-3 text-slate-600 hover:text-teal-600 transition group">
                    <span v-if="other.icon" class="text-xl">{{ other.icon }}</span>
                    <span v-else class="w-2 h-2 rounded-full bg-teal-500 group-hover:bg-teal-600"></span>
                    <span class="text-sm font-medium">{{ other.title }}</span>
                  </Link>
                </li>
              </ul>
              <Link href="/typing"
                    class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-teal-600 hover:text-teal-700">
                View All Services
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </TypingLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import TypingLayout from '@/layouts/TypingLayout.vue'

interface SubService {
  name: string
  description?: string
}

interface TypingService {
  id: number
  title: string
  slug: string
  short_description?: string
  long_description?: string
  icon?: string
  image?: string
  image_url?: string
  featured_image?: string
  featured_image_url?: string
  gallery_images?: string[]
  gallery_urls?: string[]
  url: string
  sub_services?: SubService[]
  cta_text?: string
  cta_link?: string
  is_active: boolean
  is_featured: boolean
}

interface Props {
  service: TypingService
  otherServices?: TypingService[]
  settings?: Record<string, any>
}

const props = defineProps<Props>()

// Gallery lightbox state
const openGallery = (index: number) => {
  // For now, just open in new tab - can be enhanced with a lightbox library
  if (props.service.gallery_urls && props.service.gallery_urls[index]) {
    window.open(props.service.gallery_urls[index], '_blank')
  }
}

// Convert plain text description to HTML with proper formatting
const formattedDescription = computed(() => {
  if (!props.service.long_description) return ''

  let html = props.service.long_description

  // Convert double newlines to paragraphs
  html = html.split(/\n\n+/).map(p => `<p>${p.trim()}</p>`).join('')

  // Convert single newlines within paragraphs to <br>
  html = html.replace(/\n/g, '<br>')

  // Convert **text** to bold
  html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')

  // Convert headers (lines starting with ## or ###)
  html = html.replace(/<p>###\s*(.*?)<\/p>/g, '<h3 class="text-xl font-bold text-slate-900 mt-6 mb-3">$1</h3>')
  html = html.replace(/<p>##\s*(.*?)<\/p>/g, '<h2 class="text-2xl font-bold text-slate-900 mt-8 mb-4">$1</h2>')

  // Convert bullet points
  html = html.replace(/<p>•\s*(.*?)<\/p>/g, '<li class="ml-4">$1</li>')
  html = html.replace(/<p>-\s*(.*?)<\/p>/g, '<li class="ml-4">$1</li>')

  // Wrap consecutive <li> elements in <ul>
  html = html.replace(/(<li[^>]*>.*?<\/li>\s*)+/g, '<ul class="list-disc list-inside space-y-2 my-4">$&</ul>')

  return html
})
</script>
