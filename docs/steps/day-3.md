# Day 3: Remaining Modules & Polish
# Hajj Admin Panel Development

**Date:** Day 3 of 3  
**Focus:** Team, Testimonials, Inquiries, Settings, User Dashboard, Admin Management, Polish  
**Stack:** Laravel 12 + Blade + Alpine.js + Tailwind CSS v4

---

## Overview

Day 3 completes all remaining CRUD modules, implements the dashboard with real statistics, site settings management, **user-facing dashboard for booking tracking**, **admin user management (Super Admin)**, and final polish for a production-ready admin panel.

---

## Prerequisites from Day 2

Before starting Day 3, verify:
- [ ] Package CRUD fully functional
- [ ] Article CRUD fully functional
- [ ] Booking management fully functional
- [ ] All Blade components working
- [ ] Image upload working
- [ ] Services implemented
- [ ] User role system working

---

## Tasks Checklist

### Phase 1: Team Members Module (2-2.5 hours)

#### Task 1.1: Create Controller

```bash
php artisan make:controller Admin/Hajj/TeamMemberController
```

#### Task 1.2: Implement Controller Methods

| Method | Route | Purpose |
|--------|-------|---------|
| index | GET /admin/team | List team members |
| create | GET /admin/team/create | Show create form |
| store | POST /admin/team | Save team member |
| edit | GET /admin/team/{id}/edit | Show edit form |
| update | PUT /admin/team/{id} | Update team member |
| destroy | DELETE /admin/team/{id} | Delete team member |
| toggleActive | PATCH /admin/team/{id}/toggle | Toggle active |
| reorder | POST /admin/team/reorder | Reorder members |

#### Task 1.3: Create Form Request

```bash
php artisan make:request Admin/TeamMemberRequest
```

**Validation Rules:**
```php
return [
    'name' => ['required', 'string', 'max:255'],
    'designation' => ['required', 'string', 'max:255'],
    'email' => ['nullable', 'email', 'max:255'],
    'phone' => ['nullable', 'string', 'max:50'],
    'bio' => ['nullable', 'string', 'max:1000'],
    'photo' => ['nullable', 'image', 'max:2048'],
    'social_links' => ['nullable', 'array'],
    'social_links.*.platform' => ['required_with:social_links.*.url', 'string'],
    'social_links.*.url' => ['required_with:social_links.*.platform', 'url'],
    'sort_order' => ['nullable', 'integer', 'min:0'],
    'is_active' => ['boolean'],
];
```

#### Task 1.4: Create Views

```
resources/views/admin/pages/team/
├── index.blade.php
├── create.blade.php
└── edit.blade.php
```

**index.blade.php features:**
- Drag-to-reorder cards
- Grid layout (3-4 columns)
- Member cards with:
  - Photo (circular)
  - Name
  - Designation
  - Active status badge
  - Quick actions (edit, toggle, delete)
- Add new member button
- Empty state

**create.blade.php / edit.blade.php features:**
- Photo upload with preview
- Name, designation inputs
- Email, phone inputs
- Bio textarea
- Social links builder (Alpine.js)
  - Platform select (LinkedIn, Twitter, Instagram, etc.)
  - URL input
  - Add/remove
- Sort order input
- Active toggle

#### Task 1.5: Implement TeamService

```php
class TeamService
{
    public function list(): Collection
    public function getById(int $id): TeamMember
    public function create(array $data): TeamMember
    public function update(TeamMember $member, array $data): TeamMember
    public function delete(TeamMember $member): bool
    public function toggleActive(TeamMember $member): TeamMember
    public function reorder(array $order): void
}
```

---

### Phase 2: Testimonials Module (2 hours)

#### Task 2.1: Create Controller

```bash
php artisan make:controller Admin/Hajj/TestimonialController
```

#### Task 2.2: Implement Controller Methods

