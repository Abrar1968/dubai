# Day 1: Foundation & Setup
# Hajj Admin Panel Development

**Date:** Day 1 of 3  
**Focus:** Database, Models, Backend Foundation, Admin Layout

---

## Overview

Day 1 focuses on establishing the complete backend foundation including database migrations, models, relationships, and the basic admin layout structure.

---

## Tasks Checklist

### Phase 1: Database Setup (2-3 hours)

#### Task 1.1: Create Database Migrations
- [ ] Create `packages` table migration
- [ ] Create `package_gallery` table migration
- [ ] Create `article_categories` table migration
- [ ] Create `articles` table migration
- [ ] Create `team_members` table migration
- [ ] Create `testimonials` table migration
- [ ] Create `contact_inquiries` table migration
- [ ] Create `site_settings` table migration
- [ ] Create `office_locations` table migration

**Commands:**
```bash
php artisan make:migration create_packages_table
php artisan make:migration create_package_gallery_table
php artisan make:migration create_article_categories_table
php artisan make:migration create_articles_table
php artisan make:migration create_team_members_table
php artisan make:migration create_testimonials_table
php artisan make:migration create_contact_inquiries_table
php artisan make:migration create_site_settings_table
php artisan make:migration create_office_locations_table
```

#### Task 1.2: Run Migrations
- [ ] Run all migrations
- [ ] Verify tables created correctly

**Commands:**
```bash
php artisan migrate
php artisan migrate:status
```

---

### Phase 2: Models & Relationships (2-3 hours)

#### Task 2.1: Create Models
- [ ] Create `Package` model with fillable, casts, relationships
- [ ] Create `PackageGallery` model
- [ ] Create `Article` model with relationships
- [ ] Create `ArticleCategory` model
- [ ] Create `TeamMember` model
- [ ] Create `Testimonial` model with package relationship
- [ ] Create `ContactInquiry` model
- [ ] Create `SiteSetting` model
- [ ] Create `OfficeLocation` model

**Commands:**
```bash
php artisan make:model Package
php artisan make:model PackageGallery
php artisan make:model Article
php artisan make:model ArticleCategory
php artisan make:model TeamMember
php artisan make:model Testimonial
php artisan make:model ContactInquiry
php artisan make:model SiteSetting
php artisan make:model OfficeLocation
```

#### Task 2.2: Define Relationships
```
Package:
  - hasMany PackageGallery
  - hasMany Testimonial
  - hasMany ContactInquiry

Article:
  - belongsTo ArticleCategory
  - belongsTo User (author)

ArticleCategory:
  - hasMany Article

Testimonial:
  - belongsTo Package

ContactInquiry:
  - belongsTo Package
```

#### Task 2.3: Create Enums
- [ ] Create `PackageType` enum (hajj, umrah, tour)
- [ ] Create `InquiryStatus` enum (new, read, responded, closed)
- [ ] Create `PublishStatus` enum (draft, published)

**Location:** `app/Enums/`

---

### Phase 3: Services Layer (1-2 hours)

#### Task 3.1: Create Services
- [ ] Create `ImageUploadService` for handling image uploads
- [ ] Create `SlugService` for generating unique slugs
- [ ] Create `SettingService` for managing site settings

**Commands:**
```bash
mkdir app/Services
# Create service files manually
```

**Files to create:**
```
app/Services/
├── ImageUploadService.php
├── SlugService.php
└── SettingService.php
```

---

### Phase 4: Admin Route Setup (30 min)

#### Task 4.1: Create Admin Routes File
- [ ] Create `routes/admin.php`
- [ ] Register in `bootstrap/app.php`
- [ ] Add admin middleware group

**Add to bootstrap/app.php:**
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

---

### Phase 5: Admin Layout Frontend (2-3 hours)

#### Task 5.1: Create Admin Layout Structure
- [ ] Create `resources/js/layouts/AdminLayout.vue`
- [ ] Create admin sidebar component
- [ ] Create admin header component
- [ ] Create admin breadcrumb component

**Directory Structure:**
```
resources/js/
├── layouts/
│   └── AdminLayout.vue
├── components/
│   └── admin/
│       ├── layout/
│       │   ├── AdminSidebar.vue
│       │   ├── AdminHeader.vue
│       │   └── AdminBreadcrumb.vue
│       └── common/
│           ├── StatsCard.vue
│           └── StatusBadge.vue
└── pages/
    └── admin/
        └── Dashboard.vue
```

#### Task 5.2: Implement AdminLayout.vue
```vue
<!-- Key features to implement -->
- Responsive sidebar (collapsible on mobile)
- Dark sidebar with gold accent (matching Hajj theme)
- Top header with user menu
- Breadcrumb navigation
- Main content area with slot
```

