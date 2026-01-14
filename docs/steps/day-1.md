# Day 1: Foundation & Setup
# Hajj Admin Panel Development

**Date:** Day 1 of 3  
**Focus:** Database, Models, Services, Admin Layout, Authentication, RBAC  
**Stack:** Laravel 12 + Blade + Alpine.js + Tailwind CSS v4

---

## Overview

Day 1 establishes the complete foundation: database schema (including user roles and bookings), Eloquent models, service layer, admin layout structure, role-based access control, and authentication setup.

---

## Tasks Checklist

### Phase 1: Database Migrations (2-2.5 hours)

#### Task 1.1: Create All Migrations

| Migration | Command |
|-----------|---------|
| users (update) | `php artisan make:migration add_role_fields_to_users_table` |
| admin_sections | `php artisan make:migration create_admin_sections_table` |
| packages | `php artisan make:migration create_packages_table` |
| package_gallery | `php artisan make:migration create_package_gallery_table` |
| bookings | `php artisan make:migration create_bookings_table` |
| booking_travelers | `php artisan make:migration create_booking_travelers_table` |
| booking_status_logs | `php artisan make:migration create_booking_status_logs_table` |
| article_categories | `php artisan make:migration create_article_categories_table` |
| articles | `php artisan make:migration create_articles_table` |
| team_members | `php artisan make:migration create_team_members_table` |
| testimonials | `php artisan make:migration create_testimonials_table` |
| contact_inquiries | `php artisan make:migration create_contact_inquiries_table` |
| site_settings | `php artisan make:migration create_site_settings_table` |
| office_locations | `php artisan make:migration create_office_locations_table` |
| faqs | `php artisan make:migration create_faqs_table` |

#### Task 1.2: Define Table Schemas

Implement schemas as defined in SRS.md Section 7.2.

**Priority Tables (Core Functionality):**
1. users (update) - Add role, profile fields
2. admin_sections - Section assignments for admins
3. bookings - Customer package purchases
4. booking_travelers - Traveler information
5. booking_status_logs - Status history

#### Task 1.3: Run Migrations

```bash
php artisan migrate
php artisan migrate:status
```

**Verification:**
- [ ] All 15 tables created
- [ ] Users table has role column
- [ ] Foreign keys working
- [ ] Indexes present

---

### Phase 2: Eloquent Models (2 hours)

#### Task 2.1: Create Models

| Model | Command |
|-------|---------|
| AdminSection | `php artisan make:model AdminSection` |
| Package | `php artisan make:model Package` |
| PackageGallery | `php artisan make:model PackageGallery` |
| Booking | `php artisan make:model Booking` |
| BookingTraveler | `php artisan make:model BookingTraveler` |
| BookingStatusLog | `php artisan make:model BookingStatusLog` |
| Article | `php artisan make:model Article` |
| ArticleCategory | `php artisan make:model ArticleCategory` |
| TeamMember | `php artisan make:model TeamMember` |
| Testimonial | `php artisan make:model Testimonial` |
| ContactInquiry | `php artisan make:model ContactInquiry` |
| SiteSetting | `php artisan make:model SiteSetting` |
| OfficeLocation | `php artisan make:model OfficeLocation` |
| Faq | `php artisan make:model Faq` |

#### Task 2.2: Update User Model

Add to existing User model:
- role enum cast
- Relationships: assignedSections, bookings, articles
- Helper methods: isSuperAdmin(), isAdmin(), isUser(), hasSection()

#### Task 2.3: Define Model Properties

For each model:
- [ ] Define $fillable array
- [ ] Define $casts array
- [ ] Add SoftDeletes trait (where applicable)

#### Task 2.4: Define Relationships

