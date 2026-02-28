# Typing Section - Step 1: Backend Foundation

**Document Version:** 1.0  
**Date:** January 28, 2026  
**Phase:** Step 1 of 2  
**Focus:** Database, Models, Services, Controllers

---

## Overview

This step covers the backend foundation for the Typing section:
1. Database migration for `typing_services` table
2. TypingService model with relationships and scopes
3. TypingServiceService class for business logic
4. Admin controllers (Settings + Services)
5. Public controller for frontend data
6. Route configuration
7. Seeder with initial data

---

## 1. Migration: typing_services Table

### File: `database/migrations/2026_01_28_000001_create_typing_services_table.php`

**Table Structure:**
```
typing_services
├── id (bigint, auto-increment, PK)
├── title (varchar 255, required)
├── slug (varchar 255, unique, required)
├── short_description (text, nullable) - For homepage grid cards
├── long_description (text, nullable) - For service detail page
├── icon (varchar 255, nullable) - Icon class or path
├── image (varchar 255, nullable) - Featured image
├── sub_services (json, nullable) - Array of {title, description}
├── cta_text (varchar 100, default 'Apply')
├── cta_link (varchar 255, default '/contactus')
├── sort_order (int unsigned, default 0)
├── is_active (boolean, default true)
├── is_featured (boolean, default false)
├── meta_title (varchar 255, nullable)
├── meta_description (text, nullable)
├── created_at (timestamp)
├── updated_at (timestamp)
└── INDEXES: (is_active, sort_order), (slug)
```

**JSON sub_services Format:**
```json
[
  {"title": "Establishment card", "description": "Register your company as a legal sponsor..."},
  {"title": "NEW VISA APPLY", "description": "We handle new visa applications..."},
  {"title": "Medical (fitness test)", "description": "Coordination of mandatory medical..."}
]
```

---

## 2. Model: TypingService

### File: `app/Models/TypingService.php`

**Key Features:**
- Fillable fields for mass assignment
- JSON casting for `sub_services`
- Boolean casting for `is_active`, `is_featured`
- Scopes: `active()`, `featured()`, `ordered()`
- Auto-generate slug from title
- Image URL accessor

**Model Properties:**
```php
protected $fillable = [
    'title', 'slug', 'short_description', 'long_description',
    'icon', 'image', 'sub_services', 'cta_text', 'cta_link',
    'sort_order', 'is_active', 'is_featured',
    'meta_title', 'meta_description'
];

protected function casts(): array
{
    return [
        'sub_services' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];
}
```

**Scopes:**
```php
scopeActive($query)    → where('is_active', true)
scopeFeatured($query)  → where('is_featured', true)
scopeOrdered($query)   → orderBy('sort_order')->orderBy('title')
```

**Accessor:**
```php
getImageUrlAttribute() → Returns full URL or null
```

---

## 3. Service: TypingServiceService

### File: `app/Services/TypingServiceService.php`

**Methods:**

| Method | Parameters | Return | Purpose |
|--------|------------|--------|---------|
| `list()` | - | Collection | Get all services ordered |
| `getActive()` | - | Collection | Get active services ordered |
| `getFeatured(int $limit = 4)` | limit | Collection | Get featured services |
| `getBySlug(string $slug)` | slug | TypingService | Find by slug or fail |
| `getById(int $id)` | id | TypingService | Find by ID or fail |
| `create(array $data)` | data | TypingService | Create new service |
| `update(TypingService $service, array $data)` | service, data | TypingService | Update service |
| `delete(TypingService $service)` | service | bool | Delete service |
| `toggleActive(TypingService $service)` | service | TypingService | Toggle active status |
| `toggleFeatured(TypingService $service)` | service | TypingService | Toggle featured status |
| `reorder(array $orderedIds)` | array of IDs | void | Batch update sort_order |

**Image Handling:**
- Uses MediaService for uploads
- Stores in `services/` directory
- Deletes old image on update/delete

---

## 4. Admin Controller: TypingSettingController

### File: `app/Http/Controllers/Admin/Typing/SettingController.php`

**Copy from Hajj SettingController with these changes:**
- Replace all `'hajj'` with `'typing'`
- Update route names: `admin.typing.settings.*`
- Update view path (if different)

**Methods:**

| Method | Route | Purpose |
|--------|-------|---------|
| `index()` | GET /admin/typing/settings | Show settings form |
| `updateCompany(Request)` | PUT /admin/typing/settings/company | Update company settings |
| `updateSeo(Request)` | PUT /admin/typing/settings/seo | Update SEO settings |
| `updateSocial(Request)` | PUT /admin/typing/settings/social | Update social links |
| `clearCache()` | POST /admin/typing/settings/clear-cache | Clear settings cache |

**Dependencies:**
```php
public function __construct(
    protected SettingService $settingService,
    protected MediaService $mediaService
) {}
```

