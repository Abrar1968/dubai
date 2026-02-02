<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { Eye, EyeOff, Mail, Lock, LogIn } from 'lucide-vue-next'
import HajjHeader from '@/components/hajj/hajjheader.vue'
import HajjFooter from '@/components/hajj/hajjfooter.vue'
import TypingHeader from '@/components/typing/typingheader.vue'
import TypingFooter from '@/components/typing/typingfooter.vue'

const props = defineProps<{
    status?: string;
    canResetPassword?: boolean;
    canRegister?: boolean;
    settings?: Record<string, string>;
    section?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

// Determine which section we're in
const isTypingSection = computed(() => props.section === 'typing');

const submit = () => {
    form.post('/login', {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <Head title="Login" />

    <div class="min-h-screen flex flex-col bg-slate-50">
        <!-- Dynamic Header based on section -->
        <TypingHeader v-if="isTypingSection" :settings="settings || {}" />
        <HajjHeader v-else :settings="settings || {}" />

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center py-12 px-4">
            <div class="w-full max-w-md">
                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <!-- Header Section -->
                    <div class="bg-gradient-to-r from-teal-800 to-teal-900 px-8 py-10 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-full mb-4">
                            <LogIn class="w-8 h-8 text-white" />
                        </div>
                        <h1 class="text-2xl font-bold text-white">Welcome Back</h1>
                        <p class="text-teal-100 mt-2">Sign in to your account to continue</p>
                    </div>

                    <!-- Form Section -->
                    <div class="p-8">
                        <!-- Status Message -->
                        <div v-if="status" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm text-center">
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
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
                                        autofocus
                                        autocomplete="email"
                                        placeholder="Enter your email"
                                        :class="[
                                            'w-full pl-12 pr-4 py-3 border rounded-xl transition placeholder:text-slate-400 text-slate-900 bg-white',
                                            form.errors.email
                                                ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-red-500'
                                                : isTypingSection
                                                    ? 'border-slate-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500'
                                                    : 'border-slate-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500'
                                        ]"
                                    />
                                </div>
                                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</p>
                            </div>

                            <!-- Password -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="password" class="block text-sm font-semibold text-slate-700">
                                        Password
                                    </label>
                                    <Link v-if="canResetPassword" href="/forgot-password"
                                          :class="isTypingSection ? 'text-teal-600 hover:text-teal-700' : 'text-amber-600 hover:text-amber-700'"
                                          class="text-sm font-medium">
                                        Forgot password?
                                    </Link>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <Lock class="w-5 h-5 text-slate-400" />
                                    </div>
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Enter your password"
                                        :class="[
                                            'w-full pl-12 pr-12 py-3 border rounded-xl transition placeholder:text-slate-400 text-slate-900 bg-white',
                                            form.errors.password
                                                ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-red-500'
                                                : isTypingSection
                                                    ? 'border-slate-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500'
                                                    : 'border-slate-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500'
                                        ]"
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

                            <!-- Remember Me -->
                            <div class="flex items-center">
                                <input
                                    id="remember"
                                    v-model="form.remember"
                                    type="checkbox"
                                    :class="isTypingSection ? 'text-teal-600 focus:ring-teal-500' : 'text-amber-600 focus:ring-amber-500'"
                                    class="h-4 w-4 border-slate-300 rounded"
                                />
                                <label for="remember" class="ml-3 text-sm text-slate-600">
                                    Remember me
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                :class="[
                                    'w-full py-3.5 px-6 text-white font-semibold rounded-xl shadow-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2',
                                    isTypingSection
                                        ? 'bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 shadow-teal-500/25'
                                        : 'bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 shadow-amber-500/25'
                                ]"
                            >
                                <svg v-if="form.processing" class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                <span>{{ form.processing ? 'Signing in...' : 'Sign In' }}</span>
                            </button>
                        </form>

                        <!-- Register Link -->
                        <div class="mt-8 pt-6 border-t border-slate-200 text-center">
                            <p class="text-slate-600">
                                Don't have an account?
                                <Link :href="`/register${isTypingSection ? '?section=typing' : ''}`"
                                      :class="isTypingSection ? 'text-teal-600 hover:text-teal-700' : 'text-amber-600 hover:text-amber-700'"
                                      class="font-semibold ml-1">
                                    Create Account
                                </Link>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <p class="mt-6 text-center text-sm text-slate-500">
                    By signing in, you agree to our
                    <a href="#" :class="isTypingSection ? 'text-teal-600 hover:underline' : 'text-amber-600 hover:underline'">Terms of Service</a>
                    and
                    <a href="#" :class="isTypingSection ? 'text-teal-600 hover:underline' : 'text-amber-600 hover:underline'">Privacy Policy</a>
                </p>
            </div>
        </main>

        <!-- Dynamic Footer based on section -->
        <TypingFooter v-if="isTypingSection" :settings="settings || {}" />
        <HajjFooter v-else :settings="settings || {}" />
    </div>
</template>