| Method | Route | Purpose |
|--------|-------|---------|
| index | GET /admin/testimonials | List testimonials |
| create | GET /admin/testimonials/create | Show create form |
| store | POST /admin/testimonials | Save testimonial |
| edit | GET /admin/testimonials/{id}/edit | Show edit form |
| update | PUT /admin/testimonials/{id} | Update testimonial |
| destroy | DELETE /admin/testimonials/{id} | Delete testimonial |
| approve | PATCH /admin/testimonials/{id}/approve | Approve testimonial |
| toggleFeatured | PATCH /admin/testimonials/{id}/featured | Toggle featured |

#### Task 2.3: Create Form Request

```bash
php artisan make:request Admin/TestimonialRequest
```

**Validation Rules:**
```php
return [
    'client_name' => ['required', 'string', 'max:255'],
    'client_location' => ['nullable', 'string', 'max:255'],
    'client_photo' => ['nullable', 'image', 'max:2048'],
    'package_id' => ['nullable', 'exists:packages,id'],
    'content' => ['required', 'string', 'max:1000'],
    'rating' => ['required', 'integer', 'min:1', 'max:5'],
    'travel_date' => ['nullable', 'date'],
    'is_approved' => ['boolean'],
    'is_featured' => ['boolean'],
];
```

#### Task 2.4: Create Views

```
resources/views/admin/pages/testimonials/
├── index.blade.php
├── create.blade.php
└── edit.blade.php
```

**index.blade.php features:**
- Filter tabs (All, Pending, Approved, Featured)
- Package filter dropdown
- Data table with:
  - Client photo
  - Client name
  - Package name
  - Rating stars
  - Content preview (truncated)
  - Status badges (Approved, Featured)
  - Actions
- Bulk approve action
- Pagination

**create.blade.php / edit.blade.php features:**
- Client name input
- Client location input
- Client photo upload
- Package select (optional)
- Content textarea
- Star rating selector (Alpine.js)
- Travel date picker
- Approved toggle
- Featured toggle

#### Task 2.5: Star Rating Component

```
resources/views/admin/components/form/star-rating.blade.php
```

**Features:**
- 5 clickable stars
- Hover preview
- Alpine.js state
- Hidden input for form

#### Task 2.6: Implement TestimonialService

```php
class TestimonialService
{
    public function list(array $filters = []): LengthAwarePaginator
    public function getById(int $id): Testimonial
    public function create(array $data): Testimonial
    public function update(Testimonial $testimonial, array $data): Testimonial
    public function delete(Testimonial $testimonial): bool
    public function approve(Testimonial $testimonial): Testimonial
    public function toggleFeatured(Testimonial $testimonial): Testimonial
    public function getPending(): Collection
}
```

---

### Phase 3: Contact Inquiries Module (1.5-2 hours)

#### Task 3.1: Create Controller

```bash
php artisan make:controller Admin/Hajj/InquiryController
```

#### Task 3.2: Implement Controller Methods

| Method | Route | Purpose |
|--------|-------|---------|
| index | GET /admin/inquiries | List inquiries |
| show | GET /admin/inquiries/{id} | View inquiry |
| markAsRead | PATCH /admin/inquiries/{id}/read | Mark as read |
| respond | POST /admin/inquiries/{id}/respond | Send response |
| updateStatus | PATCH /admin/inquiries/{id}/status | Update status |
| destroy | DELETE /admin/inquiries/{id} | Delete inquiry |
| bulkDelete | POST /admin/inquiries/bulk-delete | Bulk delete |

#### Task 3.3: Create Views

```
resources/views/admin/pages/inquiries/
├── index.blade.php
└── show.blade.php
```

**index.blade.php features:**
- Status filter tabs (All, New, Read, Responded, Closed)
- Package filter dropdown
- Date range filter
- Search by name/email
- Data table with:
  - Status indicator (colored dot)
  - Name
  - Email
  - Package name
  - Subject/Message preview
  - Date submitted
  - Actions (View, Delete)
- Bulk selection for delete
- New inquiry count badge
- Pagination

**show.blade.php features:**
- Inquiry details card:
  - Client info (name, email, phone)
  - Package info (if selected)
  - Message content (full)
  - Submitted date/time
  - Status badge