**Settings Keys (same as Hajj):**
- Company: company_name, company_tagline, company_email, company_phone, company_whatsapp, company_address, company_logo, company_description, hero_image
- SEO: meta_title, meta_description, meta_keywords, og_image, google_analytics
- Social: social_facebook, social_twitter, social_instagram, social_linkedin, social_youtube, contact_description

---

## 5. Admin Controller: TypingServiceController

### File: `app/Http/Controllers/Admin/Typing/ServiceController.php`

**Methods:**

| Method | Route | View |
|--------|-------|------|
| `index()` | GET /admin/typing/services | admin.pages.typing.services.index |
| `create()` | GET /admin/typing/services/create | admin.pages.typing.services.create |
| `store(Request)` | POST /admin/typing/services | redirect to index |
| `edit(TypingService)` | GET /admin/typing/services/{service}/edit | admin.pages.typing.services.edit |
| `update(Request, TypingService)` | PUT /admin/typing/services/{service} | redirect to index |
| `destroy(TypingService)` | DELETE /admin/typing/services/{service} | redirect to index |
| `toggleActive(TypingService)` | PATCH /admin/typing/services/{service}/toggle-active | redirect back |
| `toggleFeatured(TypingService)` | PATCH /admin/typing/services/{service}/toggle-featured | redirect back |
| `reorder(Request)` | POST /admin/typing/services/reorder | JSON response |

**Dependencies:**
```php
public function __construct(
    protected TypingServiceService $serviceService,
    protected MediaService $mediaService
) {}
```

---

## 6. Public Controller: TypingController

### File: `app/Http/Controllers/Public/TypingController.php`

**Methods:**

| Method | Route | Data Returned |
|--------|-------|---------------|
| `home()` | GET /typing | settings, services, auth |
| `service($slug)` | GET /typing/services/{slug} | service, settings, auth |

**home() Method:**
```php
public function home(): Response
{
    $settings = $this->getSettings('typing');
    $services = $this->serviceService->getActive();
    
    return Inertia::render('typing/typinghome', [
        'settings' => $settings,
        'services' => $services->map(fn($s) => [
            'id' => $s->id,
            'title' => $s->title,
            'slug' => $s->slug,
            'desc' => $s->short_description,
            'url' => '/typing/services/' . $s->slug,
            'icon' => $s->icon,
        ]),
    ]);
}
```

**service() Method:**
```php
public function service(string $slug): Response
{
    $service = $this->serviceService->getBySlug($slug);
    $settings = $this->getSettings('typing');
    
    return Inertia::render('typing/services/ServiceDetail', [
        'service' => [
            'id' => $service->id,
            'title' => $service->title,
            'description' => $service->long_description,
            'sub_services' => $service->sub_services ?? [],
            'cta_text' => $service->cta_text,
            'cta_link' => $service->cta_link,
            'image' => $service->image_url,
            'meta_title' => $service->meta_title,
            'meta_description' => $service->meta_description,
        ],
        'settings' => $settings,
    ]);
}
```

---

## 7. Form Request: TypingServiceRequest

### File: `app/Http/Requests/Admin/TypingServiceRequest.php`

**Validation Rules:**
```php
public function rules(): array
{
    $serviceId = $this->route('service')?->id;
    
    return [
        'title' => ['required', 'string', 'max:255'],
        'slug' => ['required', 'string', 'max:255', Rule::unique('typing_services')->ignore($serviceId)],
        'short_description' => ['nullable', 'string', 'max:500'],
        'long_description' => ['nullable', 'string', 'max:5000'],
        'icon' => ['nullable', 'string', 'max:255'],
        'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        'sub_services' => ['nullable', 'array'],
        'sub_services.*.title' => ['required_with:sub_services', 'string', 'max:255'],
        'sub_services.*.description' => ['required_with:sub_services', 'string', 'max:2000'],
        'cta_text' => ['nullable', 'string', 'max:100'],
        'cta_link' => ['nullable', 'string', 'max:255'],
        'sort_order' => ['nullable', 'integer', 'min:0'],
        'is_active' => ['boolean'],
        'is_featured' => ['boolean'],
        'meta_title' => ['nullable', 'string', 'max:255'],
        'meta_description' => ['nullable', 'string', 'max:500'],
    ];
}
```

---

## 8. Routes Configuration

### Update: `routes/admin.php`

**Replace typing placeholder with:**
```php
// ==========================================
// Typing Services Section Routes
// ==========================================
Route::prefix('typing')->name('typing.')->middleware('section:typing')->group(function () {
    // Dashboard/Index
    Route::get('/', [\App\Http\Controllers\Admin\Typing\ServiceController::class, 'index'])->name('index');
    
    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings/company', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'updateCompany'])->name('settings.update-company');
    Route::put('settings/seo', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'updateSeo'])->name('settings.update-seo');
    Route::put('settings/social', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'updateSocial'])->name('settings.update-social');
    Route::post('settings/clear-cache', [\App\Http\Controllers\Admin\Typing\SettingController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Services CRUD
    Route::resource('services', \App\Http\Controllers\Admin\Typing\ServiceController::class);
    Route::patch('services/{service}/toggle-active', [\App\Http\Controllers\Admin\Typing\ServiceController::class, 'toggleActive'])->name('services.toggle-active');
    Route::patch('services/{service}/toggle-featured', [\App\Http\Controllers\Admin\Typing\ServiceController::class, 'toggleFeatured'])->name('services.toggle-featured');
    Route::post('services/reorder', [\App\Http\Controllers\Admin\Typing\ServiceController::class, 'reorder'])->name('services.reorder');
});
```

