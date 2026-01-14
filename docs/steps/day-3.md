# Day 3: Completion & Polish
# Hajj Admin Panel Development

**Date:** Day 3 of 3  
**Focus:** Remaining Features, Dashboard, Testing, Integration

---

## Overview

Day 3 completes the admin panel with team management, testimonials, inquiries, settings, dashboard, and final testing/polish.

---

## Tasks Checklist

### Phase 1: Team Management (1.5-2 hours)

#### Task 1.1: Create Team Controllers
- [ ] Create `TeamMemberController`
- [ ] Implement CRUD operations
- [ ] Implement reorder functionality

**Command:**
```bash
php artisan make:controller Admin/Hajj/TeamMemberController --resource
```

#### Task 1.2: Create TeamMember Form Request
- [ ] Create validation request

**Command:**
```bash
php artisan make:request Admin/Hajj/TeamMemberRequest
```

#### Task 1.3: Register Team Routes
```php
Route::resource('team', TeamMemberController::class);
Route::post('team/reorder', [TeamMemberController::class, 'reorder'])->name('team.reorder');
```

#### Task 1.4: Create Team Frontend Pages
- [ ] Create `resources/js/pages/admin/hajj/team/Index.vue`
- [ ] Create `resources/js/pages/admin/hajj/team/Create.vue`
- [ ] Create `resources/js/pages/admin/hajj/team/Edit.vue`
- [ ] Implement drag-to-reorder functionality

**Features:**
- Drag and drop ordering
- Photo upload with preview
- Social links management
- Active/inactive toggle

---

### Phase 2: Testimonial Management (1-1.5 hours)

#### Task 2.1: Create Testimonial Controller
- [ ] Create `TestimonialController`
- [ ] CRUD operations
- [ ] Approve/reject functionality

**Command:**
```bash
php artisan make:controller Admin/Hajj/TestimonialController --resource
```

#### Task 2.2: Create Form Request
- [ ] Create `TestimonialRequest`

**Command:**
```bash
php artisan make:request Admin/Hajj/TestimonialRequest
```

#### Task 2.3: Register Routes
```php
Route::resource('testimonials', TestimonialController::class);
Route::put('testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
Route::put('testimonials/{testimonial}/reject', [TestimonialController::class, 'reject'])->name('testimonials.reject');
```

#### Task 2.4: Create Testimonial Frontend Pages
- [ ] Create `resources/js/pages/admin/hajj/testimonials/Index.vue`
- [ ] Create `resources/js/pages/admin/hajj/testimonials/Create.vue`
- [ ] Create `resources/js/pages/admin/hajj/testimonials/Edit.vue`

**Features:**
- Star rating display/edit
- Package association
- Approval status badge
- Quick approve/reject actions

---

### Phase 3: Inquiry Management (1-1.5 hours)

#### Task 3.1: Create Inquiry Controller
- [ ] Create `InquiryController`
- [ ] List with filters
- [ ] View details
- [ ] Mark as read/responded
- [ ] Delete

**Command:**
```bash
php artisan make:controller Admin/Hajj/InquiryController
```

#### Task 3.2: Register Routes
```php
Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
Route::get('inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
Route::put('inquiries/{inquiry}/read', [InquiryController::class, 'markAsRead'])->name('inquiries.read');
Route::put('inquiries/{inquiry}/respond', [InquiryController::class, 'markAsResponded'])->name('inquiries.respond');
Route::delete('inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');
```

#### Task 3.3: Create Inquiry Frontend Pages
- [ ] Create `resources/js/pages/admin/hajj/inquiries/Index.vue`
- [ ] Create `resources/js/pages/admin/hajj/inquiries/Show.vue`

**Features:**
- Unread indicator badge
- Filter by status (new, read, responded)
- View inquiry details in modal or page
- One-click mark as read/responded
- Bulk mark as read

---

### Phase 4: Settings Management (1.5-2 hours)

#### Task 4.1: Create Settings Controller
- [ ] Create `SettingsController`
- [ ] Load settings by groups
- [ ] Save settings

**Command:**
```bash
php artisan make:controller Admin/Hajj/SettingsController
```

#### Task 4.2: Create Settings Form Request
- [ ] Create `SettingsRequest`

**Command:**
```bash
php artisan make:request Admin/Hajj/SettingsRequest
```

#### Task 4.3: Register Routes
```php
Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
Route::get('settings/{group}', [SettingsController::class, 'show'])->name('settings.show');
```

#### Task 4.4: Create Settings Frontend Page
- [ ] Create `resources/js/pages/admin/hajj/settings/Index.vue`

**Settings Groups:**

1. **General Settings**
   - Site name
   - Logo upload
   - Contact email
   - Contact phone
   - Address