| Model | Relationships |
|-------|---------------|
| User | hasMany(AdminSection), hasMany(Booking), hasMany(Article) |
| AdminSection | belongsTo(User), belongsTo(User, 'assigned_by') |
| Package | hasMany(PackageGallery), hasMany(Booking), hasMany(Testimonial), hasMany(ContactInquiry) |
| Booking | belongsTo(User), belongsTo(Package), hasMany(BookingTraveler), hasMany(BookingStatusLog) |
| BookingTraveler | belongsTo(Booking) |
| BookingStatusLog | belongsTo(Booking), belongsTo(User, 'changed_by') |
| Article | belongsTo(ArticleCategory), belongsTo(User) |
| ArticleCategory | hasMany(Article) |
| Testimonial | belongsTo(Package) |
| ContactInquiry | belongsTo(Package) |

#### Task 2.5: Add Scopes

| Model | Scopes |
|-------|--------|
| User | admins(), superAdmins(), customers(), active() |
| Package | active(), featured(), hajj(), umrah() |
| Booking | pending(), confirmed(), paid(), active(), forUser(userId) |
| Article | published(), draft(), recent() |
| TeamMember | active(), ordered() |
| Testimonial | approved(), featured() |

---

### Phase 3: Enums (45 min)

#### Task 3.1: Create Enums Directory

```bash
mkdir app/Enums
```

#### Task 3.2: Create Enum Files

| Enum | Values |
|------|--------|
| UserRole | super_admin, admin, user |
| PackageType | hajj, umrah, tour |
| BookingStatus | pending, confirmed, paid, processing, ready, completed, cancelled |
| InquiryStatus | new, read, responded, closed |
| PublishStatus | draft, published |

**Files to create:**
- `app/Enums/UserRole.php`
- `app/Enums/PackageType.php`
- `app/Enums/BookingStatus.php`
- `app/Enums/InquiryStatus.php`
- `app/Enums/PublishStatus.php`

---

### Phase 4: Service Layer (2.5-3 hours)

#### Task 4.1: Create Services Directory

```bash
mkdir app/Services
```

#### Task 4.2: Create Service Files

| Service | Primary Responsibility |
|---------|----------------------|
| AdminService | Admin CRUD, section assignment |
| UserService | User profile, password management |
| BookingService | Booking CRUD, status updates, payment |
| PackageService | Package CRUD, status toggles |
| ArticleService | Article CRUD, publishing |
| TeamService | Team CRUD, reordering |
| TestimonialService | Testimonial CRUD, approval |
| InquiryService | Inquiry management |
| SettingService | Settings get/set |
| MediaService | Image upload/delete |
| SlugService | Slug generation |

#### Task 4.3: Implement Core Methods

**MediaService (Priority - used by others):**
- uploadImage(UploadedFile, path): string
- uploadImages(array, path): array
- deleteImage(path): bool
- createThumbnail(path, size): string

**SlugService:**
- generate(string, Model): string
- generateFrom(string): string
- isUnique(slug, Model, excludeId): bool

**SettingService:**
- get(key, default): mixed
- set(key, value, type): SiteSetting
- getByGroup(group): array
- setMany(array): void

---

### Phase 5: Admin Routes & Middleware (45 min)

#### Task 5.1: Create Admin Routes File

Create `routes/admin.php`

#### Task 5.2: Register in Bootstrap

Update `bootstrap/app.php`:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function () {
        Route::middleware(['web', 'auth'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('routes/admin.php'));
    },
)
```

#### Task 5.3: Define Initial Routes

Basic routes for:
- Login page
- Dashboard
- Placeholder routes for all modules

#### Task 5.4: Create AdminMiddleware (Optional)

If role-based access needed:
- Create `app/Http/Middleware/AdminMiddleware.php`
- Register in `bootstrap/app.php`

---

### Phase 6: Admin Layout (Blade) (2.5-3 hours)

#### Task 6.1: Create Directory Structure

```
resources/views/admin/
├── layouts/
│   ├── app.blade.php
│   └── auth.blade.php
├── components/
│   └── layout/
│       ├── sidebar.blade.php
│       ├── header.blade.php
│       └── breadcrumb.blade.php
├── pages/
│   └── dashboard.blade.php
└── auth/
    └── login.blade.php
