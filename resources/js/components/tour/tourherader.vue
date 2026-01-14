<template>
  <header class="w-full bg-white border-b border-border">
    <!-- TOP BAR -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="h-20 flex items-center justify-between gap-4">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
          <!-- Logo Image -->
          <img src="/assets/img/tour/logo/ss.png" alt="GoFly Logo" class="h-12 w-auto object-contain" />
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden lg:flex items-center gap-8">
          <a class="text-foreground font-semibold hover:text-primary transition-colors" href="#">Home</a>

          <!-- Mega Dropdown Trigger -->
          <div class="relative" @mouseenter="openMenu('destination')" @mouseleave="closeMenuDelayed">
            <button class="text-foreground font-semibold hover:text-primary transition-colors flex items-center gap-2"
              @click.prevent="toggleMenu('destination')" :aria-expanded="activeMenu === 'destination'">
              Destination
              <svg class="h-4 w-4 text-muted-foreground" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                  clip-rule="evenodd" />
              </svg>
            </button>
          </div>

          <a class="text-foreground font-semibold hover:text-primary transition-colors" href="#">Travel Package</a>
          <a class="text-foreground font-semibold hover:text-primary transition-colors" href="#">Visa</a>
          <a class="text-foreground font-semibold hover:text-primary transition-colors" href="#">Pages</a>
          <a class="text-foreground font-semibold hover:text-primary transition-colors" href="#">Contact</a>
        </nav>

        <!-- Right Area -->
        <div class="flex items-center gap-3">
          <div class="hidden md:flex items-center gap-3 text-foreground">
            <div class="h-10 w-10 rounded-full border border-border flex items-center justify-center bg-muted">
              <span>📞</span>
            </div>
            <div class="leading-tight">
              <p class="text-xs text-muted-foreground font-medium">Need Help?</p>
              <p class="font-semibold text-foreground">+91 345 533 865</p>
            </div>
          </div>

          <button
            class="h-10 w-10 rounded-full border border-border flex items-center justify-center hover:bg-muted transition-colors">
            🔍
          </button>

          <a href="#"
            class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-primary-foreground font-semibold hover:opacity-90 transition-opacity">
            👤 Login
          </a>

          <!-- Mobile menu button -->
          <button
            class="lg:hidden h-10 w-10 rounded-xl border border-border flex items-center justify-center hover:bg-muted transition-colors"
            @click="mobileOpen = !mobileOpen" aria-label="Open menu">
            ☰
          </button>
        </div>
      </div>
    </div>

    <!-- MEGA DROPDOWN (Full width) -->
    <transition name="mega">
      <div v-if="activeMenu" class="relative" @mouseenter="cancelClose" @mouseleave="closeMenuDelayed">
        <!-- Overlay click-away -->
        <div class="fixed inset-0 z-40" @click="closeMenu"></div>

        <!-- Dropdown Panel -->
        <div class="absolute left-0 right-0 z-50 bg-background border-t border-border shadow-lg">
          <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-x-10 gap-y-8">
              <!-- Dynamic columns -->
              <div v-for="(col, idx) in megaMenus[activeMenu]" :key="idx">
                <h4 class="text-foreground font-bold text-base mb-4">
                  {{ col.title }}
                </h4>

                <ul class="space-y-3">
                  <li v-for="(item, i) in col.items" :key="i">
                    <a href="#"
                      class="inline-flex items-center gap-2 text-muted-foreground hover:text-foreground transition-colors">
                      <span class="h-2 w-2 rounded-full bg-muted"></span>
                      <span class="text-sm font-medium">{{ item.label }}</span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Optional bottom faint decoration row -->
            <div class="mt-10 border-t border-border pt-6 flex items-center justify-between">
              <p class="text-xs text-muted-foreground">You can replace this footer area later</p>
              <button class="text-xs font-semibold text-foreground hover:text-primary transition-colors"
                @click="closeMenu">
                Close ✕
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Mobile Nav (simple) -->
    <transition name="fade">
      <div v-if="mobileOpen" class="lg:hidden border-t border-border">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 space-y-2">
          <a class="block px-3 py-3 rounded-xl text-foreground font-semibold hover:bg-muted transition-colors"
            href="#">Home</a>

          <button
            class="block px-3 py-3 rounded-xl text-foreground font-semibold hover:bg-muted transition-colors w-full flex items-center justify-between"
            @click="toggleMenu('destination')">
            Destination
            <span class="text-muted-foreground">▾</span>
          </button>

          <div v-if="activeMenu === 'destination'" class="pl-3 pb-2">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div v-for="(col, idx) in megaMenus.destination" :key="idx" class="rounded-xl border border-border p-3">
                <p class="font-bold text-foreground text-sm mb-2">{{ col.title }}</p>
                <div class="space-y-2">
                  <a v-for="(item, i) in col.items" :key="i" href="#"
                    class="block text-sm text-muted-foreground hover:text-foreground transition-colors">
                    {{ item.label }}
                  </a>
                </div>
              </div>
            </div>
          </div>

          <a class="block px-3 py-3 rounded-xl text-foreground font-semibold hover:bg-muted transition-colors"
            href="#">Travel Package</a>
          <a class="block px-3 py-3 rounded-xl text-foreground font-semibold hover:bg-muted transition-colors"
            href="#">Visa</a>
          <a class="block px-3 py-3 rounded-xl text-foreground font-semibold hover:bg-muted transition-colors"
            href="#">Pages</a>
          <a class="block px-3 py-3 rounded-xl text-foreground font-semibold hover:bg-muted transition-colors"
            href="#">Contact</a>
        </div>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const mobileOpen = ref(false)
