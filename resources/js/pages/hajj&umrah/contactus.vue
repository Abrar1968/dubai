<template>
    <section class="bg-[#eef8ef] py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <!-- Heading -->
            <div class="text-center">
                <p class="text-[#1b7f3a] text-xs sm:text-sm font-extrabold tracking-[0.22em] uppercase">
                    GET IN TOUCH
                </p>

                <h1 class="mt-3 text-3xl sm:text-5xl font-serif font-semibold text-slate-900">
                    Don't Hesitate to Contact Us
                </h1>

                <p class="mt-4 max-w-2xl mx-auto text-slate-600 text-sm sm:text-base leading-relaxed">
                    {{ displaySettings.contact_description || 'We are here to help you with your Hajj & Umrah journey. Feel free to reach out to us.' }}
                </p>
            </div>

            <!-- Success Message -->
            <div v-if="showSuccess" class="mt-8 p-4 bg-green-100 border border-green-300 rounded-lg text-green-800 text-center">
                Thank you for your message! We will get back to you within 24 hours.
            </div>

            <!-- Card -->
            <div
                class="mt-12 overflow-hidden rounded-2xl bg-white border border-slate-200 shadow-[0_20px_60px_rgba(0,0,0,0.10)]">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <!-- LEFT: Contact info (Dark) -->
                    <div class="bg-[#142a1a] text-white px-8 py-10 sm:px-12 sm:py-12">
                        <h2 class="text-2xl font-serif font-semibold">
                            Contact Information
                        </h2>
                        <p class="mt-2 text-white/70 text-sm">
                            Fill up the form and our team will get back you within 24 hours.
                        </p>

                        <!-- Dynamic Offices -->
                        <div v-for="(office, idx) in displayOffices" :key="office.id || idx" class="mt-8">
                            <h3 class="text-xl font-serif font-semibold">{{ office.name }}</h3>

                            <ul class="mt-4 space-y-3 text-sm text-white/75">
                                <li class="flex gap-3">
                                    <span class="mt-[2px] text-[#55c06a]"></span>
                                    <span>{{ office.address }}</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="mt-[2px] text-[#55c06a]"></span>
                                    <span>{{ office.phone }}</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="mt-[2px] text-[#55c06a]"></span>
                                    <span>{{ office.email }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Social -->
                        <div class="mt-12">
                            <h3 class="text-xl font-serif font-semibold">Social Media</h3>

                            <div class="mt-4 flex items-center gap-3">
                                <!-- Facebook -->
                                <a :href="displaySettings.facebook_url || '#'" aria-label="Facebook" class="h-10 w-10 rounded-full bg-[#1f8c3f] flex items-center justify-center
             text-white transition hover:brightness-110 active:scale-[0.95]">
                                    <Facebook class="w-5 h-5" />
                                </a>

                                <!-- Twitter -->
                                <a :href="displaySettings.twitter_url || '#'" aria-label="Twitter" class="h-10 w-10 rounded-full bg-white/10 border border-white/15
             flex items-center justify-center text-white
             transition hover:bg-white/15 active:scale-[0.95]">
                                    <Twitter class="w-5 h-5" />
                                </a>

                                <!-- Instagram -->
                                <a :href="displaySettings.instagram_url || '#'" aria-label="Instagram" class="h-10 w-10 rounded-full bg-white/10 border border-white/15
             flex items-center justify-center text-white
             transition hover:bg-white/15 active:scale-[0.95]">
                                    <Instagram class="w-5 h-5" />
                                </a>

                                <!-- LinkedIn -->
                                <a :href="displaySettings.linkedin_url || '#'" aria-label="LinkedIn" class="h-10 w-10 rounded-full bg-white/10 border border-white/15
             flex items-center justify-center text-white
             transition hover:bg-white/15 active:scale-[0.95]">
                                    <Linkedin class="w-5 h-5" />
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT: Form -->
                    <div class="px-8 py-10 sm:px-12 sm:py-12">
                        <form @submit.prevent="submitForm">
                            <div class="space-y-6">
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                                        Your Name
                                    </label>
                                    <input v-model="form.name" type="text" placeholder="Input your name" 
                                        :class="{'border-red-500': errors.name}"
                                        class="w-full rounded-full border border-slate-200 bg-white px-5 py-3.5 text-sm
                           outline-none transition focus:border-[#1b7f3a]/40 focus:ring-4 focus:ring-[#1b7f3a]/10" />
                                    <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                                        Your Email
                                    </label>
                                    <input v-model="form.email" type="email" placeholder="Input your email" 
                                        :class="{'border-red-500': errors.email}"
                                        class="w-full rounded-full border border-slate-200 bg-white px-5 py-3.5 text-sm
                           outline-none transition focus:border-[#1b7f3a]/40 focus:ring-4 focus:ring-[#1b7f3a]/10" />
                                    <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                                </div>

                                <!-- Subject -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                                        Your Subject
                                    </label>
                                    <input v-model="form.subject" type="text" placeholder="Input your subject" 
                                        :class="{'border-red-500': errors.subject}"
                                        class="w-full rounded-full border border-slate-200 bg-white px-5 py-3.5 text-sm
                           outline-none transition focus:border-[#1b7f3a]/40 focus:ring-4 focus:ring-[#1b7f3a]/10" />
                                    <p v-if="errors.subject" class="text-red-500 text-xs mt-1">{{ errors.subject }}</p>
                                </div>

                                <!-- Message -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-2">
                                        Your Message
                                    </label>
                                    <textarea v-model="form.message" rows="6" placeholder="Write your message"
                                        :class="{'border-red-500': errors.message}"
                                        class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm resize-none
                           outline-none transition focus:border-[#1b7f3a]/40 focus:ring-4 focus:ring-[#1b7f3a]/10"></textarea>
                                    <p v-if="errors.message" class="text-red-500 text-xs mt-1">{{ errors.message }}</p>
                                </div>

                                <!-- Button -->
                                <div>
                                    <button type="submit" 
                                        :disabled="isSubmitting"
                                        class="inline-flex items-center justify-center rounded-full bg-[#0f6a25] px-10 py-3.5
                           text-white font-semibold transition
                           hover:bg-[#0d5b20] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                                        {{ isSubmitting ? 'Sending...' : 'Send Message' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

<script setup lang="ts">
import { reactive, ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import HajjLayout from '@/layouts/HajjUmrahLayout.vue'
import { Facebook, Twitter, Instagram, Linkedin } from 'lucide-vue-next'

defineOptions({ layout: HajjLayout })

// TypeScript interfaces
interface Office {
    id?: number;
    name: string;
    address: string;
    phone: string;
    email: string;
}

interface Settings {
    contact_description?: string;
    facebook_url?: string;
    twitter_url?: string;
    instagram_url?: string;
    linkedin_url?: string;
}

const props = withDefaults(defineProps<{
    offices?: Office[];
    settings?: Settings;
}>(), {
    offices: () => [],
    settings: () => ({}),
});

// Use props directly - data comes from backend
const displayOffices = computed(() => props.offices);

// Settings from backend
const displaySettings = computed(() => ({
    contact_description: props.settings?.contact_description || 'We are here to help you with your Hajj & Umrah journey. Feel free to reach out to us.',
    facebook_url: props.settings?.facebook_url || '#',
    twitter_url: props.settings?.twitter_url || '#',
    instagram_url: props.settings?.instagram_url || '#',
    linkedin_url: props.settings?.linkedin_url || '#',
}));

const form = reactive({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const errors = reactive<Record<string, string>>({});
const isSubmitting = ref(false);
const showSuccess = ref(false);

const submitForm = () => {
    // Clear previous errors
    Object.keys(errors).forEach(key => delete errors[key]);
    
    // Basic validation
    if (!form.name.trim()) errors.name = 'Name is required';
    if (!form.email.trim()) errors.email = 'Email is required';
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) errors.email = 'Invalid email format';
    if (!form.subject.trim()) errors.subject = 'Subject is required';
    if (!form.message.trim()) errors.message = 'Message is required';
    
    if (Object.keys(errors).length > 0) return;
    
    isSubmitting.value = true;
    
    router.post('/contactus', form, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess.value = true;
            form.name = '';
            form.email = '';
            form.subject = '';
            form.message = '';
            isSubmitting.value = false;
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                showSuccess.value = false;
            }, 5000);
        },
        onError: (serverErrors) => {
            Object.assign(errors, serverErrors);
            isSubmitting.value = false;
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
};
</script>
