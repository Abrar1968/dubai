# Day 2: Core Feature Implementation
# Hajj Admin Panel Development

**Date:** Day 2 of 3  
**Focus:** Controllers, Form Requests, CRUD Operations, Frontend Forms

---

## Overview

Day 2 focuses on implementing the complete CRUD operations for Packages and Articles, including controllers, form requests, frontend forms, and reusable components.

---

## Tasks Checklist

### Phase 1: Package Management Backend (2-3 hours)

#### Task 1.1: Create Package Controller
- [ ] Create `PackageController` with all CRUD methods
- [ ] Implement index with filtering & pagination
- [ ] Implement create/store
- [ ] Implement edit/update
- [ ] Implement destroy (soft delete)
- [ ] Implement duplicate
- [ ] Implement toggleStatus
- [ ] Implement toggleFeatured

**Command:**
```bash
php artisan make:controller Admin/Hajj/PackageController --resource
```

**File:** `app/Http/Controllers/Admin/Hajj/PackageController.php`

#### Task 1.2: Create Package Form Request
- [ ] Create `PackageRequest` with validation rules
- [ ] Define all field validations
- [ ] Add custom error messages

**Command:**
```bash
php artisan make:request Admin/Hajj/PackageRequest
```

**File:** `app/Http/Requests/Admin/Hajj/PackageRequest.php`

#### Task 1.3: Register Package Routes
- [ ] Add all package routes to `routes/admin.php`

```php
Route::prefix('hajj')->name('hajj.')->group(function () {
    Route::resource('packages', PackageController::class);
    Route::post('packages/{package}/duplicate', [PackageController::class, 'duplicate'])->name('packages.duplicate');
    Route::put('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
    Route::put('packages/{package}/toggle-featured', [PackageController::class, 'toggleFeatured'])->name('packages.toggle-featured');
});
```

---

### Phase 2: Package Management Frontend (3-4 hours)

#### Task 2.1: Create DataTable Component
- [ ] Create reusable `DataTable.vue` component
- [ ] Support sorting, pagination, selection
- [ ] Support custom cell rendering
- [ ] Support loading state

**File:** `resources/js/components/admin/common/DataTable.vue`

**Props:**
```typescript
interface DataTableProps {
  columns: TableColumn[];
  data: any[];
  loading?: boolean;
  selectable?: boolean;
  pagination?: PaginationData;
}
```

#### Task 2.2: Create SearchFilter Component
- [ ] Create `SearchFilter.vue` component
- [ ] Support search input
- [ ] Support dropdown filters
- [ ] Emit filter change events

**File:** `resources/js/components/admin/common/SearchFilter.vue`

#### Task 2.3: Create Package List Page
- [ ] Create `resources/js/pages/admin/hajj/packages/Index.vue`
- [ ] Implement DataTable with package data
- [ ] Add search and filter functionality
- [ ] Add action buttons (Edit, Delete, Duplicate)
- [ ] Add bulk actions

**Key Features:**
- Search by title
- Filter by type (Hajj/Umrah)
- Filter by status (Active/Inactive)
- Toggle featured inline
- Pagination

#### Task 2.4: Create ImageUploader Component
- [ ] Create `ImageUploader.vue` component
- [ ] Support drag & drop
- [ ] Support click to upload
- [ ] Show preview
- [ ] Show upload progress
- [ ] Handle errors

**File:** `resources/js/components/admin/common/ImageUploader.vue`

#### Task 2.5: Create DynamicList Component
- [ ] Create `DynamicList.vue` component
- [ ] Support add/remove items
- [ ] Support drag to reorder
- [ ] Used for features, inclusions, etc.

**File:** `resources/js/components/admin/common/DynamicList.vue`

#### Task 2.6: Create Package Form Component
- [ ] Create `PackageForm.vue` component
- [ ] All form fields from SRS
- [ ] Form validation display
- [ ] Image upload integration
- [ ] Dynamic lists for features

**File:** `resources/js/components/admin/forms/PackageForm.vue`

**Sections:**
1. Basic Information (title, slug, type, price, duration)
2. Media (main image, gallery)
3. Features (dynamic list)
4. Description (rich text editor - can use simple textarea first)
5. Itinerary (dynamic day sections)
6. Hotel Information
7. Inclusions/Exclusions (two lists)
8. Availability (dates, capacity)
9. Status (featured, active toggles)

#### Task 2.7: Create Package Create Page
- [ ] Create `resources/js/pages/admin/hajj/packages/Create.vue`
- [ ] Use PackageForm component
- [ ] Handle form submission
- [ ] Handle success/error messages

#### Task 2.8: Create Package Edit Page
- [ ] Create `resources/js/pages/admin/hajj/packages/Edit.vue`
- [ ] Load existing package data
- [ ] Pre-populate form
- [ ] Handle update submission