const activeMenu = ref(null)
let closeTimer = null

// ✅ Dynamic mega menu data (replace later)
const megaMenus = {
  destination: [
    {
      title: 'Europe',
      items: [
        { label: 'Paris, France' },
        { label: 'United Kingdom' },
        { label: 'Netherlands' },
        { label: 'Italy' },
        { label: 'Greece' },
        { label: 'Romania' },
      ],
    },
    {
      title: 'Asia',
      items: [
        { label: 'Tokyo, Japan' },
        { label: 'Indonesia' },
        { label: 'Thailand' },
        { label: 'Malaysia' },
        { label: 'Hanoi, Vietnam' },
        { label: 'India' },
      ],
    },
    {
      title: 'Africa',
      items: [
        { label: 'Egypt' },
        { label: 'South Africa' },
        { label: 'Zimbabwe' },
        { label: 'Kenya' },
        { label: 'Morocco' },
        { label: 'Senegal' },
      ],
    },
    {
      title: 'Oceania',
      items: [
        { label: 'Australia' },
        { label: 'New Zealand' },
        { label: 'Papua New Guinea' },
      ],
    },
    {
      title: 'Middle East',
      items: [
        { label: 'United Arab Emirates' },
        { label: 'Qatar' },
        { label: 'Bahrain' },
        { label: 'Saudi Arabia' },
        { label: 'Jordan' },
        { label: 'Palestine' },
      ],
    },
    {
      title: 'North America',
      items: [
        { label: 'United States' },
        { label: 'Canada' },
        { label: 'Mexico' },
        { label: 'Jamaica' },
        { label: 'Costa Rica' },
      ],
    },
  ],
}

const openMenu = (key) => {
  cancelClose()
  activeMenu.value = key
}

const toggleMenu = (key) => {
  cancelClose()
  activeMenu.value = activeMenu.value === key ? null : key
}

const closeMenu = () => {
  cancelClose()
  activeMenu.value = null
}

const closeMenuDelayed = () => {
  cancelClose()
  closeTimer = setTimeout(() => {
    activeMenu.value = null
  }, 180)
}

const cancelClose = () => {
  if (closeTimer) clearTimeout(closeTimer)
  closeTimer = null
}

// ESC close
const onKeydown = (e) => {
  if (e.key === 'Escape') closeMenu()
}

onMounted(() => window.addEventListener('keydown', onKeydown))
onBeforeUnmount(() => window.removeEventListener('keydown', onKeydown))
</script>

<style scoped>
/* Dropdown transition */
.mega-enter-active,
.mega-leave-active {
  transition: opacity 200ms ease, transform 200ms ease;
}

.mega-enter-from,
.mega-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

/* Mobile fade */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 180ms ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