- Response section:
  - Previous responses (if any)
  - Response textarea
  - Send response button
  - Email preview toggle
- Status change dropdown
- Delete button with confirmation
- Back to list button

#### Task 3.4: Email Response

**Create Mailable:**
```bash
php artisan make:mail InquiryResponse
```

**Features:**
- Personalized greeting
- Response content
- Company signature
- Contact information

#### Task 3.5: Implement InquiryService

```php
class InquiryService
{
    public function list(array $filters = []): LengthAwarePaginator
    public function getById(int $id): ContactInquiry
    public function markAsRead(ContactInquiry $inquiry): ContactInquiry
    public function updateStatus(ContactInquiry $inquiry, InquiryStatus $status): ContactInquiry
    public function respond(ContactInquiry $inquiry, string $response): ContactInquiry
    public function delete(ContactInquiry $inquiry): bool
    public function bulkDelete(array $ids): int
    public function getNewCount(): int
    public function getByDateRange(Carbon $start, Carbon $end): Collection
}
```

---

### Phase 4: Site Settings Module (2-2.5 hours)

#### Task 4.1: Create Controller

```bash
php artisan make:controller Admin/Hajj/SettingController
```

#### Task 4.2: Implement Controller Methods

| Method | Route | Purpose |
|--------|-------|---------|
| index | GET /admin/settings | Show all settings |
| updateGeneral | PUT /admin/settings/general | Update general |
| updateContact | PUT /admin/settings/contact | Update contact |
| updateSocial | PUT /admin/settings/social | Update social |
| updateSeo | PUT /admin/settings/seo | Update SEO |
| updateBranding | PUT /admin/settings/branding | Update branding |

#### Task 4.3: Create Settings View

```
resources/views/admin/pages/settings/
└── index.blade.php
```

**Tabbed interface:**

**Tab 1: General Settings**
- Site name
- Site tagline
- Admin email
- Timezone select
- Date format
- Currency

**Tab 2: Contact Information**
- Main phone
- WhatsApp number
- Main email
- Inquiry email
- Address textarea
- Map embed code

**Tab 3: Social Media**
- Facebook URL
- Instagram URL
- Twitter URL
- YouTube URL
- LinkedIn URL
- TikTok URL

**Tab 4: SEO Settings**
- Default meta title
- Default meta description
- Meta keywords
- Google Analytics ID
- Facebook Pixel ID
- Schema markup

**Tab 5: Branding**
- Logo upload (light)
- Logo upload (dark)
- Favicon upload
- Primary color picker
- Secondary color picker
- Font selection

**Each tab:**
- Save button
- Reset to defaults option
- Unsaved changes warning (Alpine.js)

#### Task 4.4: Implement SettingService

```php
class SettingService
{
    public function get(string $key, mixed $default = null): mixed
    public function set(string $key, mixed $value, string $type = 'string'): SiteSetting
    public function getByGroup(string $group): array
    public function setMany(array $settings): void
    
    // Convenience methods
    public function getSiteName(): string
    public function getLogo(): ?string
    public function getSocialLinks(): array
    public function getSeoDefaults(): array
}
```

#### Task 4.5: Settings Caching

```php
// Cache settings for performance
public function getCached(string $key, mixed $default = null): mixed
{
    return Cache::rememberForever("setting:{$key}", function () use ($key, $default) {
        return $this->get($key, $default);
    });
}

public function clearCache(?string $key = null): void
{
    if ($key) {
        Cache::forget("setting:{$key}");
    } else {
        // Clear all settings cache
        Cache::flush();
    }
}
```

---

### Phase 5: FAQs Module (Optional - 1 hour)

#### Task 5.1: Create Controller

```bash
php artisan make:controller Admin/Hajj/FaqController
```

#### Task 5.2: Create Views

```
resources/views/admin/pages/faqs/
└── index.blade.php
```

**Features:**
- Inline editing
- Drag-to-reorder
- Category grouping
- Accordion preview
- Add/Edit modal

---

