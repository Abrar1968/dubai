<x-admin.layouts.app title="Typing Settings">
    @php
        $currentTab = request()->get('tab', 'company');
    @endphp

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Typing Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Configure your typing services website settings and preferences.</p>
            </div>
            <form action="{{ route('admin.typing.settings.clear-cache') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Clear Cache
                </button>
            </form>
        </div>

        <!-- Tabs -->
        <div x-data="{ activeTab: '{{ $currentTab }}' }">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button type="button" @click="activeTab = 'company'"
                            :class="{ 'border-purple-500 text-purple-600': activeTab === 'company', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'company' }"
                            class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                        <svg class="-ml-0.5 mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Company
                    </button>
                    <button type="button" @click="activeTab = 'seo'"
                            :class="{ 'border-purple-500 text-purple-600': activeTab === 'seo', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'seo' }"
                            class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                        <svg class="-ml-0.5 mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        SEO
                    </button>
                    <button type="button" @click="activeTab = 'social'"
                            :class="{ 'border-purple-500 text-purple-600': activeTab === 'social', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'social' }"
                            class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                        <svg class="-ml-0.5 mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                        Social Media
                    </button>
                    <button type="button" @click="activeTab = 'contact'"
                            :class="{ 'border-purple-500 text-purple-600': activeTab === 'contact', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'contact' }"
                            class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                        <svg class="-ml-0.5 mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Contact Info
                    </button>
                </nav>
            </div>

            <!-- Company Tab -->
            <div x-show="activeTab === 'company'" x-cloak class="mt-6">
                <form action="{{ route('admin.typing.settings.update-company') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <x-admin.ui.card>
                        <div class="space-y-6">
                            <!-- Logo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Company Logo</label>
                                <div class="mt-2" x-data="{ preview: '{{ isset($settings['company']['company_logo']) && $settings['company']['company_logo'] ? Storage::url($settings['company']['company_logo']) : '' }}' }">
                                    <div class="flex items-center gap-4">
                                        <div class="h-20 w-40 overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                                            <template x-if="preview">
                                                <img :src="preview" class="h-full w-full object-contain p-2">
                                            </template>
                                            <template x-if="!preview">
                                                <div class="flex h-full items-center justify-center text-gray-400">
                                                    <span class="text-xs">No logo</span>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <input type="file" name="company_logo" id="company_logo" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml,image/heic,image/heif" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                                            <label for="company_logo" class="cursor-pointer rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50">
                                                Choose Logo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <x-admin.ui.input
                                name="company_name"
                                label="Company Name"
                                :value="$settings['company']['company_name'] ?? ''"
                                placeholder="SS Group Travels & Typing"
                            />

                            <x-admin.ui.input
                                name="company_tagline"
                                label="Company Tagline"
                                :value="$settings['company']['company_tagline'] ?? ''"
                                placeholder="Professional Document & Typing Services in UAE"
                                hint="Displayed in homepage hero section"
                            />

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <x-admin.ui.input
                                    type="email"
                                    name="company_email"
                                    label="Company Email"
                                    :value="$settings['company']['company_email'] ?? ''"
                                    placeholder="info@company.com"
                                />

                                <x-admin.ui.input
                                    name="company_phone"
                                    label="Company Phone"
                                    :value="$settings['company']['company_phone'] ?? ''"
                                    placeholder="+971 50 123 4567"
                                />
                            </div>

                            <x-admin.ui.input
                                name="company_whatsapp"
                                label="WhatsApp Number"
                                :value="$settings['company']['company_whatsapp'] ?? ''"
                                placeholder="+971501234567"
                                hint="Floating WhatsApp button on homepage (include country code)"
                            />

                            <x-admin.ui.textarea
                                name="company_address"
                                label="Company Address"
                                :value="$settings['company']['company_address'] ?? ''"
                                rows="2"
                                placeholder="Dubai, United Arab Emirates"
                            />

                            <x-admin.ui.textarea
                                name="company_description"
                                label="Short Description"
                                :value="$settings['company']['company_description'] ?? ''"
                                rows="3"
                                placeholder="Brief description for footer and about sections..."
                            />

                            <!-- Mission, Vision, Values -->
                            <x-admin.ui.textarea
                                name="company_mission"
                                label="Company Mission"
                                :value="$settings['company']['company_mission'] ?? ''"
                                rows="3"
                                placeholder="Our mission statement..."
                            />

                            <x-admin.ui.textarea
                                name="company_vision"
                                label="Company Vision"
                                :value="$settings['company']['company_vision'] ?? ''"
                                rows="3"
                                placeholder="Our vision statement..."
                            />

                            <x-admin.ui.textarea
                                name="company_values"
                                label="Company Values"
                                :value="$settings['company']['company_values'] ?? ''"
                                rows="3"
                                placeholder="Our core values..."
                            />

                            <!-- Hero Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Homepage Hero Background</label>
                                <p class="mt-1 text-sm text-gray-500">Upload large hero image for homepage (recommended: 1920x1080px, max 10MB)</p>
                                <div class="mt-2" x-data="{ preview: '{{ isset($settings['company']['hero_image']) && $settings['company']['hero_image'] ? Storage::url($settings['company']['hero_image']) : '' }}' }">
                                    <div class="space-y-4">
                                        <div class="h-48 w-full overflow-hidden rounded-lg border-2 border-dashed border-gray-300 bg-gray-50">
                                            <template x-if="preview">
                                                <img :src="preview" class="h-full w-full object-cover">
                                            </template>
                                            <template x-if="!preview">
                                                <div class="flex h-full flex-col items-center justify-center text-gray-400">
                                                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="mt-2 text-sm">No hero background image</span>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <input type="file" name="hero_image" id="hero_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/heic,image/heif" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                                            <label for="hero_image" class="cursor-pointer rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50">
                                                Choose Hero Image
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <x-slot:footer>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-500">
                                    Save Company Settings
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin.ui.card>
                </form>
            </div>

            <!-- SEO Tab -->
            <div x-show="activeTab === 'seo'" x-cloak class="mt-6">
                <form action="{{ route('admin.typing.settings.update-seo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <x-admin.ui.card>
                        <div class="space-y-6">
                            <x-admin.ui.input
                                name="meta_title"
                                label="Default Meta Title"
                                :value="$settings['seo']['meta_title'] ?? ''"
                                placeholder="SS Group Travels & Typing - Professional Document Services in UAE"
                                hint="Used when pages don't have their own title"
                            />

                            <x-admin.ui.textarea
                                name="meta_description"
                                label="Default Meta Description"
                                :value="$settings['seo']['meta_description'] ?? ''"
                                rows="3"
                                placeholder="Professional typing and document services in Dubai..."
                                hint="Keep it under 160 characters for best SEO results"
                            />

                            <x-admin.ui.input
                                name="meta_keywords"
                                label="Meta Keywords"
                                :value="$settings['seo']['meta_keywords'] ?? ''"
                                placeholder="typing services, document processing, visa services, dubai"
                                hint="Comma-separated keywords"
                            />

                            <x-admin.ui.input
                                name="google_analytics"
                                label="Google Analytics ID"
                                :value="$settings['seo']['google_analytics'] ?? ''"
                                placeholder="G-XXXXXXXXXX"
                            />

                            <!-- OG Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Social Share Image (OG Image)</label>
                                <p class="text-xs text-gray-500">Recommended size: 1200x630 pixels</p>
                                <div class="mt-2" x-data="{ preview: '{{ isset($settings['seo']['og_image']) && $settings['seo']['og_image'] ? Storage::url($settings['seo']['og_image']) : '' }}' }">
                                    <div class="flex items-start gap-4">
                                        <div class="h-[105px] w-[200px] overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                                            <template x-if="preview">
                                                <img :src="preview" class="h-full w-full object-cover">
                                            </template>
                                            <template x-if="!preview">
                                                <div class="flex h-full items-center justify-center text-gray-400">
                                                    <span class="text-xs">No image</span>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <input type="file" name="og_image" id="og_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,svg,heic,heif" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                                            <label for="og_image" class="cursor-pointer rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50">
                                                Choose Image
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <x-slot:footer>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-500">
                                    Save SEO Settings
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin.ui.card>
                </form>
            </div>

            <!-- Social Media Tab -->
            <div x-show="activeTab === 'social'" x-cloak class="mt-6">
                <form action="{{ route('admin.typing.settings.update-social') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <x-admin.ui.card>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <x-admin.ui.input
                                    type="url"
                                    name="social_facebook"
                                    label="Facebook URL"
                                    :value="$settings['social']['social_facebook'] ?? ''"
                                    placeholder="https://facebook.com/yourpage"
                                />

                                <x-admin.ui.input
                                    type="url"
                                    name="social_twitter"
                                    label="Twitter / X URL"
                                    :value="$settings['social']['social_twitter'] ?? ''"
                                    placeholder="https://twitter.com/yourhandle"
                                />

                                <x-admin.ui.input
                                    type="url"
                                    name="social_instagram"
                                    label="Instagram URL"
                                    :value="$settings['social']['social_instagram'] ?? ''"
                                    placeholder="https://instagram.com/yourprofile"
                                />

                                <x-admin.ui.input
                                    type="url"
                                    name="social_linkedin"
                                    label="LinkedIn URL"
                                    :value="$settings['social']['social_linkedin'] ?? ''"
                                    placeholder="https://linkedin.com/company/yourcompany"
                                />

                                <x-admin.ui.input
                                    type="url"
                                    name="social_youtube"
                                    label="YouTube URL"
                                    :value="$settings['social']['social_youtube'] ?? ''"
                                    placeholder="https://youtube.com/@yourchannel"
                                />

                                <x-admin.ui.input
                                    type="url"
                                    name="social_tiktok"
                                    label="TikTok URL"
                                    :value="$settings['social']['social_tiktok'] ?? ''"
                                    placeholder="https://tiktok.com/@yourprofile"
                                />
                            </div>
                        </div>

                        <x-slot:footer>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-500">
                                    Save Social Media Settings
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin.ui.card>
                </form>
            </div>

            <!-- Contact Info Tab -->
            <div x-show="activeTab === 'contact'" x-cloak class="mt-6">
                <form action="{{ route('admin.typing.settings.update-contact') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                        </x-slot:header>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <x-admin.ui.input
                                    type="email"
                                    name="contact_email"
                                    label="Contact Email"
                                    :value="$settings['contact']['contact_email'] ?? ''"
                                    placeholder="contact@company.com"
                                />

                                <x-admin.ui.input
                                    name="contact_phone"
                                    label="Contact Phone"
                                    :value="$settings['contact']['contact_phone'] ?? ''"
                                    placeholder="+971 50 123 4567"
                                />
                            </div>

                            <x-admin.ui.textarea
                                name="office_address"
                                label="Office Address"
                                :value="$settings['contact']['office_address'] ?? ''"
                                rows="3"
                                placeholder="Full office address..."
                            />

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <x-admin.ui.input
                                    name="office_hours"
                                    label="Office Hours"
                                    :value="$settings['contact']['office_hours'] ?? ''"
                                    placeholder="Sun-Thu: 9AM-6PM"
                                />

                                <x-admin.ui.input
                                    name="google_maps_embed"
                                    label="Google Maps Embed URL"
                                    :value="$settings['contact']['google_maps_embed'] ?? ''"
                                    placeholder="https://www.google.com/maps/embed?..."
                                    hint="Embed URL from Google Maps"
                                />
                            </div>

                            <x-admin.ui.textarea
                                name="contact_description"
                                label="Contact Page Description"
                                :value="$settings['contact']['contact_description'] ?? ''"
                                rows="3"
                                placeholder="Brief description for contact page..."
                            />
                        </div>

                        <x-slot:footer>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-500">
                                    Save Contact Settings
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin.ui.card>
                </form>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
