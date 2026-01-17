@extends('admin.layouts.app')

@section('title', 'Articles')

@section('header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Articles</h1>
            <p class="mt-1 text-sm text-slate-600">Manage your blog articles and news</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Article
        </a>
    </div>
@endsection

@section('content')
    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="p-4 flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-600">Status:</span>
                <a href="{{ route('admin.articles.index') }}"
                   class="px-3 py-1.5 text-sm rounded-lg {{ !$currentStatus ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    All
                </a>
                @foreach($statuses as $status)
                    <a href="{{ route('admin.articles.index', ['status' => $status->value]) }}"
                       class="px-3 py-1.5 text-sm rounded-lg {{ $currentStatus === $status ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        {{ ucfirst($status->value) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Articles Table --}}
    @if($articles->count() > 0)
        <x-admin.data.table :columns="[
            'image' => ['label' => '', 'sortable' => false, 'width' => '16'],
            'title' => 'Title',
            'category' => 'Category',
            'author' => 'Author',
            'status' => 'Status',
            'views' => 'Views',
            'published' => 'Published',
            'actions' => ['label' => '', 'sortable' => false, 'width' => '32'],
        ]">
            @foreach($articles as $article)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-4">
                        @if($article->featured_image)
                            <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="h-12 w-16 object-cover rounded-lg">
                        @else
                            <div class="h-12 w-16 bg-slate-200 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ Str::limit($article->title, 50) }}</div>
                        @if($article->excerpt)
                            <div class="text-sm text-slate-500 truncate max-w-xs">{{ Str::limit($article->excerpt, 60) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($article->category)
                            <x-admin.ui.badge type="secondary">{{ $article->category->name }}</x-admin.ui.badge>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $article->author?->name ?? 'Unknown' }}
                    </td>
                    <td class="px-6 py-4">
                        <x-admin.ui.badge :type="match($article->status) {
                            \App\Enums\PublishStatus::PUBLISHED => 'success',
                            \App\Enums\PublishStatus::DRAFT => 'warning',
                            \App\Enums\PublishStatus::ARCHIVED => 'secondary',
                            default => 'secondary'
                        }">
                            {{ ucfirst($article->status->value) }}
                        </x-admin.ui.badge>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ number_format($article->views_count) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $article->published_at?->format('M d, Y') ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.articles.show', $article) }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg" title="View">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                            <a href="{{ route('admin.articles.edit', $article) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg" title="Edit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            @if($article->status === \App\Enums\PublishStatus::DRAFT)
                                <form action="{{ route('admin.articles.publish', $article) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-green-600 hover:bg-green-50 rounded-lg" title="Publish">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach

            <x-slot name="footer">
                <x-admin.data.pagination :paginator="$articles" />
            </x-slot>
        </x-admin.data.table>
    @else
        <x-admin.data.empty-state
            title="No articles found"
            description="Get started by writing your first article."
            actionText="New Article"
            :actionHref="route('admin.articles.create')"
        />
    @endif
@endsection
