<script setup lang="ts">
import { Mail, Phone, Facebook, Twitter, Instagram, Linkedin, ChevronDown } from 'lucide-vue-next'
</script>

<template>
  <header class="w-full">
    <!-- Top Info Bar -->
    <div class="bg-teal-900 text-white">
      <div class="max-w-7xl mx-auto px-4 md:px-16">
        <div class="h-10 flex items-center justify-between">
          <!-- Left: Email + Phone -->
          <div class="flex items-center gap-6 text-sm">
            <a href="mailto:support@yourdomain.tld" class="flex items-center gap-2 hover:text-white/80 transition">
              <Mail class="w-4 h-4" />
              <span>support@yourdomain.tld</span>
            </a>

            <a href="tel:+18884002424" class="flex items-center gap-2 hover:text-white/80 transition">
              <Phone class="w-4 h-4" />
              <span>(888) 4000-2424</span>
            </a>
          </div>

          <!-- Right: Social Icons -->
          <div class="flex items-center gap-2">
            <a href="#" class="h-8 w-8 rounded-full bg-white/10 hover:bg-white/20 grid place-items-center transition"
              aria-label="Facebook">
              <Facebook class="w-4 h-4" />
            </a>
            <a href="#" class="h-8 w-8 rounded-full bg-white/10 hover:bg-white/20 grid place-items-center transition"
              aria-label="Instagram">
              <Instagram class="w-4 h-4" />
            </a>
            <a href="#" class="h-8 w-8 rounded-full bg-white/10 hover:bg-white/20 grid place-items-center transition"
              aria-label="Twitter">
              <Twitter class="w-4 h-4" />
            </a>
            <a href="#" class="h-8 w-8 rounded-full bg-white/10 hover:bg-white/20 grid place-items-center transition"
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
            <img src="/assets/img/tour/logo/ss.png" alt="Almahyrа Logo" class="h-12 w-auto object-contain" />
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
                  class="block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-b-xl transition">
                  Umrah Package
                </a>
              </div>
            </div>

            <a href="/articles" class="hover:text-slate-900 transition">ARTICLES</a>

          </nav>

          <!-- CTA -->
          <div class="flex items-center gap-3">
            <a href="/contactus" class="hidden sm:inline-flex items-center justify-center rounded-xl bg-yellow-500 hover:bg-yellow-400
                     px-6 py-3 text-sm font-extrabold tracking-widest text-white transition active:scale-[0.98]">
              Contact Us
            </a>

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