---

### Phase 3: Article Management Backend (1-2 hours)

#### Task 3.1: Create Article Controller
- [ ] Create `ArticleController` with CRUD methods
- [ ] Implement publish/unpublish methods
- [ ] Handle image uploads

**Command:**
```bash
php artisan make:controller Admin/Hajj/ArticleController --resource
```

#### Task 3.2: Create ArticleCategory Controller
- [ ] Create `ArticleCategoryController`
- [ ] Simple CRUD for categories

**Command:**
```bash
php artisan make:controller Admin/Hajj/ArticleCategoryController --resource
```

#### Task 3.3: Create Form Requests
- [ ] Create `ArticleRequest`
- [ ] Create `ArticleCategoryRequest`

**Commands:**
```bash
php artisan make:request Admin/Hajj/ArticleRequest
php artisan make:request Admin/Hajj/ArticleCategoryRequest
```

#### Task 3.4: Register Article Routes
```php
Route::resource('articles', ArticleController::class);
Route::put('articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
Route::put('articles/{article}/unpublish', [ArticleController::class, 'unpublish'])->name('articles.unpublish');
Route::resource('categories', ArticleCategoryController::class)->except(['create', 'edit', 'show']);
```

---

### Phase 4: Article Management Frontend (2-3 hours)

#### Task 4.1: Create Article List Page
- [ ] Create `resources/js/pages/admin/hajj/articles/Index.vue`
- [ ] DataTable with articles
- [ ] Filter by category, status
- [ ] Quick publish/unpublish toggle

#### Task 4.2: Create RichTextEditor Component (Basic)
- [ ] Create `RichTextEditor.vue` component
- [ ] Basic implementation (can use textarea initially)
- [ ] Plan for Tiptap integration later

**File:** `resources/js/components/admin/common/RichTextEditor.vue`

**Note:** For Day 2, can use simple textarea with markdown support. Full Tiptap can be added in enhancement phase.

#### Task 4.3: Create Article Form Component
- [ ] Create `ArticleForm.vue`
- [ ] Title, slug, excerpt
- [ ] Category select/create
- [ ] Content editor
- [ ] Featured image
- [ ] SEO fields
- [ ] Publishing options

**File:** `resources/js/components/admin/forms/ArticleForm.vue`

#### Task 4.4: Create Article Create/Edit Pages
- [ ] Create `resources/js/pages/admin/hajj/articles/Create.vue`
- [ ] Create `resources/js/pages/admin/hajj/articles/Edit.vue`

---

### Phase 5: Confirm Modal & Toast (30 min)

#### Task 5.1: Create ConfirmModal Component
- [ ] Create `ConfirmModal.vue`
- [ ] Support danger/warning variants
- [ ] Customizable text

**File:** `resources/js/components/admin/common/ConfirmModal.vue`

#### Task 5.2: Implement Toast Notifications
- [ ] Use Inertia flash messages
- [ ] Create toast display component or use existing UI

---

### Phase 6: Media Upload Controller (1 hour)

#### Task 6.1: Create MediaController
- [ ] Create `MediaController`
- [ ] Implement uploadImage endpoint
- [ ] Implement uploadImages endpoint
- [ ] Implement deleteImage endpoint

**Command:**
```bash
php artisan make:controller Admin/MediaController
```

**File:** `app/Http/Controllers/Admin/MediaController.php`

#### Task 6.2: Register Media Routes
```php
Route::post('upload/image', [MediaController::class, 'uploadImage'])->name('upload.image');
Route::post('upload/images', [MediaController::class, 'uploadImages'])->name('upload.images');
Route::delete('upload/{path}', [MediaController::class, 'deleteImage'])->name('upload.delete')->where('path', '.*');
```

---

## File Creation Checklist

### Backend Files
```
☐ app/Http/Controllers/Admin/Hajj/PackageController.php
☐ app/Http/Controllers/Admin/Hajj/ArticleController.php
☐ app/Http/Controllers/Admin/Hajj/ArticleCategoryController.php
☐ app/Http/Controllers/Admin/MediaController.php

☐ app/Http/Requests/Admin/Hajj/PackageRequest.php
☐ app/Http/Requests/Admin/Hajj/ArticleRequest.php
☐ app/Http/Requests/Admin/Hajj/ArticleCategoryRequest.php
```

