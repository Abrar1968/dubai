@extends('admin.layouts.app')

@section('title', 'Create Article')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.hajj.articles.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Create Article</h1>
            <p class="mt-1 text-sm text-slate-600">Write a new blog article or news post</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.hajj.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Article Content</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <x-admin.ui.input name="title" label="Title" :value="old('title')" required placeholder="Enter article title..." />

                        <x-admin.ui.input name="slug" label="Slug" :value="old('slug')" placeholder="Leave empty to auto-generate" hint="URL-friendly identifier" />

                        <x-admin.ui.textarea name="excerpt" label="Excerpt" :value="old('excerpt')" rows="2" maxlength="500" showCount placeholder="Brief summary for previews..." />

                        <x-admin.form.rich-editor name="content" label="Content" :value="old('content')" required />
                    </div>
                </x-admin.ui.card>

                {{-- SEO --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">SEO Settings</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <x-admin.ui.input name="meta_title" label="Meta Title" :value="old('meta_title')" maxlength="60" placeholder="SEO title (60 chars max)" hint="Leave empty to use article title" />

                        <x-admin.ui.textarea name="meta_description" label="Meta Description" :value="old('meta_description')" rows="2" maxlength="160" showCount placeholder="SEO description (160 chars max)" />

                        <x-admin.ui.input name="meta_keywords" label="Meta Keywords" :value="old('meta_keywords')" placeholder="keyword1, keyword2, keyword3" hint="Comma-separated keywords" />
                    </div>
                </x-admin.ui.card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Publish --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Publish</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <x-admin.ui.select name="status" label="Status" :options="collect($statuses)->mapWithKeys(fn($s) => [$s->value => ucfirst($s->value)])->toArray()" :value="old('status', 'draft')" required />

                        <x-admin.form.date-picker name="published_at" label="Publish Date" :value="old('published_at')" hint="Leave empty to publish immediately when status is Published" />
                    </div>

                    <x-slot name="footer">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.hajj.articles.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900">
                                Cancel
                            </a>
                            <x-admin.ui.button type="submit">
                                Create Article
                            </x-admin.ui.button>
                        </div>
                    </x-slot>
                </x-admin.ui.card>

                {{-- Category --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Category</h3>
                    </x-slot>

                    <x-admin.ui.select name="category_id" label="Article Category" :options="$categories->pluck('name', 'id')->toArray()" :value="old('category_id')" required placeholder="Select a category" />

                    <div class="mt-3">
                        <a href="{{ route('admin.hajj.article-categories.create') }}" class="text-sm text-amber-600 hover:text-amber-700">
                            + Create new category
                        </a>
                    </div>
                </x-admin.ui.card>

                {{-- Featured Image --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Featured Image</h3>
                    </x-slot>

                    <x-admin.form.image-upload name="featured_image" label="Featured Image" required accept="image/jpeg,image/png,image/jpg,image/webp" maxSize="2" hint="Recommended: 1200x630px for social sharing" />
                </x-admin.ui.card>
            </div>
        </div>
    </form>
@endsection
