<x-admin.layouts.app title="Site Settings">
    @php
        $currentTab = request()->get('tab', 'company');
    @endphp

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Site Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Configure your website settings and preferences.</p>
            </div>
            <form action="{{ route('admin.hajj.settings.clear-cache') }}" method="POST">
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
                            :class="{ 'border-amber-500 text-amber-600': activeTab === 'company', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'company' }"
                            class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                        <svg class="-ml-0.5 mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Company
                    </button>
                    <button type="button" @click="activeTab = 'seo'"
                            :class="{ 'border-amber-500 text-amber-600': activeTab === 'seo', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'seo' }"
                            class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                        <svg class="-ml-0.5 mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        SEO
                    </button>
                    <button type="button" @click="activeTab = 'social'"
                            :class="{ 'border-amber-500 text-amber-600': activeTab === 'social', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'social' }"
                            class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                        <svg class="-ml-0.5 mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                        Social Media
                    </button>
                    <button type="button" @click="activeTab = 'booking'"
                            :class="{ 'border-amber-500 text-amber-600': activeTab === 'booking', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'booking' }"
                            class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                        <svg class="-ml-0.5 mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Booking & Policies
                    </button>
                </nav>
            </div>

            <!-- Company Tab -->
            <div x-show="activeTab === 'company'" x-cloak class="mt-6">
                <form action="{{ route('admin.hajj.settings.update-company') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <x-admin.ui.card>
                        <div class="space-y-6">
                            <!-- Logo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Company Logo</label>
                                <div class="mt-2" x-data="{ preview: '{{ $settings['company']['company_logo'] ? asset('storage/' . $settings['company']['company_logo']) : '' }}' }">
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
                                :value="$settings['company']['company_name']"
                                placeholder="SS Group Typing & Travels"
                            />

                            <x-admin.ui.input
                                name="company_tagline"
                                label="Company Tagline"
                                :value="$settings['company']['company_tagline'] ?? ''"
                                placeholder="Your Trusted Partner for Sacred Journeys"
                                hint="Displayed in homepage hero section"
                            />

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <x-admin.ui.input
                                    type="email"
                                    name="company_email"
                                    label="Company Email"
                                    :value="$settings['company']['company_email']"
                                    placeholder="info@company.com"
                                />

                                <x-admin.ui.input
                                    name="company_phone"
                                    label="Company Phone"
                                    :value="$settings['company']['company_phone']"
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
                                :value="$settings['company']['company_address']"
                                rows="2"
                                placeholder="Dubai, United Arab Emirates"
                            />

                            <x-admin.ui.textarea
                                name="company_description"
                                label="Short Description"
                                :value="$settings['company']['company_description']"
                                rows="3"
                                placeholder="Brief description for footer and about sections..."
                            />

                            <!-- Banner Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Top Banner Image</label>
                                <p class="mt-1 text-sm text-gray-500">Upload banner image for top section (recommended: 1920x200px, max 10MB)</p>
                                <div class="mt-2" x-data="{ preview: '{{ $settings['company']['banner_image'] ?? '' ? asset('storage/' . $settings['company']['banner_image']) : '' }}' }">
                                    <div class="space-y-4">
                                        <div class="h-24 w-full overflow-hidden rounded-lg border-2 border-dashed border-gray-300 bg-gray-50">
                                            <template x-if="preview">
                                                <img :src="preview" class="h-full w-full object-cover">
                                            </template>
                                            <template x-if="!preview">
                                                <div class="flex h-full flex-col items-center justify-center text-gray-400">
                                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="mt-2 text-xs">No banner image</span>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <input type="file" name="banner_image" id="banner_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/heic,image/heif" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                                            <label for="banner_image" class="cursor-pointer rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50">
                                                Choose Banner Image
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hero Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Homepage Hero Background</label>
                                <p class="mt-1 text-sm text-gray-500">Upload large hero image for homepage (recommended: 1920x1080px, max 10MB)</p>
                                <div class="mt-2" x-data="{ preview: '{{ $settings['company']['hero_image'] ?? '' ? asset('storage/' . $settings['company']['hero_image']) : '' }}' }">
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
                                <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                                    Save Company Settings
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin.ui.card>
                </form>
            </div>

            <!-- SEO Tab -->
            <div x-show="activeTab === 'seo'" x-cloak class="mt-6">
                <form action="{{ route('admin.hajj.settings.update-seo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <x-admin.ui.card>
                        <div class="space-y-6">
                            <x-admin.ui.input
                                name="meta_title"
                                label="Default Meta Title"
                                :value="$settings['seo']['meta_title']"
                                placeholder="SS Group Typing & Travels - Trusted Pilgrimage Packages"
                                hint="Used when pages don't have their own title"
                            />

                            <x-admin.ui.textarea
                                name="meta_description"
                                label="Default Meta Description"
                                :value="$settings['seo']['meta_description']"
                                rows="3"
                                placeholder="Premium Hajj and Umrah packages from Dubai..."
                                hint="Keep it under 160 characters for best SEO results"
                            />

                            <x-admin.ui.input
                                name="meta_keywords"
                                label="Meta Keywords"
                                :value="$settings['seo']['meta_keywords']"
                                placeholder="hajj, umrah, dubai, packages, pilgrimage"
                                hint="Comma-separated keywords"
                            />

                            <x-admin.ui.input
                                name="google_analytics"
                                label="Google Analytics ID"
                                :value="$settings['seo']['google_analytics']"
                                placeholder="G-XXXXXXXXXX"
                            />

                            <!-- OG Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Social Share Image (OG Image)</label>
                                <p class="text-xs text-gray-500">Recommended size: 1200x630 pixels</p>
                                <div class="mt-2" x-data="{ preview: '{{ $settings['seo']['og_image'] ? asset('storage/' . $settings['seo']['og_image']) : '' }}' }">
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
                                            <input type="file" name="og_image" id="og_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml,image/heic,image/heif" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
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
                                <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                                    Save SEO Settings
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin.ui.card>
                </form>
            </div>

            <!-- Social Media Tab -->
            <div x-show="activeTab === 'social'" x-cloak class="mt-6">
                <form action="{{ route('admin.hajj.settings.update-social') }}" method="POST">
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
                            </div>

                            <x-admin.ui.textarea
                                name="contact_description"
                                label="Contact Page Description"
                                :value="$settings['social']['contact_description'] ?? ''"
                                rows="3"
                                placeholder="Brief description for contact page..."
                            />
                        </div>

                        <x-slot:footer>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                                    Save Social Media Settings
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin.ui.card>
                </form>
            </div>

            <!-- Booking & Policies Tab -->
            <div x-show="activeTab === 'booking'" x-cloak class="mt-6">
                <form action="{{ route('admin.hajj.settings.update-booking') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <x-admin.ui.card>
                            <x-slot:header>
                                <h3 class="text-lg font-medium text-gray-900">Booking Contact</h3>
                            </x-slot:header>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <x-admin.ui.input
                                    type="email"
                                    name="booking_email"
                                    label="Booking Email"
                                    :value="$settings['booking']['booking_email']"
                                    placeholder="bookings@company.com"
                                    hint="Booking confirmations will be sent from this email"
                                />

                                <x-admin.ui.input
                                    name="booking_phone"
                                    label="Booking Hotline"
                                    :value="$settings['booking']['booking_phone']"
                                    placeholder="+971 50 123 4567"
                                />
                            </div>
                        </x-admin.ui.card>

                        <x-admin.ui.card>
                            <x-slot:header>
                                <h3 class="text-lg font-medium text-gray-900">Policies</h3>
                            </x-slot:header>

                            <div class="space-y-4">
                                <x-admin.ui.textarea
                                    name="terms_conditions"
                                    label="Terms & Conditions"
                                    :value="$settings['booking']['terms_conditions']"
                                    rows="6"
                                    placeholder="Enter your terms and conditions..."
                                />

                                <x-admin.ui.textarea
                                    name="privacy_policy"
                                    label="Privacy Policy"
                                    :value="$settings['booking']['privacy_policy']"
                                    rows="6"
                                    placeholder="Enter your privacy policy..."
                                />

                                <x-admin.ui.textarea
                                    name="cancellation_policy"
                                    label="Cancellation Policy"
                                    :value="$settings['booking']['cancellation_policy']"
                                    rows="6"
                                    placeholder="Enter your cancellation policy..."
                                />
                            </div>

                            <x-slot:footer>
                                <div class="flex justify-end">
                                    <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                                        Save Booking Settings
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin.ui.card>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