### Update: `routes/web.php`

**Replace inline closures with controller:**
```php
// ==========================================
// Typing Services Public Routes
// ==========================================
Route::get('/typing', [TypingController::class, 'home'])->name('typing.index');
Route::get('/typing/services/{slug}', [TypingController::class, 'service'])->name('typing.service');

// Keep specific routes for special pages (FamilyVisaProcess, etc.) if needed
Route::get('/typing/services/family-visa-process', function () {
    return Inertia::render('typing/services/FamilyVisaProcess');
})->name('typing.services.family-visa-process');

// Family sub-pages (these have special interactive logic)
Route::get('/typing/services/family/new-residency', function () {
    return Inertia::render('typing/services/family/NewResidency');
})->name('typing.services.family.new-residency');
// ... other family routes
```

---

## 9. Seeder: TypingSectionSeeder

### File: `database/seeders/TypingSectionSeeder.php`

**Seed Data:**

1. **Site Settings** (section='typing'):
   - company_name: "SS Group Typing Services"
   - company_email: "typing@ssgroup.ae"
   - company_phone: "+971 4 123 4567"
   - company_address: "Dubai, UAE"
   - social_facebook, social_instagram, etc.

2. **Typing Services** (12 records):
   ```php
   [
       ['title' => 'Immigration', 'slug' => 'immigration', 'short_description' => 'Emirates ID, residency and immigration document services.', 'sub_services' => [...], 'sort_order' => 1],
       ['title' => 'Labour Ministry', 'slug' => 'labour-ministry', ...],
       ['title' => 'Tasheel Services', 'slug' => 'tasheel-services', ...],
       ['title' => 'Domestic Workers Visa', 'slug' => 'domestic-workers-visa', ...],
       ['title' => 'Family Visa', 'slug' => 'family-visa-process', ...],
       ['title' => 'Health Insurance', 'slug' => 'health-insurance', ...],
       ['title' => 'Ministry of Interior', 'slug' => 'ministry-of-interior', ...],
       ['title' => 'Certificate Attestation', 'slug' => 'certificate-attestation', ...],
       ['title' => 'VAT Registration', 'slug' => 'vat-registration', ...],
       ['title' => 'CT Registration', 'slug' => 'ct-registration', ...],
       ['title' => 'Passport Renewal', 'slug' => 'passport-renewal', ...],
       ['title' => 'Immigration Card', 'slug' => 'immigration-card', ...],
   ]
   ```

---

## 10. Sidebar Update

### Update: `resources/views/admin/components/layout/sidebar-content.blade.php`

**Replace Typing "Coming Soon" with:**
```blade
@if(in_array('typing', $user->getSectionNames()) || $user->isSuperAdmin())
<div class="space-y-1">
    <p class="px-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Typing Services</p>
    
    <a href="{{ route('admin.typing.services.index') }}"
       class="{{ request()->routeIs('admin.typing.services.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} group flex items-center rounded-md px-4 py-2 text-sm font-medium">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        Services
    </a>
    
    <a href="{{ route('admin.typing.settings.index') }}"
       class="{{ request()->routeIs('admin.typing.settings.*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} group flex items-center rounded-md px-4 py-2 text-sm font-medium">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Settings
    </a>
</div>
@endif
```

---

## Execution Checklist

### Step 1 Checklist

- [ ] Create migration: `typing_services` table
- [ ] Run migration: `php artisan migrate`
- [ ] Create model: `TypingService.php`
- [ ] Create service: `TypingServiceService.php`
- [ ] Create admin controller: `Typing/SettingController.php`
- [ ] Create admin controller: `Typing/ServiceController.php`
- [ ] Create public controller: `TypingController.php`
- [ ] Create form request: `TypingServiceRequest.php`
- [ ] Update routes: `admin.php`
- [ ] Update routes: `web.php`
- [ ] Create seeder: `TypingSectionSeeder.php`
- [ ] Run seeder: `php artisan db:seed --class=TypingSectionSeeder`

### Verification Commands

```bash
# Run migration
php artisan migrate

# Check table exists
php artisan tinker
> Schema::hasTable('typing_services')

# Run seeder
php artisan db:seed --class=TypingSectionSeeder

# Verify data
php artisan tinker
> App\Models\TypingService::count()
> App\Models\SiteSetting::where('section', 'typing')->count()

# Clear cache
php artisan optimize:clear
```

---

*Proceed to Step 2 after completing all items in this checklist.*