### Phase 5B: User Dashboard Module (2-2.5 hours)

> **Note:** This is for regular users (customers) to track their bookings.

#### Task 5B.1: Create User Controllers

```bash
php artisan make:controller User/DashboardController
php artisan make:controller User/BookingController
php artisan make:controller User/ProfileController
```

#### Task 5B.2: Implement User Dashboard Controller

```php
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        return view('user.dashboard', [
            'activeBookings' => $user->bookings()->whereNotIn('status', ['completed', 'cancelled'])->latest()->get(),
            'completedBookings' => $user->bookings()->where('status', 'completed')->count(),
            'recentBookings' => $user->bookings()->latest()->take(5)->get(),
        ]);
    }
}
```

#### Task 5B.3: Implement User Booking Controller

```php
class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with(['package', 'travelers', 'statusLogs'])
            ->latest()
            ->paginate(10);
            
        return view('user.bookings.index', compact('bookings'));
    }
    
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load(['package', 'travelers', 'statusLogs.changedBy']);
        
        return view('user.bookings.show', compact('booking'));
    }
}
```

#### Task 5B.4: Create User Views Directory

```
resources/views/user/
├── layouts/
│   └── app.blade.php
├── components/
│   ├── navigation.blade.php
│   └── booking-card.blade.php
├── dashboard.blade.php
├── bookings/
│   ├── index.blade.php
│   └── show.blade.php
└── profile/
    ├── edit.blade.php
    └── password.blade.php
```

#### Task 5B.5: User Dashboard View Features

**dashboard.blade.php:**
- Welcome message with user name
- Active bookings count
- Completed bookings count
- Quick stats cards
- Recent bookings list
- Link to view all bookings
- Profile quick access

**bookings/index.blade.php:**
- Booking cards with:
  - Package name and image
  - Booking reference number
  - Status badge (color-coded)
  - Date and travelers count
  - Total amount
  - View details button
- Filter by status
- Pagination

**bookings/show.blade.php:**
- Booking header with status
- Package details card
- Travelers list
- Status timeline (visual progress)
- Important dates
- Download invoice (if paid)
- Contact support button

#### Task 5B.6: Add User Routes

```php
// routes/web.php

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [User\DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [User\BookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [User\BookingController::class, 'show'])->name('show');
    });
    
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [User\ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [User\ProfileController::class, 'update'])->name('update');
        Route::get('/password', [User\ProfileController::class, 'password'])->name('password');
        Route::put('/password', [User\ProfileController::class, 'updatePassword'])->name('password.update');
    });
});
```

---

### Phase 5C: Admin User Management (Super Admin Only - 1.5 hours)

> **Note:** Only Super Admin can manage admin users and assign sections.

#### Task 5C.1: Create Admin User Controller

```bash
php artisan make:controller Admin/UserController
```

#### Task 5C.2: Implement Controller Methods

| Method | Route | Purpose |
|--------|-------|---------|
| index | GET /admin/users | List all admin users |
| create | GET /admin/users/create | Show create form |
| store | POST /admin/users | Create admin user |
| edit | GET /admin/users/{id}/edit | Show edit form |
| update | PUT /admin/users/{id} | Update admin user |
| destroy | DELETE /admin/users/{id} | Delete admin user |
| toggleStatus | PATCH /admin/users/{id}/toggle | Enable/disable user |
| assignSections | POST /admin/users/{id}/sections | Assign sections to admin |

#### Task 5C.3: Create AdminService

```php
class AdminService
{
    public function listAdmins(): LengthAwarePaginator
    {
        return User::whereIn('role', ['admin', 'super_admin'])
            ->with('sections')
            ->latest()
            ->paginate(15);
    }
    
    public function createAdmin(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'is_active' => $data['is_active'] ?? true,
        ]);
        
        if (!empty($data['sections'])) {
            $this->assignSections($user, $data['sections']);
        }
        
        return $user;
    }
    
    public function assignSections(User $user, array $sections): void
    {
        // Remove existing assignments
        AdminSection::where('user_id', $user->id)->delete();
        
        // Add new assignments
        foreach ($sections as $section) {
            AdminSection::create([
                'user_id' => $user->id,
                'section' => $section, // 'hajj', 'tour', or 'typing'
            ]);
        }
    }
    
    public function getAvailableSections(): array
    {
        return ['hajj', 'tour', 'typing'];
    }
}
```

