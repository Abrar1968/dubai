<script setup lang="ts">
import { ref } from 'vue'
import { Mail, Phone, Facebook, Twitter, Instagram, Linkedin, ChevronDown, User, LogOut, Menu, X } from 'lucide-vue-next'
import { router, Link } from '@inertiajs/vue3'

const mobileMenuOpen = ref(false)
const mobilePackageOpen = ref(false)
const mobileAboutOpen = ref(false)

interface Settings {
    company_email?: string;
    company_phone?: string;
    company_logo?: string;
    company_name?: string;
    social_facebook?: string;
    social_twitter?: string;
    social_instagram?: string;
    social_linkedin?: string;
}

interface AuthUser {
    id: number;
    name: string;
    email: string;
    role?: string;
}

const props = withDefaults(defineProps<{
    settings?: Settings;
    auth?: {
        user?: AuthUser;
    };
}>(), {
    settings: () => ({}),
    auth: () => ({}),
});

// Check if logged-in user is a regular user (not admin/super_admin)
const isRegularUser = () => {
    return props.auth?.user && props.auth.user.role === 'user';
};

// Check if user is logged in (any role)
const isLoggedIn = () => {
    return !!props.auth?.user;
};

// Check if user is admin/super_admin
const isAdminUser = () => {
    return props.auth?.user && (props.auth.user.role === 'admin' || props.auth.user.role === 'super_admin');
};

const logout = () => {
    router.post('/logout');
};
</script>

