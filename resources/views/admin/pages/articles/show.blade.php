@extends('admin.layouts.app')

@section('title', $article->title)

@section('header')
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.hajj.articles.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ Str::limit($article->title, 50) }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <x-admin.ui.badge :type="match($article->status) {
                        \App\Enums\PublishStatus::PUBLISHED => 'success',
                        \App\Enums\PublishStatus::DRAFT => 'warning',
                        \App\Enums\PublishStatus::ARCHIVED => 'secondary',
                        default => 'secondary'
                    }">
                        {{ ucfirst($article->status->value) }}
                    </x-admin.ui.badge>
                    @if($article->category)
                        <x-admin.ui.badge type="info">{{ $article->category->name }}</x-admin.ui.badge>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($article->status === \App\Enums\PublishStatus::DRAFT)
                <form action="{{ route('admin.hajj.articles.publish', $article) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <x-admin.ui.button type="submit" variant="success">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Publish
                    </x-admin.ui.button>
                </form>
            @else
                <form action="{{ route('admin.hajj.articles.unpublish', $article) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <x-admin.ui.button type="submit" variant="secondary">
                        Unpublish
                    </x-admin.ui.button>
                </form>
            @endif
            <a href="{{ route('admin.hajj.articles.edit', $article) }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.hajj.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Featured Image --}}
            @if($article->featured_image)
                <x-admin.ui.card>
                    <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-64 object-cover rounded-lg">
                </x-admin.ui.card>
            @endif

            {{-- Content --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Content</h3>
                </x-slot>

                @if($article->excerpt)
                    <div class="mb-4 p-4 bg-slate-50 rounded-lg">
                        <p class="text-slate-600 italic">{{ $article->excerpt }}</p>
                    </div>
                @endif

                <div class="prose prose-sm max-w-none">
                    {!! $article->content !!}
                </div>
            </x-admin.ui.card>

            {{-- SEO Preview --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">SEO Preview</h3>
                </x-slot>

                <div class="p-4 bg-white border border-slate-200 rounded-lg">
                    <div class="text-blue-600 text-lg hover:underline cursor-pointer">
                        {{ $article->meta_title ?? $article->title }}
                    </div>
                    <div class="text-green-700 text-sm">
                        {{ url('/articles/' . $article->slug) }}
                    </div>
                    <div class="text-sm text-slate-600 mt-1">
                        {{ $article->meta_description ?? Str::limit(strip_tags($article->content), 160) }}
                    </div>
                </div>

                @if($article->meta_keywords)
                    <div class="mt-4">
                        <span class="text-sm text-slate-600">Keywords:</span>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach(explode(',', $article->meta_keywords) as $keyword)
                                <x-admin.ui.badge type="secondary">{{ trim($keyword) }}</x-admin.ui.badge>
                            @endforeach
                        </div>
                    </div>
                @endif
            </x-admin.ui.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Info --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Details</h3>
                </x-slot>

                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-600">Author</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $article->author?->name ?? 'Unknown' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-600">Category</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $article->category?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-600">Reading Time</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $article->reading_time }} min</dd>
                    </div>
                    @if($article->published_at)
                        <div class="flex justify-between">
                            <dt class="text-sm text-slate-600">Published</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $article->published_at->format('M d, Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </x-admin.ui.card>

            {{-- Statistics --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Statistics</h3>
                </x-slot>

                <div class="text-center py-4">
                    <p class="text-3xl font-bold text-slate-900">{{ number_format($article->views_count) }}</p>
                    <p class="text-sm text-slate-600">Total Views</p>
                </div>
            </x-admin.ui.card>

            {{-- Meta Info --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Meta</h3>
                </x-slot>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Created</dt>
                        <dd class="text-slate-900">{{ $article->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Updated</dt>
                        <dd class="text-slate-900">{{ $article->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Slug</dt>
                        <dd class="text-slate-900 font-mono text-xs">{{ $article->slug }}</dd>
                    </div>
                </dl>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