### Frontend Files
```
☐ resources/js/components/admin/common/DataTable.vue
☐ resources/js/components/admin/common/SearchFilter.vue
☐ resources/js/components/admin/common/ImageUploader.vue
☐ resources/js/components/admin/common/DynamicList.vue
☐ resources/js/components/admin/common/RichTextEditor.vue
☐ resources/js/components/admin/common/ConfirmModal.vue

☐ resources/js/components/admin/forms/PackageForm.vue
☐ resources/js/components/admin/forms/ArticleForm.vue

☐ resources/js/pages/admin/hajj/packages/Index.vue
☐ resources/js/pages/admin/hajj/packages/Create.vue
☐ resources/js/pages/admin/hajj/packages/Edit.vue

☐ resources/js/pages/admin/hajj/articles/Index.vue
☐ resources/js/pages/admin/hajj/articles/Create.vue
☐ resources/js/pages/admin/hajj/articles/Edit.vue
```

---

## Verification Steps

### End of Day 2 Checklist
- [ ] Package list page shows all packages
- [ ] Package filtering works (search, type, status)
- [ ] Create new package works
- [ ] Edit package works
- [ ] Delete package works (with confirmation)
- [ ] Duplicate package works
- [ ] Toggle status/featured works
- [ ] Image upload works
- [ ] Article list page shows all articles
- [ ] Create/Edit article works
- [ ] Publish/Unpublish works
- [ ] Form validation displays errors
- [ ] Success/error toasts display

### Test Scenarios

**Package CRUD:**
```
1. Navigate to /admin/hajj/packages
2. Click "Add Package"
3. Fill form with test data
4. Upload image
5. Submit and verify redirect
6. Edit the created package
7. Change some fields
8. Update and verify
9. Duplicate the package
10. Delete a package
```

**Article CRUD:**
```
1. Navigate to /admin/hajj/articles
2. Create new article
3. Select/create category
4. Add content
5. Save as draft
6. Publish article
7. Verify publish date set
8. Unpublish and verify
```

---

## Notes for Tomorrow (Day 3)

Day 3 will focus on:
1. Team Member management
2. Testimonial management
3. Inquiry management
4. Settings page
5. Dashboard statistics
6. Final testing and polish

**Prerequisites for Day 3:**
- Package CRUD fully working
- Article CRUD fully working
- Image upload working
- Form validation working

---

## Estimated Time: 10-12 hours

| Phase | Time |
|-------|------|
| Phase 1: Package Backend | 2-3 hours |
| Phase 2: Package Frontend | 3-4 hours |
| Phase 3: Article Backend | 1-2 hours |
| Phase 4: Article Frontend | 2-3 hours |
| Phase 5: Confirm Modal & Toast | 30 min |
| Phase 6: Media Upload | 1 hour |

---

## Code Snippets

### Package Controller Index Method
```php
public function index(): Response
{
    $packages = Package::query()
        ->when(request('search'), fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
        ->when(request('type'), fn($q, $t) => $q->where('type', $t))
        ->when(request('status') !== null, function($q) {
            return request('status') === 'active' 
                ? $q->where('is_active', true) 
                : $q->where('is_active', false);
        })
        ->orderBy(request('sort', 'created_at'), request('order', 'desc'))
        ->paginate(request('per_page', 10))
        ->withQueryString();

    return Inertia::render('admin/hajj/packages/Index', [
        'packages' => $packages,
        'filters' => request()->only(['search', 'type', 'status', 'sort', 'order']),
    ]);
}
```

### DataTable Column Definition Example
```typescript
const columns: TableColumn[] = [
  {
    key: 'image',
    label: '',
    width: '60px',
    render: (_, row) => h('img', { src: row.image_url, class: 'w-12 h-12 rounded object-cover' })
  },
  { key: 'title', label: 'Title', sortable: true },
  { key: 'type', label: 'Type', sortable: true },
  { 
    key: 'price', 
    label: 'Price', 
    sortable: true,
    render: (value, row) => `${row.currency} ${value}`
  },
  {
    key: 'is_active',
    label: 'Status',
    render: (value) => h(StatusBadge, { status: value ? 'active' : 'inactive' })
  },
  {
    key: 'actions',
    label: '',
    render: (_, row) => h(ActionDropdown, { package: row })
  }
];
```

### Inertia Form Usage
```typescript
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  title: props.package?.title ?? '',
  type: props.package?.type ?? 'hajj',
  price: props.package?.price ?? 0,
  // ... other fields
});

const submit = () => {
  if (props.package) {
    form.put(route('admin.hajj.packages.update', props.package.id));
  } else {
    form.post(route('admin.hajj.packages.store'));
  }
};
```

---

## Troubleshooting

### Image Upload Issues
```bash
# Check storage link
php artisan storage:link

# Check permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Form Validation Not Showing
```vue
<!-- Make sure to pass errors -->
<InputError :message="form.errors.title" />
```

### Inertia Page Props Not Updating
```typescript
// Use router.reload() or form.reset()
import { router } from '@inertiajs/vue3';
router.reload({ only: ['packages'] });
```
