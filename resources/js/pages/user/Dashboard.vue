<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'

defineOptions({ layout: UserLayout })

interface Booking {
    id: number;
    booking_number: string;
    package?: {
        title: string;
    };
    status: string;
    created_at: string;
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

const getStatusColor = (status: string) => {
    switch (status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'confirmed': return 'bg-green-100 text-green-800';
        case 'completed': return 'bg-blue-100 text-blue-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
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
    return `$${amount.toLocaleString()}`;
};
</script>

<template>
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="overflow-hidden rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 p-6 text-white shadow-lg">
            <h1 class="text-2xl font-bold">Welcome back, {{ props.user?.name || 'User' }}!</h1>
            <p class="mt-1 text-amber-100">Track your bookings and manage your profile.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-lg bg-blue-100 p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.total_bookings }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-lg bg-yellow-100 p-3">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.pending_bookings }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-lg bg-green-100 p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Confirmed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.confirmed_bookings }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-lg bg-purple-100 p-3">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.completed_bookings }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Bookings</h2>
                    <Link href="/user/bookings" class="text-sm font-medium text-amber-600 hover:text-amber-500">
                        View all →
                    </Link>
                </div>
            </div>

            <ul v-if="recentBookings.length > 0" class="divide-y divide-gray-200">
                <li v-for="booking in recentBookings" :key="booking.id" class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ booking.package?.title || 'Package' }}</p>
                            <p class="text-sm text-gray-500">
                                Booking #{{ booking.booking_number }} • {{ formatDate(booking.created_at) }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span :class="[getStatusColor(booking.status), 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize']">
                                {{ booking.status }}
                            </span>
                            <Link :href="`/user/bookings/${booking.id}`" class="text-amber-600 hover:text-amber-500">
                                View →
                            </Link>
                        </div>
                    </div>
                </li>
            </ul>

            <div v-else class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by browsing our packages.</p>
                <div class="mt-6">
                    <Link href="/hajj-umrah" class="inline-flex items-center rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                        Browse Packages
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