2. **Social Media**
   - Facebook URL
   - Instagram URL
   - Twitter URL
   - YouTube URL
   - WhatsApp number

3. **SEO Settings**
   - Default meta title
   - Default meta description
   - Meta keywords
   - Google Analytics ID

4. **Appearance**
   - Primary color
   - Hero banner image
   - Hero text

**Implementation:**
```vue
<template>
  <AdminLayout>
    <div class="flex gap-6">
      <!-- Settings Navigation -->
      <aside class="w-48">
        <nav class="space-y-1">
          <button
            v-for="group in groups"
            :key="group.key"
            @click="activeGroup = group.key"
            :class="[
              'w-full text-left px-3 py-2 rounded',
              activeGroup === group.key
                ? 'bg-amber-100 text-amber-800'
                : 'hover:bg-gray-100'
            ]"
          >
            {{ group.label }}
          </button>
        </nav>
      </aside>

      <!-- Settings Form -->
      <div class="flex-1">
        <form @submit.prevent="save">
          <GeneralSettings v-if="activeGroup === 'general'" v-model="form.general" />
          <SocialSettings v-if="activeGroup === 'social'" v-model="form.social" />
          <!-- ... other groups -->
          
          <div class="mt-6">
            <Button type="submit" :loading="form.processing">
              Save Settings
            </Button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
```

---

### Phase 5: Dashboard (1.5-2 hours)

#### Task 5.1: Create Dashboard Controller
- [ ] Create `DashboardController`
- [ ] Gather statistics
- [ ] Recent activity

**Command:**
```bash
php artisan make:controller Admin/Hajj/DashboardController
```

**File:** `app/Http/Controllers/Admin/Hajj/DashboardController.php`

#### Task 5.2: Register Route
```php
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
```

#### Task 5.3: Create Stats Card Component
- [ ] Create `StatsCard.vue`

**File:** `resources/js/components/admin/dashboard/StatsCard.vue`

```vue
<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-500">{{ label }}</p>
        <p class="text-2xl font-bold">{{ value }}</p>
        <p v-if="change" :class="changeClass" class="text-sm">
          {{ change > 0 ? '+' : '' }}{{ change }}% from last month
        </p>
      </div>
      <div :class="iconClass" class="p-3 rounded-full">
        <component :is="icon" class="w-6 h-6" />
      </div>
    </div>
  </div>
</template>
```

#### Task 5.4: Create Dashboard Page
- [ ] Create `resources/js/pages/admin/hajj/Dashboard.vue`

**Dashboard Content:**

1. **Stats Cards Row**
   - Total Packages (with Hajj/Umrah breakdown)
   - Total Articles
   - New Inquiries (unread count)
   - Total Testimonials

2. **Charts Row**
   - Inquiries per month (line/bar chart)
   - Popular packages (pie chart)

3. **Recent Activity**
   - Recent inquiries list
   - Recently updated packages

4. **Quick Actions**
   - Add new package
   - Add new article
   - View inquiries

**Dashboard Layout:**
```vue
<template>
  <AdminLayout title="Dashboard">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <StatsCard
        v-for="stat in stats"
        :key="stat.key"
        :label="stat.label"
        :value="stat.value"
        :icon="stat.icon"
        :change="stat.change"
        :color="stat.color"
      />
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Recent Inquiries -->
      <div class="lg:col-span-2 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex items-center justify-between">
          <h3 class="font-semibold">Recent Inquiries</h3>
          <Link :href="route('admin.hajj.inquiries.index')" class="text-amber-600 text-sm">
            View All
          </Link>
        </div>
        <div class="p-6">
          <RecentInquiriesList :inquiries="recentInquiries" />
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
          <h3 class="font-semibold">Quick Actions</h3>
        </div>
        <div class="p-6 space-y-3">
          <QuickActionButton
            v-for="action in quickActions"
            :key="action.route"
            :label="action.label"
            :icon="action.icon"
            :href="action.route"
          />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
```

---

### Phase 6: FAQ Management (30-45 min)

#### Task 6.1: Create FAQ Controller
- [ ] Create `FaqController`
- [ ] CRUD with ordering

**Command:**
```bash
php artisan make:controller Admin/Hajj/FaqController --resource
```

#### Task 6.2: Register Routes
```php
Route::resource('faqs', FaqController::class);
Route::post('faqs/reorder', [FaqController::class, 'reorder'])->name('faqs.reorder');
```

#### Task 6.3: Create FAQ Frontend
- [ ] Create `resources/js/pages/admin/hajj/faqs/Index.vue`
- [ ] Inline edit capability
- [ ] Drag to reorder

---

### Phase 7: Public API Integration (1 hour)

#### Task 7.1: Create Public Controllers
- [ ] Update frontend pages to use database data
- [ ] Create public data endpoints or Inertia props

