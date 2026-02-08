<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import {
    CalendarDays,
    Clock,
    CheckCircle2,
    Sparkles,
    ArrowRight,
    Plane,
    TrendingUp,
    AlertCircle,
    Package,
    ChevronRight
} from 'lucide-vue-next'
import UserLayout from '@/layouts/UserLayout.vue'

defineOptions({ layout: UserLayout })

interface Booking {
    id: number;
    booking_number: string;
    package?: {
        title: string;
        type?: string;
        image?: string;
    };
    status: string;
    created_at: string;
    travel_date?: string;
    total_amount: number;
}

interface Stats {
    total_bookings: number;
    pending_bookings: number;
    confirmed_bookings: number;
    completed_bookings: number;
}

const props = withDefaults(defineProps<{
    stats?: Stats;
    recentBookings?: Booking[];
    user?: {
        name: string;
        email?: string;
    };
}>(), {
    stats: () => ({
        total_bookings: 0,
        pending_bookings: 0,
        confirmed_bookings: 0,
        completed_bookings: 0,
    }),
    recentBookings: () => [],
});

const getStatusConfig = (status: string) => {
    switch (status) {
        case 'pending':
            return {
                bg: 'bg-amber-50',
                text: 'text-amber-700',
                border: 'border-amber-200',
                dot: 'bg-amber-500',
                icon: Clock
            };
        case 'confirmed':
            return {
                bg: 'bg-emerald-50',
                text: 'text-emerald-700',
                border: 'border-emerald-200',
                dot: 'bg-emerald-500',
                icon: CheckCircle2
            };
        case 'completed':
            return {
                bg: 'bg-blue-50',
                text: 'text-blue-700',
                border: 'border-blue-200',
                dot: 'bg-blue-500',
                icon: Sparkles
            };
        case 'cancelled':
            return {
                bg: 'bg-red-50',
                text: 'text-red-700',
                border: 'border-red-200',
                dot: 'bg-red-500',
                icon: AlertCircle
            };
        default:
            return {
                bg: 'bg-slate-50',
                text: 'text-slate-700',
                border: 'border-slate-200',
                dot: 'bg-slate-500',
                icon: Package
            };
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};

const formatAmount = (amount: number) => {
    return new Intl.NumberFormat('en-AE', {
        style: 'currency',
        currency: 'AED',
        minimumFractionDigits: 0,
    }).format(amount);
};

const greeting = computed(() => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Good morning';
    if (hour < 18) return 'Good afternoon';
    return 'Good evening';
});

const firstName = computed(() => {
    return props.user?.name?.split(' ')[0] || 'User';
});
</script>

