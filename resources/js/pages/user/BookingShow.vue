<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'

defineOptions({ layout: UserLayout })

interface Package {
    id: number;
    title: string;
    slug: string;
    type: string;
    price: number;
    currency: string;
    duration_days: number;
    image: string;
}

interface Traveler {
    id: number;
    full_name: string;
    passport_number: string;
    date_of_birth: string;
    nationality: string;
}

interface StatusLog {
    id: number;
    status: string;
    notes?: string;
    created_at: string;
}

interface Booking {
    id: number;
    booking_number: string;
    package?: Package;
    status: string;
    created_at: string;
    travel_date: string;
    total_amount: number;
    traveler_count: number;
    special_requirements?: string;
    travelers?: Traveler[];
    status_logs?: StatusLog[];
}

const props = withDefaults(defineProps<{
    booking?: Booking;
}>(), {
    booking: undefined,
});

const getStatusColor = (status: string) => {
    switch (status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        case 'confirmed': return 'bg-green-100 text-green-800 border-green-200';
        case 'completed': return 'bg-blue-100 text-blue-800 border-blue-200';
        case 'cancelled': return 'bg-red-100 text-red-800 border-red-200';
        default: return 'bg-gray-100 text-gray-800 border-gray-200';
    }
};

const getStatusIcon = (status: string) => {
    switch (status) {
        case 'pending': return 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z';
        case 'confirmed': return 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
        case 'completed': return 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z';
        case 'cancelled': return 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';
        default: return 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
};

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatAmount = (amount: number) => {
    return `$${amount.toLocaleString()}`;
};
</script>

<template>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link href="/user/bookings" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Booking Details</h1>
                    <p class="mt-1 text-sm text-gray-500" v-if="booking">
                        Booking #{{ booking.booking_number }}
                    </p>
                </div>
            </div>
        </div>

        <template v-if="booking">
            <!-- Status Banner -->
            <div :class="[getStatusColor(booking.status), 'flex items-center gap-3 rounded-xl border p-4']">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getStatusIcon(booking.status)"/>
                </svg>
                <div>
                    <p class="font-medium capitalize">{{ booking.status }}</p>
                    <p class="text-sm opacity-75">Booked on {{ formatDate(booking.created_at) }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Package Info -->
                    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Package Information</h2>
                        </div>
                        <div class="p-6">
                            <div v-if="booking.package" class="flex gap-4">
                                <img v-if="booking.package.image" :src="booking.package.image" :alt="booking.package.title" class="h-24 w-24 rounded-lg object-cover" />
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ booking.package.title }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 capitalize">{{ booking.package.type }} Package</p>
                                    <div class="mt-3 flex flex-wrap gap-4 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ booking.package.duration_days }} days
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ booking.package.currency }} {{ booking.package.price.toLocaleString() }} per person
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Travelers -->
                    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Travelers ({{ booking.travelers?.length || booking.traveler_count }})</h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <div v-for="(traveler, index) in booking.travelers" :key="traveler.id" class="p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600 font-medium">
                                        {{ index + 1 }}
                                    </div>
                                    <div class="flex-1 grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <p class="text-xs text-gray-500">Full Name</p>
                                            <p class="font-medium text-gray-900">{{ traveler.full_name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Passport Number</p>
                                            <p class="font-medium text-gray-900">{{ traveler.passport_number }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Date of Birth</p>
                                            <p class="font-medium text-gray-900">{{ formatDate(traveler.date_of_birth) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Nationality</p>
                                            <p class="font-medium text-gray-900">{{ traveler.nationality }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="!booking.travelers || booking.travelers.length === 0" class="p-6 text-center text-gray-500">
                                No traveler details available
                            </div>
                        </div>
                    </div>

                    <!-- Special Requirements -->
                    <div v-if="booking.special_requirements" class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Special Requirements</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 whitespace-pre-wrap">{{ booking.special_requirements }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Payment Summary -->
                    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Payment Summary</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Travelers</span>
                                <span class="text-gray-900">{{ booking.traveler_count }}</span>
                            </div>
                            <div v-if="booking.package" class="flex justify-between text-sm">
                                <span class="text-gray-500">Price per person</span>
                                <span class="text-gray-900">{{ formatAmount(booking.package.price) }}</span>
                            </div>
                            <hr class="border-gray-200" />
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-900">Total</span>
                                <span class="text-lg font-bold text-amber-600">{{ formatAmount(booking.total_amount) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Travel Date -->
                    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Travel Date</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ formatDate(booking.travel_date) }}</p>
                                    <p class="text-sm text-gray-500">Departure Date</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status History -->
                    <div v-if="booking.status_logs && booking.status_logs.length > 0" class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Status History</h2>
                        </div>
                        <div class="p-6">
                            <div class="relative">
                                <div class="absolute left-2.5 top-3 bottom-3 w-0.5 bg-gray-200"></div>
                                <div v-for="log in booking.status_logs" :key="log.id" class="relative flex gap-3 pb-4 last:pb-0">
                                    <div :class="[getStatusColor(log.status).split(' ')[0], 'relative z-10 h-5 w-5 rounded-full border-2 border-white flex items-center justify-center']">
                                        <div class="h-2 w-2 rounded-full bg-current"></div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 capitalize">{{ log.status }}</p>
                                        <p v-if="log.notes" class="text-xs text-gray-500 mt-0.5">{{ log.notes }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ formatDateTime(log.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Not Found State -->
        <template v-else>
            <div class="rounded-xl bg-white px-6 py-12 text-center shadow-sm ring-1 ring-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Booking not found</h3>
                <p class="mt-1 text-sm text-gray-500">The booking you're looking for doesn't exist.</p>
                <div class="mt-6">
                    <Link href="/user/bookings" class="inline-flex items-center rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                        View All Bookings
                    </Link>
                </div>
            </div>
        </template>
    </div>
</template>
