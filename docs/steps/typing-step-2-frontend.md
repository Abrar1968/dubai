# Typing Section - Step 2: Admin Views & Frontend Integration

**Document Version:** 1.0  
**Date:** January 28, 2026  
**Phase:** Step 2 of 2  
**Focus:** Blade Views, Sidebar, Frontend Vue Updates

---

## Overview

This step covers the frontend implementation:
1. Admin Blade views for Settings
2. Admin Blade views for Services CRUD
3. Sidebar navigation update
4. Vue component updates to receive props
5. Dynamic service page rendering

---

## 1. Settings Blade View

### File: `resources/views/admin/pages/typing/settings/index.blade.php`

**Structure (Mirror Hajj settings):**

```blade
<x-admin.layouts.app title="Typing Settings">
    @php
        $currentTab = request()->get('tab', 'company');
    @endphp

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Typing Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Configure your typing services website settings.</p>
            </div>
            <form action="{{ route('admin.typing.settings.clear-cache') }}" method="POST">
                @csrf
                <button type="submit" class="...">Clear Cache</button>
            </form>
        </div>

        <!-- Tabs: Company | SEO | Social Media -->
        <div x-data="{ activeTab: '{{ $currentTab }}' }">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'company'" :class="...">Company</button>
                    <button @click="activeTab = 'seo'" :class="...">SEO</button>
                    <button @click="activeTab = 'social'" :class="...">Social Media</button>
                </nav>
            </div>

            <!-- Company Tab Content -->
            <div x-show="activeTab === 'company'" x-cloak>
                <form action="{{ route('admin.typing.settings.update-company') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Company Logo -->
                    <!-- Company Name -->
                    <!-- Company Tagline -->
                    <!-- Company Email + Phone -->
                    <!-- Company WhatsApp -->
                    <!-- Company Address -->
                    <!-- Company Description -->
                    <!-- Hero Image -->
                </form>
            </div>

            <!-- SEO Tab Content -->
            <div x-show="activeTab === 'seo'" x-cloak>
                <form action="{{ route('admin.typing.settings.update-seo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Meta Title -->
                    <!-- Meta Description -->
                    <!-- Meta Keywords -->
                    <!-- Google Analytics ID -->
                    <!-- OG Image -->
                </form>
            </div>

            <!-- Social Media Tab Content -->
            <div x-show="activeTab === 'social'" x-cloak>
                <form action="{{ route('admin.typing.settings.update-social') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Facebook URL -->
                    <!-- Twitter URL -->
                    <!-- Instagram URL -->
                    <!-- LinkedIn URL -->
                    <!-- YouTube URL -->
                    <!-- Contact Description -->
                </form>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
```

