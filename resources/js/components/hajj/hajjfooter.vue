<script setup lang="ts">
import { Mail, Phone } from 'lucide-vue-next';
</script>

<template>
    <footer class="bg-[#111] text-white py-16 px-4 md:px-16">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Brand Section -->
            <div class="flex flex-col gap-6">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="text-2xl font-serif">
                        <span class="inline-block border border-white/30 p-1 rounded-sm mr-2">
                             <!-- Simple Icon Placeholder representing the lantern in the logo -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M12 2v20M8 10v4M16 10v4M4 14h16M12 2l-2 4h4l-2-4z"/></svg>
                        </span>
                        Tawaf
                    </div>
                </div>
                
                <p class="text-sm text-gray-400">
                    Powered by <span class="text-[#D3A762]">SocioLib.</span>
                </p>
            </div>

            <!-- Office Section -->
            <div class="flex flex-col gap-6">
                <h3 class="text-sm font-semibold tracking-wider text-gray-400 uppercase">OFFICE</h3>
                
                <div class="text-sm text-white leading-relaxed">
                    Tawaf Corp, Amphitheatre Parkway,<br>
                    Mountain View, California, 94043
                </div>

                <div class="flex flex-col gap-3 text-sm">
                    <a href="mailto:mail@tawaf.co" class="flex items-center gap-3 text-white hover:text-gray-300 transition-colors">
                        <Mail class="w-4 h-4" />
                        <span>mail@tawaf.co</span>
                    </a>
                    <a href="tel:+123456789" class="flex items-center gap-3 text-white hover:text-gray-300 transition-colors">
                        <Phone class="w-4 h-4" />
                        <span>+123 456 789</span>
                    </a>
                </div>
            </div>

            <!-- Menu Section -->
            <div class="flex flex-col gap-6">
                <h3 class="text-sm font-semibold tracking-wider text-gray-400 uppercase">MENU</h3>
                <nav class="flex flex-col gap-4 text-sm text-white">
                    <a href="#" class="hover:text-gray-300 transition-colors">Services</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Features</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Testimonials</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Packages</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Partners</a>
                </nav>
            </div>

            <!-- Links Section -->
            <div class="flex flex-col gap-6">
                <h3 class="text-sm font-semibold tracking-wider text-gray-400 uppercase">LINKS</h3>
                <nav class="flex flex-col gap-4 text-sm text-white">
                    <a href="#" class="hover:text-gray-300 transition-colors">About Us</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Contact</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Help Center</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Career</a>
                </nav>
            </div>
        </div>
    </footer>
</template>
