<script setup lang="ts">
import { Mail, Phone, Facebook, Instagram, Twitter, Youtube } from 'lucide-vue-next';

interface Settings {
    company_name?: string;
    company_tagline?: string;
    company_logo?: string;
    company_email?: string;
    company_phone?: string;
    company_whatsapp?: string;
    company_address?: string;
    social_facebook?: string;
    social_instagram?: string;
    social_twitter?: string;
    social_youtube?: string;
}

const props = withDefaults(defineProps<{
    settings?: Settings;
}>(), {
    settings: () => ({}),
});

const socialLinks = [
    { name: 'Facebook', url: props.settings.social_facebook, icon: Facebook },
    { name: 'Instagram', url: props.settings.social_instagram, icon: Instagram },
    { name: 'Twitter', url: props.settings.social_twitter, icon: Twitter },
    { name: 'YouTube', url: props.settings.social_youtube, icon: Youtube },
].filter(link => link.url);
</script>

<template>
    <footer class="bg-[#111] text-white py-16 px-4 md:px-16">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Brand Section -->
            <div class="flex flex-col gap-6">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img v-if="settings.company_logo" :src="`/storage/${settings.company_logo}`" :alt="`${settings.company_name || 'Company'} Logo`" class="h-24 w-auto object-contain" />
                    <div v-else class="text-2xl font-serif">
                        <span class="inline-block border border-white/30 p-1 rounded-sm mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M12 2v20M8 10v4M16 10v4M4 14h16M12 2l-2 4h4l-2-4z"/></svg>
                        </span>
                    </div>
                    <span class="text-xl font-serif">{{ settings.company_name || 'Typing Services' }}</span>
                </div>

                <p v-if="settings.company_tagline" class="text-sm text-gray-400">
                    {{ settings.company_tagline }}
                </p>

                <!-- Social Links -->
                <div v-if="socialLinks.length > 0" class="flex gap-4 mt-2">
                    <a v-for="social in socialLinks" :key="social.name" :href="social.url" target="_blank" rel="noopener noreferrer"
                       class="text-white hover:text-[#D3A762] transition-colors" :aria-label="social.name">
                        <component :is="social.icon" class="w-5 h-5" />
                    </a>
                </div>
            </div>

            <!-- Office Section -->
            <div class="flex flex-col gap-6">
                <h3 class="text-sm font-semibold tracking-wider text-gray-400 uppercase">OFFICE</h3>

                <div v-if="settings.company_address" class="text-sm text-white leading-relaxed" v-html="settings.company_address.replace(/\n/g, '<br>')"></div>

                <div class="flex flex-col gap-3 text-sm">
                    <a v-if="settings.company_email" :href="`mailto:${settings.company_email}`" class="flex items-center gap-3 text-white hover:text-gray-300 transition-colors">
                        <Mail class="w-4 h-4" />
                        <span>{{ settings.company_email }}</span>
                    </a>
                    <a v-if="settings.company_phone" :href="`tel:${settings.company_phone}`" class="flex items-center gap-3 text-white hover:text-gray-300 transition-colors">
                        <Phone class="w-4 h-4" />
                        <span>{{ settings.company_phone }}</span>
                    </a>
                </div>
            </div>

            <!-- Quick Links Section -->
            <div class="flex flex-col gap-6">
                <h3 class="text-sm font-semibold tracking-wider text-gray-400 uppercase">QUICK LINKS</h3>
                <nav class="flex flex-col gap-4 text-sm text-white">
                    <a href="/typing" class="hover:text-gray-300 transition-colors">Home</a>
                    <a href="/typing#services" class="hover:text-gray-300 transition-colors">Services</a>
                    <a href="/typing/contact" class="hover:text-gray-300 transition-colors">Contact Us</a>
                    <a href="/admin/login" class="hover:text-[#D3A762] transition-colors text-gray-500 text-xs mt-4">Admin Login</a>
                </nav>
            </div>
        </div>

        <!-- Copyright -->
        <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
            <p>&copy; {{ new Date().getFullYear() }} {{ settings.company_name || 'Typing Services' }}. All rights reserved.</p>
        </div>
    </footer>
</template>