**Update existing pages:**
- `hajjhome.vue` - Load packages, articles, testimonials from props
- `hajjpackage.vue` - Load packages from props
- `umrahpackage.vue` - Load packages from props
- `articles.vue` - Load articles from props
- `article_detail.vue` - Load single article
- `team.vue` - Load team and FAQs from props
- `contactus.vue` - Submit inquiry to database

#### Task 7.2: Update Web Routes
```php
// routes/web.php

use App\Http\Controllers\Hajj\HomeController;
use App\Http\Controllers\Hajj\PackageController;
use App\Http\Controllers\Hajj\ArticleController;
use App\Http\Controllers\Hajj\TeamController;
use App\Http\Controllers\Hajj\InquiryController;

Route::prefix('hajj')->name('hajj.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/packages', [PackageController::class, 'hajj'])->name('packages');
    Route::get('/umrah-packages', [PackageController::class, 'umrah'])->name('umrah');
    Route::get('/packages/{package:slug}', [PackageController::class, 'show'])->name('packages.show');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles');
    Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/team', [TeamController::class, 'index'])->name('team');
    Route::get('/contact', [InquiryController::class, 'create'])->name('contact');
    Route::post('/contact', [InquiryController::class, 'store'])->name('contact.store');
});
```

---

### Phase 8: Testing (1-1.5 hours)

#### Task 8.1: Write Feature Tests
- [ ] Create package CRUD tests
- [ ] Create article CRUD tests
- [ ] Create inquiry submission test

**Test Files:**
- `tests/Feature/Admin/PackageTest.php`
- `tests/Feature/Admin/ArticleTest.php`
- `tests/Feature/ContactFormTest.php`

**Example Test:**
```php
// tests/Feature/Admin/PackageTest.php
<?php

use App\Models\Package;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('admin can view packages list', function () {
    Package::factory()->count(3)->create();

    $response = $this->actingAs($this->user)
        ->get(route('admin.hajj.packages.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/hajj/packages/Index')
        ->has('packages.data', 3)
    );
});

test('admin can create a package', function () {
    $packageData = [
        'title' => 'Premium Hajj 2025',
        'type' => 'hajj',
        'price' => 5000,
        'currency' => 'AED',
        'duration_days' => 21,
        'description' => 'A premium hajj package',
        'is_active' => true,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.hajj.packages.store'), $packageData);

    $response->assertRedirect(route('admin.hajj.packages.index'));
    $this->assertDatabaseHas('packages', ['title' => 'Premium Hajj 2025']);
});

test('admin can update a package', function () {
    $package = Package::factory()->create();

    $response = $this->actingAs($this->user)
        ->put(route('admin.hajj.packages.update', $package), [
            'title' => 'Updated Title',
            'type' => $package->type,
            'price' => $package->price,
            'currency' => $package->currency,
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('packages', ['id' => $package->id, 'title' => 'Updated Title']);
});

test('admin can delete a package', function () {
    $package = Package::factory()->create();

    $response = $this->actingAs($this->user)
        ->delete(route('admin.hajj.packages.destroy', $package));

    $response->assertRedirect();
    $this->assertSoftDeleted('packages', ['id' => $package->id]);
});
```

#### Task 8.2: Run All Tests
```bash
php artisan test
```

---

### Phase 9: Final Polish (1 hour)

#### Task 9.1: Form Validation Messages
- [ ] Review all form error messages
- [ ] Ensure consistent formatting
- [ ] Add helpful validation hints

#### Task 9.2: Loading States
- [ ] Add loading spinners to buttons
- [ ] Add skeleton loaders to lists
- [ ] Disable buttons during submission

#### Task 9.3: Empty States
- [ ] Design empty state for no packages
- [ ] Design empty state for no articles
- [ ] Design empty state for no inquiries

**Example Empty State:**
```vue
<template>
  <div v-if="!items.length" class="text-center py-12">
    <Package class="w-12 h-12 mx-auto text-gray-400 mb-4" />
    <h3 class="text-lg font-medium text-gray-900 mb-2">No packages yet</h3>
    <p class="text-gray-500 mb-4">Get started by creating your first package.</p>
    <Button :href="route('admin.hajj.packages.create')">
      <Plus class="w-4 h-4 mr-2" />
      Add Package
    </Button>
  </div>
</template>
```

#### Task 9.4: Breadcrumbs
- [ ] Add breadcrumbs to all pages
- [ ] Ensure consistent navigation

#### Task 9.5: Responsive Design Check
- [ ] Test admin panel on tablet (1024px)
- [ ] Ensure sidebar collapses properly
- [ ] Ensure tables are scrollable
- [ ] Ensure forms stack properly