**Key Differences from Hajj:**
- Route names: `admin.typing.settings.*`
- Title: "Typing Settings"
- No "Booking & Policies" tab (typing services don't have booking system)

---

## 2. Services Index View

### File: `resources/views/admin/pages/typing/services/index.blade.php`

**Structure:**

```blade
<x-admin.layouts.app title="Typing Services">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Typing Services</h1>
                <p class="mt-1 text-sm text-gray-500">Manage services displayed on the typing website.</p>
            </div>
            <a href="{{ route('admin.typing.services.create') }}" 
               class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                <svg class="mr-2 h-4 w-4" ...>+</svg>
                Add Service
            </a>
        </div>

        <!-- Services Table -->
        <x-admin.ui.card>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th>Order</th>
                        <th>Title</th>
                        <th>Sub-services</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($services as $service)
                    <tr>
                        <td>{{ $service->sort_order }}</td>
                        <td>
                            <div class="flex items-center">
                                @if($service->icon)
                                <span class="mr-2">{{ $service->icon }}</span>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $service->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($service->short_description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ count($service->sub_services ?? []) }} items</td>
                        <td>
                            <form action="{{ route('admin.typing.services.toggle-active', $service) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="...">
                                    @if($service->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold text-green-800">Active</span>
                                    @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 text-xs font-semibold text-gray-800">Inactive</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.typing.services.toggle-featured', $service) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit">
                                    @if($service->is_featured)
                                    <span class="text-amber-500">★</span>
                                    @else
                                    <span class="text-gray-300">☆</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.typing.services.edit', $service) }}" class="text-amber-600 hover:text-amber-900">Edit</a>
                            <form action="{{ route('admin.typing.services.destroy', $service) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-3" onclick="return confirm('Delete this service?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">No services found. Create your first service.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </x-admin.ui.card>
    </div>
</x-admin.layouts.app>
```

---

## 3. Services Create/Edit View

### File: `resources/views/admin/pages/typing/services/create.blade.php`
### File: `resources/views/admin/pages/typing/services/edit.blade.php`

**Form Structure:**

```blade
<x-admin.layouts.app title="{{ isset($service) ? 'Edit Service' : 'Create Service' }}">
    <form action="{{ isset($service) ? route('admin.typing.services.update', $service) : route('admin.typing.services.store') }}" 
          method="POST" 
          enctype="multipart/form-data"
          x-data="serviceForm(@js($service->sub_services ?? []))">
        @csrf
        @if(isset($service)) @method('PUT') @endif

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ isset($service) ? 'Edit Service' : 'Create Service' }}</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ isset($service) ? 'Update service details' : 'Add a new typing service' }}</p>
                </div>
                <a href="{{ route('admin.typing.services.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Services</a>
            </div>

            <!-- Basic Info Card -->
            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                </x-slot:header>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-admin.ui.input name="title" label="Service Title" :value="$service->title ?? ''" required />
                        <x-admin.ui.input name="slug" label="URL Slug" :value="$service->slug ?? ''" required hint="Used in URL: /typing/services/{slug}" />
                    </div>

                    <x-admin.ui.textarea name="short_description" label="Short Description" :value="$service->short_description ?? ''" rows="2" hint="Displayed on homepage grid cards (max 500 chars)" />

                    <x-admin.ui.textarea name="long_description" label="Full Description" :value="$service->long_description ?? ''" rows="4" hint="Displayed on service detail page" />
                </div>
            </x-admin.ui.card>

            <!-- Sub-Services Card (Dynamic) -->
            <x-admin.ui.card>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Sub-Services</h3>
                        <button type="button" @click="addSubService()" class="text-sm text-amber-600 hover:text-amber-500">+ Add Sub-Service</button>
                    </div>
                </x-slot:header>

                <div class="space-y-4">
                    <template x-for="(item, index) in subServices" :key="index">
                        <div class="rounded-lg border border-gray-200 p-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700" x-text="'Sub-Service #' + (index + 1)"></span>
                                <button type="button" @click="removeSubService(index)" class="text-red-500 text-sm">Remove</button>
                            </div>
                            <div class="space-y-3">
                                <input type="text" :name="'sub_services[' + index + '][title]'" x-model="item.title" placeholder="Title" class="..." />
                                <textarea :name="'sub_services[' + index + '][description]'" x-model="item.description" placeholder="Description" rows="2" class="..."></textarea>
                            </div>
                        </div>
                    </template>
                    <p x-show="subServices.length === 0" class="text-sm text-gray-500">No sub-services added. Click "Add Sub-Service" to add one.</p>
                </div>
            </x-admin.ui.card>

            <!-- CTA & Display Card -->
            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">Display & CTA</h3>
                </x-slot:header>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <x-admin.ui.input name="cta_text" label="CTA Button Text" :value="$service->cta_text ?? 'Apply'" />
                        <x-admin.ui.input name="cta_link" label="CTA Button Link" :value="$service->cta_link ?? '/contactus'" />
                        <x-admin.ui.input name="sort_order" label="Sort Order" type="number" :value="$service->sort_order ?? 0" />
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-admin.ui.checkbox name="is_active" label="Active" :checked="$service->is_active ?? true" hint="Show on website" />
                        <x-admin.ui.checkbox name="is_featured" label="Featured" :checked="$service->is_featured ?? false" hint="Highlight on homepage" />
                    </div>
                </div>
            </x-admin.ui.card>

            <!-- SEO Card -->
            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">SEO Settings</h3>
                </x-slot:header>

                <div class="space-y-4">
                    <x-admin.ui.input name="meta_title" label="Meta Title" :value="$service->meta_title ?? ''" placeholder="Service Title | Typing Services" />
                    <x-admin.ui.textarea name="meta_description" label="Meta Description" :value="$service->meta_description ?? ''" rows="2" />
                </div>
            </x-admin.ui.card>

            <!-- Submit -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.typing.services.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">{{ isset($service) ? 'Update Service' : 'Create Service' }}</button>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        function serviceForm(initialSubServices) {
            return {
                subServices: initialSubServices || [],
                addSubService() {
                    this.subServices.push({ title: '', description: '' });
                },
                removeSubService(index) {
                    this.subServices.splice(index, 1);
                }
            }
        }
    </script>
    @endpush
</x-admin.layouts.app>
```

---

## 4. Sidebar Navigation Update

### File: `resources/views/admin/components/layout/sidebar-content.blade.php`

**Find the Typing "Coming Soon" section and replace with:**

```blade
{{-- Typing Services Section --}}
@if(in_array('typing', $user->getSectionNames()) || $user->isSuperAdmin())
<div class="space-y-1">
    <p class="px-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Typing Services</p>
    
    {{-- Services Link --}}
    <a href="{{ route('admin.typing.services.index') }}"
       class="{{ request()->routeIs('admin.typing.services.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} group flex items-center rounded-md px-4 py-2 text-sm font-medium">
        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        Services
    </a>
    
    {{-- Settings Link --}}
    <a href="{{ route('admin.typing.settings.index') }}"
       class="{{ request()->routeIs('admin.typing.settings.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} group flex items-center rounded-md px-4 py-2 text-sm font-medium">
        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Settings
    </a>
</div>
@endif
```

---

## 5. Vue Component Updates

### 5.1 Update: `resources/js/pages/typing/typinghome.vue`

**Change from hardcoded to props:**

```vue
<script setup>
import TypingLayout from '@/layouts/TypingLayout.vue';

// Define props from controller
const props = defineProps({
    settings: {
        type: Object,
        default: () => ({})
    },
    services: {
        type: Array,
        default: () => []
    }
});

// Hero image from settings or fallback
const heroImage = computed(() => {
    return props.settings.hero_image 
        ? `/storage/${props.settings.hero_image}` 
        : '/assets/img/typing/hero-default.jpg';
});

// Services from props or fallback
const displayServices = computed(() => {
    if (props.services && props.services.length > 0) {
        return props.services;
    }
    // Fallback to hardcoded if no data
    return [
        { title: 'Immigration', desc: 'Emirates ID, residency...', url: '/typing/services/immigration' },
        // ... other fallback services
    ];
});
</script>

<template>
    <TypingLayout :settings="settings">
        <!-- Hero uses heroImage computed -->
        <section class="relative h-[260px]...">
            <img :src="heroImage" alt="Hero Banner" ... />
        </section>

        <!-- Services Grid uses displayServices computed -->
        <section class="py-10 bg-white">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <article v-for="s in displayServices" :key="s.url || s.slug">
                    <h4>{{ s.title }}</h4>
                    <p>{{ s.desc }}</p>
                    <a :href="s.url">Learn more</a>
                </article>
            </div>
        </section>
    </TypingLayout>
</template>
```

### 5.2 Update: `resources/js/layouts/TypingLayout.vue`

**Pass settings to header/footer:**

```vue
<script setup>
import typingheader from '@/components/typing/typingheader.vue';
import typingfooter from '@/components/typing/typingfooter.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({})
    }
});

// Get auth from Inertia shared data
const page = usePage();
const auth = computed(() => page.props.auth || {});
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <typingheader :settings="settings" :auth="auth" />
        <main class="flex-grow">
            <slot />
        </main>
        <typingfooter :settings="settings" />
    </div>
</template>
```

### 5.3 Optional: Dynamic Service Detail Page

**Create: `resources/js/pages/typing/services/ServiceDetail.vue`**

For fully dynamic service pages:

```vue
<script setup>
import TypingLayout from '@/layouts/TypingLayout.vue';

const props = defineProps({
    service: {
        type: Object,
        required: true
    },
    settings: {
        type: Object,
        default: () => ({})
    }
});
</script>

<template>
    <TypingLayout :settings="settings">
        <section class="py-14 bg-white">
            <div class="mx-auto max-w-6xl px-4">
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2">★ {{ service.title }}</h1>
                <p class="mt-4 text-slate-600">{{ service.description }}</p>

                <div class="mt-8 space-y-8">
                    <div v-for="(sub, index) in service.sub_services" :key="index">
                        <h2 class="text-lg md:text-xl font-bold text-slate-900">{{ sub.title }}</h2>
                        <p class="mt-2 text-slate-700">{{ sub.description }}</p>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a :href="service.cta_link" class="inline-flex items-center justify-center rounded-xl bg-[#D3A762] hover:bg-[#c29652] px-6 py-3 text-sm font-semibold text-white transition">
                        {{ service.cta_text }}
                    </a>
                </div>
            </div>
        </section>
    </TypingLayout>
</template>
```

---

## 6. Execution Checklist

### Step 2 Checklist

- [ ] Create settings view: `admin/pages/typing/settings/index.blade.php`
- [ ] Create services index: `admin/pages/typing/services/index.blade.php`
- [ ] Create services create: `admin/pages/typing/services/create.blade.php`
- [ ] Create services edit: `admin/pages/typing/services/edit.blade.php`
- [ ] Update sidebar: `admin/components/layout/sidebar-content.blade.php`
- [ ] Update Vue: `typinghome.vue` to use props
- [ ] Update Vue: `TypingLayout.vue` to pass settings
- [ ] (Optional) Create: `ServiceDetail.vue` for dynamic pages

### Verification

1. **Admin Panel:**
   - Login as super_admin or typing admin
   - Navigate to Typing Services in sidebar
   - Verify Services list page loads
   - Create a new service
   - Edit the service
   - Toggle active/featured
   - Navigate to Settings
   - Update company info
   - Update social links

2. **Frontend:**
   - Visit `/typing`
   - Verify hero image from settings
   - Verify services grid shows database data
   - Click a service and verify detail page

3. **Cache:**
   ```bash
   php artisan optimize:clear
   npm run build
   ```

---

## 7. File Summary

### Files to Create (4 Blade views)

| # | Path |
|---|------|
| 1 | `resources/views/admin/pages/typing/settings/index.blade.php` |
| 2 | `resources/views/admin/pages/typing/services/index.blade.php` |
| 3 | `resources/views/admin/pages/typing/services/create.blade.php` |
| 4 | `resources/views/admin/pages/typing/services/edit.blade.php` |

### Files to Update (4 files)

| # | Path | Change |
|---|------|--------|
| 1 | `resources/views/admin/components/layout/sidebar-content.blade.php` | Add typing menu |
| 2 | `resources/js/pages/typing/typinghome.vue` | Use props instead of hardcoded |
| 3 | `resources/js/layouts/TypingLayout.vue` | Pass settings to children |
| 4 | (Optional) Create `ServiceDetail.vue` | Dynamic service pages |

---

## 8. Post-Implementation

After completing both steps:

1. **Test full flow:**
   - Admin creates/edits service → Frontend displays it
   - Admin updates settings → Header/footer show new values

2. **Documentation:**
   - Update SRS.md with Typing section details
   - Update copilot-instructions.md

3. **Git commit:**
   ```bash
   git add .
   git commit -m "feat: implement typing section admin panel with services and settings management"
   git push origin abrar
   ```

---

*This completes the Typing Section implementation plan.*