#### Task 5C.4: Create Admin User Views

```
resources/views/admin/pages/users/
├── index.blade.php
├── create.blade.php
└── edit.blade.php
```

**index.blade.php features:**
- Table with admin users
- Name, email, role
- Assigned sections (badges)
- Status toggle
- Actions (edit, delete)
- Add new admin button

**create.blade.php / edit.blade.php features:**
- Name input
- Email input
- Password input (required on create, optional on edit)
- Role select (admin only, super admin requires special permission)
- Section checkboxes (Hajj, Tour, Typing)
- Active toggle

#### Task 5C.5: Add Admin User Routes

```php
// routes/admin.php (inside auth middleware)

Route::middleware('role:super_admin')->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::patch('/{user}/toggle', [UserController::class, 'toggleStatus'])->name('toggle');
    Route::post('/{user}/sections', [UserController::class, 'assignSections'])->name('sections');
});
```

#### Task 5C.6: Create Role Middleware

```php
// app/Http/Middleware/CheckRole.php

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Unauthorized access.');
        }
        
        return $next($request);
    }
}
```

Register in `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

---

### Phase 6: Dashboard Statistics (1.5-2 hours)

#### Task 6.1: Enhance DashboardController

```php
public function index()
{
    return view('admin.pages.dashboard', [
        'stats' => $this->getStats(),
        'recentInquiries' => $this->getRecentInquiries(),
        'popularPackages' => $this->getPopularPackages(),
        'recentTestimonials' => $this->getRecentTestimonials(),
        'chartData' => $this->getChartData(),
    ]);
}

private function getStats(): array
{
    return [
        'packages' => Package::count(),
        'active_packages' => Package::active()->count(),
        'articles' => Article::count(),
        'published_articles' => Article::published()->count(),
        'testimonials' => Testimonial::count(),
        'pending_testimonials' => Testimonial::where('is_approved', false)->count(),
        'inquiries' => ContactInquiry::count(),
        'new_inquiries' => ContactInquiry::where('status', InquiryStatus::NEW)->count(),
        'team_members' => TeamMember::active()->count(),
    ];
}
```

#### Task 6.2: Enhance Dashboard View

**dashboard.blade.php sections:**

**Stats Grid (4 columns):**
- Total Packages (active count)
- Total Articles (published count)
- Testimonials (pending count)
- Inquiries (new count highlight)

**Recent Inquiries Card:**
- Last 5 new inquiries
- Name, package, date
- Quick view link
- View all link

**Popular Packages Card:**
- Top 5 by inquiry count
- Package name, type, inquiries
- Edit link

**Chart Section (Optional):**
- Inquiries over time (Chart.js)
- Monthly breakdown
- Line or bar chart

**Recent Testimonials Card:**
- Last 5 pending
- Client name, rating, preview
- Quick approve button
- View all link

**Quick Actions Panel:**
- Add Package
- Add Article
- Add Team Member
- View Inquiries

---

### Phase 7: Notifications & Alerts (1 hour)

#### Task 7.1: Toast Notification System

**Create toast component:**
```
resources/views/admin/components/ui/toast.blade.php
```

**Alpine.js Toast Store:**
```javascript
Alpine.store('toast', {
    messages: [],
    add(message, type = 'success') {
        const id = Date.now();
        this.messages.push({ id, message, type });
        setTimeout(() => this.remove(id), 5000);
    },
    remove(id) {
        this.messages = this.messages.filter(m => m.id !== id);
    }
});
```

**Types:**
- success (green)
- error (red)
- warning (yellow)
- info (blue)

#### Task 7.2: Flash Messages

```php
// In controllers
return redirect()->route('admin.packages.index')
    ->with('success', 'Package created successfully');

