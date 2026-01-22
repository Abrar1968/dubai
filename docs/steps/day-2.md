# Day 2: Core CRUD Modules
# Hajj Admin Panel Development

**Date:** Day 2 of 3  
**Focus:** Package CRUD, Booking Management, Article CRUD, Blade Components  
**Stack:** Laravel 12 + Blade + Alpine.js + Tailwind CSS v4

---

## Overview

Day 2 implements the core CRUD modules (Packages, Bookings, and Articles) along with reusable Blade components and image upload functionality.

---

## Prerequisites from Day 1

Before starting Day 2, verify:
- [ ] All migrations run successfully (15 tables including bookings)
- [ ] All models created with relationships
- [ ] Services created (PackageService, BookingService, ArticleService, MediaService)
- [ ] Admin layout functional with role-based sidebar
- [ ] Login working for all roles
- [ ] Dashboard displaying

---

## Tasks Checklist

### Phase 1: Reusable Blade Components (2-2.5 hours)

#### Task 1.1: Create Component Directory

```
resources/views/admin/components/
├── ui/
│   ├── button.blade.php
│   ├── input.blade.php
│   ├── textarea.blade.php
│   ├── select.blade.php
│   ├── checkbox.blade.php
│   ├── toggle.blade.php
│   ├── alert.blade.php
│   ├── badge.blade.php
│   ├── card.blade.php
│   └── modal.blade.php
├── form/
│   ├── image-upload.blade.php
│   ├── multi-image-upload.blade.php
│   ├── rich-editor.blade.php
│   └── date-picker.blade.php
└── data/
    ├── table.blade.php
    ├── pagination.blade.php
    └── empty-state.blade.php
```

#### Task 1.2: Implement UI Components

**button.blade.php**
Props: type, variant (primary/secondary/danger), size, icon, loading, disabled, href

**input.blade.php**
Props: name, type, label, value, error, placeholder, required, icon, hint

**textarea.blade.php**
Props: name, label, value, error, rows, placeholder, required

**select.blade.php**
Props: name, label, options, value, error, placeholder, required

**toggle.blade.php**
Props: name, label, checked, disabled, description

**alert.blade.php**
Props: type (success/error/warning/info), message, dismissible

**badge.blade.php**
Props: type (success/warning/danger/info), text

**card.blade.php**
Props: title, description, footer slot, actions slot

**modal.blade.php** (Alpine.js)
Props: name, title, maxWidth, closeable
Alpine: x-data, @keydown.escape

#### Task 1.3: Implement Form Components

**image-upload.blade.php**
Features:
- Drag & drop zone
- Image preview
- Progress indicator
- Remove button
- File type validation
- Alpine.js handling

**multi-image-upload.blade.php**
Features:
- Multiple file selection
- Drag & drop
- Sortable previews
- Individual remove
- Limit enforcement

**rich-editor.blade.php**
Options:
- TinyMCE or Quill integration
- Basic formatting toolbar
- Image upload
- Clean paste

**date-picker.blade.php**
Features:
- Calendar popup
- Date format display
- Range selection option
- Alpine.js integration

#### Task 1.4: Implement Data Components

**table.blade.php**
Props: columns, sortable, selectable
Features:
- Responsive horizontal scroll
- Sortable headers
- Row selection
- Hover states
- Actions slot

**pagination.blade.php**
Props: paginator
Features:
- Previous/Next
- Page numbers
- Per-page selector

**empty-state.blade.php**
Props: icon, title, description, action

---

### Phase 2: Package Controller (1 hour)

#### Task 2.1: Create Controller

```bash
php artisan make:controller Admin/Hajj/PackageController
```

#### Task 2.2: Implement Controller Methods

| Method | Route | Purpose |
|--------|-------|---------|
| index | GET /admin/packages | List packages |
| create | GET /admin/packages/create | Show create form |
| store | POST /admin/packages | Save new package |
| show | GET /admin/packages/{id} | View package |
| edit | GET /admin/packages/{id}/edit | Show edit form |
| update | PUT /admin/packages/{id} | Update package |
| destroy | DELETE /admin/packages/{id} | Delete package |
| toggleStatus | PATCH /admin/packages/{id}/toggle | Toggle active |
| toggleFeatured | PATCH /admin/packages/{id}/featured | Toggle featured |
| reorderGallery | POST /admin/packages/{id}/gallery/reorder | Sort gallery |
| deleteGalleryImage | DELETE /admin/packages/{id}/gallery/{image} | Remove image |

