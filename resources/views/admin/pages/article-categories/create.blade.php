@extends('admin.layouts.app')

@section('title', 'Create Category')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.article-categories.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Create Category</h1>
            <p class="mt-1 text-sm text-slate-600">Add a new article category</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <form action="{{ route('admin.article-categories.store') }}" method="POST">
            @csrf

            <x-admin.ui.card>
                <div class="space-y-4">
                    <x-admin.ui.input name="name" label="Category Name" :value="old('name')" required placeholder="e.g., Travel Tips" />

                    <x-admin.ui.input name="slug" label="Slug" :value="old('slug')" placeholder="Leave empty to auto-generate" hint="URL-friendly identifier" />

                    <x-admin.ui.textarea name="description" label="Description" :value="old('description')" rows="3" maxlength="500" placeholder="Brief description of this category..." />
                </div>

                <x-slot name="footer">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.article-categories.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900">
                            Cancel
                        </a>
                        <x-admin.ui.button type="submit">
                            Create Category
                        </x-admin.ui.button>
                    </div>
                </x-slot>
            </x-admin.ui.card>
        </form>
    </div>
@endsection