#### Task 9.6: Documentation
- [ ] Update README with admin panel info
- [ ] Document new routes
- [ ] Document environment variables

---

## File Creation Checklist

### Day 3 Backend Files
```
☐ app/Http/Controllers/Admin/Hajj/TeamMemberController.php
☐ app/Http/Controllers/Admin/Hajj/TestimonialController.php
☐ app/Http/Controllers/Admin/Hajj/InquiryController.php
☐ app/Http/Controllers/Admin/Hajj/SettingsController.php
☐ app/Http/Controllers/Admin/Hajj/DashboardController.php
☐ app/Http/Controllers/Admin/Hajj/FaqController.php

☐ app/Http/Controllers/Hajj/HomeController.php
☐ app/Http/Controllers/Hajj/PackageController.php
☐ app/Http/Controllers/Hajj/ArticleController.php
☐ app/Http/Controllers/Hajj/TeamController.php
☐ app/Http/Controllers/Hajj/InquiryController.php

☐ app/Http/Requests/Admin/Hajj/TeamMemberRequest.php
☐ app/Http/Requests/Admin/Hajj/TestimonialRequest.php
☐ app/Http/Requests/Admin/Hajj/SettingsRequest.php
☐ app/Http/Requests/Admin/Hajj/FaqRequest.php
```

### Day 3 Frontend Files
```
☐ resources/js/pages/admin/hajj/Dashboard.vue
☐ resources/js/pages/admin/hajj/team/Index.vue
☐ resources/js/pages/admin/hajj/team/Create.vue
☐ resources/js/pages/admin/hajj/team/Edit.vue
☐ resources/js/pages/admin/hajj/testimonials/Index.vue
☐ resources/js/pages/admin/hajj/testimonials/Create.vue
☐ resources/js/pages/admin/hajj/testimonials/Edit.vue
☐ resources/js/pages/admin/hajj/inquiries/Index.vue
☐ resources/js/pages/admin/hajj/inquiries/Show.vue
☐ resources/js/pages/admin/hajj/settings/Index.vue
☐ resources/js/pages/admin/hajj/faqs/Index.vue

☐ resources/js/components/admin/dashboard/StatsCard.vue
☐ resources/js/components/admin/dashboard/RecentInquiriesList.vue
☐ resources/js/components/admin/dashboard/QuickActionButton.vue
```

### Test Files
```
☐ tests/Feature/Admin/PackageTest.php
☐ tests/Feature/Admin/ArticleTest.php
☐ tests/Feature/ContactFormTest.php
```

---

## End of Day 3 Verification

### Functionality Checklist
- [ ] Dashboard displays correctly with stats
- [ ] All CRUD operations work for all entities
- [ ] Image upload works everywhere
- [ ] Settings save and load correctly
- [ ] Inquiries can be viewed and managed
- [ ] Testimonials can be approved/rejected
- [ ] Team members can be reordered
- [ ] FAQs can be managed
- [ ] Public pages load data from database
- [ ] Contact form submits to database
- [ ] All forms validate correctly
- [ ] All tests pass

### Quality Checklist
- [ ] No console errors
- [ ] No PHP errors/warnings
- [ ] Loading states work
- [ ] Empty states display
- [ ] Responsive design works
- [ ] Consistent styling throughout

### Final Commands
```bash
# Run tests
php artisan test

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rebuild assets
npm run build

# Commit all changes
git add .
git commit -m "Complete Hajj admin panel implementation"
git push
```

---

## Summary: 3-Day Development Plan

### Day 1: Foundation
- Database migrations & models
- Services layer
- Admin routes setup
- Admin layout frontend
- Seeders with sample data

### Day 2: Core Features
- Package CRUD (backend + frontend)
- Article CRUD (backend + frontend)
- Reusable components (DataTable, ImageUploader, etc.)
- Form validation & feedback

### Day 3: Completion
- Team, Testimonials, Inquiries, FAQs
- Settings management
- Dashboard with stats
- Public pages integration
- Testing & polish

---

## Estimated Total Time

| Day | Hours |
|-----|-------|
| Day 1 | 8-10 hours |
| Day 2 | 10-12 hours |
| Day 3 | 9-11 hours |
| **Total** | **27-33 hours** |

---

## Post-Development Enhancements

After the 3-day implementation, consider these enhancements:

1. **Rich Text Editor** - Integrate Tiptap for full WYSIWYG
2. **Email Notifications** - Send emails on new inquiries
3. **Charts Library** - Add Chart.js for dashboard analytics
4. **Export/Import** - CSV/Excel export for data
5. **Activity Log** - Track admin actions
6. **Multi-language** - Prepare for i18n
7. **Role-based Access** - Different admin roles
8. **API Documentation** - OpenAPI/Swagger docs
