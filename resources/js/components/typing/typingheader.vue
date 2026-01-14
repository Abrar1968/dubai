<template>
    <header class="w-full bg-white relative z-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">
                <!-- LEFT: Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <!-- Replace with your logo -->
                        <!-- <img src="/path/logo.png" class="h-9 w-auto" alt="Logo" /> -->
                        <span class="text-xl font-bold text-slate-900">LOGO</span>
                    </a>
                </div>

                <!-- RIGHT: Desktop Nav -->
                <nav class="hidden md:flex items-center gap-10">
                    <a href="/" class="text-sm font-medium transition"
                        :class="isActive('/') ? 'text-[#d39b4a]' : 'text-slate-900 hover:text-[#d39b4a]'">
                        Home
                    </a>

                    <a href="/about" class="text-sm font-medium transition"
                        :class="isActive('/about') ? 'text-[#d39b4a]' : 'text-slate-900 hover:text-[#d39b4a]'">
                        About Us
                    </a>

                    <!-- Our Services Dropdown -->
                    <div class="relative" @mouseenter="openMenu" @mouseleave="closeMenu">
                        <button type="button" class="text-sm font-medium transition inline-flex items-center gap-2"
                            :class="isServicesActive ? 'text-[#d39b4a]' : 'text-slate-900 hover:text-[#d39b4a]'">
                            Our Services
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="{ 'rotate-180': servicesOpen }" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <transition enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                            <div v-show="servicesOpen"
                                class="absolute right-0 mt-3 w-64 rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                                <a href="/services/family-visa-procedure"
                                    class="block px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">
                                    Family Visa Procedure
                                </a>
                            </div>
                        </transition>
                    </div>

                    <a href="/blog" class="text-sm font-medium transition"
                        :class="isActive('/blog') ? 'text-[#d39b4a]' : 'text-slate-900 hover:text-[#d39b4a]'">
                        Blog
                    </a>

                    <a href="/contact" class="text-sm font-medium transition"
                        :class="isActive('/contact') ? 'text-[#d39b4a]' : 'text-slate-900 hover:text-[#d39b4a]'">
                        Contact Us
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <button type="button"
                    class="md:hidden inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-2 text-slate-700 hover:bg-slate-50"
                    @click="mobileOpen = !mobileOpen" aria-label="Open menu">
                    ☰
                </button>
            </div>

            <!-- ✅ Mobile Menu -->
            <transition enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2">
                <div v-show="mobileOpen" class="md:hidden pb-4">
                    <div class="mt-2 rounded-xl border border-slate-200 bg-white overflow-hidden">
                        <a href="/" class="block px-4 py-3 text-sm text-slate-800 hover:bg-slate-50">Home</a>
                        <a href="/about" class="block px-4 py-3 text-sm text-slate-800 hover:bg-slate-50">About Us</a>

                        <!-- Mobile Services Accordion -->
                        <button type="button"
                            class="w-full flex items-center justify-between px-4 py-3 text-sm text-slate-800 hover:bg-slate-50"
                            @click="mobileServicesOpen = !mobileServicesOpen">
                            Our Services
                            <span class="text-slate-500">{{ mobileServicesOpen ? '−' : '+' }}</span>
                        </button>

                        <div v-show="mobileServicesOpen" class="border-t border-slate-200">
                            <a href="/services/family-visa-procedure"
                                class="block px-6 py-3 text-sm text-slate-700 hover:bg-slate-50">
                                Family Visa Procedure
                            </a>
                        </div>

                        <a href="/blog" class="block px-4 py-3 text-sm text-slate-800 hover:bg-slate-50">Blog</a>
                        <a href="/contact" class="block px-4 py-3 text-sm text-slate-800 hover:bg-slate-50">Contact
                            Us</a>
                    </div>
                </div>
            </transition>
        </div>
    </header>
</template>

<script setup>
import { ref, computed } from 'vue'

const servicesOpen = ref(false)
const mobileOpen = ref(false)
const mobileServicesOpen = ref(false)

let hoverTimeout = null

const openMenu = () => {
    if (hoverTimeout) clearTimeout(hoverTimeout)
    servicesOpen.value = true
}

const closeMenu = () => {
    hoverTimeout = setTimeout(() => {
        servicesOpen.value = false
    }, 300) // 300ms delay to make it easier to reach
}

const isActive = (path) => {
    try {
        return window.location.pathname === path
    } catch {
        return false
    }
}

// highlight "Our Services" if any services page is active
const isServicesActive = computed(() => {
    const p = window.location.pathname
    return p.startsWith('/services')
})
</script>