#### Task 2.3: Use Service Pattern

```php
public function __construct(
    private PackageService $packageService,
    private MediaService $mediaService
) {}
```

---

### Phase 3: Package Form Request (45 min)

#### Task 3.1: Create Form Request

```bash
php artisan make:request Admin/PackageRequest
```

#### Task 3.2: Define Validation Rules

```php
public function rules(): array
{
    $packageId = $this->route('package');
    
    return [
        'name' => ['required', 'string', 'max:255'],
        'slug' => ['nullable', 'string', 'max:255', Rule::unique('packages')->ignore($packageId)],
        'type' => ['required', Rule::enum(PackageType::class)],
        'price' => ['required', 'numeric', 'min:0'],
        'duration_days' => ['required', 'integer', 'min:1'],
        'departure_date' => ['nullable', 'date', 'after_or_equal:today'],
        'short_description' => ['required', 'string', 'max:500'],
        'description' => ['required', 'string'],
        'includes' => ['nullable', 'array'],
        'excludes' => ['nullable', 'array'],
        'itinerary' => ['nullable', 'array'],
        'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        'gallery.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        'is_active' => ['boolean'],
        'is_featured' => ['boolean'],
    ];
}
```

#### Task 3.3: Define Custom Messages

Add user-friendly error messages.

---

### Phase 4: Package Views (2.5-3 hours)

#### Task 4.1: Create Package Views Directory

```
resources/views/admin/pages/packages/
├── index.blade.php
├── create.blade.php
├── edit.blade.php
└── show.blade.php
```

#### Task 4.2: Implement Index View

**index.blade.php features:**
- Search input
- Filter buttons (All, Hajj, Umrah, Tour)
- Status filter dropdown
- Data table with:
  - Thumbnail
  - Name (linked)
  - Type badge
  - Price
  - Duration
  - Status toggle
  - Featured toggle
  - Actions dropdown
- Pagination
- Empty state

#### Task 4.3: Implement Create/Edit Form

**create.blade.php / edit.blade.php features:**

**Tab 1: Basic Information**
- Name input
- Slug input (auto-generate)
- Type select
- Price input
- Duration input
- Departure date picker
- Short description textarea
- Status toggle
- Featured toggle

**Tab 2: Description**
- Rich text editor
- Full description
- Preview option

**Tab 3: Includes/Excludes**
- Dynamic list builder (Alpine.js)
- Add/remove items
- Drag to reorder

**Tab 4: Itinerary**
- Day-by-day builder
- Day number
- Title
- Description
- Activities list

**Tab 5: Media**
- Featured image upload
- Gallery multi-upload
- Drag to reorder
- Delete images

**Form Footer:**
- Cancel button
- Save as Draft (create only)
- Save button
- Loading states

#### Task 4.4: Implement Show View

**show.blade.php features:**
- Header with title, actions
- Status badges
- Information cards
- Gallery preview
- Itinerary timeline
- Related testimonials
- Related inquiries
- Edit/Delete buttons

---

### Phase 5: PackageService Implementation (1.5 hours)

#### Task 5.1: Complete CRUD Methods

```php
class PackageService
{
    public function list(array $filters = []): LengthAwarePaginator
    public function getById(int $id): Package
    public function create(array $data): Package
    public function update(Package $package, array $data): Package
    public function delete(Package $package): bool
    public function toggleActive(Package $package): Package
    public function toggleFeatured(Package $package): Package
}
```

#### Task 5.2: Implement Image Handling

```php
private function handleFeaturedImage(array $data, ?Package $package = null): array
{
    // Handle featured image upload
    // Delete old image if replacing
}

private function handleGalleryImages(Package $package, array $images): void
{
    // Upload each image
    // Create PackageGallery records
}

public function reorderGallery(Package $package, array $order): void
{
    // Update sort_order for each gallery item
}

public function deleteGalleryImage(PackageGallery $image): bool
{
    // Delete file
    // Delete record
}
```

