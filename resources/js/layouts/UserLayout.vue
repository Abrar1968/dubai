<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const page = usePage()

const user = computed(() => page.props.auth?.user || { name: 'User', email: '' })
const settings = computed(() => page.props.settings || {})

const isMobileMenuOpen = ref(false)

const navigation = [
    { name: 'Dashboard', href: '/user/dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name: 'My Bookings', href: '/user/bookings', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
    { name: 'Profile', href: '/user/profile', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
]

const isActive = (href: string) => {
    const currentPath = page.url || ''
    return currentPath.startsWith(href)
}
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Top Navigation Bar -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center gap-4">
                        <Link href="/" class="flex items-center gap-2">
                            <img 
                                v-if="settings.company_logo" 
                                :src="`/storage/${settings.company_logo}`" 
                                :alt="`${settings.company_name || 'Dubai Tourism'} Logo`" 
                                class="h-12 w-auto object-contain" 
                            />
                            <div v-else class="text-xl font-bold text-amber-600">Dubai Tourism</div>
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex md:items-center md:gap-1">
                        <Link
                            v-for="item in navigation"
                            :key="item.name"
                            :href="item.href"
                            :class="[
                                isActive(item.href) 
                                    ? 'bg-amber-50 text-amber-700' 
                                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors'
                            ]"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"/>
                            </svg>
                            {{ item.name }}
                        </Link>
                        
                        <!-- Divider -->
                        <div class="h-6 w-px bg-gray-200 mx-2"></div>
                        
                        <!-- Back to Website -->
                        <a
                            href="/hajjhome"
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-amber-600 hover:bg-amber-50 transition-colors"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Website
                        </a>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center gap-4">
                        <div class="hidden md:flex md:items-center md:gap-3">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                <p class="text-xs text-gray-500">{{ user.email }}</p>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center">
                                <span class="text-sm font-semibold text-amber-700">{{ user.name?.charAt(0)?.toUpperCase() || 'U' }}</span>
                            </div>
                        </div>

                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="hidden md:inline-flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </Link>

                        <!-- Mobile Menu Button -->
                        <button
                            @click="isMobileMenuOpen = !isMobileMenuOpen"
                            class="md:hidden rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                        >
                            <svg v-if="!isMobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div v-if="isMobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white">
                <div class="space-y-1 px-4 py-3">
                    <!-- User Info -->
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                        <div class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center">
                            <span class="text-sm font-semibold text-amber-700">{{ user.name?.charAt(0)?.toUpperCase() || 'U' }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                            <p class="text-xs text-gray-500">{{ user.email }}</p>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        @click="isMobileMenuOpen = false"
                        :class="[
                            isActive(item.href) 
                                ? 'bg-amber-50 text-amber-700' 
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium'
                        ]"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"/>
                        </svg>
                        {{ item.name }}
                    </Link>
                    
                    <!-- Back to Website -->
                    <a
                        href="/hajjhome"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-amber-600 hover:bg-amber-50"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Website
                    </a>

                    <!-- Logout -->
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="pt-16">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <p class="text-sm text-gray-500">
                        © {{ new Date().getFullYear() }} Dubai Tourism & Travel. All rights reserved.
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <Link href="/hajj-umrah" class="hover:text-gray-900">Browse Packages</Link>
                        <Link href="/contact" class="hover:text-gray-900">Contact Us</Link>
                        <Link href="/articles" class="hover:text-gray-900">Articles</Link>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