```

#### Task 6.2: Implement Main Layout

**app.blade.php features:**
- Responsive sidebar (hidden on mobile)
- Fixed header with user dropdown
- Main content area with breadcrumb
- Alpine.js sidebar toggle
- Tailwind CSS v4 styling

#### Task 6.3: Implement Sidebar Component

**sidebar.blade.php features:**
- Dark background (#1e293b)
- Gold accent (#D3A762) for active items
- Section grouping (Hajj & Umrah, etc.)
- Smooth transitions
- Mobile overlay mode

#### Task 6.4: Implement Header Component

**header.blade.php features:**
- Mobile menu toggle
- Search input
- Notifications bell
- User dropdown (profile, logout)

#### Task 6.5: Implement Auth Layout

**auth.blade.php features:**
- Centered card design
- Logo display
- Clean, professional look

---

### Phase 7: Authentication Setup (1 hour)

#### Task 7.1: Create Login Controller

```bash
php artisan make:controller Admin/Auth/LoginController
```

Methods:
- showLoginForm(): View
- login(Request): RedirectResponse
- logout(Request): RedirectResponse

#### Task 7.2: Create Login View

**login.blade.php features:**
- Email input with icon
- Password input with show/hide toggle
- Remember me checkbox
- Submit button with loading state
- Error message display
- Forgot password link

#### Task 7.3: Configure Auth Guards (if needed)

For separate admin guard, update `config/auth.php`

#### Task 7.4: Add Admin Login Link to Footer

Update `hajjfooter.vue`:
- Add "Admin Login" link
- Link to `/admin/login`

---

### Phase 8: Dashboard Setup (1 hour)

#### Task 8.1: Create Dashboard Controller

```bash
php artisan make:controller Admin/Hajj/DashboardController
```

#### Task 8.2: Implement Dashboard View

**dashboard.blade.php features:**
- Stats cards row (4 cards)
- Recent inquiries section
- Quick actions panel
- Activity feed (optional)

#### Task 8.3: Create Stats Card Component

**components/dashboard/stats-card.blade.php:**
- Icon display
- Title, value
- Change indicator (optional)
- Click-through link

---

### Phase 9: Seeders (1 hour)

#### Task 9.1: Create Seeders

| Seeder | Command |
|--------|---------|
| ArticleCategorySeeder | `php artisan make:seeder ArticleCategorySeeder` |
| PackageSeeder | `php artisan make:seeder PackageSeeder` |
| ArticleSeeder | `php artisan make:seeder ArticleSeeder` |
| TeamMemberSeeder | `php artisan make:seeder TeamMemberSeeder` |
| TestimonialSeeder | `php artisan make:seeder TestimonialSeeder` |
| SiteSettingSeeder | `php artisan make:seeder SiteSettingSeeder` |
| FaqSeeder | `php artisan make:seeder FaqSeeder` |

#### Task 9.2: Implement Seeders

Add realistic sample data for testing.

#### Task 9.3: Update DatabaseSeeder

Call all seeders in correct order.

#### Task 9.4: Run Seeders

```bash
php artisan db:seed
```

---

### Phase 10: Admin CSS Setup (30 min)

#### Task 10.1: Create Admin CSS

Create `resources/css/admin.css`:
- Import Tailwind
- Custom admin utilities
- Component styles

#### Task 10.2: Update Vite Config

Add admin CSS/JS to build.

#### Task 10.3: Build Assets

```bash
npm run build
```

---

## File Creation Checklist

### Migrations
```
☐ database/migrations/xxxx_create_packages_table.php
☐ database/migrations/xxxx_create_package_gallery_table.php
☐ database/migrations/xxxx_create_article_categories_table.php
☐ database/migrations/xxxx_create_articles_table.php
☐ database/migrations/xxxx_create_team_members_table.php
☐ database/migrations/xxxx_create_testimonials_table.php
☐ database/migrations/xxxx_create_contact_inquiries_table.php
☐ database/migrations/xxxx_create_site_settings_table.php
☐ database/migrations/xxxx_create_office_locations_table.php
☐ database/migrations/xxxx_create_faqs_table.php
```

### Models
```
☐ app/Models/Package.php
☐ app/Models/PackageGallery.php
☐ app/Models/Article.php
☐ app/Models/ArticleCategory.php
☐ app/Models/TeamMember.php
☐ app/Models/Testimonial.php
☐ app/Models/ContactInquiry.php
☐ app/Models/SiteSetting.php
☐ app/Models/OfficeLocation.php
☐ app/Models/Faq.php
```

### Enums
```
☐ app/Enums/PackageType.php
☐ app/Enums/InquiryStatus.php
☐ app/Enums/PublishStatus.php
```

### Services
```
☐ app/Services/PackageService.php
☐ app/Services/ArticleService.php
☐ app/Services/TeamService.php
☐ app/Services/TestimonialService.php
☐ app/Services/InquiryService.php
☐ app/Services/SettingService.php
☐ app/Services/MediaService.php
☐ app/Services/SlugService.php
```

### Controllers
```
☐ app/Http/Controllers/Admin/Auth/LoginController.php
☐ app/Http/Controllers/Admin/Hajj/DashboardController.php
```

### Routes
```
☐ routes/admin.php
```

### Views
```
☐ resources/views/admin/layouts/app.blade.php
☐ resources/views/admin/layouts/auth.blade.php
☐ resources/views/admin/components/layout/sidebar.blade.php
☐ resources/views/admin/components/layout/header.blade.php
☐ resources/views/admin/components/layout/breadcrumb.blade.php
☐ resources/views/admin/components/dashboard/stats-card.blade.php
☐ resources/views/admin/pages/dashboard.blade.php
☐ resources/views/admin/auth/login.blade.php
```

### Seeders
```
☐ database/seeders/ArticleCategorySeeder.php
☐ database/seeders/PackageSeeder.php
☐ database/seeders/ArticleSeeder.php
☐ database/seeders/TeamMemberSeeder.php
☐ database/seeders/TestimonialSeeder.php
☐ database/seeders/SiteSettingSeeder.php
☐ database/seeders/FaqSeeder.php
```

### Assets
```
☐ resources/css/admin.css
☐ resources/js/admin/app.js
```

---

## End of Day 1 Verification

### Functionality Checklist
- [ ] All migrations run successfully
- [ ] All models created with relationships
- [ ] Services created with basic methods
- [ ] Admin routes registered
- [ ] Login page accessible at /admin/login
- [ ] Login authentication working
- [ ] Dashboard displays after login
- [ ] Sidebar navigation visible
- [ ] Responsive layout working
- [ ] Seeders populate sample data

### Test Commands

```bash
# Check migrations
php artisan migrate:status