#### Task 5.3: Implement Filters

```php
private function applyFilters(Builder $query, array $filters): Builder
{
    if ($filters['type'] ?? null) {
        $query->where('type', $filters['type']);
    }
    if ($filters['status'] ?? null) {
        $query->where('is_active', $filters['status'] === 'active');
    }
    if ($filters['search'] ?? null) {
        $query->where('name', 'like', "%{$filters['search']}%");
    }
    return $query;
}
```

---

### Phase 6: Article Controller (1 hour)

#### Task 6.1: Create Controller

```bash
php artisan make:controller Admin/Hajj/ArticleController
```

#### Task 6.2: Implement Methods

| Method | Route | Purpose |
|--------|-------|---------|
| index | GET /admin/articles | List articles |
| create | GET /admin/articles/create | Show create form |
| store | POST /admin/articles | Save article |
| edit | GET /admin/articles/{id}/edit | Show edit form |
| update | PUT /admin/articles/{id} | Update article |
| destroy | DELETE /admin/articles/{id} | Delete article |
| togglePublish | PATCH /admin/articles/{id}/publish | Toggle publish |
| preview | GET /admin/articles/{id}/preview | Preview article |

---

### Phase 7: Article Form Request (30 min)

#### Task 7.1: Create Form Request

```bash
php artisan make:request Admin/ArticleRequest
```

#### Task 7.2: Define Validation

```php
public function rules(): array
{
    $articleId = $this->route('article');
    
    return [
        'title' => ['required', 'string', 'max:255'],
        'slug' => ['nullable', 'string', 'max:255', Rule::unique('articles')->ignore($articleId)],
        'article_category_id' => ['required', 'exists:article_categories,id'],
        'excerpt' => ['nullable', 'string', 'max:500'],
        'content' => ['required', 'string'],
        'featured_image' => ['nullable', 'image', 'max:5120'],
        'meta_title' => ['nullable', 'string', 'max:70'],
        'meta_description' => ['nullable', 'string', 'max:160'],
        'status' => ['required', Rule::enum(PublishStatus::class)],
        'published_at' => ['nullable', 'date'],
    ];
}
```

---

### Phase 8: Article Views (2-2.5 hours)

#### Task 8.1: Create Article Views

```
resources/views/admin/pages/articles/
├── index.blade.php
├── create.blade.php
├── edit.blade.php
└── preview.blade.php
```

#### Task 8.2: Implement Index View

**index.blade.php features:**
- Search input
- Category filter
- Status filter (All, Published, Draft)
- Data table with:
  - Featured image thumb
  - Title (linked)
  - Category badge
  - Author
  - Status badge
  - Published date
  - View count (if tracked)
  - Actions
- Bulk actions (publish, unpublish, delete)
- Pagination

#### Task 8.3: Implement Create/Edit Form

**Two-column layout:**

**Left Column (2/3 width):**
- Title input
- Slug input (auto-generate from title)
- Excerpt textarea
- Content rich editor

**Right Column (1/3 width):**
- Publish panel
  - Status select
  - Published date picker
  - Save button
- Category select
- Featured image upload
- SEO panel
  - Meta title input
  - Meta description textarea
  - SEO preview

#### Task 8.4: Implement Preview

**preview.blade.php:**
- Article formatted as public view
- Header with "Back to Edit" button
- Responsive preview sizes

---

### Phase 9: ArticleService Implementation (1 hour)

#### Task 9.1: Complete CRUD Methods

```php
class ArticleService
{
    public function list(array $filters = []): LengthAwarePaginator
    public function getById(int $id): Article
    public function create(array $data): Article
    public function update(Article $article, array $data): Article
    public function delete(Article $article): bool
    public function publish(Article $article): Article
    public function unpublish(Article $article): Article
}
```

#### Task 9.2: Handle Publishing Logic

```php
private function handlePublishing(array $data): array
{
    if ($data['status'] === PublishStatus::PUBLISHED->value) {
        $data['published_at'] ??= now();
    }
    return $data;
}
```

---

