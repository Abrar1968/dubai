@extends('admin.layouts.app')

@section('title', 'Edit Article')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.articles.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Article</h1>
            <p class="mt-1 text-sm text-slate-600">{{ Str::limit($article->title, 50) }}</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Article Content</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <x-admin.ui.input name="title" label="Title" :value="old('title', $article->title)" required placeholder="Enter article title..." />

                        <x-admin.ui.input name="slug" label="Slug" :value="old('slug', $article->slug)" placeholder="Leave empty to auto-generate" hint="URL-friendly identifier" />

                        <x-admin.ui.textarea name="excerpt" label="Excerpt" :value="old('excerpt', $article->excerpt)" rows="2" maxlength="500" showCount placeholder="Brief summary for previews..." />

                        <x-admin.form.rich-editor name="content" label="Content" :value="old('content', $article->content)" required />
                    </div>
                </x-admin.ui.card>

                {{-- SEO --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">SEO Settings</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <x-admin.ui.input name="meta_title" label="Meta Title" :value="old('meta_title', $article->meta_title)" maxlength="60" placeholder="SEO title (60 chars max)" hint="Leave empty to use article title" />

                        <x-admin.ui.textarea name="meta_description" label="Meta Description" :value="old('meta_description', $article->meta_description)" rows="2" maxlength="160" showCount placeholder="SEO description (160 chars max)" />

                        <x-admin.ui.input name="meta_keywords" label="Meta Keywords" :value="old('meta_keywords', $article->meta_keywords)" placeholder="keyword1, keyword2, keyword3" hint="Comma-separated keywords" />
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
                        <x-admin.ui.select name="status" label="Status" :options="collect($statuses)->mapWithKeys(fn($s) => [$s->value => ucfirst($s->value)])->toArray()" :value="old('status', $article->status->value)" required />

                        <x-admin.form.date-picker name="published_at" label="Publish Date" :value="old('published_at', $article->published_at?->format('Y-m-d'))" hint="Leave empty to publish immediately when status is Published" />
                    </div>

                    <x-slot name="footer">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900">
                                Cancel
                            </a>
                            <x-admin.ui.button type="submit">
                                Update Article
                            </x-admin.ui.button>
                        </div>
                    </x-slot>
                </x-admin.ui.card>

                {{-- Category --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Category</h3>
                    </x-slot>

                    <x-admin.ui.select name="category_id" label="Article Category" :options="$categories->pluck('name', 'id')->toArray()" :value="old('category_id', $article->category_id)" required placeholder="Select a category" />

                    <div class="mt-3">
                        <a href="{{ route('admin.article-categories.create') }}" class="text-sm text-amber-600 hover:text-amber-700">
                            + Create new category
                        </a>
                    </div>
                </x-admin.ui.card>

                {{-- Featured Image --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Featured Image</h3>
                    </x-slot>

                    <x-admin.form.image-upload
                        name="featured_image"
                        label="Featured Image"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        maxSize="2"
                        hint="Recommended: 1200x630px for social sharing"
                        :existingImage="$article->featured_image ? Storage::url($article->featured_image) : null"
                    />
                </x-admin.ui.card>

                {{-- Stats --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Statistics</h3>
                    </x-slot>

                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Views</dt>
                            <dd class="font-medium text-slate-900">{{ number_format($article->views_count) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Reading Time</dt>
                            <dd class="font-medium text-slate-900">{{ $article->reading_time }} min</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Created</dt>
                            <dd class="text-slate-900">{{ $article->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Updated</dt>
                            <dd class="text-slate-900">{{ $article->updated_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </x-admin.ui.card>
            </div>
        </div>
    </form>
@endsection
