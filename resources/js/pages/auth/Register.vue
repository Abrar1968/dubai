<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Eye, EyeOff, Mail, Lock, User, UserPlus } from 'lucide-vue-next'
import HajjHeader from '@/components/hajj/hajjheader.vue'
import HajjFooter from '@/components/hajj/hajjfooter.vue'

defineProps<{
    settings?: Record<string, string>;
}>();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const submit = () => {
    form.post('/register', {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="Create Account" />

    <div class="min-h-screen flex flex-col bg-slate-50">
        <!-- Header -->
        <HajjHeader :settings="settings || {}" />

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center py-12 px-4">
            <div class="w-full max-w-md">
                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <!-- Header Section -->
                    <div class="bg-gradient-to-r from-teal-800 to-teal-900 px-8 py-10 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-full mb-4">
                            <UserPlus class="w-8 h-8 text-white" />
                        </div>
                        <h1 class="text-2xl font-bold text-white">Create Account</h1>
                        <p class="text-teal-100 mt-2">Join us to book your spiritual journey</p>
                    </div>

                    <!-- Form Section -->
                    <div class="p-8">
                        <form @submit.prevent="submit" class="space-y-5">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <User class="w-5 h-5 text-slate-400" />
                                    </div>
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        placeholder="Enter your full name"
                                        class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition placeholder:text-slate-400"
                                        :class="{ 'border-red-500': form.errors.name }"
                                    />
                                </div>
                                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Email Address
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <Mail class="w-5 h-5 text-slate-400" />
                                    </div>
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        required
                                        autocomplete="email"
                                        placeholder="Enter your email"
                                        class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition placeholder:text-slate-400"
                                        :class="{ 'border-red-500': form.errors.email }"
                                    />
                                </div>
                                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</p>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <Lock class="w-5 h-5 text-slate-400" />
                                    </div>
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Create a password"
                                        class="w-full pl-12 pr-12 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition placeholder:text-slate-400"
                                        :class="{ 'border-red-500': form.errors.password }"
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600"
                                    >
                                        <EyeOff v-if="showPassword" class="w-5 h-5" />
                                        <Eye v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <Lock class="w-5 h-5 text-slate-400" />
                                    </div>
                                    <input
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Confirm your password"
                                        class="w-full pl-12 pr-12 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition placeholder:text-slate-400"
                                        :class="{ 'border-red-500': form.errors.password_confirmation }"
                                    />
                                    <button
                                        type="button"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600"
                                    >
                                        <EyeOff v-if="showConfirmPassword" class="w-5 h-5" />
                                        <Eye v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">{{ form.errors.password_confirmation }}</p>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full py-3.5 px-6 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 mt-6"
                            >
                                <svg v-if="form.processing" class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                <span>{{ form.processing ? 'Creating Account...' : 'Create Account' }}</span>
                            </button>
                        </form>

                        <!-- Login Link -->
                        <div class="mt-8 pt-6 border-t border-slate-200 text-center">
                            <p class="text-slate-600">
                                Already have an account?
                                <Link href="/login" class="text-amber-600 hover:text-amber-700 font-semibold ml-1">
                                    Sign In
                                </Link>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <p class="mt-6 text-center text-sm text-slate-500">
                    By creating an account, you agree to our
                    <a href="#" class="text-amber-600 hover:underline">Terms of Service</a>
                    and
                    <a href="#" class="text-amber-600 hover:underline">Privacy Policy</a>
                </p>
            </div>
        </main>

        <!-- Footer -->
        <HajjFooter :settings="settings || {}" />
    </div>
</template>