return redirect()->back()
    ->with('error', 'Failed to save changes');
```

**View handling:**
```blade
@if(session('success'))
    <div x-data x-init="$store.toast.add('{{ session('success') }}', 'success')"></div>
@endif
```

#### Task 7.3: Confirmation Modals

**Delete confirmation modal (Alpine.js):**
- Warning icon
- Confirmation message
- Cancel/Delete buttons
- Form submission

---

### Phase 8: Activity Log (Optional - 1 hour)

#### Task 8.1: Create Activity Log Table

```bash
php artisan make:migration create_activity_logs_table
```

**Schema:**
- id
- user_id (foreign)
- action (string)
- model_type (string)
- model_id (integer)
- description (text)
- ip_address (string)
- created_at

#### Task 8.2: Create ActivityLog Model & Service

```php
class ActivityLogService
{
    public function log(string $action, Model $model, string $description = null): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'description' => $description ?? $this->generateDescription($action, $model),
            'ip_address' => request()->ip(),
        ]);
    }
}
```

#### Task 8.3: Dashboard Activity Feed

Show recent activities on dashboard:
- User name
- Action description
- Time ago
- Model link

---

### Phase 9: Polish & UX Improvements (1.5-2 hours)

#### Task 9.1: Loading States

- Button loading spinners
- Form submission overlay
- Table loading skeleton
- Page transition loading

#### Task 9.2: Empty States

Consistent empty states for:
- No packages
- No articles
- No testimonials
- No inquiries
- No search results

#### Task 9.3: Responsive Testing

Test and fix on:
- Mobile (320px-480px)
- Tablet (768px)
- Desktop (1024px+)
- Large screens (1440px+)

**Focus areas:**
- Sidebar collapse on mobile
- Form layouts on mobile
- Tables horizontal scroll
- Modal sizes
- Card layouts

#### Task 9.4: Keyboard Shortcuts

```javascript
// Alpine.js keyboard shortcuts
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        // Close modals
    }
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        // Save current form
    }
});
```

#### Task 9.5: Form Improvements

- Autosave drafts (localStorage)
- Unsaved changes warning
- Form reset option
- Required field indicators

---

### Phase 10: Routes Finalization (30 min)

#### Task 10.1: Complete routes/admin.php

```php
<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Hajj\DashboardController;
use App\Http\Controllers\Admin\Hajj\PackageController;
use App\Http\Controllers\Admin\Hajj\ArticleController;
use App\Http\Controllers\Admin\Hajj\ArticleCategoryController;
use App\Http\Controllers\Admin\Hajj\TeamMemberController;
use App\Http\Controllers\Admin\Hajj\TestimonialController;
use App\Http\Controllers\Admin\Hajj\InquiryController;
use App\Http\Controllers\Admin\Hajj\SettingController;
use App\Http\Controllers\Admin\Hajj\FaqController;

