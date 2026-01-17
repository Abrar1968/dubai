<x-admin.layouts.app title="Edit Team Member">
    <div class="mx-auto max-w-3xl">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.hajj.team.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Team
            </a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">Edit Team Member</h1>
        </div>

        <form action="{{ route('admin.hajj.team.update', $member) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                </x-slot:header>

                <div class="space-y-4">
                    <!-- Photo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Photo</label>
                        <div class="mt-2" x-data="{ preview: '{{ $member->image ? Storage::url($member->image) : '' }}' }">
                            <div class="flex items-center gap-4">
                                <div class="h-24 w-24 overflow-hidden rounded-full bg-gray-100">
                                    <template x-if="preview">
                                        <img :src="preview" class="h-full w-full object-cover">
                                    </template>
                                    <template x-if="!preview">
                                        <div class="flex h-full items-center justify-center">
                                            <svg class="h-8 w-8 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                        </div>
                                    </template>
                                </div>
                                <div>
                                    <input type="file" name="image" id="image" accept="image/*" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                                    <label for="image" class="cursor-pointer rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50">
                                        Change Photo
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG or WebP. Max 2MB.</p>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Name -->
                    <x-admin.ui.input
                        name="name"
                        label="Full Name"
                        :value="old('name', $member->name)"
                        required
                    />

                    <!-- Role -->
                    <x-admin.ui.input
                        name="role"
                        label="Role"
                        :value="old('role', $member->role)"
                        required
                    />

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Email -->
                        <x-admin.ui.input
                            type="email"
                            name="email"
                            label="Email Address"
                            :value="old('email', $member->email)"
                        />

                        <!-- Phone -->
                        <x-admin.ui.input
                            name="phone"
                            label="Phone Number"
                            :value="old('phone', $member->phone)"
                        />
                    </div>

                    <!-- Bio -->
                    <x-admin.ui.textarea
                        name="bio"
                        label="Bio"
                        :value="old('bio', $member->bio)"
                        rows="3"
                    />
                </div>
            </x-admin.ui.card>

            <!-- Social Links -->
            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">Social Links</h3>
                </x-slot:header>

                @php
                    $existingLinks = $member->social_links ?? [];
                    $linksArray = [];
                    foreach ($existingLinks as $platform => $url) {
                        $linksArray[] = ['platform' => $platform, 'url' => $url];
                    }
                @endphp

                <div x-data="{
                    links: {{ json_encode($linksArray) }},
                    platforms: ['linkedin', 'twitter', 'instagram', 'facebook', 'youtube'],
                    addLink() {
                        this.links.push({ platform: 'linkedin', url: '' });
                    },
                    removeLink(index) {
                        this.links.splice(index, 1);
                    },
                    getLinksJson() {
                        const obj = {};
                        this.links.forEach(link => {
                            if (link.url) obj[link.platform] = link.url;
                        });
                        return JSON.stringify(obj);
                    }
                }">
                    <input type="hidden" name="social_links" :value="getLinksJson()">

                    <div class="space-y-3">
                        <template x-for="(link, index) in links" :key="index">
                            <div class="flex items-center gap-3">
                                <select x-model="link.platform" class="rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500">
                                    <template x-for="platform in platforms" :key="platform">
                                        <option :value="platform" x-text="platform.charAt(0).toUpperCase() + platform.slice(1)"></option>
                                    </template>
                                </select>
                                <input type="url" x-model="link.url" placeholder="https://..." class="flex-1 rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500">
                                <button type="button" @click="removeLink(index)" class="rounded-lg p-2 text-red-500 hover:bg-red-50">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="addLink()" class="mt-3 inline-flex items-center text-sm font-medium text-amber-600 hover:text-amber-500">
                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Social Link
                    </button>
                </div>
            </x-admin.ui.card>

            <!-- Settings -->
            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">Settings</h3>
                </x-slot:header>

                <div class="space-y-4">
                    <x-admin.ui.input
                        type="number"
                        name="sort_order"
                        label="Sort Order"
                        :value="old('sort_order', $member->sort_order)"
                        min="0"
                        hint="Lower numbers appear first"
                    />

                    <x-admin.ui.toggle
                        name="is_active"
                        label="Active"
                        :checked="old('is_active', $member->is_active)"
                        description="Show this team member on the website"
                    />
                </div>
            </x-admin.ui.card>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.hajj.team.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                    Update Team Member
                </button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
