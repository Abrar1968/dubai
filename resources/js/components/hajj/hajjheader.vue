<script setup lang="ts">
import { Mail, Phone, Facebook, Twitter, Instagram, Linkedin, ChevronDown, User, LogOut } from 'lucide-vue-next'
import { router, Link } from '@inertiajs/vue3'

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
    <!-- Top Info Bar -->
    <div class="bg-teal-900 text-white">
      <div class="max-w-7xl mx-auto px-4 md:px-16">
        <div class="h-10 flex items-center justify-between">
          <!-- Left: Email + Phone -->
          <div class="flex items-center gap-6 text-sm">
            <a :href="`mailto:${settings.company_email || 'info@dubaihajj.ae'}`" class="flex items-center gap-2 hover:text-white/80 transition">
              <Mail class="w-4 h-4" />
              <span>{{ settings.company_email || 'info@dubaihajj.ae' }}</span>
            </a>

            <a :href="`tel:${settings.company_phone || '+971 4 123 4567'}`" class="flex items-center gap-2 hover:text-white/80 transition">
              <Phone class="w-4 h-4" />
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
      <div class="max-w-7xl mx-auto px-4 md:px-16">
        <div class="h-20 flex items-center justify-between">
          <!-- Logo -->
          <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
            <!-- Logo Image -->
            <img v-if="settings.company_logo" :src="`/storage/${settings.company_logo}`" :alt="`${settings.company_name || 'Company'} Logo`" class="h-20 w-auto object-contain" />
            <img v-else src="/assets/img/tour/logo/ss.png" alt="Company Logo" class="h-20 w-auto object-contain" />
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
            <a href="/contactus" class="hidden md:inline-flex items-center justify-center rounded-xl bg-yellow-500 hover:bg-yellow-400
                     px-6 py-3 text-sm font-extrabold tracking-widest text-white transition active:scale-[0.98]">
              Contact Us
            </a>

            <!-- Auth Buttons (show when NOT logged in OR when admin is logged in - admins have separate panel) -->
            <template v-if="!isLoggedIn() || isAdminUser()">
              <!-- Admin users see link to Admin Panel instead of Login -->
              <template v-if="isAdminUser()">
                <a href="/admin" class="hidden sm:inline-flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700
                         px-5 py-2.5 text-sm font-semibold text-white transition">
                  Admin Panel
                </a>
              </template>
              <!-- Non-logged in users see Account dropdown with Login/Register -->
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
            </template>

            <!-- User Dropdown (only for regular users with role='user') -->
            <div v-else class="relative group">
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

            <!-- Mobile menu button (optional) -->
            <button
              class="lg:hidden h-11 w-11 rounded-xl border border-slate-200 grid place-items-center hover:bg-slate-50 transition"
              aria-label="Open menu">
              ☰
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>
