<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'

defineOptions({ layout: UserLayout })

interface Package {
    id: number;
    title: string;
    slug: string;
}

interface Booking {
    id: number;
    booking_number: string;
    package?: Package;
    status: string;
    created_at: string;
    total_amount: number;
    traveler_count: number;
}

const props = withDefaults(defineProps<{
    bookings?: Booking[];
}>(), {
    bookings: () => [],
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
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Bookings</h1>
                <p class="mt-1 text-sm text-gray-500">View and manage your travel bookings.</p>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
            <table v-if="bookings.length > 0" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Booking
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Package
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Travelers
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Date
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="booking in bookings" :key="booking.id" class="hover:bg-gray-50">
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                #{{ booking.booking_number }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ booking.package?.title || 'N/A' }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-500">
                                {{ booking.traveler_count }} traveler(s)
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ formatAmount(booking.total_amount) }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span :class="[getStatusColor(booking.status), 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize']">
                                {{ booking.status }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                            {{ formatDate(booking.created_at) }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                            <Link :href="`/user/bookings/${booking.id}`" class="text-amber-600 hover:text-amber-900">
                                View
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>

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
