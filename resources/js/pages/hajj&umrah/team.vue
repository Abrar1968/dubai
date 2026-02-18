<script setup lang="ts">
import { ChevronDown, ChevronUp } from 'lucide-vue-next'
import { ref, computed } from 'vue'
import HajjHeader from '@/components/hajj/hajjheader.vue'
import HajjFooter from '@/components/hajj/hajjfooter.vue'

// TypeScript interfaces
interface TeamMember {
    id: number;
    name: string;
    role: string;
    image: string | null;
    bio?: string;
    social_links?: {
        facebook?: string;
        twitter?: string;
        linkedin?: string;
    };
}

interface Faq {
    id: number;
    question: string;
    answer: string;
}

const props = withDefaults(defineProps<{
    teamMembers?: TeamMember[];
    faqs?: Faq[];
    settings?: Record<string, string>;
}>(), {
    teamMembers: () => [],
    faqs: () => [],
    settings: () => ({}),
});

// Use props directly - data comes from backend
const displayTeamMembers = computed(() => props.teamMembers);

// FAQs with reactive state for accordion
const displayFaqs = ref(props.faqs.map((f, i) => ({ ...f, open: i === 0 })));

const toggleFaq = (i: number) => {
    displayFaqs.value[i].open = !displayFaqs.value[i].open;
};
</script>

<template>
    <div class="font-sans text-slate-800">
        <HajjHeader :settings="settings" :auth="$page.props.auth" />
        <main class="bg-white">
            <!-- HERO (Top banner like screenshot) -->
            <section class="relative h-[220px] sm:h-[260px] w-full overflow-hidden bg-teal-900">
                <!-- title -->
                <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-4">
                    <h1 class="text-white text-3xl sm:text-4xl font-extrabold tracking-tight">Team</h1>
                    <p class="mt-2 text-white/80 text-sm sm:text-base">
                        It takes two flints to make a fire.
                    </p>
                </div>
            </section>

            <!-- SECTION 2 (heading + team grid) -->
            <section class="pb-16 sm:pb-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-10">
                        <p class="text-[11px] font-bold tracking-widest text-teal-700 uppercase">Our Team</p>
                        <h3 class="mt-3 text-2xl sm:text-3xl font-extrabold text-slate-900">
                            Alone we can do so little, <br class="hidden sm:block" />
                            together we can do so much.
                        </h3>
                    </div>

                    <!-- Grid like screenshot -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div v-for="m in displayTeamMembers" :key="m.id" class="group rounded-2xl border border-slate-100 bg-white shadow-[0_12px_30px_rgba(0,0,0,0.06)]
                   overflow-hidden hover:-translate-y-1 transition-transform duration-300">
                            <!-- image -->
                            <div class="relative bg-slate-100">
                                <img :src="m.image || '/assets/img/hajj/hajjbg.jpg'" :alt="m.name" loading="lazy" class="w-full h-[170px] sm:h-[190px] object-cover" />

                                <!-- subtle vertical label (optional like screenshot) -->
                                <div
                                    class="absolute left-0 top-0 h-full w-8 bg-white/70 hidden sm:flex items-center justify-center">
                                    <span class="text-[10px] font-bold tracking-widest text-slate-500 rotate-90">
                                        TEAM
                                    </span>
                                </div>
                            </div>

                            <!-- footer -->
                            <div class="p-4">
                                <p class="font-extrabold text-slate-900 leading-tight">{{ m.name }}</p>
                                <p class="text-sm text-slate-500 mt-1">{{ m.role }}</p>

                                <!-- socials -->
                                <div class="mt-3 flex items-center gap-2 text-slate-500">
                                    <a :href="m.social_links?.facebook || '#'"
                                        class="h-8 w-8 rounded-lg bg-slate-50 hover:bg-slate-100 grid place-items-center transition"
                                        aria-label="facebook">
                                        f
                                    </a>
                                    <a :href="m.social_links?.twitter || '#'"
                                        class="h-8 w-8 rounded-lg bg-slate-50 hover:bg-slate-100 grid place-items-center transition"
                                        aria-label="twitter">
                                        t
                                    </a>
                                    <a :href="m.social_links?.linkedin || '#'"
                                        class="h-8 w-8 rounded-lg bg-slate-50 hover:bg-slate-100 grid place-items-center transition"
                                        aria-label="linkedin">
                                        in
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <HajjFooter :settings="settings" />
    </div>
</template>
