@extends('admin.layouts.app')

@section('title', 'Article Categories')

@section('header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Article Categories</h1>
            <p class="mt-1 text-sm text-slate-600">Organize your articles into categories</p>
        </div>
        <a href="{{ route('admin.hajj.article-categories.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Category
        </a>
    </div>
@endsection

@section('content')
    @if($categories->count() > 0)
        <x-admin.data.table :columns="[
            'name' => 'Name',
            'slug' => 'Slug',
            'articles' => 'Articles',
            'description' => 'Description',
            'actions' => ['label' => '', 'sortable' => false, 'width' => '24'],
        ]">
            @foreach($categories as $category)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ $category->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-600 font-mono">{{ $category->slug }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <x-admin.ui.badge type="secondary">{{ $category->articles_count }}</x-admin.ui.badge>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-600">{{ Str::limit($category->description, 50) ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.hajj.article-categories.edit', $category) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg" title="Edit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            @if($category->articles_count === 0)
                                <form action="{{ route('admin.hajj.article-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach

            <x-slot name="footer">
                <x-admin.data.pagination :paginator="$categories" />
            </x-slot>
        </x-admin.data.table>
    @else
        <x-admin.data.empty-state
            title="No categories found"
            description="Create categories to organize your articles."
            actionText="New Category"
            :actionHref="route('admin.hajj.article-categories.create')"
        />
    @endif
@endsection
