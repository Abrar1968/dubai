<template>
    <div class="bg-[#f8fafc] min-h-screen pb-16">
        <!-- BOOKING MODAL -->
        <div v-if="showBookingModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeBookingModal"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
                <div class="relative inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:align-middle">
                    <form @submit.prevent="submitBooking">
                        <div class="bg-white px-6 pt-5 pb-4">
                            <div class="flex items-start justify-between border-b pb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Book {{ packageData.title }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">Fill in traveler details to proceed with booking</p>
                                </div>
                                <button type="button" @click="closeBookingModal" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-4 space-y-4">
                                <!-- Package Summary -->
                                <div class="rounded-lg bg-orange-50 p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ packageData.title }}</p>
                                            <p class="text-sm text-gray-600">{{ packageData.duration_days }} Days • {{ packageData.type }}</p>
                                        </div>
                                        <p class="text-lg font-bold text-orange-600">${{ (packageData.price || 0).toLocaleString() }}/person</p>
                                    </div>
                                </div>

                                <!-- Departure Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Preferred Departure Date *</label>
                                    <input type="date" v-model="bookingForm.departure_date" required
                                        :min="minDate"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500"/>
                                </div>

                                <!-- Number of Travelers -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Number of Travelers *</label>
                                    <select v-model="bookingForm.traveler_count" @change="updateTravelers" required
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                        <option v-for="n in 10" :key="n" :value="n">{{ n }} traveler{{ n > 1 ? 's' : '' }}</option>
                                    </select>
                                </div>

                                <!-- Traveler Details -->
                                <div class="space-y-4">
                                    <h4 class="font-medium text-gray-900">Traveler Details</h4>
                                    <div v-for="(traveler, index) in bookingForm.travelers" :key="index"
                                        class="rounded-lg border border-gray-200 p-4">
                                        <h5 class="mb-3 font-medium text-gray-900">
                                            Traveler {{ index + 1 }} {{ index === 0 ? '(Primary Contact)' : '' }}
                                        </h5>
                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600">Full Name *</label>
                                                <input type="text" v-model="traveler.name" required
                                                    placeholder="As per passport"
                                                    class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none"/>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600">Passport Number</label>
                                                <input type="text" v-model="traveler.passport_number"
                                                    class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none"/>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600">Date of Birth</label>
                                                <input type="date" v-model="traveler.date_of_birth"
                                                    class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none"/>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600">Gender</label>
                                                <select v-model="traveler.gender"
                                                    class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-orange-500 focus:outline-none">
                                                    <option value="">Select</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Special Requests -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Special Requests</label>
                                    <textarea v-model="bookingForm.special_requests" rows="2"
                                        placeholder="Any dietary requirements, mobility assistance, etc."
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500"></textarea>
                                </div>

                                <!-- Total -->
                                <div class="rounded-lg bg-gray-50 p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-700">Estimated Total:</span>
                                        <span class="text-2xl font-bold text-orange-600">${{ estimatedTotal.toLocaleString() }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Final price will be confirmed by our team</p>
                                </div>

                                <!-- Error Message -->
                                <div v-if="bookingError" class="rounded-lg bg-red-50 p-3 text-sm text-red-600">
                                    {{ bookingError }}
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                            <button type="button" @click="closeBookingModal"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" :disabled="isSubmitting"
                                class="rounded-lg bg-orange-600 px-6 py-2 text-sm font-medium text-white hover:bg-orange-700 disabled:opacity-50">
                                {{ isSubmitting ? 'Submitting...' : 'Submit Booking' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- HERO HEADER -->
        <div class="relative h-[400px] w-full overflow-hidden">
            <img :src="packageData.image || '/assets/img/hajj/hajjbg.jpg'" :alt="packageData.title"
                @error="handleImageError"
                class="absolute inset-0 h-full w-full object-cover" />
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-4 max-w-7xl mx-auto">
                <span class="px-3 py-1 bg-orange-600 text-white text-xs font-bold rounded-full uppercase tracking-wider mb-4">
                    {{ packageData.type }} Package
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight mb-4">
                    {{ packageData.title }}
                </h1>
                <div class="flex items-center gap-6 text-white/90 text-sm md:text-base font-medium">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ packageData.duration_days }} Days
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Makkah & Madinah
                    </span>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- LEFT COLUMN: CONTENT -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Overview Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                        <h2 class="text-2xl font-bold text-slate-900 mb-4">Overview</h2>
                        <div class="prose prose-slate max-w-none text-slate-600" v-html="packageData.description || 'No description available.'"></div>

                        <!-- Features Grid -->
                        <div v-if="packageData.features && packageData.features.length > 0" class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div v-for="(feature, idx) in packageData.features" :key="idx" class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-sm font-medium text-slate-700">{{ feature }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Itinerary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Itinerary</h2>
                        <div class="space-y-6">
                            <div v-for="(day, idx) in itineraryList" :key="idx" class="relative pl-8 border-l-2 border-slate-200 last:border-0 pb-6 last:pb-0">
                                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-orange-500 ring-4 ring-white"></div>
                                <h3 class="text-lg font-bold text-slate-900 leading-none mb-2">{{ day.title || `Day ${idx + 1}` }}</h3>
                                <p class="text-slate-600">{{ day.description || 'Activity details...' }}</p>
                            </div>
                            <div v-if="itineraryList.length === 0" class="text-slate-500 italic">No itinerary details available.</div>
                        </div>
                    </div>

                    <!-- Inclusions & Exclusions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Inclusions -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 h-full">
                            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Included
                            </h3>
                            <ul class="space-y-3">
                                <li v-for="(item, i) in inclusionList" :key="i" class="flex items-start gap-2 text-sm text-slate-600">
                                    <span class="text-green-500 mt-0.5">✓</span> {{ item }}
                                </li>
                                <li v-if="inclusionList.length === 0" class="text-slate-400 italic text-sm">Not specified</li>
                            </ul>
                        </div>

                        <!-- Exclusions -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 h-full">
                            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Not Included
                            </h3>
                            <ul class="space-y-3">
                                <li v-for="(item, i) in exclusionList" :key="i" class="flex items-start gap-2 text-sm text-slate-600">
                                    <span class="text-red-400 mt-0.5">✕</span> {{ item }}
                                </li>
                                <li v-if="exclusionList.length === 0" class="text-slate-400 italic text-sm">Not specified</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Package Gallery -->
                    <div v-if="galleryList.length > 0" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Package Gallery</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div v-for="(img, idx) in galleryList" :key="idx" class="relative group overflow-hidden rounded-lg aspect-square">
                                <img :src="img.url" :alt="img.alt"
                                    @error="handleImageError"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: BOOKING CARD -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 bg-white rounded-2xl shadow-lg border border-slate-100 p-6 overflow-hidden">
                        <div class="text-center pb-6 border-b border-slate-100 mb-6">
                            <p class="text-sm text-slate-500 uppercase font-semibold tracking-wider">Starting From</p>
                            <div class="mt-2 flex items-baseline justify-center gap-1">
                                <span class="text-4xl font-extrabold text-slate-900">${{ (packageData.price || 0).toLocaleString() }}</span>
                                <span class="text-slate-500 font-medium text-lg">/ person</span>
                            </div>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                                <span class="text-slate-500">Duration</span>
                                <span class="font-semibold text-slate-900">{{ packageData.duration_days }} Days</span>
                            </div>
                            <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                                <span class="text-slate-500">Type</span>
                                <span class="font-semibold text-slate-900 capitalize">{{ packageData.type }}</span>
                            </div>
                            <!-- Mock Dates -->
                             <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                                <span class="text-slate-500">Next Departure</span>
                                <span class="font-semibold text-green-600">Checking...</span>
                            </div>
                        </div>

                        <button @click="handleBookClick" class="w-full py-4 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl transition shadow-lg hover:shadow-orange-500/25 flex items-center justify-center gap-2">
                            Book This Package
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>

                        <p class="mt-4 text-xs text-center text-slate-400">
                            Secure your spot today with a deposit.
                        </p>
                    </div>

                    <!-- Need Help Card -->
                    <div class="mt-8 bg-slate-900 rounded-2xl p-6 text-center text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-lg font-bold mb-2">Need Custom Plan?</h3>
                            <p class="text-slate-300 text-sm mb-4">We can customize a spiritual journey just for you and your family.</p>
                            <Link href="/contactus" class="inline-block px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-semibold transition">
                                Contact Expert
                            </Link>
                        </div>
                         <!-- Decor -->
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-orange-500/20 rounded-full blur-2xl"></div>
                    </div>
                </div>

            </div>
        </div>

        <!-- RELATED PACKAGES -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
             <h2 class="text-2xl font-bold text-slate-900 mb-8 border-b border-slate-200 pb-4">You May Also Like</h2>
             <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <Link v-for="related in relatedPackagesList" :key="related.id" :href="`/packages/${related.slug}`"
                    class="group block bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-100 overflow-hidden">
                    <div class="h-48 overflow-hidden relative">
                         <img :src="related.image || '/assets/img/hajj/hajjbg.jpg'" :alt="related.title"
                                @error="handleImageError"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                         <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur px-2 py-1 rounded-md text-xs font-bold shadow-sm">
                            ${{ (related.price || 0).toLocaleString() }}
                         </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-slate-900 group-hover:text-orange-600 transition">{{ related.title }}</h3>
                        <p class="text-xs text-slate-500 mt-1 uppercase tracking-wide">{{ related.type }}</p>
                    </div>
                </Link>
             </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import HajjUmrahLayout from '@/layouts/HajjUmrahLayout.vue';

defineOptions({ layout: HajjUmrahLayout });

interface GalleryImage {
    url: string;
    alt: string;
}

interface Package {
    id: number;
    title: string;
    slug: string;
    type: string;
    price: number;
    currency: string;
    duration_days: number;
    image: string | null;
    description: string;
    features: string[];
    inclusions: string[];
    exclusions: string[];
    itinerary: any[];
    hotel_details: any;
    departure_dates: any[];
    max_capacity: number;
    gallery?: GalleryImage[];
}

interface Traveler {
    name: string;
    passport_number: string;
    date_of_birth: string;
    nationality: string;
    gender: string;
}

const props = defineProps<{
    package: Package;
    relatedPackages: any[];
}>();

const page = usePage();
const auth = computed(() => page.props.auth as { user?: { id: number; name: string; email: string } } | undefined);

// Booking Modal State
const showBookingModal = ref(false);
const isSubmitting = ref(false);
const bookingError = ref('');

const bookingForm = ref({
    package_id: props.package.id,
    departure_date: '',
    traveler_count: 1,
    special_requests: '',
    travelers: [{ name: '', passport_number: '', date_of_birth: '', nationality: '', gender: '' }] as Traveler[],
});

// Compute minimum date (tomorrow)
const minDate = computed(() => {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    return tomorrow.toISOString().split('T')[0];
});

// Compute estimated total
const estimatedTotal = computed(() => {
    return (props.package.price || 0) * bookingForm.value.traveler_count;
});

// Update travelers array when count changes
const updateTravelers = () => {
    const count = bookingForm.value.traveler_count;
    const current = bookingForm.value.travelers;

    if (count > current.length) {
        // Add more travelers
        for (let i = current.length; i < count; i++) {
            current.push({ name: '', passport_number: '', date_of_birth: '', nationality: '', gender: '' });
        }
    } else if (count < current.length) {
        // Remove excess travelers
        bookingForm.value.travelers = current.slice(0, count);
    }
};

// Handle book button click
const handleBookClick = () => {
    if (!auth.value?.user) {
        // Not logged in - redirect to login with return URL
        router.visit('/login', {
            data: { redirect: `/packages/${props.package.slug}` },
        });
        return;
    }

    // User is logged in - show booking modal
    showBookingModal.value = true;
};

// Close booking modal
const closeBookingModal = () => {
    showBookingModal.value = false;
    bookingError.value = '';
};

// Submit booking
const submitBooking = () => {
    bookingError.value = '';

    // Validate
    if (!bookingForm.value.departure_date) {
        bookingError.value = 'Please select a departure date';
        return;
    }

    const hasEmptyNames = bookingForm.value.travelers.some(t => !t.name.trim());
    if (hasEmptyNames) {
        bookingError.value = 'Please fill in all traveler names';
        return;
    }

    isSubmitting.value = true;

    router.post('/user/bookings', {
        package_id: props.package.id,
        departure_date: bookingForm.value.departure_date,
        traveler_count: bookingForm.value.traveler_count,
        special_requests: bookingForm.value.special_requests,
        travelers: bookingForm.value.travelers,
    }, {
        onSuccess: () => {
            closeBookingModal();
        },
        onError: (errors) => {
            bookingError.value = Object.values(errors)[0] as string || 'Failed to submit booking. Please try again.';
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
};

// Compute properties to react to prop changes
const packageData = computed(() => props.package);
const relatedPackagesList = computed(() => props.relatedPackages || []);

// Helpers for list rendering
const itineraryList = computed(() => {
    // If itinerary is an array of objects
    if (Array.isArray(packageData.value.itinerary) && packageData.value.itinerary.length > 0) {
        return packageData.value.itinerary;
    }
    // Return empty array - no mock data
    return [];
});

const inclusionList = computed(() => packageData.value.inclusions || []);
const exclusionList = computed(() => packageData.value.exclusions || []);
const galleryList = computed(() => packageData.value.gallery || []);

const handleImageError = (event: Event) => {
    const target = event.target as HTMLImageElement;
    target.src = '/assets/img/hajj/hajjbg.jpg';
};
</script>