### Phase 10: Article Category Management (1 hour)

#### Task 10.1: Create Controller

```bash
php artisan make:controller Admin/Hajj/ArticleCategoryController
```

#### Task 10.2: Implement Simple CRUD

Categories are simpler - can be inline managed:
- Modal-based create/edit
- Ajax operations
- Inline delete with confirmation

#### Task 10.3: Create Views

```
resources/views/admin/pages/articles/
└── categories/
    └── index.blade.php (includes modal)
```

---

### Phase 10B: Booking Management (1.5 hours)

> **Note:** This phase is for Admin/Super Admin booking oversight.

#### Task 10B.1: Create Booking Controller

```bash
php artisan make:controller Admin/Hajj/BookingController
```

#### Task 10B.2: Implement Controller Methods

| Method | Route | Purpose |
|--------|-------|---------|
| index | GET /admin/bookings | List all bookings |
| show | GET /admin/bookings/{id} | View booking details |
| updateStatus | PATCH /admin/bookings/{id}/status | Update booking status |
| addNote | POST /admin/bookings/{id}/notes | Add internal note |
| exportPdf | GET /admin/bookings/{id}/pdf | Export booking PDF |
| exportList | GET /admin/bookings/export | Export bookings list |

#### Task 10B.3: BookingService Implementation

```php
class BookingService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return Booking::query()
            ->with(['user', 'package', 'travelers'])
            ->when($filters['status'] ?? null, fn($q, $s) => $q->where('status', $s))
            ->when($filters['package_id'] ?? null, fn($q, $p) => $q->where('package_id', $p))
            ->when($filters['search'] ?? null, fn($q, $s) => 
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%"))
            )
            ->latest()
            ->paginate(15);
    }
    
    public function updateStatus(Booking $booking, string $status, ?string $note = null): Booking
    {
        $oldStatus = $booking->status;
        $booking->update(['status' => $status]);
        
        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'old_status' => $oldStatus,
            'new_status' => $status,
            'note' => $note,
            'changed_by' => auth()->id(),
        ]);
        
        // Send notification to user
        $booking->user->notify(new BookingStatusChanged($booking));
        
        return $booking->fresh();
    }
    
    public function getTravelers(Booking $booking): Collection
    {
        return $booking->travelers;
    }
    
    public function getStatusHistory(Booking $booking): Collection
    {
        return $booking->statusLogs()->with('changedBy')->latest()->get();
    }
}
```

#### Task 10B.4: Create Booking Views

```
resources/views/admin/pages/bookings/
├── index.blade.php
└── show.blade.php
```

**index.blade.php features:**
- Search by customer name/email
- Filter by status (pending, confirmed, paid, processing, ready, completed, cancelled)
- Filter by package
- Date range filter
- Data table with:
  - Booking reference
  - Customer name
  - Package name
  - Travelers count
  - Total amount
  - Status badge
  - Created date
  - Actions (View)
- Export to Excel/PDF
- Pagination

**show.blade.php features:**
- Booking header with status
- Customer information card
- Package details card
- Travelers list with details
- Payment information
- Status timeline/history
- Admin notes section
- Status update dropdown
- Quick actions (Send email, Download PDF)

#### Task 10B.5: Add Booking Routes

```php
// routes/admin.php

Route::prefix('bookings')->name('bookings.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::get('/export', [BookingController::class, 'exportList'])->name('export');
    Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
    Route::patch('/{booking}/status', [BookingController::class, 'updateStatus'])->name('status');
    Route::post('/{booking}/notes', [BookingController::class, 'addNote'])->name('notes');
    Route::get('/{booking}/pdf', [BookingController::class, 'exportPdf'])->name('pdf');
});
```

---

### Phase 11: MediaService Implementation (45 min)

#### Task 11.1: Complete Upload Methods

```php
class MediaService
{
    public function uploadImage(UploadedFile $file, string $path): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($path, $filename, 'public');
        return $path . '/' . $filename;
    }

    public function uploadImages(array $files, string $path): array
    {
        return array_map(fn($file) => $this->uploadImage($file, $path), $files);
    }

    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
    
    public function createThumbnail(string $path, int $width = 300): string
    {
        // Use Intervention Image or similar
    }
}
```

