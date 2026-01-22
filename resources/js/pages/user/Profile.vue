<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
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
    <div class="space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your personal information and account settings.</p>
        </div>

        <!-- Profile Information -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Personal Information</h2>
                <p class="mt-1 text-sm text-gray-500">Update your personal details and travel documents.</p>
            </div>
            
            <form @submit.prevent="submitProfile" class="p-6">
                <!-- Success Message -->
                <div v-if="successMessage" class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-800">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ successMessage }}
                    </div>
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', errors.name ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                        <input
                            type="email"
                            id="email"
                            v-model="form.email"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', errors.email ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input
                            type="tel"
                            id="phone"
                            v-model="form.phone"
                            placeholder="+1 (555) 123-4567"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', errors.phone ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
                    </div>

                    <!-- Nationality -->
                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality</label>
                        <input
                            type="text"
                            id="nationality"
                            v-model="form.nationality"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', errors.nationality ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="errors.nationality" class="mt-1 text-sm text-red-600">{{ errors.nationality }}</p>
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input
                            type="date"
                            id="date_of_birth"
                            v-model="form.date_of_birth"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', errors.date_of_birth ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="errors.date_of_birth" class="mt-1 text-sm text-red-600">{{ errors.date_of_birth }}</p>
                    </div>

                    <!-- Address -->
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input
                            type="text"
                            id="address"
                            v-model="form.address"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', errors.address ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="errors.address" class="mt-1 text-sm text-red-600">{{ errors.address }}</p>
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input
                            type="text"
                            id="city"
                            v-model="form.city"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', errors.city ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="errors.city" class="mt-1 text-sm text-red-600">{{ errors.city }}</p>
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                        <input
                            type="text"
                            id="country"
                            v-model="form.country"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', errors.country ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="errors.country" class="mt-1 text-sm text-red-600">{{ errors.country }}</p>
                    </div>
                </div>

                <!-- Travel Documents Section -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Travel Documents</h3>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <!-- Passport Number -->
                        <div>
                            <label for="passport_number" class="block text-sm font-medium text-gray-700">Passport Number</label>
                            <input
                                type="text"
                                id="passport_number"
                                v-model="form.passport_number"
                                :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', errors.passport_number ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                            />
                            <p v-if="errors.passport_number" class="mt-1 text-sm text-red-600">{{ errors.passport_number }}</p>
                        </div>

                        <!-- Passport Expiry -->
                        <div>
                            <label for="passport_expiry" class="block text-sm font-medium text-gray-700">Passport Expiry Date</label>
                            <input
                                type="date"
                                id="passport_expiry"
                                v-model="form.passport_expiry"
                                :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', errors.passport_expiry ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                            />
                            <p v-if="errors.passport_expiry" class="mt-1 text-sm text-red-600">{{ errors.passport_expiry }}</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <svg v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        {{ isSubmitting ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Change Password</h2>
                <p class="mt-1 text-sm text-gray-500">Ensure your account is using a secure password.</p>
            </div>
            
            <form @submit.prevent="submitPassword" class="p-6">
                <!-- Success Message -->
                <div v-if="passwordSuccessMessage" class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-800">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ passwordSuccessMessage }}
                    </div>
                </div>

                <div class="grid gap-6 sm:grid-cols-3">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password *</label>
                        <input
                            type="password"
                            id="current_password"
                            v-model="passwordForm.current_password"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', passwordErrors.current_password ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="passwordErrors.current_password" class="mt-1 text-sm text-red-600">{{ passwordErrors.current_password }}</p>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password *</label>
                        <input
                            type="password"
                            id="password"
                            v-model="passwordForm.password"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', passwordErrors.password ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="passwordErrors.password" class="mt-1 text-sm text-red-600">{{ passwordErrors.password }}</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            v-model="passwordForm.password_confirmation"
                            :class="['mt-1 block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2', passwordErrors.password_confirmation ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-500']"
                        />
                        <p v-if="passwordErrors.password_confirmation" class="mt-1 text-sm text-red-600">{{ passwordErrors.password_confirmation }}</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button
                        type="submit"
                        :disabled="isPasswordSubmitting"
                        class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <svg v-if="isPasswordSubmitting" class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        {{ isPasswordSubmitting ? 'Updating...' : 'Update Password' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
