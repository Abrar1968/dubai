<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import {
    CalendarDays,
    Clock,
    CheckCircle2,
    Sparkles,
    AlertCircle,
    Package,
    Users,
    Search,
    Filter,
    ChevronRight,
    Plane,
    ArrowUpDown
} from 'lucide-vue-next'
import UserLayout from '@/layouts/UserLayout.vue'
import LazyImage from '@/components/ui/LazyImage.vue'

defineOptions({ layout: UserLayout })

interface Package {
    id: number;
    title: string;
    slug: string;
    type?: string;
    image?: string;
}

interface Booking {
    id: number;
    booking_number: string;
    package?: Package;
    status: string;
    created_at: string;
    travel_date?: string;
    total_amount: number;
    traveler_count: number;
}

const props = withDefaults(defineProps<{
    bookings?: Booking[];
}>(), {
    bookings: () => [],
});

const searchQuery = ref('');
const selectedStatus = ref('all');

const statusOptions = [
    { value: 'all', label: 'All Status' },
    { value: 'pending', label: 'Pending' },
    { value: 'confirmed', label: 'Confirmed' },
    { value: 'completed', label: 'Completed' },
    { value: 'cancelled', label: 'Cancelled' },
];

const filteredBookings = computed(() => {
    return props.bookings.filter(booking => {
        const matchesSearch = searchQuery.value === '' ||
            booking.booking_number.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            booking.package?.title.toLowerCase().includes(searchQuery.value.toLowerCase());

        const matchesStatus = selectedStatus.value === 'all' || booking.status === selectedStatus.value;

        return matchesSearch && matchesStatus;
    });
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
</script>

<template>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">My Bookings</h1>
                <p class="mt-1 text-sm text-slate-500">View and manage all your travel reservations</p>
            </div>
            <Link
                href="/hajj-umrah"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition duration-200 hover:from-amber-600 hover:to-amber-700 hover:shadow-xl"
            >
                <Plane class="h-4 w-4" />
                New Booking
            </Link>
        </div>

        <!-- Filters -->
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="relative flex-1">
                <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search by booking number or package..."
                    class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20"
                />
            </div>

            <!-- Status Filter -->
            <div class="relative">
                <Filter class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <select
                    v-model="selectedStatus"
                    class="appearance-none rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-10 text-sm text-slate-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20"
                >
                    <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
                <ArrowUpDown class="absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 pointer-events-none" />
            </div>
        </div>

        <!-- Bookings Grid/List -->
        <div v-if="filteredBookings.length > 0" class="space-y-4">
            <div
                v-for="booking in filteredBookings"
                :key="booking.id"
                class="group relative overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60 transition duration-300 hover:shadow-lg hover:ring-slate-300"
            >
                <Link :href="`/user/bookings/${booking.id}`" class="block">
                    <div class="flex flex-col lg:flex-row">
                        <!-- Package Image -->
                        <div class="relative lg:w-56 h-48 lg:h-auto flex-shrink-0">
                            <div
                                v-if="booking.package?.image"
                                class="absolute inset-0"
                            >
                                <LazyImage
                                    :src="`/storage/${booking.package.image}`"
                                    :alt="booking.package?.title"
                                    fallback="/assets/img/hajj/hajjbg.jpg"
                                    img-class="transition duration-500 group-hover:scale-105"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent lg:bg-gradient-to-r z-10"></div>
                            </div>
                            <div
                                v-else
                                class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-amber-100 to-amber-50"
                            >
                                <Plane class="h-16 w-16 text-amber-400" />
                            </div>

                            <!-- Status Badge (Mobile) -->
                            <div class="absolute top-4 left-4 lg:hidden z-20">
                                <span
                                    :class="[
                                        getStatusConfig(booking.status).bg,
                                        getStatusConfig(booking.status).text,
                                        getStatusConfig(booking.status).border,
                                        'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold capitalize backdrop-blur-sm'
                                    ]"
                                >
                                    <span :class="[getStatusConfig(booking.status).dot, 'h-1.5 w-1.5 rounded-full']"></span>
                                    {{ booking.status }}
                                </span>
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="flex-1 p-5 lg:p-6">
                            <div class="flex flex-col h-full">
                                <!-- Header -->
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-mono text-slate-600">
                                                #{{ booking.booking_number }}
                                            </span>
                                            <!-- Status Badge (Desktop) -->
                                            <span
                                                :class="[
                                                    getStatusConfig(booking.status).bg,
                                                    getStatusConfig(booking.status).text,
                                                    getStatusConfig(booking.status).border,
                                                    'hidden lg:inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold capitalize'
                                                ]"
                                            >
                                                <span :class="[getStatusConfig(booking.status).dot, 'h-1.5 w-1.5 rounded-full']"></span>
                                                {{ booking.status }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-slate-900 group-hover:text-amber-600 transition-colors">
                                            {{ booking.package?.title || 'Hajj/Umrah Package' }}
                                        </h3>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-slate-900">
                                            {{ formatAmount(booking.total_amount) }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5">Total amount</p>
                                    </div>
                                </div>

                                <!-- Info Grid -->
                                <div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <CalendarDays class="h-4 w-4 text-slate-400" />
                                        Booked: {{ formatDate(booking.created_at) }}
                                    </span>
                                    <span v-if="booking.travel_date" class="flex items-center gap-1.5">
                                        <Plane class="h-4 w-4 text-slate-400" />
                                        Travel: {{ formatDate(booking.travel_date) }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <Users class="h-4 w-4 text-slate-400" />
                                        {{ booking.traveler_count }} traveler{{ booking.traveler_count !== 1 ? 's' : '' }}
                                    </span>
                                </div>

                                <!-- Action -->
                                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <span v-if="booking.package?.type" class="text-sm text-slate-500 capitalize">
                                        {{ booking.package.type }} Package
                                    </span>
                                    <span v-else class="text-sm text-slate-500">
                                        View booking details
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-amber-600 group-hover:gap-2 transition-all">
                                        View Details
                                        <ChevronRight class="h-4 w-4" />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
                    <CalendarDays class="h-10 w-10 text-slate-400" />
                </div>
                <h3 class="mt-4 text-base font-semibold text-slate-900">
                    {{ searchQuery || selectedStatus !== 'all' ? 'No bookings found' : 'No bookings yet' }}
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">
                    {{ searchQuery || selectedStatus !== 'all'
                        ? 'Try adjusting your search or filter criteria'
                        : 'Start your spiritual journey today by exploring our carefully curated Hajj and Umrah packages.'
                    }}
                </p>
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <button
                        v-if="searchQuery || selectedStatus !== 'all'"
                        @click="searchQuery = ''; selectedStatus = 'all'"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition duration-200 hover:bg-slate-50"
                    >
                        Clear Filters
                    </button>
                    <Link
                        href="/hajj-umrah"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition duration-200 hover:from-amber-600 hover:to-amber-700 hover:shadow-xl"
                    >
                        <Plane class="h-4 w-4" />
                        Browse Packages
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
