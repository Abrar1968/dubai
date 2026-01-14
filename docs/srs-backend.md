# Backend Software Requirements Specification (SRS)
# Hajj Section - Admin Panel

**Version:** 1.0  
**Date:** January 14, 2026  
**Project:** Dubai Travel & Services Admin Panel

---

## Table of Contents
1. [Introduction](#1-introduction)
2. [System Architecture](#2-system-architecture)
3. [Database Schema](#3-database-schema)
4. [API Endpoints](#4-api-endpoints)
5. [Controllers](#5-controllers)
6. [Models & Relationships](#6-models--relationships)
7. [Form Requests (Validation)](#7-form-requests-validation)
8. [Services](#8-services)
9. [File Storage](#9-file-storage)
10. [Authentication & Authorization](#10-authentication--authorization)
11. [Migrations](#11-migrations)
12. [Seeders](#12-seeders)
13. [Testing Requirements](#13-testing-requirements)

---

## 1. Introduction

### 1.1 Purpose
This document specifies the backend requirements for the Hajj Section Admin Panel, including database design, API endpoints, controllers, models, and all server-side logic.

### 1.2 Technology Stack
| Component | Technology | Version |
|-----------|------------|---------|
| Framework | Laravel | 12.x |
| Database | MySQL | 8.x |
| PHP | PHP | 8.2+ |
| Authentication | Laravel Fortify | 1.x |
| File Storage | Laravel Storage | Local/S3 |
| Queue | Database/Redis | - |

### 1.3 Coding Standards
- Follow PSR-12 coding standards
- Use Laravel Pint for formatting
- Type hints for all parameters and returns
- DocBlocks for all public methods

---

## 2. System Architecture

### 2.1 Directory Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       └── Hajj/
│   │           ├── PackageController.php
│   │           ├── ArticleController.php
│   │           ├── TeamController.php
│   │           ├── TestimonialController.php
│   │           ├── InquiryController.php
│   │           └── SettingController.php
│   ├── Middleware/
│   │   └── AdminAccess.php
│   └── Requests/
│       └── Admin/
│           └── Hajj/
│               ├── PackageRequest.php
│               ├── ArticleRequest.php
│               ├── TeamMemberRequest.php
│               ├── TestimonialRequest.php
│               └── SettingRequest.php
├── Models/
│   ├── Package.php
│   ├── Article.php
│   ├── ArticleCategory.php
│   ├── TeamMember.php
│   ├── Testimonial.php
│   ├── ContactInquiry.php
│   ├── SiteSetting.php
│   └── OfficeLocation.php
├── Services/
│   ├── ImageUploadService.php
│   ├── SlugService.php
│   └── SettingService.php
└── Enums/
    ├── PackageType.php
    ├── InquiryStatus.php
    └── PublishStatus.php
```

### 2.2 Route Organization
```
routes/
├── web.php              # Public routes
├── admin.php            # Admin routes (new file)
└── api.php              # API routes (if needed)
```

---

## 3. Database Schema

### 3.1 packages Table
```sql
CREATE TABLE packages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) UNIQUE NOT NULL,
    type ENUM('hajj', 'umrah', 'tour') NOT NULL DEFAULT 'hajj',
    price DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) NOT NULL DEFAULT 'USD',
    duration_days INT UNSIGNED NOT NULL,
    image VARCHAR(500) NULL,
    thumbnail VARCHAR(500) NULL,
    features JSON NULL,
    description TEXT NULL,
    inclusions JSON NULL,
    exclusions JSON NULL,
    itinerary JSON NULL,
    hotel_details JSON NULL,
    departure_dates JSON NULL,
    max_capacity INT UNSIGNED NULL,
    current_bookings INT UNSIGNED DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_type (type),
    INDEX idx_is_active (is_active),
    INDEX idx_is_featured (is_featured)
);
```

### 3.2 package_gallery Table
```sql
CREATE TABLE package_gallery (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    package_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);
```

### 3.3 article_categories Table
```sql
CREATE TABLE article_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) UNIQUE NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### 3.4 articles Table
```sql
CREATE TABLE articles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) UNIQUE NOT NULL,
    excerpt VARCHAR(500) NULL,
    content LONGTEXT NULL,
    category_id BIGINT UNSIGNED NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    image VARCHAR(500) NULL,
    thumbnail VARCHAR(500) NULL,
    meta_title VARCHAR(60) NULL,
    meta_description VARCHAR(160) NULL,
    tags JSON NULL,
    status ENUM('draft', 'published') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    views_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (category_id) REFERENCES article_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_status (status),
    INDEX idx_category (category_id),
    INDEX idx_published_at (published_at)
);
```

### 3.5 team_members Table
```sql
CREATE TABLE team_members (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    image VARCHAR(500) NULL,
    bio TEXT NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(50) NULL,
    social_links JSON NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_is_active (is_active),
    INDEX idx_sort_order (sort_order)
);
```

### 3.6 testimonials Table
```sql
CREATE TABLE testimonials (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(100) NULL,
    avatar VARCHAR(500) NULL,
    rating TINYINT UNSIGNED NOT NULL DEFAULT 5,
    content TEXT NOT NULL,
    package_id BIGINT UNSIGNED NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL,
    
    INDEX idx_is_approved (is_approved),
    INDEX idx_is_featured (is_featured)
);
```

### 3.7 contact_inquiries Table
```sql
CREATE TABLE contact_inquiries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    package_id BIGINT UNSIGNED NULL,
    status ENUM('new', 'read', 'responded', 'closed') DEFAULT 'new',
    admin_notes TEXT NULL,
    responded_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL,
    
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);
```

### 3.8 site_settings Table
```sql
CREATE TABLE site_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section VARCHAR(50) NOT NULL DEFAULT 'hajj',
    key VARCHAR(100) NOT NULL,
    value TEXT NULL,
    type ENUM('string', 'text', 'json', 'boolean', 'integer') DEFAULT 'string',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    UNIQUE KEY unique_section_key (section, key)
);
```

### 3.9 office_locations Table
```sql
CREATE TABLE office_locations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section VARCHAR(50) NOT NULL DEFAULT 'hajj',
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(50) NULL,
    email VARCHAR(255) NULL,
    map_lat DECIMAL(10, 8) NULL,
    map_lng DECIMAL(11, 8) NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_section (section)
);
```

---

## 4. API Endpoints

### 4.1 Package Endpoints
| Method | Endpoint | Controller Method | Description |
|--------|----------|-------------------|-------------|
| GET | `/admin/hajj/packages` | `index` | List all packages |
| GET | `/admin/hajj/packages/create` | `create` | Show create form |
| POST | `/admin/hajj/packages` | `store` | Store new package |
| GET | `/admin/hajj/packages/{id}/edit` | `edit` | Show edit form |
| PUT | `/admin/hajj/packages/{id}` | `update` | Update package |
| DELETE | `/admin/hajj/packages/{id}` | `destroy` | Delete package |
| POST | `/admin/hajj/packages/{id}/duplicate` | `duplicate` | Duplicate package |
| PUT | `/admin/hajj/packages/{id}/toggle-status` | `toggleStatus` | Toggle active status |
| PUT | `/admin/hajj/packages/{id}/toggle-featured` | `toggleFeatured` | Toggle featured |

### 4.2 Article Endpoints
| Method | Endpoint | Controller Method | Description |
|--------|----------|-------------------|-------------|
| GET | `/admin/hajj/articles` | `index` | List all articles |
| GET | `/admin/hajj/articles/create` | `create` | Show create form |
| POST | `/admin/hajj/articles` | `store` | Store new article |
| GET | `/admin/hajj/articles/{id}/edit` | `edit` | Show edit form |
| PUT | `/admin/hajj/articles/{id}` | `update` | Update article |
| DELETE | `/admin/hajj/articles/{id}` | `destroy` | Delete article |
| PUT | `/admin/hajj/articles/{id}/publish` | `publish` | Publish article |
| PUT | `/admin/hajj/articles/{id}/unpublish` | `unpublish` | Unpublish article |

### 4.3 Article Category Endpoints
| Method | Endpoint | Controller Method | Description |
|--------|----------|-------------------|-------------|
| GET | `/admin/hajj/categories` | `index` | List categories |
| POST | `/admin/hajj/categories` | `store` | Create category |
| PUT | `/admin/hajj/categories/{id}` | `update` | Update category |
| DELETE | `/admin/hajj/categories/{id}` | `destroy` | Delete category |

### 4.4 Team Member Endpoints
| Method | Endpoint | Controller Method | Description |
|--------|----------|-------------------|-------------|
| GET | `/admin/hajj/team` | `index` | List team members |
| POST | `/admin/hajj/team` | `store` | Add team member |
| PUT | `/admin/hajj/team/{id}` | `update` | Update member |
| DELETE | `/admin/hajj/team/{id}` | `destroy` | Delete member |
| PUT | `/admin/hajj/team/reorder` | `reorder` | Reorder members |

### 4.5 Testimonial Endpoints
| Method | Endpoint | Controller Method | Description |
|--------|----------|-------------------|-------------|
| GET | `/admin/hajj/testimonials` | `index` | List testimonials |
| POST | `/admin/hajj/testimonials` | `store` | Add testimonial |
| PUT | `/admin/hajj/testimonials/{id}` | `update` | Update testimonial |
| DELETE | `/admin/hajj/testimonials/{id}` | `destroy` | Delete testimonial |
| PUT | `/admin/hajj/testimonials/{id}/approve` | `approve` | Approve testimonial |
| PUT | `/admin/hajj/testimonials/{id}/reject` | `reject` | Reject testimonial |

### 4.6 Inquiry Endpoints
| Method | Endpoint | Controller Method | Description |
|--------|----------|-------------------|-------------|
| GET | `/admin/hajj/inquiries` | `index` | List inquiries |
| GET | `/admin/hajj/inquiries/{id}` | `show` | View inquiry detail |
| PUT | `/admin/hajj/inquiries/{id}/status` | `updateStatus` | Update status |
| PUT | `/admin/hajj/inquiries/{id}/notes` | `updateNotes` | Update admin notes |
| DELETE | `/admin/hajj/inquiries/{id}` | `destroy` | Delete inquiry |

### 4.7 Settings Endpoints
| Method | Endpoint | Controller Method | Description |
|--------|----------|-------------------|-------------|
| GET | `/admin/hajj/settings` | `index` | Get all settings |
| PUT | `/admin/hajj/settings` | `update` | Update settings |
| GET | `/admin/hajj/settings/offices` | `offices` | Get office locations |
| POST | `/admin/hajj/settings/offices` | `storeOffice` | Add office |
| PUT | `/admin/hajj/settings/offices/{id}` | `updateOffice` | Update office |
| DELETE | `/admin/hajj/settings/offices/{id}` | `destroyOffice` | Delete office |

### 4.8 Dashboard Endpoints
| Method | Endpoint | Controller Method | Description |
|--------|----------|-------------------|-------------|
| GET | `/admin/dashboard` | `index` | Dashboard with stats |

### 4.9 Media Upload Endpoints
| Method | Endpoint | Controller Method | Description |
|--------|----------|-------------------|-------------|
| POST | `/admin/upload/image` | `uploadImage` | Upload single image |
| POST | `/admin/upload/images` | `uploadImages` | Upload multiple images |
| DELETE | `/admin/upload/{path}` | `deleteImage` | Delete image |

---

## 5. Controllers

### 5.1 PackageController
```php
<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hajj\PackageRequest;
use App\Models\Package;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PackageController extends Controller
{
    public function __construct(
        private ImageUploadService $imageService
    ) {}

    /**
     * Display a listing of packages.
     */
    public function index(): Response
    {
        $packages = Package::query()
            ->when(request('search'), fn($q, $search) => 
                $q->where('title', 'like', "%{$search}%")
            )
            ->when(request('type'), fn($q, $type) => 
                $q->where('type', $type)
            )
            ->when(request('status'), fn($q, $status) => 
                $q->where('is_active', $status === 'active')
            )
            ->latest()
            ->paginate(request('per_page', 10));

        return Inertia::render('admin/hajj/packages/Index', [
            'packages' => $packages,
            'filters' => request()->only(['search', 'type', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new package.
     */
    public function create(): Response
    {
        return Inertia::render('admin/hajj/packages/Create');
    }

    /**
     * Store a newly created package.
     */
    public function store(PackageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageService->upload(
                $request->file('image'),
                'packages'
            );
        }

        Package::create($data);

        return redirect()
            ->route('admin.hajj.packages.index')
            ->with('success', 'Package created successfully.');
    }

    /**
     * Show the form for editing the package.
     */
    public function edit(Package $package): Response
    {
        return Inertia::render('admin/hajj/packages/Edit', [
            'package' => $package,
        ]);
    }

    /**
     * Update the specified package.
     */
    public function update(PackageRequest $request, Package $package): RedirectResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $this->imageService->delete($package->image);
            $data['image'] = $this->imageService->upload(
                $request->file('image'),
                'packages'
            );
        }

        $package->update($data);

        return redirect()
            ->route('admin.hajj.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified package.
     */
    public function destroy(Package $package): RedirectResponse
    {
        $this->imageService->delete($package->image);
        $package->delete();

        return redirect()
            ->route('admin.hajj.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    /**
     * Duplicate a package.
     */
    public function duplicate(Package $package): RedirectResponse
    {
        $newPackage = $package->replicate();
        $newPackage->title = "Copy of {$package->title}";
        $newPackage->slug = null; // Will be auto-generated
        $newPackage->is_active = false;
        $newPackage->save();

        return redirect()
            ->route('admin.hajj.packages.edit', $newPackage)
            ->with('success', 'Package duplicated successfully.');
    }

    /**
     * Toggle package active status.
     */
    public function toggleStatus(Package $package): RedirectResponse
    {
        $package->update(['is_active' => !$package->is_active]);

        return back()->with('success', 'Package status updated.');
    }

    /**
     * Toggle package featured status.
     */
    public function toggleFeatured(Package $package): RedirectResponse
    {
        $package->update(['is_featured' => !$package->is_featured]);

        return back()->with('success', 'Package featured status updated.');
    }
}
```

### 5.2 ArticleController (Signature)
```php
<?php

namespace App\Http\Controllers\Admin\Hajj;

class ArticleController extends Controller
{
    public function index(): Response;
    public function create(): Response;
    public function store(ArticleRequest $request): RedirectResponse;
    public function edit(Article $article): Response;
    public function update(ArticleRequest $request, Article $article): RedirectResponse;
    public function destroy(Article $article): RedirectResponse;
    public function publish(Article $article): RedirectResponse;
    public function unpublish(Article $article): RedirectResponse;
}
```

### 5.3 Other Controllers (Signatures)
Similar patterns for:
- `TeamController`
- `TestimonialController`
- `InquiryController`
- `SettingController`
- `DashboardController`

---

## 6. Models & Relationships

### 6.1 Package Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'price',
        'currency',
        'duration_days',
        'image',
        'thumbnail',
        'features',
        'description',
        'inclusions',
        'exclusions',
        'itinerary',
        'hotel_details',
        'departure_dates',
        'max_capacity',
        'current_bookings',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'itinerary' => 'array',
        'hotel_details' => 'array',
        'departure_dates' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Package $package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->title);
            }
        });
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(PackageGallery::class)->orderBy('sort_order');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(ContactInquiry::class);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image 
                ? asset('storage/' . $this->image) 
                : null,
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
```

### 6.2 Article Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category_id',
        'author_id',
        'image',
        'thumbnail',
        'meta_title',
        'meta_description',
        'tags',
        'status',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
```

### 6.3 Other Models
- `ArticleCategory`
- `TeamMember`
- `Testimonial`
- `ContactInquiry`
- `SiteSetting`
- `OfficeLocation`
- `PackageGallery`

---

## 7. Form Requests (Validation)

### 7.1 PackageRequest
```php
<?php

namespace App\Http\Requests\Admin\Hajj;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add proper authorization
    }

    public function rules(): array
    {
        $packageId = $this->route('package')?->id;

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => [
                'nullable',
                'string',
                'max:220',
                Rule::unique('packages', 'slug')->ignore($packageId),
            ],
            'type' => ['required', Rule::in(['hajj', 'umrah', 'tour'])],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'image' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'max:5120', // 5MB
                'mimes:jpg,jpeg,png,webp',
            ],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:200'],
            'description' => ['required', 'string', 'min:100'],
            'inclusions' => ['nullable', 'array'],
            'inclusions.*' => ['string', 'max:200'],
            'exclusions' => ['nullable', 'array'],
            'exclusions.*' => ['string', 'max:200'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.day' => ['required_with:itinerary', 'integer'],
            'itinerary.*.title' => ['required_with:itinerary', 'string'],
            'itinerary.*.description' => ['nullable', 'string'],
            'itinerary.*.activities' => ['nullable', 'array'],
            'hotel_details' => ['nullable', 'array'],
            'hotel_details.name' => ['nullable', 'string', 'max:200'],
            'hotel_details.rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'hotel_details.location' => ['nullable', 'string'],
            'hotel_details.distance_to_haram' => ['nullable', 'string'],
            'hotel_details.amenities' => ['nullable', 'array'],
            'departure_dates' => ['nullable', 'array'],
            'departure_dates.*' => ['date'],
            'max_capacity' => ['nullable', 'integer', 'min:1'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Package title is required.',
            'title.max' => 'Title cannot exceed 200 characters.',
            'type.required' => 'Please select a package type.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price cannot be negative.',
            'duration_days.required' => 'Duration is required.',
            'duration_days.min' => 'Duration must be at least 1 day.',
            'image.required' => 'Package image is required.',
            'image.max' => 'Image size cannot exceed 5MB.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 100 characters.',
        ];
    }
}
```

### 7.2 ArticleRequest
```php
<?php

namespace App\Http\Requests\Admin\Hajj;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
{
    public function rules(): array
    {
        $articleId = $this->route('article')?->id;

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => [
                'nullable',
                'string',
                'max:220',
                Rule::unique('articles', 'slug')->ignore($articleId),
            ],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:article_categories,id'],
            'image' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'max:5120',
                'mimes:jpg,jpeg,png,webp',
            ],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
```

### 7.3 Other Request Classes
Similar validation for:
- `TeamMemberRequest`
- `TestimonialRequest`
- `InquiryUpdateRequest`
- `SettingRequest`

---

## 8. Services

### 8.1 ImageUploadService
```php
<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageUploadService
{
    /**
     * Upload an image and create thumbnail.
     */
    public function upload(UploadedFile $file, string $folder): string
    {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = "{$folder}/{$filename}";
        
        // Store original
        Storage::disk('public')->putFileAs($folder, $file, $filename);
        
        return $path;
    }

    /**
     * Upload with thumbnail creation.
     */
    public function uploadWithThumbnail(
        UploadedFile $file, 
        string $folder,
        int $thumbWidth = 300,
        int $thumbHeight = 200
    ): array {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $thumbFilename = 'thumb_' . $filename;
        
        // Store original
        Storage::disk('public')->putFileAs($folder, $file, $filename);
        
        // Create thumbnail
        $image = Image::make($file);
        $image->fit($thumbWidth, $thumbHeight);
        Storage::disk('public')->put(
            "{$folder}/{$thumbFilename}",
            $image->encode()
        );
        
        return [
            'image' => "{$folder}/{$filename}",
            'thumbnail' => "{$folder}/{$thumbFilename}",
        ];
    }

    /**
     * Delete an image.
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
```

### 8.2 SlugService
```php
<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugService
{
    /**
     * Generate unique slug.
     */
    public function generate(string $title, string $table, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;
        
        while ($this->slugExists($slug, $table, $ignoreId)) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        
        return $slug;
    }

    private function slugExists(string $slug, string $table, ?int $ignoreId): bool
    {
        $query = \DB::table($table)->where('slug', $slug);
        
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        
        return $query->exists();
    }
}
```

### 8.3 SettingService
```php
<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private string $section;

    public function __construct(string $section = 'hajj')
    {
        $this->section = $section;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = "settings.{$this->section}.{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = SiteSetting::where('section', $this->section)
                                  ->where('key', $key)
                                  ->first();
            
            return $setting ? $this->castValue($setting) : $default;
        });
    }

    public function set(string $key, mixed $value, string $type = 'string'): void
    {
        SiteSetting::updateOrCreate(
            ['section' => $this->section, 'key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value, 'type' => $type]
        );
        
        Cache::forget("settings.{$this->section}.{$key}");
    }

    public function all(): array
    {
        return SiteSetting::where('section', $this->section)
                          ->get()
                          ->mapWithKeys(fn ($s) => [$s->key => $this->castValue($s)])
                          ->toArray();
    }

    private function castValue(SiteSetting $setting): mixed
    {
        return match ($setting->type) {
            'json' => json_decode($setting->value, true),
            'boolean' => (bool) $setting->value,
            'integer' => (int) $setting->value,
            default => $setting->value,
        };
    }
}
```

---

## 9. File Storage

### 9.1 Storage Configuration
```php
// config/filesystems.php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

### 9.2 Directory Structure
```
storage/app/public/
├── packages/           # Package images
├── articles/           # Article images
├── team/               # Team member avatars
├── testimonials/       # Testimonial avatars
├── settings/           # Site settings images (logos, banners)
└── temp/               # Temporary uploads
```

### 9.3 Image Requirements
| Entity | Max Size | Formats | Dimensions |
|--------|----------|---------|------------|
| Package | 5MB | jpg, png, webp | 1200x800 recommended |
| Article | 5MB | jpg, png, webp | 1200x630 recommended |
| Team Avatar | 2MB | jpg, png | 400x400 square |
| Testimonial | 2MB | jpg, png | 200x200 square |
| Logo | 1MB | png, svg | 200x60 recommended |

---

## 10. Authentication & Authorization

### 10.1 Middleware
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Add role check when roles are implemented
        // if (!auth()->user()->isAdmin()) {
        //     abort(403, 'Unauthorized access');
        // }

        return $next($request);
    }
}
```

### 10.2 Route Middleware Registration
```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminAccess::class,
    ]);
})
```

### 10.3 Future Role Implementation
```php
// When implementing roles:
// - Super Admin: Full access
// - Admin: Section-based access (Hajj, Tour, Typing)
// - Editor: Create/Edit only, no delete or settings
```

---

## 11. Migrations

### 11.1 Migration Files List
```
database/migrations/
├── 2026_01_14_000001_create_packages_table.php
├── 2026_01_14_000002_create_package_gallery_table.php
├── 2026_01_14_000003_create_article_categories_table.php
├── 2026_01_14_000004_create_articles_table.php
├── 2026_01_14_000005_create_team_members_table.php
├── 2026_01_14_000006_create_testimonials_table.php
├── 2026_01_14_000007_create_contact_inquiries_table.php
├── 2026_01_14_000008_create_site_settings_table.php
└── 2026_01_14_000009_create_office_locations_table.php
```

### 11.2 Sample Migration
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->enum('type', ['hajj', 'umrah', 'tour'])->default('hajj');
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->unsignedInteger('duration_days');
            $table->string('image', 500)->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->json('features')->nullable();
            $table->text('description')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('itinerary')->nullable();
            $table->json('hotel_details')->nullable();
            $table->json('departure_dates')->nullable();
            $table->unsignedInteger('max_capacity')->nullable();
            $table->unsignedInteger('current_bookings')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('type');
            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
```

---

## 12. Seeders

### 12.1 Package Seeder
```php
<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'title' => 'Premium Hajj Package',
                'slug' => 'premium-hajj-package',
                'type' => 'hajj',
                'price' => 5900.00,
                'currency' => 'USD',
                'duration_days' => 15,
                'features' => ['5 Star Hotel', 'Direct Flight', 'Visa Included', 'Full Board'],
                'description' => 'Experience the journey of a lifetime with our premium Hajj package...',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Ramadan Umrah',
                'slug' => 'ramadan-umrah',
                'type' => 'umrah',
                'price' => 2250.00,
                'currency' => 'USD',
                'duration_days' => 10,
                'features' => ['4 Star Hotel', 'Direct Flight', 'Visa Included', 'Breakfast'],
                'description' => 'Perform Umrah during the blessed month of Ramadan...',
                'is_featured' => true,
                'is_active' => true,
            ],
            // Add more seed data...
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
```

### 12.2 DatabaseSeeder Update
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PackageSeeder::class,
            ArticleCategorySeeder::class,
            ArticleSeeder::class,
            TeamMemberSeeder::class,
            TestimonialSeeder::class,
            SiteSettingSeeder::class,
        ]);
    }
}
```

---

## 13. Testing Requirements

### 13.1 Feature Tests
```php
<?php

namespace Tests\Feature\Admin\Hajj;

use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PackageTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_admin_can_view_packages_list(): void
    {
        Package::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)
                         ->get(route('admin.hajj.packages.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('admin/hajj/packages/Index')
                 ->has('packages.data', 5)
        );
    }

    public function test_admin_can_create_package(): void
    {
        Storage::fake('public');

        $data = [
            'title' => 'Test Package',
            'type' => 'hajj',
            'price' => 1000,
            'currency' => 'USD',
            'duration_days' => 7,
            'description' => str_repeat('Test description ', 10),
            'features' => ['Feature 1', 'Feature 2'],
            'is_active' => true,
            'image' => UploadedFile::fake()->image('package.jpg'),
        ];

        $response = $this->actingAs($this->admin)
                         ->post(route('admin.hajj.packages.store'), $data);

        $response->assertRedirect(route('admin.hajj.packages.index'));
        $this->assertDatabaseHas('packages', ['title' => 'Test Package']);
    }

    public function test_admin_can_update_package(): void
    {
        $package = Package::factory()->create();

        $response = $this->actingAs($this->admin)
                         ->put(route('admin.hajj.packages.update', $package), [
                             'title' => 'Updated Title',
                             'type' => $package->type,
                             'price' => $package->price,
                             'currency' => $package->currency,
                             'duration_days' => $package->duration_days,
                             'description' => $package->description,
                             'is_active' => true,
                         ]);

        $response->assertRedirect(route('admin.hajj.packages.index'));
        $this->assertDatabaseHas('packages', ['title' => 'Updated Title']);
    }

    public function test_admin_can_delete_package(): void
    {
        $package = Package::factory()->create();

        $response = $this->actingAs($this->admin)
                         ->delete(route('admin.hajj.packages.destroy', $package));

        $response->assertRedirect(route('admin.hajj.packages.index'));
        $this->assertSoftDeleted('packages', ['id' => $package->id]);
    }

    public function test_package_validation_works(): void
    {
        $response = $this->actingAs($this->admin)
                         ->post(route('admin.hajj.packages.store'), []);

        $response->assertSessionHasErrors(['title', 'type', 'price', 'duration_days', 'description']);
    }
}
```

### 13.2 Unit Tests
```php
<?php

namespace Tests\Unit;

use App\Services\SlugService;
use Tests\TestCase;

class SlugServiceTest extends TestCase
{
    public function test_generates_slug_from_title(): void
    {
        $service = new SlugService();
        $slug = $service->generate('Test Package Title', 'packages');
        
        $this->assertEquals('test-package-title', $slug);
    }

    public function test_generates_unique_slug_when_duplicate_exists(): void
    {
        // Create existing package with slug
        \DB::table('packages')->insert([
            'title' => 'Test',
            'slug' => 'test-package',
            'type' => 'hajj',
            'price' => 100,
            'currency' => 'USD',
            'duration_days' => 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $service = new SlugService();
        $slug = $service->generate('Test Package', 'packages');
        
        $this->assertEquals('test-package-1', $slug);
    }
}
```

### 13.3 Test Coverage Requirements
| Area | Minimum Coverage |
|------|------------------|
| Controllers | 80% |
| Models | 70% |
| Services | 90% |
| Form Requests | 100% |

---

## Appendix A: Route File

```php
<?php
// routes/admin.php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Hajj\PackageController;
use App\Http\Controllers\Admin\Hajj\ArticleController;
use App\Http\Controllers\Admin\Hajj\ArticleCategoryController;
use App\Http\Controllers\Admin\Hajj\TeamController;
use App\Http\Controllers\Admin\Hajj\TestimonialController;
use App\Http\Controllers\Admin\Hajj\InquiryController;
use App\Http\Controllers\Admin\Hajj\SettingController;
use App\Http\Controllers\Admin\MediaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Hajj Section
    Route::prefix('hajj')->name('hajj.')->group(function () {
        
        // Packages
        Route::resource('packages', PackageController::class);
        Route::post('packages/{package}/duplicate', [PackageController::class, 'duplicate'])->name('packages.duplicate');
        Route::put('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
        Route::put('packages/{package}/toggle-featured', [PackageController::class, 'toggleFeatured'])->name('packages.toggle-featured');
        
        // Articles
        Route::resource('articles', ArticleController::class);
        Route::put('articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
        Route::put('articles/{article}/unpublish', [ArticleController::class, 'unpublish'])->name('articles.unpublish');
        
        // Categories
        Route::resource('categories', ArticleCategoryController::class)->except(['create', 'edit', 'show']);
        
        // Team
        Route::resource('team', TeamController::class)->except(['create', 'edit', 'show']);
        Route::put('team/reorder', [TeamController::class, 'reorder'])->name('team.reorder');
        
        // Testimonials
        Route::resource('testimonials', TestimonialController::class)->except(['create', 'edit', 'show']);
        Route::put('testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
        Route::put('testimonials/{testimonial}/reject', [TestimonialController::class, 'reject'])->name('testimonials.reject');
        
        // Inquiries
        Route::resource('inquiries', InquiryController::class)->only(['index', 'show', 'destroy']);
        Route::put('inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('inquiries.status');
        Route::put('inquiries/{inquiry}/notes', [InquiryController::class, 'updateNotes'])->name('inquiries.notes');
        
        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::resource('settings/offices', OfficeController::class)->except(['create', 'edit', 'show'])->names([
            'index' => 'settings.offices.index',
            'store' => 'settings.offices.store',
            'update' => 'settings.offices.update',
            'destroy' => 'settings.offices.destroy',
        ]);
    });
    
    // Media Upload (shared)
    Route::post('upload/image', [MediaController::class, 'uploadImage'])->name('upload.image');
    Route::post('upload/images', [MediaController::class, 'uploadImages'])->name('upload.images');
    Route::delete('upload/{path}', [MediaController::class, 'deleteImage'])->name('upload.delete')->where('path', '.*');
});
```

---

**Document Version History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-14 | System | Initial document |