---

### Phase 12: Alpine.js Interactive Features (1 hour)

#### Task 12.1: Dynamic List Builder

For package includes/excludes:
```javascript
function listBuilder(items = []) {
    return {
        items: items,
        newItem: '',
        add() {
            if (this.newItem.trim()) {
                this.items.push(this.newItem.trim());
                this.newItem = '';
            }
        },
        remove(index) {
            this.items.splice(index, 1);
        },
        // Drag & drop using SortableJS
    }
}
```

#### Task 12.2: Itinerary Builder

```javascript
function itineraryBuilder(days = []) {
    return {
        days: days,
        addDay() {
            this.days.push({
                day: this.days.length + 1,
                title: '',
                description: '',
                activities: []
            });
        },
        removeDay(index) {
            this.days.splice(index, 1);
            // Renumber days
        }
    }
}
```

#### Task 12.3: Slug Generator

```javascript
function slugGenerator() {
    return {
        title: '',
        slug: '',
        autoGenerate: true,
        generateSlug() {
            if (this.autoGenerate) {
                this.slug = this.title
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-|-$/g, '');
            }
        }
    }
}
```

---

### Phase 13: Routes Update (30 min)

#### Task 13.1: Add Package Routes

```php
// routes/admin.php

Route::prefix('packages')->name('packages.')->group(function () {
    Route::get('/', [PackageController::class, 'index'])->name('index');
    Route::get('/create', [PackageController::class, 'create'])->name('create');
    Route::post('/', [PackageController::class, 'store'])->name('store');
    Route::get('/{package}', [PackageController::class, 'show'])->name('show');
    Route::get('/{package}/edit', [PackageController::class, 'edit'])->name('edit');
    Route::put('/{package}', [PackageController::class, 'update'])->name('update');
    Route::delete('/{package}', [PackageController::class, 'destroy'])->name('destroy');
    Route::patch('/{package}/toggle', [PackageController::class, 'toggleStatus'])->name('toggle');
    Route::patch('/{package}/featured', [PackageController::class, 'toggleFeatured'])->name('featured');
    Route::post('/{package}/gallery/reorder', [PackageController::class, 'reorderGallery'])->name('gallery.reorder');
    Route::delete('/{package}/gallery/{image}', [PackageController::class, 'deleteGalleryImage'])->name('gallery.delete');
});
```

#### Task 13.2: Add Article Routes

```php
Route::prefix('articles')->name('articles.')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/create', [ArticleController::class, 'create'])->name('create');
    Route::post('/', [ArticleController::class, 'store'])->name('store');
    Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
    Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
    Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
    Route::patch('/{article}/publish', [ArticleController::class, 'togglePublish'])->name('publish');
    Route::get('/{article}/preview', [ArticleController::class, 'preview'])->name('preview');
    
    // Categories
    Route::get('/categories', [ArticleCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [ArticleCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [ArticleCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [ArticleCategoryController::class, 'destroy'])->name('categories.destroy');
});
```

---

## File Creation Checklist

### Controllers
```
☐ app/Http/Controllers/Admin/Hajj/PackageController.php
☐ app/Http/Controllers/Admin/Hajj/ArticleController.php
☐ app/Http/Controllers/Admin/Hajj/ArticleCategoryController.php
☐ app/Http/Controllers/Admin/Hajj/BookingController.php
```

### Form Requests
```
☐ app/Http/Requests/Admin/PackageRequest.php
☐ app/Http/Requests/Admin/ArticleRequest.php
☐ app/Http/Requests/Admin/BookingStatusRequest.php
```

### Components (UI)
```
☐ resources/views/admin/components/ui/button.blade.php
☐ resources/views/admin/components/ui/input.blade.php
☐ resources/views/admin/components/ui/textarea.blade.php
☐ resources/views/admin/components/ui/select.blade.php
☐ resources/views/admin/components/ui/checkbox.blade.php
☐ resources/views/admin/components/ui/toggle.blade.php
☐ resources/views/admin/components/ui/alert.blade.php
☐ resources/views/admin/components/ui/badge.blade.php
☐ resources/views/admin/components/ui/card.blade.php
☐ resources/views/admin/components/ui/modal.blade.php
```