// Auth routes (no auth middleware)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // All resource routes as defined in Day 2 and today
    // ... packages, articles, team, testimonials, inquiries, settings, faqs
});
```

---

### Phase 11: Testing & Bug Fixes (1.5-2 hours)

#### Task 11.1: Manual Testing Checklist

**Authentication:**
- [ ] Login works
- [ ] Logout works
- [ ] Session persists
- [ ] Redirect after login

**Dashboard:**
- [ ] Stats display correctly
- [ ] Recent items show
- [ ] Quick actions work
- [ ] Charts render (if applicable)

**Packages:**
- [ ] Create with all fields
- [ ] Edit updates correctly
- [ ] Image upload works
- [ ] Gallery works
- [ ] Toggle states work
- [ ] Delete works
- [ ] Filters work
- [ ] Pagination works

**Articles:**
- [ ] Create with rich editor
- [ ] Categories manageable
- [ ] Publish/unpublish works
- [ ] Preview works
- [ ] SEO fields save

**Team:**
- [ ] Create with photo
- [ ] Reorder works
- [ ] Social links work
- [ ] Toggle active works

**Testimonials:**
- [ ] Create with rating
- [ ] Approve works
- [ ] Featured toggle works
- [ ] Package link works

**Inquiries:**
- [ ] List displays
- [ ] View details works
- [ ] Mark as read works
- [ ] Respond sends email
- [ ] Status update works

**Settings:**
- [ ] All tabs load
- [ ] Settings save
- [ ] Image uploads work
- [ ] Cache clears

#### Task 11.2: Fix Known Issues

Address any bugs found during testing.

#### Task 11.3: Performance Check

- Check page load times
- Verify eager loading
- Check N+1 queries
- Optimize if needed

---

### Phase 12: Documentation (30 min)

#### Task 12.1: Update README

Add admin panel section:
- Access URL
- Default credentials
- Feature overview

#### Task 12.2: Admin Guide

Create `docs/admin-guide.md`:
- Login instructions
- Module descriptions
- Common tasks
- Troubleshooting

---

## File Creation Checklist

### Controllers
```
☐ app/Http/Controllers/Admin/Hajj/TeamMemberController.php
☐ app/Http/Controllers/Admin/Hajj/TestimonialController.php
☐ app/Http/Controllers/Admin/Hajj/InquiryController.php
☐ app/Http/Controllers/Admin/Hajj/SettingController.php
☐ app/Http/Controllers/Admin/Hajj/FaqController.php (optional)
☐ app/Http/Controllers/Admin/UserController.php
☐ app/Http/Controllers/User/DashboardController.php
☐ app/Http/Controllers/User/BookingController.php
☐ app/Http/Controllers/User/ProfileController.php
```

### Form Requests
```
☐ app/Http/Requests/Admin/TeamMemberRequest.php
☐ app/Http/Requests/Admin/TestimonialRequest.php
☐ app/Http/Requests/Admin/AdminUserRequest.php
```

### Middleware
```
☐ app/Http/Middleware/CheckRole.php
```

### Services
```
☐ Update app/Services/TeamService.php
☐ Update app/Services/TestimonialService.php
☐ Update app/Services/InquiryService.php
☐ Update app/Services/SettingService.php
☐ Update app/Services/AdminService.php
☐ Update app/Services/UserService.php
☐ app/Services/ActivityLogService.php (optional)
```

### Views - Team
```
☐ resources/views/admin/pages/team/index.blade.php
☐ resources/views/admin/pages/team/create.blade.php
☐ resources/views/admin/pages/team/edit.blade.php
```

### Views - Testimonials
```
☐ resources/views/admin/pages/testimonials/index.blade.php
☐ resources/views/admin/pages/testimonials/create.blade.php
☐ resources/views/admin/pages/testimonials/edit.blade.php
```

### Views - Inquiries
```
☐ resources/views/admin/pages/inquiries/index.blade.php
☐ resources/views/admin/pages/inquiries/show.blade.php
```

### Views - Settings
```
☐ resources/views/admin/pages/settings/index.blade.php
```

### Views - FAQs (optional)
```
☐ resources/views/admin/pages/faqs/index.blade.php
```

### Views - Admin Users (Super Admin)
```
☐ resources/views/admin/pages/users/index.blade.php
☐ resources/views/admin/pages/users/create.blade.php
☐ resources/views/admin/pages/users/edit.blade.php
```

### Views - User Dashboard
```
☐ resources/views/user/layouts/app.blade.php
☐ resources/views/user/components/navigation.blade.php
☐ resources/views/user/components/booking-card.blade.php
☐ resources/views/user/dashboard.blade.php
☐ resources/views/user/bookings/index.blade.php
☐ resources/views/user/bookings/show.blade.php
☐ resources/views/user/profile/edit.blade.php
☐ resources/views/user/profile/password.blade.php
```

### Components
```
☐ resources/views/admin/components/form/star-rating.blade.php
☐ resources/views/admin/components/ui/toast.blade.php
```

### Mail
```
☐ app/Mail/InquiryResponse.php
☐ app/Mail/BookingStatusChanged.php
☐ resources/views/emails/inquiry-response.blade.php
☐ resources/views/emails/booking-status-changed.blade.php
```

### Migrations (optional)
```
☐ database/migrations/xxxx_create_activity_logs_table.php
```

---

## End of Day 3 Verification

### Complete Functionality Checklist
- [ ] All authentication working
- [ ] Dashboard with real stats
- [ ] Package CRUD complete
- [ ] Article CRUD complete
- [ ] Team Members CRUD complete
- [ ] Testimonials CRUD complete
- [ ] Inquiries management complete
- [ ] Settings management complete
- [ ] **User dashboard accessible**
- [ ] **User can view their bookings**
- [ ] **User can see booking status timeline**
- [ ] **Super Admin can manage admin users**
- [ ] **Super Admin can assign sections to admins**
- [ ] **Admin sees only assigned sections**
- [ ] All forms validated
- [ ] All images upload correctly
- [ ] All toggles working
- [ ] Toast notifications working
- [ ] Responsive on all devices
- [ ] No console errors
- [ ] Performance acceptable

### Final Commands

```bash
# Clear all caches
php artisan optimize:clear