# Test models in tinker
php artisan tinker
>>> \App\Models\Package::count()

# Check routes
php artisan route:list --path=admin

# Build and test
npm run build
php artisan serve
```

---

## Notes for Day 2

Day 2 will focus on:
- Package CRUD (full implementation)
- Article CRUD (full implementation)
- Blade form components
- Image upload functionality
- DataTable component

**Prerequisites:**
- All models working
- Services with basic methods
- Admin layout functional
- Sample data in database

---

## Estimated Time: 10-12 hours

| Phase | Time |
|-------|------|
| Phase 1: Migrations | 1.5-2 hours |
| Phase 2: Models | 1.5-2 hours |
| Phase 3: Enums | 30 min |
| Phase 4: Services | 2-2.5 hours |
| Phase 5: Routes | 45 min |
| Phase 6: Layout | 2.5-3 hours |
| Phase 7: Auth | 1 hour |
| Phase 8: Dashboard | 1 hour |
| Phase 9: Seeders | 1 hour |
| Phase 10: Assets | 30 min |

---

## Troubleshooting

**Migration fails:**
```bash
php artisan migrate:fresh
```

**Model not found:**
```bash
composer dump-autoload
```

**Route not working:**
```bash
php artisan route:clear
php artisan cache:clear
```

**View not found:**
```bash
php artisan view:clear
```

**Vite build errors:**
```bash
rm -rf node_modules
npm install
npm run build
```

---

*End of Day 1 Steps*