### Components (Form)
```
☐ resources/views/admin/components/form/image-upload.blade.php
☐ resources/views/admin/components/form/multi-image-upload.blade.php
☐ resources/views/admin/components/form/rich-editor.blade.php
☐ resources/views/admin/components/form/date-picker.blade.php
```

### Components (Data)
```
☐ resources/views/admin/components/data/table.blade.php
☐ resources/views/admin/components/data/pagination.blade.php
☐ resources/views/admin/components/data/empty-state.blade.php
```

### Package Views
```
☐ resources/views/admin/pages/packages/index.blade.php
☐ resources/views/admin/pages/packages/create.blade.php
☐ resources/views/admin/pages/packages/edit.blade.php
☐ resources/views/admin/pages/packages/show.blade.php
```

### Article Views
```
☐ resources/views/admin/pages/articles/index.blade.php
☐ resources/views/admin/pages/articles/create.blade.php
☐ resources/views/admin/pages/articles/edit.blade.php
☐ resources/views/admin/pages/articles/preview.blade.php
☐ resources/views/admin/pages/articles/categories/index.blade.php
```

### Booking Views (Admin)
```
☐ resources/views/admin/pages/bookings/index.blade.php
☐ resources/views/admin/pages/bookings/show.blade.php
```

---

## End of Day 2 Verification

### Functionality Checklist
- [ ] Package list displays with filters
- [ ] Package create form works
- [ ] Package edit form populates data
- [ ] Package image upload works
- [ ] Package gallery works
- [ ] Package delete works
- [ ] Article list displays with filters
- [ ] Article create/edit works
- [ ] Article rich editor works
- [ ] Article publish/unpublish works
- [ ] Article categories manageable
- [ ] Booking list displays with filters
- [ ] Booking detail view works
- [ ] Booking status update works
- [ ] Booking status history displays
- [ ] All form validation working
- [ ] All components reusable

### Test Actions

1. **Package CRUD:**
   - Create new Hajj package with all fields
   - Upload featured image
   - Add gallery images
   - Edit and update
   - Toggle active/featured
   - Delete package

2. **Article CRUD:**
   - Create article category
   - Create new article with image
   - Preview article
   - Publish article
   - Edit and update
   - Unpublish
   - Delete article

3. **Booking Management:**
   - View bookings list with filters
   - View booking details
   - Update booking status
   - Verify status history is logged
   - Add admin note to booking

---

## Notes for Day 3

Day 3 will focus on:
- Team Members CRUD
- Testimonials CRUD
- Contact Inquiries management
- Site Settings
- **User Dashboard & Booking Interface**
- Admin User Management (Super Admin only)
- Dashboard statistics
- Final polish and testing

**Prerequisites:**
- All components working
- Services implemented
- Package & Article modules complete
- Booking management complete

---

## Estimated Time: 14.5-16.5 hours

| Phase | Time |
|-------|------|
| Phase 1: Blade Components | 2-2.5 hours |
| Phase 2: Package Controller | 1 hour |
| Phase 3: Package Request | 45 min |
| Phase 4: Package Views | 2.5-3 hours |
| Phase 5: PackageService | 1.5 hours |
| Phase 6: Article Controller | 1 hour |
| Phase 7: Article Request | 30 min |
| Phase 8: Article Views | 2-2.5 hours |
| Phase 9: ArticleService | 1 hour |
| Phase 10: Categories | 1 hour |
| Phase 10B: Booking Management | 1.5 hours |
| Phase 11: MediaService | 45 min |
| Phase 12: Alpine.js Features | 1 hour |
| Phase 13: Routes | 30 min |

---

## Troubleshooting

**Image not uploading:**
```bash
php artisan storage:link
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

**Validation errors not showing:**
- Check `@error` directive usage
- Verify `old()` values
- Check form method spoofing

**Alpine.js not working:**
- Check script inclusion
- Verify x-data syntax
- Check browser console

**Rich editor not loading:**
- Verify CDN/package import
- Check initialization timing
- Review console errors

---

*End of Day 2 Steps*