# Rebuild
npm run build

# Final migration check
php artisan migrate:status

# Route list verification
php artisan route:list --path=admin

# Run tests (if any)
php artisan test
```

---

## Project Completion Summary

### Modules Implemented
1. ✅ Authentication (Login/Logout)
2. ✅ Dashboard (Stats, Recent Items)
3. ✅ Packages (Full CRUD, Gallery)
4. ✅ Articles (Full CRUD, Categories)
5. ✅ Team Members (CRUD, Reorder)
6. ✅ Testimonials (CRUD, Approval)
7. ✅ Inquiries (Management, Responses)
8. ✅ Settings (All categories)
9. ✅ User Dashboard (Booking Tracking)
10. ✅ Admin User Management (Super Admin)
11. ⚪ FAQs (Optional)
12. ⚪ Activity Log (Optional)

### Architecture Summary
- **Frontend:** Blade + Alpine.js + Tailwind CSS v4
- **Backend:** Laravel 12 with Service Pattern
- **Database:** MySQL with proper relationships
- **Authentication:** Session-based via Fortify
- **File Storage:** Local with public disk
- **Role System:** Super Admin, Admin, User

### Key Features
- Fully responsive design
- Professional UI/UX
- Smooth animations
- Reusable components
- Form validation
- Toast notifications
- Drag-to-reorder
- Image management
- **Role-based access control**
- **Section assignment for admins**
- **User booking tracking**
- **Booking status history**

---

## Estimated Time: 16-19 hours

| Phase | Time |
|-------|------|
| Phase 1: Team Module | 2-2.5 hours |
| Phase 2: Testimonials | 2 hours |
| Phase 3: Inquiries | 1.5-2 hours |
| Phase 4: Settings | 2-2.5 hours |
| Phase 5: FAQs (optional) | 1 hour |
| **Phase 5B: User Dashboard** | 2-2.5 hours |
| **Phase 5C: Admin User Management** | 1.5 hours |
| Phase 6: Dashboard Stats | 1.5-2 hours |
| Phase 7: Notifications | 1 hour |
| Phase 8: Activity Log (optional) | 1 hour |
| Phase 9: Polish | 1.5-2 hours |
| Phase 10: Routes | 30 min |
| Phase 11: Testing | 1.5-2 hours |
| Phase 12: Documentation | 30 min |

---

## Next Steps (Post-Day 3)

1. **Public Website Integration**
   - Connect admin data to public pages
   - Display packages, articles, testimonials
   - Contact form submissions

2. **Advanced Features**
   - Email notifications
   - Scheduled content publishing
   - Backup system
   - Multi-language support

3. **Security Hardening**
   - Rate limiting
   - Security audit
   - Penetration testing

4. **Deployment**
   - Server setup
   - SSL configuration
   - Production optimization

---

*End of Day 3 Steps - Admin Panel Complete!*