<template>
  <header class="w-full">
    <!-- Top Info Bar - Hidden on mobile -->
    <div class="hidden md:block bg-teal-900 text-white">
      <div class="max-w-7xl mx-auto px-4 md:px-8 lg:px-16">
        <div class="h-10 flex items-center justify-between">
          <!-- Left: Email + Phone -->
          <div class="flex items-center gap-2 sm:gap-4 lg:gap-6 text-xs sm:text-sm">
            <a :href="`mailto:${settings.company_email || 'info@dubaihajj.ae'}`" class="flex items-center gap-2 hover:text-white/80 transition">
              <Mail class="w-4 h-4 shrink-0" />
              <span class="truncate max-w-[150px] lg:max-w-none">{{ settings.company_email || 'info@dubaihajj.ae' }}</span>
            </a>

            <a :href="`tel:${settings.company_phone || '+971 4 123 4567'}`" class="flex items-center gap-2 hover:text-white/80 transition">
              <Phone class="w-4 h-4 shrink-0" />
              <span>{{ settings.company_phone || '+971 4 123 4567' }}</span>
            </a>
          </div>

          <!-- Right: Social Icons -->
          <div class="flex items-center gap-2">
            <a v-if="settings.social_facebook" :href="settings.social_facebook" target="_blank" rel="noopener noreferrer" class="h-8 w-8 rounded-full bg-white/10 hover:bg-white/20 grid place-items-center transition"
              aria-label="Facebook">
              <Facebook class="w-4 h-4" />
            </a>
            <a v-if="settings.social_instagram" :href="settings.social_instagram" target="_blank" rel="noopener noreferrer" class="h-8 w-8 rounded-full bg-white/10 hover:bg-white/20 grid place-items-center transition"
              aria-label="Instagram">
              <Instagram class="w-4 h-4" />
            </a>
            <a v-if="settings.social_twitter" :href="settings.social_twitter" target="_blank" rel="noopener noreferrer" class="h-8 w-8 rounded-full bg-white/10 hover:bg-white/20 grid place-items-center transition"
              aria-label="Twitter">
              <Twitter class="w-4 h-4" />
            </a>
            <a v-if="settings.social_linkedin" :href="settings.social_linkedin" target="_blank" rel="noopener noreferrer" class="h-8 w-8 rounded-full bg-white/10 hover:bg-white/20 grid place-items-center transition"
              aria-label="LinkedIn">
              <Linkedin class="w-4 h-4" />
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Navbar -->
    <div class="bg-white border-b border-slate-200">
      <div class="max-w-7xl mx-auto px-4 md:px-8 lg:px-16">
        <div class="h-16 sm:h-20 md:h-24 lg:h-28 flex items-center justify-between">
          <!-- Logo -->
          <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
            <!-- Logo Image -->
            <img v-if="settings.company_logo" :src="`/storage/${settings.company_logo}`" :alt="`${settings.company_name || 'Company'} Logo`" class="h-12 sm:h-16 md:h-20 lg:h-24 w-auto object-contain" />
            <img v-else src="/assets/img/tour/logo/ss.png" alt="Company Logo" class="h-12 sm:h-16 md:h-20 lg:h-24 w-auto object-contain" />
          </a>

          <!-- Menu -->
          <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-slate-700">
            <a href="/hajjhome" class="hover:text-slate-900 transition">HOME</a>

            <!-- ABOUT US (with submenu on hover) -->
            <div class="relative group">
              <a href="#" class="hover:text-slate-900 transition inline-flex items-center gap-1">
                ABOUT US
                <ChevronDown class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" />
              </a>

              <!-- Submenu -->
              <div class="absolute left-0 top-full pt-3 opacity-0 invisible translate-y-2
                       group-hover:opacity-100 group-hover:visible group-hover:translate-y-0
                       transition-all duration-200 z-50">
                <div class="w-48 rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                  <a href="/hajj-umrah/team"
                    class="block px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                    Team
                  </a>
                </div>
              </div>
            </div>

            <div class="relative group">
              <!-- Parent -->
              <a href="#"
                class="inline-flex items-center gap-1 font-semibold text-slate-700 hover:text-slate-900 transition">
                PACKAGE
                <ChevronDown class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" />
              </a>

              <!-- Dropdown -->
              <div class="absolute left-0 top-full mt-3 w-48 rounded-xl bg-white border border-slate-200
           shadow-[0_20px_40px_rgba(0,0,0,0.12)]
           opacity-0 invisible translate-y-2
           group-hover:opacity-100 group-hover:visible group-hover:translate-y-0
           transition-all duration-200 z-50">
                <a href="/hajjpackage"
                  class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-t-xl transition">
                  Hajj Package
                </a>

                <a href="/umrahpackage"
                  class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                  Umrah Package
                </a>

                <a href="/tourpackage"
                  class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-b-xl transition">
                  Tour Package
                </a>
              </div>
            </div>

            <a href="/articles" class="hover:text-slate-900 transition">ARTICLES</a>

          </nav>

          <!-- CTA / Auth Section -->
          <div class="flex items-center gap-3">
            <!-- Contact Us Button (always visible) -->
            <a href="/contactus" class="hidden md:inline-flex items-center justify-center rounded-xl bg-[#D3A762] hover:bg-[#c29652]
                     px-6 py-3 text-sm font-extrabold tracking-widest text-white transition active:scale-[0.98]">
              Contact Us
            </a>

            <!-- Auth Section (Only for Users - Admins have separate admin panel) -->
            <!-- User Dropdown (only for regular users with role='user') -->
            <template v-if="isRegularUser()">
              <div class="relative group">
                <button class="flex items-center gap-2 rounded-xl border-2 border-slate-200 hover:border-slate-300
                               px-4 py-2.5 text-sm font-semibold text-slate-700 transition">
                  <User class="w-4 h-4" />
                  <span class="hidden sm:inline">{{ auth.user.name }}</span>
                  <ChevronDown class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" />
                </button>

                <!-- Dropdown Menu -->
                <div class="absolute right-0 top-full mt-3 w-48 rounded-xl bg-white border border-slate-200
                         shadow-[0_20px_40px_rgba(0,0,0,0.12)]
                         opacity-0 invisible translate-y-2
                         group-hover:opacity-100 group-hover:visible group-hover:translate-y-0
                         transition-all duration-200 z-50">
                  <a href="/user/dashboard"
                    class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-t-xl transition">
                    Dashboard
                  </a>
                  <a href="/user/bookings"
                    class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                    My Bookings
                  </a>
                  <a href="/user/profile"
                    class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                    Profile
                  </a>
                  <button @click="logout"
                    class="w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-b-xl transition text-left">
                    <LogOut class="w-4 h-4" />
                    Logout
                  </button>
                </div>
              </div>
            </template>

            <!-- Account Dropdown (for non-logged in users) -->
            <template v-else>
              <div class="relative group">
                <button class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-[#D3A762] hover:bg-[#c29652]
                         px-5 py-2.5 text-sm font-semibold text-white transition active:scale-[0.98]">
                  <User class="w-4 h-4" />
                  Account
                  <ChevronDown class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" />
                </button>
                <div class="absolute right-0 top-full mt-3 w-40 rounded-xl bg-white border border-slate-200
                         shadow-[0_20px_40px_rgba(0,0,0,0.12)]
                         opacity-0 invisible translate-y-2
                         group-hover:opacity-100 group-hover:visible group-hover:translate-y-0
                         transition-all duration-200 z-50">
                  <Link href="/login" class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-t-xl transition">
                    Login
                  </Link>
                  <Link href="/register" class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-b-xl transition">
                    Register
                  </Link>
                </div>
              </div>
            </template>

            <!-- Mobile menu button -->
            <button
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="lg:hidden h-10 w-10 sm:h-11 sm:w-11 rounded-xl border border-slate-200 grid place-items-center hover:bg-slate-50 transition"
              aria-label="Toggle menu">
              <Menu v-if="!mobileMenuOpen" class="w-5 h-5" />
              <X v-else class="w-5 h-5" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile Menu Drawer -->
    <div v-if="mobileMenuOpen" class="lg:hidden fixed inset-0 z-50 bg-black/50" @click="mobileMenuOpen = false">
      <div class="absolute right-0 top-0 h-full w-80 max-w-[85vw] bg-white shadow-xl overflow-y-auto" @click.stop>
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
          <span class="font-bold text-lg">Menu</span>
          <button @click="mobileMenuOpen = false" class="p-2 hover:bg-slate-100 rounded-lg">
            <X class="w-5 h-5" />
          </button>
        </div>
        <nav class="p-4 space-y-1">
          <a href="/hajjhome" class="block px-4 py-3 rounded-lg hover:bg-slate-50 font-medium">Home</a>

          <!-- About Us Accordion -->
          <div>
            <button @click="mobileAboutOpen = !mobileAboutOpen" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-50 font-medium">
              About Us
              <ChevronDown :class="['w-4 h-4 transition-transform', mobileAboutOpen ? 'rotate-180' : '']" />
            </button>
            <div v-if="mobileAboutOpen" class="pl-4 space-y-1">
              <a href="/hajj-umrah/team" class="block px-4 py-2 rounded-lg hover:bg-slate-50 text-slate-600">Team</a>
            </div>
          </div>

          <!-- Package Accordion -->
          <div>
            <button @click="mobilePackageOpen = !mobilePackageOpen" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-50 font-medium">
              Package
              <ChevronDown :class="['w-4 h-4 transition-transform', mobilePackageOpen ? 'rotate-180' : '']" />
            </button>
            <div v-if="mobilePackageOpen" class="pl-4 space-y-1">
              <a href="/hajjpackage" class="block px-4 py-2 rounded-lg hover:bg-slate-50 text-slate-600">Hajj Package</a>
              <a href="/umrahpackage" class="block px-4 py-2 rounded-lg hover:bg-slate-50 text-slate-600">Umrah Package</a>
              <a href="/tourpackage" class="block px-4 py-2 rounded-lg hover:bg-slate-50 text-slate-600">Tour Package</a>
            </div>
          </div>

          <a href="/articles" class="block px-4 py-3 rounded-lg hover:bg-slate-50 font-medium">Articles</a>
          <a href="/contactus" class="block px-4 py-3 rounded-lg hover:bg-slate-50 font-medium">Contact Us</a>

          <!-- Auth Links -->
          <div class="border-t border-slate-200 mt-4 pt-4">
            <template v-if="isRegularUser()">
              <a href="/user/dashboard" class="block px-4 py-3 rounded-lg hover:bg-slate-50 font-medium">Dashboard</a>
              <a href="/user/bookings" class="block px-4 py-3 rounded-lg hover:bg-slate-50 font-medium">My Bookings</a>
              <a href="/user/profile" class="block px-4 py-3 rounded-lg hover:bg-slate-50 font-medium">Profile</a>
              <button @click="logout" class="w-full text-left px-4 py-3 rounded-lg hover:bg-red-50 font-medium text-red-600">Logout</button>
            </template>
            <template v-else>
              <Link href="/login" class="block px-4 py-3 rounded-lg hover:bg-slate-50 font-medium">Login</Link>
              <Link href="/register" class="block px-4 py-3 rounded-lg hover:bg-slate-50 font-medium">Register</Link>
            </template>
          </div>
        </nav>
      </div>
    </div>
  </header>
</template>