#### Task 5.3: Implement AdminSidebar.vue
```vue
<!-- Navigation structure -->
- Dashboard
- Hajj & Umrah (expandable)
  - Packages
  - Articles
  - Team
  - Testimonials
  - Inquiries
  - Settings
- Tour & Travel (disabled/coming soon)
- Typing Services (disabled/coming soon)
```

#### Task 5.4: Create Dashboard Controller & Page
- [ ] Create `DashboardController` 
- [ ] Create `Dashboard.vue` page
- [ ] Add dashboard route

**Controller location:** `app/Http/Controllers/Admin/DashboardController.php`

---

### Phase 6: Seeders (1 hour)

#### Task 6.1: Create Seeders
- [ ] Create `PackageSeeder` with sample packages
- [ ] Create `ArticleCategorySeeder`
- [ ] Create `ArticleSeeder` with sample articles
- [ ] Create `TeamMemberSeeder`
- [ ] Create `TestimonialSeeder`
- [ ] Create `SiteSettingSeeder` with default settings

**Commands:**
```bash
php artisan make:seeder PackageSeeder
php artisan make:seeder ArticleCategorySeeder
php artisan make:seeder ArticleSeeder
php artisan make:seeder TeamMemberSeeder
php artisan make:seeder TestimonialSeeder
php artisan make:seeder SiteSettingSeeder
```

#### Task 6.2: Run Seeders
```bash
php artisan db:seed
```

---

## File Creation Checklist

### Backend Files
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

☐ app/Models/Package.php
☐ app/Models/PackageGallery.php
☐ app/Models/Article.php
☐ app/Models/ArticleCategory.php
☐ app/Models/TeamMember.php
☐ app/Models/Testimonial.php
☐ app/Models/ContactInquiry.php
☐ app/Models/SiteSetting.php
☐ app/Models/OfficeLocation.php

☐ app/Enums/PackageType.php
☐ app/Enums/InquiryStatus.php
☐ app/Enums/PublishStatus.php

☐ app/Services/ImageUploadService.php
☐ app/Services/SlugService.php
☐ app/Services/SettingService.php

☐ app/Http/Controllers/Admin/DashboardController.php

☐ routes/admin.php

☐ database/seeders/PackageSeeder.php
☐ database/seeders/ArticleCategorySeeder.php
☐ database/seeders/ArticleSeeder.php
☐ database/seeders/TeamMemberSeeder.php
☐ database/seeders/TestimonialSeeder.php
☐ database/seeders/SiteSettingSeeder.php
```

### Frontend Files
```
☐ resources/js/layouts/AdminLayout.vue
☐ resources/js/components/admin/layout/AdminSidebar.vue
☐ resources/js/components/admin/layout/AdminHeader.vue
☐ resources/js/components/admin/layout/AdminBreadcrumb.vue
☐ resources/js/components/admin/common/StatsCard.vue
☐ resources/js/components/admin/common/StatusBadge.vue
☐ resources/js/pages/admin/Dashboard.vue
☐ resources/js/types/admin.d.ts
```

---

## Verification Steps

### End of Day 1 Checklist
- [ ] All migrations run successfully
- [ ] All models created with correct relationships
- [ ] Database tables verified in MySQL
- [ ] Admin route `/admin/dashboard` accessible
- [ ] Admin layout renders correctly
- [ ] Sidebar navigation displays all menu items
- [ ] Sample data seeded successfully
- [ ] No TypeScript errors
- [ ] No PHP errors

### Test Commands
```bash
# Verify migrations
php artisan migrate:status

# Test database connection
php artisan tinker
>>> \App\Models\Package::count()

# Test routes
php artisan route:list --path=admin

# Build frontend
npm run build

# Start development server
composer dev
```

---

## Notes for Tomorrow (Day 2)

Day 2 will focus on:
1. Package CRUD (Controllers, Requests, Forms, Pages)
2. Article CRUD
3. Form validation
4. Image upload functionality
5. DataTable component

**Prerequisites for Day 2:**
- All models working
- Admin layout functional
- Database seeded with test data

---

## Estimated Time: 8-10 hours

| Phase | Time |
|-------|------|
| Phase 1: Database Migrations | 2-3 hours |
| Phase 2: Models & Relationships | 2-3 hours |
| Phase 3: Services Layer | 1-2 hours |
| Phase 4: Admin Routes | 30 min |
| Phase 5: Admin Layout Frontend | 2-3 hours |
| Phase 6: Seeders | 1 hour |

---

## Troubleshooting

### Common Issues

**Migration fails:**
```bash
php artisan migrate:fresh  # Reset and re-run
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

**Vite build errors:**
```bash
rm -rf node_modules
npm install
npm run build
```
