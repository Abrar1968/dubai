<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

// Get hajj settings from Inertia shared props
const page = usePage();
const settings = computed(() => page.props.hajjSettings || page.props.settings || {});
const companyName = computed(() => settings.value.company_name || 'SS Group');
const companyLogo = computed(() => settings.value.company_logo ? `/storage/${settings.value.company_logo}` : null);
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-md overflow-hidden"
        :class="companyLogo ? 'bg-white' : 'bg-sidebar-primary text-sidebar-primary-foreground'"
    >
        <img v-if="companyLogo" :src="companyLogo" :alt="companyName" class="size-8 object-contain" />
        <span v-else class="text-xs font-bold">{{ companyName.substring(0, 2).toUpperCase() }}</span>
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold">{{ companyName }}</span>
    </div>
</template>