<template>
    <div class="space-y-8">
        <!-- Welcome Hero Section -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 via-amber-600 to-orange-600 p-6 sm:p-8 text-white shadow-xl">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 -mt-12 -mr-12 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-16 -ml-16 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute top-1/2 right-1/4 h-24 w-24 rounded-full bg-white/5 blur-xl"></div>

            <div class="relative">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/20 px-3 py-1 text-sm font-medium backdrop-blur-sm">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-white"></span>
                            </span>
                            Dashboard
                        </div>
                        <h1 class="mt-4 text-2xl sm:text-3xl font-bold tracking-tight">
                            {{ greeting }}, {{ firstName }}!
                        </h1>
                        <p class="mt-2 text-amber-100 max-w-md">
                            Track your spiritual journeys, manage bookings, and discover new pilgrimage experiences.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <Link
                            href="/hajj-umrah"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-amber-600 shadow-lg shadow-amber-900/20 transition duration-200 hover:bg-amber-50 hover:shadow-xl hover:-translate-y-0.5"
                        >
                            <Plane class="h-4 w-4" />
                            <span>Browse Packages</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Bookings -->
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 transition duration-300 hover:shadow-lg hover:ring-slate-300">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600 transition-transform group-hover:scale-110">
                            <CalendarDays class="h-6 w-6" />
                        </div>
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-600">
                            <TrendingUp class="h-3 w-3" />
                            All time
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-slate-500">Total Bookings</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ stats.total_bookings }}</p>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 transition duration-300 hover:shadow-lg hover:ring-slate-300">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition-transform group-hover:scale-110">
                            <Clock class="h-6 w-6" />
                        </div>
                        <span v-if="stats.pending_bookings > 0" class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex h-3 w-3 rounded-full bg-amber-500"></span>
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-slate-500">Pending</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ stats.pending_bookings }}</p>
                    </div>
                </div>
            </div>

            <!-- Confirmed -->
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 transition duration-300 hover:shadow-lg hover:ring-slate-300">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 transition-transform group-hover:scale-110">
                            <CheckCircle2 class="h-6 w-6" />
                        </div>
                        <span v-if="stats.confirmed_bookings > 0" class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-600">
                            Active
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-slate-500">Confirmed</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ stats.confirmed_bookings }}</p>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/60 transition duration-300 hover:shadow-lg hover:ring-slate-300">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-600 transition-transform group-hover:scale-110">
                            <Sparkles class="h-6 w-6" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-slate-500">Completed</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ stats.completed_bookings }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
            <div class="border-b border-slate-200 px-6 py-5 sm:px-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Recent Bookings</h2>
                        <p class="mt-1 text-sm text-slate-500">Your latest pilgrimage journeys and reservations</p>
                    </div>
                    <Link
                        href="/user/bookings"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition duration-200 hover:bg-slate-50 hover:border-slate-300"
                    >
                        View all
                        <ArrowRight class="h-4 w-4" />
                    </Link>
                </div>
            </div>

            <!-- Bookings List -->
            <div v-if="recentBookings.length > 0">
                <ul class="divide-y divide-slate-100">
                    <li
                        v-for="booking in recentBookings"
                        :key="booking.id"
                        class="group relative"
                    >
                        <Link
                            :href="`/user/bookings/${booking.id}`"
                            class="block p-6 sm:px-8 transition duration-200 hover:bg-slate-50"
                        >
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <!-- Package Image/Icon -->
                                <div class="flex-shrink-0">
                                    <div
                                        v-if="booking.package?.image"
                                        class="h-16 w-16 sm:h-20 sm:w-20 rounded-xl overflow-hidden ring-2 ring-slate-100"
                                    >
                                        <img
                                            :src="`/storage/${booking.package.image}`"
                                            :alt="booking.package?.title"
                                            class="h-full w-full object-cover transition duration-300 group-hover:scale-110"
                                        />
                                    </div>
                                    <div
                                        v-else
                                        class="flex h-16 w-16 sm:h-20 sm:w-20 items-center justify-center rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 text-amber-600"
                                    >
                                        <Plane class="h-8 w-8" />
                                    </div>
                                </div>

                                <!-- Booking Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                        <div>
                                            <h3 class="text-base font-semibold text-slate-900 group-hover:text-amber-600 transition-colors">
                                                {{ booking.package?.title || 'Hajj/Umrah Package' }}
                                            </h3>
                                            <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-slate-500">
                                                <span class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded">
                                                    #{{ booking.booking_number }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <CalendarDays class="h-3.5 w-3.5" />
                                                    {{ formatDate(booking.created_at) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <!-- Status Badge -->
                                            <span
                                                :class="[
                                                    getStatusConfig(booking.status).bg,
                                                    getStatusConfig(booking.status).text,
                                                    getStatusConfig(booking.status).border,
                                                    'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-medium capitalize'
                                                ]"
                                            >
                                                <span :class="[getStatusConfig(booking.status).dot, 'h-1.5 w-1.5 rounded-full']"></span>
                                                {{ booking.status }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Price & Action -->
                                    <div class="mt-3 flex items-center justify-between">
                                        <p class="text-lg font-bold text-slate-900">
                                            {{ formatAmount(booking.total_amount) }}
                                        </p>
                                        <span class="inline-flex items-center gap-1 text-sm font-medium text-amber-600 opacity-0 transition-opacity group-hover:opacity-100">
                                            View details
                                            <ChevronRight class="h-4 w-4" />
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Empty State -->
            <div v-else class="px-6 py-16 sm:px-8 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
                    <CalendarDays class="h-10 w-10 text-slate-400" />
                </div>
                <h3 class="mt-4 text-base font-semibold text-slate-900">No bookings yet</h3>
                <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">
                    Start your spiritual journey today by exploring our carefully curated Hajj and Umrah packages.
                </p>
                <div class="mt-6">
                    <Link
                        href="/hajj-umrah"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition duration-200 hover:from-amber-600 hover:to-amber-700 hover:shadow-xl hover:-translate-y-0.5"
                    >
                        <Plane class="h-4 w-4" />
                        Browse Packages
                    </Link>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid gap-4 sm:grid-cols-3">
            <Link
                href="/hajj-umrah"
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 text-white shadow-lg transition duration-300 hover:shadow-xl hover:-translate-y-1"
            >
                <div class="absolute inset-0 bg-black/0 transition-colors group-hover:bg-black/5"></div>
                <div class="relative">
                    <Plane class="h-8 w-8 mb-3" />
                    <h3 class="text-lg font-semibold">Book New Package</h3>
                    <p class="mt-1 text-sm text-amber-100">Explore our Hajj & Umrah packages</p>
                </div>
            </Link>

            <Link
                href="/user/bookings"
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-6 text-white shadow-lg transition duration-300 hover:shadow-xl hover:-translate-y-1"
            >
                <div class="absolute inset-0 bg-black/0 transition-colors group-hover:bg-black/5"></div>
                <div class="relative">
                    <CalendarDays class="h-8 w-8 mb-3" />
                    <h3 class="text-lg font-semibold">View All Bookings</h3>
                    <p class="mt-1 text-sm text-blue-100">Track and manage your reservations</p>
                </div>
            </Link>

            <Link
                href="/user/profile"
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-6 text-white shadow-lg transition duration-300 hover:shadow-xl hover:-translate-y-1"
            >
                <div class="absolute inset-0 bg-black/0 transition-colors group-hover:bg-black/5"></div>
                <div class="relative">
                    <Sparkles class="h-8 w-8 mb-3" />
                    <h3 class="text-lg font-semibold">Update Profile</h3>
                    <p class="mt-1 text-sm text-emerald-100">Keep your travel details up to date</p>
                </div>
            </Link>
        </div>
    </div>
</template>
