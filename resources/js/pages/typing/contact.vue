<template>
    <TypingLayout>
        <div class="bg-white">
            <!-- Header Section -->
            <section class="bg-gradient-to-r from-teal-900 to-teal-800 py-12">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-3xl sm:text-4xl font-bold text-white mb-4">Contact Us</h1>
                    <p class="text-teal-100 max-w-2xl mx-auto">
                        Have questions about our typing services? Get in touch with us and we'll respond as soon as possible.
                    </p>
                </div>
            </section>

            <!-- Contact Form Section -->
            <section class="py-14">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <!-- Contact Form -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 border border-slate-100">
                            <h2 class="text-2xl font-bold text-slate-900 mb-6">Send us a Message</h2>
                            
                            <form @submit.prevent="submitForm" class="space-y-6">
                                <!-- Success Message -->
                                <div v-if="form.recentlySuccessful" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                                    <p class="font-medium">Thank you for contacting us!</p>
                                    <p class="text-sm">We'll get back to you as soon as possible.</p>
                                </div>

                                <!-- Error Message -->
                                <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                                    <p class="font-medium">Please correct the following errors:</p>
                                    <ul class="list-disc list-inside text-sm mt-2">
                                        <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                                    </ul>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Full Name *</label>
                                        <input 
                                            type="text" 
                                            id="name" 
                                            v-model="form.name"
                                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                                            placeholder="Your name"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address *</label>
                                        <input 
                                            type="email" 
                                            id="email" 
                                            v-model="form.email"
                                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                                            placeholder="your@email.com"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                                        <input 
                                            type="tel" 
                                            id="phone" 
                                            v-model="form.phone"
                                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                                            placeholder="+971 50 123 4567"
                                        />
                                    </div>
                                    <div>
                                        <label for="service" class="block text-sm font-medium text-slate-700 mb-2">Service Interested In</label>
                                        <select 
                                            id="service" 
                                            v-model="form.service"
                                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                                        >
                                            <option value="">Select a service</option>
                                            <option v-for="service in services" :key="service.id" :value="service.title">
                                                {{ service.title }}
                                            </option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="subject" class="block text-sm font-medium text-slate-700 mb-2">Subject *</label>
                                    <input 
                                        type="text" 
                                        id="subject" 
                                        v-model="form.subject"
                                        class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                                        placeholder="How can we help?"
                                        required
                                    />
                                </div>

                                <div>
                                    <label for="message" class="block text-sm font-medium text-slate-700 mb-2">Message *</label>
                                    <textarea 
                                        id="message" 
                                        v-model="form.message"
                                        rows="5"
                                        class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition resize-none"
                                        placeholder="Tell us more about your requirements..."
                                        required
                                    ></textarea>
                                </div>

                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-6 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="form.processing">Sending...</span>
                                    <span v-else>Send Message</span>
                                </button>
                            </form>
                        </div>

                        <!-- Contact Info -->
                        <div class="space-y-8">
                            <!-- Contact Cards -->
                            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                                <h3 class="text-xl font-bold text-slate-900 mb-6">Contact Information</h3>
                                
                                <div class="space-y-6">
                                    <div v-if="settings.company_phone" class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                                            <Phone class="w-6 h-6 text-teal-600" />
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-slate-900">Phone</h4>
                                            <a :href="`tel:${settings.company_phone}`" class="text-slate-600 hover:text-teal-600 transition">
                                                {{ settings.company_phone }}
                                            </a>
                                        </div>
                                    </div>

                                    <div v-if="settings.company_whatsapp" class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                            <MessageCircle class="w-6 h-6 text-green-600" />
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-slate-900">WhatsApp</h4>
                                            <a :href="`https://wa.me/${settings.company_whatsapp?.replace(/[^0-9]/g, '')}`" target="_blank" class="text-slate-600 hover:text-green-600 transition">
                                                {{ settings.company_whatsapp }}
                                            </a>
                                        </div>
                                    </div>

                                    <div v-if="settings.company_email" class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <Mail class="w-6 h-6 text-blue-600" />
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-slate-900">Email</h4>
                                            <a :href="`mailto:${settings.company_email}`" class="text-slate-600 hover:text-blue-600 transition">
                                                {{ settings.company_email }}
                                            </a>
                                        </div>
                                    </div>

                                    <div v-if="settings.company_address" class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                                            <MapPin class="w-6 h-6 text-amber-600" />
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-slate-900">Office Address</h4>
                                            <p class="text-slate-600" v-html="settings.company_address?.replace(/\n/g, '<br>')"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Office Hours -->
                            <div class="bg-teal-50 rounded-2xl p-8 border border-teal-100">
                                <h3 class="text-xl font-bold text-slate-900 mb-4">Office Hours</h3>
                                <div class="space-y-2 text-slate-700">
                                    <div class="flex justify-between">
                                        <span>Monday - Friday</span>
                                        <span class="font-medium">9:00 AM - 6:00 PM</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Saturday</span>
                                        <span class="font-medium">9:00 AM - 2:00 PM</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Sunday</span>
                                        <span class="font-medium text-red-600">Closed</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </TypingLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import TypingLayout from '@/layouts/TypingLayout.vue';
import { Phone, Mail, MapPin, MessageCircle } from 'lucide-vue-next';

interface TypingService {
    id: number;
    title: string;
    slug: string;
}

interface Settings {
    company_name?: string;
    company_email?: string;
    company_phone?: string;
    company_whatsapp?: string;
    company_address?: string;
}

const props = withDefaults(defineProps<{
    services?: TypingService[];
    settings?: Settings;
}>(), {
    services: () => [],
    settings: () => ({}),
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    service: '',
    subject: '',
    message: '',
    section: 'typing',
});

const submitForm = () => {
    form.post('/typing/contact', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
