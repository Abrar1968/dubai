<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import {
    ChevronLeft,
    CalendarDays,
    Clock,
    CheckCircle2,
    Sparkles,
    AlertCircle,
    Package,
    Users,
    Plane,
    CreditCard,
    MapPin,
    FileText,
    User,
    Globe,
    Hash,
    Calendar
} from 'lucide-vue-next'
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

const getStatusConfig = (status: string) => {
    switch (status) {
        case 'pending':
            return {
                bg: 'bg-amber-50',
                text: 'text-amber-700',
                border: 'border-amber-200',
                gradient: 'from-amber-500 to-orange-500',
                icon: Clock,
                description: 'Your booking is being processed'
            };
        case 'confirmed':
            return {
                bg: 'bg-emerald-50',
                text: 'text-emerald-700',
                border: 'border-emerald-200',
                gradient: 'from-emerald-500 to-teal-500',
                icon: CheckCircle2,
                description: 'Your booking has been confirmed'
            };
        case 'completed':
            return {
                bg: 'bg-blue-50',
                text: 'text-blue-700',
                border: 'border-blue-200',
                gradient: 'from-blue-500 to-indigo-500',
                icon: Sparkles,
                description: 'Journey completed successfully'
            };
        case 'cancelled':
            return {
                bg: 'bg-red-50',
                text: 'text-red-700',
                border: 'border-red-200',
                gradient: 'from-red-500 to-rose-500',
                icon: AlertCircle,
                description: 'This booking has been cancelled'
            };
        default:
            return {
                bg: 'bg-slate-50',
                text: 'text-slate-700',
                border: 'border-slate-200',
                gradient: 'from-slate-500 to-slate-600',
                icon: Package,
                description: 'Status unknown'
            };
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
    return new Intl.NumberFormat('en-AE', {
        style: 'currency',
        currency: 'AED',
        minimumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <Link
                href="/user/bookings"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition duration-200 hover:bg-slate-50 hover:border-slate-300 self-start"
            >
                <ChevronLeft class="h-4 w-4" />
                Back to Bookings
            </Link>
        </div>

        <template v-if="booking">
            <!-- Status Hero Banner -->
            <div
                class="relative overflow-hidden rounded-2xl p-6 sm:p-8 text-white shadow-xl"
                :class="`bg-gradient-to-br ${getStatusConfig(booking.status).gradient}`"
            >
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 -mt-8 -mr-8 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -mb-12 -ml-12 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>

                <div class="relative">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm">
                            <component :is="getStatusConfig(booking.status).icon" class="h-8 w-8" />
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-sm font-medium backdrop-blur-sm capitalize">
                                    {{ booking.status }}
                                </span>
                                <span class="text-white/80 text-sm font-mono">
                                    #{{ booking.booking_number }}
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-bold">
                                {{ booking.package?.title || 'Hajj/Umrah Package' }}
                            </h1>
                            <p class="mt-2 text-white/80">
                                {{ getStatusConfig(booking.status).description }}
                            </p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-white/60 text-sm">Total Amount</p>
                            <p class="text-3xl font-bold">{{ formatAmount(booking.total_amount) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Package Info -->
                    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
                        <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50">
                            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                                <Plane class="h-5 w-5 text-amber-500" />
                                Package Details
                            </h2>
                        </div>
                        <div class="p-6">
                            <div v-if="booking.package" class="flex flex-col sm:flex-row gap-6">
                                <div class="flex-shrink-0">
                                    <div
                                        v-if="booking.package.image"
                                        class="h-32 w-32 sm:h-40 sm:w-40 rounded-xl overflow-hidden ring-2 ring-slate-100"
                                    >
                                        <img
                                            :src="`/storage/${booking.package.image}`"
                                            :alt="booking.package.title"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <div
                                        v-else
                                        class="h-32 w-32 sm:h-40 sm:w-40 rounded-xl flex items-center justify-center bg-gradient-to-br from-amber-100 to-amber-50"
                                    >
                                        <Plane class="h-16 w-16 text-amber-400" />
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-slate-900">{{ booking.package.title }}</h3>
                                    <p class="mt-1 text-sm text-slate-500 capitalize">{{ booking.package.type }} Package</p>

                                    <div class="mt-4 grid grid-cols-2 gap-4">
                                        <div class="rounded-xl bg-slate-50 p-4">
                                            <div class="flex items-center gap-2 text-slate-500 text-sm mb-1">
                                                <CalendarDays class="h-4 w-4" />
                                                Duration
                                            </div>
                                            <p class="font-semibold text-slate-900">{{ booking.package.duration_days }} days</p>
                                        </div>
                                        <div class="rounded-xl bg-slate-50 p-4">
                                            <div class="flex items-center gap-2 text-slate-500 text-sm mb-1">
                                                <CreditCard class="h-4 w-4" />
                                                Per Person
                                            </div>
                                            <p class="font-semibold text-slate-900">
                                                {{ booking.package.currency }} {{ booking.package.price.toLocaleString() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Travelers -->
                    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
                        <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50">
                            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                                <Users class="h-5 w-5 text-amber-500" />
                                Travelers
                                <span class="ml-auto inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                                    {{ booking.travelers?.length || booking.traveler_count }}
                                </span>
                            </h2>
                        </div>
                        <div class="divide-y divide-slate-100">
                            <div
                                v-for="(traveler, index) in booking.travelers"
                                :key="traveler.id"
                                class="p-6 hover:bg-slate-50/50 transition-colors"
                            >
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-amber-50 text-amber-600 font-bold text-lg">
                                        {{ index + 1 }}
                                    </div>
                                    <div class="flex-1 grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-1">
                                                <User class="h-3 w-3" />
                                                Full Name
                                            </div>
                                            <p class="font-semibold text-slate-900">{{ traveler.full_name }}</p>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-1">
                                                <Hash class="h-3 w-3" />
                                                Passport Number
                                            </div>
                                            <p class="font-semibold text-slate-900 font-mono">{{ traveler.passport_number }}</p>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-1">
                                                <Calendar class="h-3 w-3" />
                                                Date of Birth
                                            </div>
                                            <p class="font-semibold text-slate-900">{{ formatDate(traveler.date_of_birth) }}</p>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-1">
                                                <Globe class="h-3 w-3" />
                                                Nationality
                                            </div>
                                            <p class="font-semibold text-slate-900">{{ traveler.nationality }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="!booking.travelers || booking.travelers.length === 0" class="p-8 text-center">
                                <Users class="h-10 w-10 text-slate-300 mx-auto mb-3" />
                                <p class="text-slate-500">No traveler details available</p>
                            </div>
                        </div>
                    </div>

                    <!-- Special Requirements -->
                    <div v-if="booking.special_requirements" class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
                        <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50">
                            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                                <FileText class="h-5 w-5 text-amber-500" />
                                Special Requirements
                            </h2>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-600 whitespace-pre-wrap leading-relaxed">{{ booking.special_requirements }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Payment Summary -->
                    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
                        <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50">
                            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                                <CreditCard class="h-5 w-5 text-amber-500" />
                                Payment Summary
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Travelers</span>
                                <span class="font-medium text-slate-900">{{ booking.traveler_count }}</span>
                            </div>
                            <div v-if="booking.package" class="flex justify-between text-sm">
                                <span class="text-slate-500">Price per person</span>
                                <span class="font-medium text-slate-900">{{ formatAmount(booking.package.price) }}</span>
                            </div>
                            <hr class="border-slate-200" />
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-slate-900">Total Amount</span>
                                <span class="text-2xl font-bold text-amber-600">{{ formatAmount(booking.total_amount) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Travel Date -->
                    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
                        <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50">
                            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                                <CalendarDays class="h-5 w-5 text-amber-500" />
                                Travel Date
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 text-amber-600">
                                    <Plane class="h-7 w-7" />
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-slate-900">{{ formatDate(booking.travel_date) }}</p>
                                    <p class="text-sm text-slate-500">Departure Date</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status History -->
                    <div v-if="booking.status_logs && booking.status_logs.length > 0" class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
                        <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50">
                            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                                <Clock class="h-5 w-5 text-amber-500" />
                                Status History
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="relative">
                                <div class="absolute left-3 top-3 bottom-3 w-0.5 bg-slate-200"></div>
                                <div v-for="(log, index) in booking.status_logs" :key="log.id" class="relative flex gap-4 pb-5 last:pb-0">
                                    <div
                                        class="relative z-10 flex h-6 w-6 items-center justify-center rounded-full ring-4 ring-white"
                                        :class="getStatusConfig(log.status).bg"
                                    >
                                        <div
                                            class="h-2 w-2 rounded-full"
                                            :class="index === 0 ? 'bg-current animate-pulse' : 'bg-current'"
                                            :style="{ color: getStatusConfig(log.status).text.replace('text-', '').replace('-700', '') }"
                                        ></div>
                                    </div>
                                    <div class="flex-1 pt-0.5">
                                        <p class="text-sm font-semibold text-slate-900 capitalize">{{ log.status }}</p>
                                        <p v-if="log.notes" class="text-xs text-slate-500 mt-0.5">{{ log.notes }}</p>
                                        <p class="text-xs text-slate-400 mt-1">{{ formatDateTime(log.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Need Help -->
                    <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100/50 p-6 ring-1 ring-slate-200/60">
                        <h3 class="font-semibold text-slate-900 mb-2">Need Assistance?</h3>
                        <p class="text-sm text-slate-600 mb-4">Our support team is here to help with your booking.</p>
                        <Link
                            href="/contact"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 transition duration-200 hover:bg-slate-50"
                        >
                            <MapPin class="h-4 w-4" />
                            Contact Support
                        </Link>
                    </div>
                </div>
            </div>
        </template>

        <!-- Not Found State -->
        <template v-else>
            <div class="rounded-2xl bg-white px-6 py-16 text-center shadow-sm ring-1 ring-slate-200/60">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
                    <AlertCircle class="h-10 w-10 text-slate-400" />
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Booking Not Found</h3>
                <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">
                    The booking you're looking for doesn't exist or may have been removed.
                </p>
                <div class="mt-6">
                    <Link
                        href="/user/bookings"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition duration-200 hover:from-amber-600 hover:to-amber-700"
                    >
                        View All Bookings
                    </Link>
                </div>
            </div>
        </template>
    </div>
</template>
