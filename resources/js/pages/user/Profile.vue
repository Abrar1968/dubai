<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import {
    User,
    Mail,
    Phone,
    MapPin,
    Globe,
    Calendar,
    FileText,
    Shield,
    Lock,
    Eye,
    EyeOff,
    CheckCircle2,
    AlertCircle,
    Loader2,
    Save
} from 'lucide-vue-next'
import UserLayout from '@/layouts/UserLayout.vue'

defineOptions({ layout: UserLayout })

interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    address?: string;
    city?: string;
    country?: string;
    date_of_birth?: string;
    nationality?: string;
    passport_number?: string;
    passport_expiry?: string;
    profile_photo_url?: string;
}

const props = withDefaults(defineProps<{
    user?: User;
}>(), {
    user: undefined,
});

const page = usePage();

const form = ref({
    name: props.user?.name || '',
    email: props.user?.email || '',
    phone: props.user?.phone || '',
    address: props.user?.address || '',
    city: props.user?.city || '',
    country: props.user?.country || '',
    date_of_birth: props.user?.date_of_birth || '',
    nationality: props.user?.nationality || '',
    passport_number: props.user?.passport_number || '',
    passport_expiry: props.user?.passport_expiry || '',
});

const passwordForm = ref({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const errors = ref<Record<string, string>>({});
const passwordErrors = ref<Record<string, string>>({});
const isSubmitting = ref(false);
const isPasswordSubmitting = ref(false);
const successMessage = ref('');
const passwordSuccessMessage = ref('');

const displayUser = computed(() => {
    return props.user || {
        id: 0,
        name: page.props.auth?.user?.name || 'User',
        email: page.props.auth?.user?.email || '',
    };
});

const userInitials = computed(() => {
    const name = displayUser.value.name || 'U';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
});

const validateForm = () => {
    errors.value = {};

    if (!form.value.name.trim()) {
        errors.value.name = 'Name is required';
    }

    if (!form.value.email.trim()) {
        errors.value.email = 'Email is required';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
        errors.value.email = 'Please enter a valid email address';
    }

    if (form.value.phone && !/^[\d\s\-\+\(\)]+$/.test(form.value.phone)) {
        errors.value.phone = 'Please enter a valid phone number';
    }

    if (form.value.passport_expiry) {
        const expiryDate = new Date(form.value.passport_expiry);
        if (expiryDate < new Date()) {
            errors.value.passport_expiry = 'Passport has expired';
        }
    }

    return Object.keys(errors.value).length === 0;
};

const validatePasswordForm = () => {
    passwordErrors.value = {};

    if (!passwordForm.value.current_password) {
        passwordErrors.value.current_password = 'Current password is required';
    }

    if (!passwordForm.value.password) {
        passwordErrors.value.password = 'New password is required';
    } else if (passwordForm.value.password.length < 8) {
        passwordErrors.value.password = 'Password must be at least 8 characters';
    }

    if (passwordForm.value.password !== passwordForm.value.password_confirmation) {
        passwordErrors.value.password_confirmation = 'Passwords do not match';
    }

    return Object.keys(passwordErrors.value).length === 0;
};

const submitProfile = () => {
    if (!validateForm()) return;

    isSubmitting.value = true;
    successMessage.value = '';

    router.put('/user/profile', form.value, {
        preserveScroll: true,
        onSuccess: () => {
            successMessage.value = 'Profile updated successfully!';
            setTimeout(() => {
                successMessage.value = '';
            }, 5000);
        },
        onError: (errs) => {
            errors.value = errs;
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
};

const submitPassword = () => {
    if (!validatePasswordForm()) return;

    isPasswordSubmitting.value = true;
    passwordSuccessMessage.value = '';

    router.put('/user/password', passwordForm.value, {
        preserveScroll: true,
        onSuccess: () => {
            passwordSuccessMessage.value = 'Password updated successfully!';
            passwordForm.value = {
                current_password: '',
                password: '',
                password_confirmation: '',
            };
            setTimeout(() => {
                passwordSuccessMessage.value = '';
            }, 5000);
        },
        onError: (errs) => {
            passwordErrors.value = errs;
        },
        onFinish: () => {
            isPasswordSubmitting.value = false;
        },
    });
};
</script>

<template>
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white text-2xl font-bold shadow-lg shadow-amber-500/25">
                {{ userInitials }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Profile Settings</h1>
                <p class="mt-1 text-slate-500">Manage your personal information, travel documents, and account security</p>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
            <div class="border-b border-slate-100 px-6 py-5 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <User class="h-5 w-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Personal Information</h2>
                        <p class="text-sm text-slate-500">Update your personal details and contact information</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submitProfile" class="p-6">
                <!-- Success Message -->
                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="transform -translate-y-2 opacity-0"
                    enter-to-class="transform translate-y-0 opacity-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="transform translate-y-0 opacity-100"
                    leave-to-class="transform -translate-y-2 opacity-0"
                >
                    <div v-if="successMessage" class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700">
                        <CheckCircle2 class="h-5 w-5 flex-shrink-0" />
                        <span class="font-medium">{{ successMessage }}</span>
                    </div>
                </Transition>

                <div class="grid gap-6 sm:grid-cols-2">
                    <!-- Name -->
                    <div class="group">
                        <label for="name" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            <User class="h-4 w-4 text-slate-400" />
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            placeholder="Enter your full name"
                            class="block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400"
                            :class="errors.name
                                ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                        />
                        <p v-if="errors.name" class="mt-2 flex items-center gap-1 text-sm text-red-600">
                            <AlertCircle class="h-4 w-4" />
                            {{ errors.name }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            <Mail class="h-4 w-4 text-slate-400" />
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            v-model="form.email"
                            placeholder="your@email.com"
                            class="block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400"
                            :class="errors.email
                                ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                        />
                        <p v-if="errors.email" class="mt-2 flex items-center gap-1 text-sm text-red-600">
                            <AlertCircle class="h-4 w-4" />
                            {{ errors.email }}
                        </p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            <Phone class="h-4 w-4 text-slate-400" />
                            Phone Number
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            v-model="form.phone"
                            placeholder="+1 (555) 123-4567"
                            class="block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400"
                            :class="errors.phone
                                ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                        />
                        <p v-if="errors.phone" class="mt-2 flex items-center gap-1 text-sm text-red-600">
                            <AlertCircle class="h-4 w-4" />
                            {{ errors.phone }}
                        </p>
                    </div>

                    <!-- Nationality -->
                    <div>
                        <label for="nationality" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            <Globe class="h-4 w-4 text-slate-400" />
                            Nationality
                        </label>
                        <input
                            type="text"
                            id="nationality"
                            v-model="form.nationality"
                            placeholder="e.g. American, British"
                            class="block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400"
                            :class="errors.nationality
                                ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                        />
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            <Calendar class="h-4 w-4 text-slate-400" />
                            Date of Birth
                        </label>
                        <input
                            type="date"
                            id="date_of_birth"
                            v-model="form.date_of_birth"
                            class="block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2"
                            :class="errors.date_of_birth
                                ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                        />
                    </div>

                    <!-- Address -->
                    <div class="sm:col-span-2">
                        <label for="address" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            <MapPin class="h-4 w-4 text-slate-400" />
                            Address
                        </label>
                        <input
                            type="text"
                            id="address"
                            v-model="form.address"
                            placeholder="123 Main Street, Apt 4B"
                            class="block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400"
                            :class="errors.address
                                ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                        />
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            City
                        </label>
                        <input
                            type="text"
                            id="city"
                            v-model="form.city"
                            placeholder="New York"
                            class="block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400"
                            :class="errors.city
                                ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                        />
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            Country
                        </label>
                        <input
                            type="text"
                            id="country"
                            v-model="form.country"
                            placeholder="United States"
                            class="block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400"
                            :class="errors.country
                                ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                        />
                    </div>
                </div>

                <!-- Travel Documents Section -->
                <div class="mt-8 pt-8 border-t border-slate-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                            <FileText class="h-5 w-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Travel Documents</h3>
                            <p class="text-sm text-slate-500">Required for Hajj & Umrah bookings</p>
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <!-- Passport Number -->
                        <div>
                            <label for="passport_number" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                                Passport Number
                            </label>
                            <input
                                type="text"
                                id="passport_number"
                                v-model="form.passport_number"
                                placeholder="AB1234567"
                                class="block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400 font-mono"
                                :class="errors.passport_number
                                    ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                    : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                            />
                            <p v-if="errors.passport_number" class="mt-2 flex items-center gap-1 text-sm text-red-600">
                                <AlertCircle class="h-4 w-4" />
                                {{ errors.passport_number }}
                            </p>
                        </div>

                        <!-- Passport Expiry -->
                        <div>
                            <label for="passport_expiry" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                                Passport Expiry Date
                            </label>
                            <input
                                type="date"
                                id="passport_expiry"
                                v-model="form.passport_expiry"
                                class="block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2"
                                :class="errors.passport_expiry
                                    ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                    : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                            />
                            <p v-if="errors.passport_expiry" class="mt-2 flex items-center gap-1 text-sm text-red-600">
                                <AlertCircle class="h-4 w-4" />
                                {{ errors.passport_expiry }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition duration-200 hover:from-amber-600 hover:to-amber-700 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <Loader2 v-if="isSubmitting" class="h-4 w-4 animate-spin" />
                        <Save v-else class="h-4 w-4" />
                        {{ isSubmitting ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60">
            <div class="border-b border-slate-100 px-6 py-5 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                        <Shield class="h-5 w-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Account Security</h2>
                        <p class="text-sm text-slate-500">Update your password to keep your account secure</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submitPassword" class="p-6">
                <!-- Success Message -->
                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="transform -translate-y-2 opacity-0"
                    enter-to-class="transform translate-y-0 opacity-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="transform translate-y-0 opacity-100"
                    leave-to-class="transform -translate-y-2 opacity-0"
                >
                    <div v-if="passwordSuccessMessage" class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700">
                        <CheckCircle2 class="h-5 w-5 flex-shrink-0" />
                        <span class="font-medium">{{ passwordSuccessMessage }}</span>
                    </div>
                </Transition>

                <div class="grid gap-6 sm:grid-cols-3">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            <Lock class="h-4 w-4 text-slate-400" />
                            Current Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="showCurrentPassword ? 'text' : 'password'"
                                id="current_password"
                                v-model="passwordForm.current_password"
                                placeholder="••••••••"
                                class="block w-full rounded-xl border px-4 py-3 pr-12 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400"
                                :class="passwordErrors.current_password
                                    ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                    : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                            />
                            <button
                                type="button"
                                @click="showCurrentPassword = !showCurrentPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                            >
                                <EyeOff v-if="showCurrentPassword" class="h-5 w-5" />
                                <Eye v-else class="h-5 w-5" />
                            </button>
                        </div>
                        <p v-if="passwordErrors.current_password" class="mt-2 flex items-center gap-1 text-sm text-red-600">
                            <AlertCircle class="h-4 w-4" />
                            {{ passwordErrors.current_password }}
                        </p>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="showNewPassword ? 'text' : 'password'"
                                id="password"
                                v-model="passwordForm.password"
                                placeholder="••••••••"
                                class="block w-full rounded-xl border px-4 py-3 pr-12 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400"
                                :class="passwordErrors.password
                                    ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                    : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                            />
                            <button
                                type="button"
                                @click="showNewPassword = !showNewPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                            >
                                <EyeOff v-if="showNewPassword" class="h-5 w-5" />
                                <Eye v-else class="h-5 w-5" />
                            </button>
                        </div>
                        <p v-if="passwordErrors.password" class="mt-2 flex items-center gap-1 text-sm text-red-600">
                            <AlertCircle class="h-4 w-4" />
                            {{ passwordErrors.password }}
                        </p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="showConfirmPassword ? 'text' : 'password'"
                                id="password_confirmation"
                                v-model="passwordForm.password_confirmation"
                                placeholder="••••••••"
                                class="block w-full rounded-xl border px-4 py-3 pr-12 text-sm shadow-sm transition duration-200 focus:outline-none focus:ring-2 placeholder:text-slate-400"
                                :class="passwordErrors.password_confirmation
                                    ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                                    : 'border-slate-200 focus:border-amber-500 focus:ring-amber-500/20'"
                            />
                            <button
                                type="button"
                                @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                            >
                                <EyeOff v-if="showConfirmPassword" class="h-5 w-5" />
                                <Eye v-else class="h-5 w-5" />
                            </button>
                        </div>
                        <p v-if="passwordErrors.password_confirmation" class="mt-2 flex items-center gap-1 text-sm text-red-600">
                            <AlertCircle class="h-4 w-4" />
                            {{ passwordErrors.password_confirmation }}
                        </p>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="mt-4 rounded-xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-600">
                        <strong>Password requirements:</strong> Minimum 8 characters
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button
                        type="submit"
                        :disabled="isPasswordSubmitting"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-purple-500/25 transition duration-200 hover:from-purple-600 hover:to-purple-700 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <Loader2 v-if="isPasswordSubmitting" class="h-4 w-4 animate-spin" />
                        <Lock v-else class="h-4 w-4" />
                        {{ isPasswordSubmitting ? 'Updating...' : 'Update Password' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
