# Copilot Instructions — Dubai Tourism & Travel (Laravel 12)

## 🎯 Core Workflow Rules (CRITICAL)

### 98/2 Rule: Code > Chat
- **98% code execution, 2% conversation** — implement solutions directly, minimize explanatory text
- Only ask clarifying questions when specifications are genuinely ambiguous or missing
- Default to implementation over discussion

### Auto-Continue on Length Limits
- If response reaches token/length limit, **automatically continue** in next message without prompting
- Resume exactly where left off, maintain context
- Complete all pending todos before stopping

### Comprehensive Analysis → Todo List → Execute
1. **ALWAYS read all related files thoroughly** before any implementation:
   - All relevant `docs/*.md` files (SRS.md, srs-backend.md, srs-frontend.md, hajj-section-overview.md)
   - Related step files (`docs/steps/day-*.md`)
   - Existing codebase files that will be modified or referenced
2. **Create comprehensive, detailed todo list** using `manage_todo_list` tool with:
   - Specific, actionable items (not vague descriptions)
   - File paths and method names where applicable
   - Dependencies and order clearly defined
3. **Execute todos strictly one-by-one**:
   - Mark todo as `in-progress` before starting
   - Complete the entire todo (all code changes, tests if applicable)
   - Mark as `completed` immediately after finishing
   - Move to next todo
   - **Never stop until ALL todos are completed**

### Documentation Synchronization
- **ALWAYS cross-check with docs** before implementing:
  - `docs/SRS.md` for requirements and database schema
  - `docs/srs-backend.md` for backend architecture, services, models
  - `docs/srs-frontend.md` for frontend components, layouts, Alpine.js patterns
  - `docs/hajj-section-overview.md` for data models and module specs
- **Zero tolerance for mismatches** — if implementation differs from specs, it's wrong
- **Auto-update documentation** when external changes or new requirements are introduced:
  - Update relevant `docs/*.md` files
  - Update this `.github/copilot-instructions.md`
  - Document all changes in next scrum report

### GitHub Integration
- **Auto-push changes** to GitHub after completing all todos with proper commit messages:
  - Use conventional commits format: `feat:`, `fix:`, `docs:`, `refactor:`, etc.
  - Include detailed description of what was implemented
  - Reference relevant docs or issues
  - Push to current branch (check with user if uncertain which branch)

### Scrum Reporting
- After completing all todos for a given topic/task, **generate scrum report**:
  - Follow format in `docs/reports/2026-01-14-scrum-report.md`
  - Include: date, task, summary of work, files modified, metrics, notes, next steps
  - Save as `docs/reports/YYYY-MM-DD-scrum-report.md`

---

## 📋 Project Architecture Overview

### What This Project Is
- **Dual Stack**: Laravel 12 backend with TWO frontend approaches:
  1. **Public Website**: Vue 3 + Inertia.js + TypeScript (SPA-like pages) — already implemented
  2. **Admin Panel**: Laravel Blade + Alpine.js + Tailwind CSS v4 (server-rendered) — **NOT YET IMPLEMENTED**
  3. **User Dashboard**: Laravel Blade + Alpine.js (for customers to track bookings) — **NOT YET IMPLEMENTED**
- **Database**: MySQL with comprehensive schema (16 tables including users, bookings, packages, articles, etc.)
- **Authentication**: Laravel Fortify with 2FA support
- **Architecture**: Service Pattern — all business logic in service classes (`app/Services/*`)

### Critical Structural Knowledge
- **Three Sections**: Hajj & Umrah (active), Tour & Travel (phase 2), Typing Services (phase 3)
- **Three User Roles**:
  - `super_admin`: full system access, manages admins, assigns sections
  - `admin`: section-specific access (can be assigned hajj, tour, typing), no admin management
  - `user`: customer role, can book packages, track orders via user dashboard
- **Role-Based Sidebar**: Admin panel sidebar dynamically shows only assigned sections based on `admin_sections` table
- **Public Frontend (Vue)**: Uses folder names with special chars like `hajj&umrah/`, `tour&travel/` — **NEVER rename these**
- **Admin Panel (Blade)**: Located at `/admin/*` routes, uses `routes/admin.php`, views in `resources/views/admin/`
- **User Dashboard (Blade)**: Located at `/user/*` routes (planned), views in `resources/views/user/`
- **Image Storage**: Uses Laravel's storage system with `public` disk, symlinked to `public/storage`
- **Test Data Available**: Run `php artisan db:seed --class=HajjSectionSeeder` to populate sample data (3 users, 6 packages, 5 articles, etc.)

### Database Schema Highlights (see docs/SRS.md Section 7.2)
- **users**: enhanced with `role` enum, profile fields, soft deletes
- **admin_sections**: tracks section assignments for admin users (user_id, section, assigned_by)
- **bookings**: customer package purchases with `booking_number`, status enum, traveler info, status logs
- **booking_travelers**: passenger details per booking
- **booking_status_logs**: audit trail for booking status changes
- **packages**: travel packages with JSON fields (features, inclusions, exclusions, itinerary, hotel_details)
- **articles**: blog content with categories, SEO fields, publishing workflow
- **testimonials**: customer reviews with approval workflow
- **contact_inquiries**: contact form submissions with status tracking
- **site_settings**: key-value config per section

### Migrations Status
All migrations completed and located in `database/migrations/`:
- ✅ `2026_01_17_055938_add_role_fields_to_users_table.php`
- ✅ `2026_01_17_055939_create_admin_sections_table.php`
- ✅ `2026_01_17_055940_create_packages_table.php`
- ✅ `2026_01_17_055941_create_package_gallery_table.php`
- ✅ `2026_01_17_055942_create_bookings_table.php`
- ✅ `2026_01_17_055943_create_booking_travelers_table.php`
- ✅ `2026_01_17_055944_create_booking_status_logs_table.php`
- ✅ `2026_01_17_055945_create_article_categories_table.php`
- ✅ `2026_01_17_055946_create_articles_table.php`
- ✅ `2026_01_17_055947_create_team_members_table.php`
- ✅ `2026_01_17_055948_create_testimonials_table.php`
- ✅ `2026_01_17_055949_create_contact_inquiries_table.php`
- ✅ `2026_01_17_055950_create_site_settings_table.php`
- ✅ `2026_01_17_055951_create_office_locations_table.php`
- ✅ `2026_01_17_055952_create_faqs_table.php`
- ✅ `2026_01_20_090841_rename_image_to_featured_image_in_articles_table.php`
- ✅ `2026_01_20_095008_add_missing_columns_to_packages_and_bookings_tables.php`

---

## 🛠️ Development Workflows & Commands

### Quick Access URLs
```
Public Website: http://localhost:8000
Admin Login: http://localhost:8000/admin/login
Admin Dashboard: http://localhost:8000/admin
```

### Local Development (Concurrent)
```bash
composer dev  # Runs: php artisan serve + queue:listen + npm run dev
```

### SSR Development
```bash
composer dev:ssr  # Runs: build:ssr + artisan serve + queue + pail + inertia:start-ssr
```

### Individual Services
```bash
php artisan serve          # Laravel dev server
npm run dev                # Vite HMR
php artisan queue:listen   # Queue worker
```

### Build & Deploy
```bash
npm run build              # Production build (public Vue frontend)
npm run build:ssr          # SSR build
```

### Testing
```bash
php artisan test           # Laravel test suite
./vendor/bin/pest          # Pest directly
```

### Code Quality
```bash
composer pint              # PHP formatting (Laravel Pint)
npm run format             # Prettier (frontend)
npm run lint               # ESLint (frontend)
```

### Setup (First Time)
```bash
composer run setup         # Install deps + generate key + migrate + npm install + build
```

### Seeding Data
```bash
php artisan db:seed --class=SuperAdminSeeder    # Create super admin only
php artisan db:seed --class=HajjSectionSeeder  # Full Hajj section data (users, packages, articles, etc.)
php artisan migrate:fresh --seed               # Reset DB + seed all data
```

### Storage Setup
```bash
php artisan storage:link   # Create symlink from public/storage to storage/app/public
```

---

## 📐 Coding Patterns & Conventions

### Backend Service Pattern (MANDATORY)
- **Controllers**: Thin, HTTP-only — delegate to services
- **Services** (`app/Services/*`): All business logic, orchestration
  - **Existing Services**: `AdminSectionService`, `AdminUserService`, `ArticleCategoryService`, `ArticleService`, `BookingService`, `ContactInquiryService`, `FaqService`, `MediaService`, `OfficeLocationService`, `PackageService`, `SettingService`, `SiteSettingService`, `TeamMemberService`, `TestimonialService`
- **Models**: Data structure, relationships, scopes only — NO business logic
- **Example Flow**:
  ```
  Request → FormRequest (validation) → Controller → Service → Model → Database
  ```
- **Service Method Naming**:
  - `list()` or `getAll()` for listings
  - `getById(int $id)` for single retrieval
  - `create(array $data)` for creation
  - `update(Model $model, array $data)` for updates
  - `delete(Model $model)` for deletion
  - `toggle*()` for boolean toggles (e.g., `toggleActive()`, `toggleFeatured()`)
  - `updateStatus()`, `approve()`, `reject()` for status workflows

### Model Conventions
- Use `protected function casts(): array` method (NOT `$casts` property) — see `app/Models/User.php`
- Define relationships explicitly
- Add scopes for common queries (e.g., `scopeActive`, `scopePublished`)
- Use Enums for status fields (see `app/Enums/*`)

### Frontend: Public (Vue + Inertia)
- Pages: `resources/js/pages/<section>/<Name>.vue`
- Routes: `routes/web.php` → `Inertia::render('<section>/<Name>')`
- Components: `resources/js/components/` with `ui/` subfolder for reusables
- **Preserve folder naming**: `hajj&umrah`, `tour&travel` (ampersands are intentional)

### Frontend: Admin Panel (Blade + Alpine.js) — ✅ FULLY IMPLEMENTED
- **Layout**: `resources/views/admin/layouts/app.blade.php` (responsive sidebar + header)
- **Components**: `resources/views/admin/components/` organized in:
  - `ui/` — 11 reusable UI components (button, input, select, card, modal, etc.)
  - `form/` — 4 advanced form components (image-upload, multi-image-upload, rich-editor, date-picker)
  - `data/` — 1 table component with sorting
  - `layout/` — 3 layout components (app, sidebar, header)
- **Pages**: `resources/views/admin/pages/<module>/` — All modules have index, create, edit views
- **Routes**: `routes/admin.php` with `admin.` prefix
- **Interactivity**: Alpine.js for dropdowns, modals, toggles, dynamic lists, image uploads
- **Styling**: Tailwind CSS v4
- **Access Control**: Role-aware sidebar using `auth()->user()->hasSection()` and `isSuperAdmin()`
- **Middleware**: `admin`, `section:<name>`, `super_admin` applied to route groups

### Frontend: User Dashboard (Blade + Alpine.js) — NOT YET IMPLEMENTED
- Layout: `resources/views/user/layouts/app.blade.php`
- Pages: `resources/views/user/pages/` (dashboard, bookings, profile)
- Routes: `routes/user.php` (planned) or within `web.php` with `user.` prefix

### Form Requests
- **All form requests are in `app/Http/Requests/Admin/`** (no Hajj subfolder)
- **Existing Requests**:
  - `AdminUserRequest.php` — Admin user create/edit validation
  - `ArticleRequest.php` — Article validation with image rules
  - `FaqRequest.php` — FAQ validation
  - `PackageRequest.php` — Package validation with complex JSON fields
  - `TeamMemberRequest.php` — Team member validation
  - `TestimonialRequest.php` — Testimonial validation
- Include custom error messages in `messages()` method
- Authorization logic in `authorize()` method (typically `return true` for admin-authenticated users)
- Use proper validation rules for JSON fields, images, enums

### Blade Components
- Create reusable components in `resources/views/admin/components/ui/`
- Props: use `@props(['name', 'label', 'value' => null, 'error' => null])`
- Styling: Tailwind CSS v4 utilities
- Examples to follow (from docs/srs-frontend.md):
  - `button.blade.php` (variants: primary, secondary, danger, ghost)
  - `input.blade.php`, `select.blade.php`, `textarea.blade.php`
  - `modal.blade.php` (Alpine.js powered)
  - `image-upload.blade.php` (drag-drop + preview)
  - `table.blade.php` (sortable, selectable)

### Enums (Laravel Backed Enums)
- Location: `app/Enums/`
- Use backed string or int enums
- Examples: `UserRole`, `PackageType`, `BookingStatus`, `InquiryStatus`, `PublishStatus`

---

## 🗂️ Key Files to Review Before Changes

### Always Check These First
1. **Requirements & Specs**:
   - `docs/SRS.md` — full requirements, DB schema, module specs
   - `docs/srs-backend.md` — backend architecture, services, models
   - `docs/srs-frontend.md` — frontend components, Alpine.js patterns, layouts
   - `docs/hajj-section-overview.md` — data models, admin modules overview
2. **Implementation Plans**:
   - `docs/steps/day-1.md` — migrations, models, services, admin layout, auth
   - `docs/steps/day-2.md` — package CRUD, booking management, article CRUD, Blade components
   - `docs/steps/day-3.md` — team, testimonials, inquiries, settings, user dashboard, admin management
3. **Implementation Reports**:
   - `docs/reports/2026-01-17-day2-day3-scrum-report.md` — Complete Day 2 & 3 implementation
   - `docs/reports/2026-01-17-frontend-integration-scrum-report.md` — Frontend integration details
   - `docs/reports/2026-01-14-scrum-report.md` — Initial setup report
4. **Existing Code**:
   - `README.md` — setup, commands, git workflow
   - `composer.json`, `package.json` — scripts, dependencies
   - `routes/web.php`, `routes/admin.php`, `routes/settings.php`
   - `app/Models/User.php` — model casting pattern
   - `app/Providers/FortifyServiceProvider.php` — auth views, actions
   - `vite.config.ts` — frontend build config
   - `tests/Pest.php` — test configuration

---

## 🧩 Common Implementation Recipes

### Add Admin Panel CRUD Module (Blade)
1. **Create migration**: `php artisan make:migration create_things_table`
2. **Create model**: `php artisan make:model Thing`
3. **Create service**: `app/Services/ThingService.php` with `list`, `getById`, `create`, `update`, `delete`
4. **Create controller**: `php artisan make:controller Admin/Hajj/ThingController` (delegate to service)
5. **Create form request**: `php artisan make:request Admin/ThingRequest`
6. **Create views**:
   - `resources/views/admin/pages/things/index.blade.php` (list)
   - `resources/views/admin/pages/things/create.blade.php` (form)
   - `resources/views/admin/pages/things/edit.blade.php` (form, pre-filled)
7. **Add routes**: `routes/admin.php` → `Route::resource('things', ThingController::class)`
8. **Update sidebar**: `resources/views/admin/components/layout/sidebar.blade.php` to include nav link

### Add Public Inertia Page
1. Create `resources/js/pages/<section>/<Name>.vue`
2. Add route in `routes/web.php`: `Route::get('/path', fn() => Inertia::render('<section>/<Name>'))`
3. Use existing components from `resources/js/components/ui/`

### Add New Service Class
```php
namespace App\Services;

use App\Models\Thing;
use Illuminate\Support\Collection;

class ThingService
{
    public function list(): Collection
    {
        return Thing::orderBy('created_at', 'desc')->get();
    }

    public function getById(int $id): Thing
    {
        return Thing::findOrFail($id);
    }

    public function create(array $data): Thing
    {
        return Thing::create($data);
    }

    public function update(Thing $thing, array $data): Thing
    {
        $thing->update($data);
        return $thing->fresh();
    }

    public function delete(Thing $thing): bool
    {
        return $thing->delete();
    }
}
```

### Add Enum
```php
namespace App\Enums;

enum ThingStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';
}
```
Then use in model casts: `'status' => ThingStatus::class`

---

## 🔐 Authentication & Authorization

### Current State
- **Fortify** configured for authentication
- **2FA** enabled via `TwoFactorAuthenticatable` trait on User model
- **Views**: Inertia components in `resources/js/pages/auth/` (Login, Register, ForgotPassword, etc.)
- **Custom Actions**: `app/Actions/Fortify/CreateNewUser.php`, `ResetUserPassword.php`

### Test Credentials (after seeding)
```bash
# Super Admin (full access)
Email: superadmin@dubai.test
Password: password
Access: All sections, admin management

# Hajj Section Admin
Email: hajjadmin@dubai.test
Password: password
Access: Hajj section only

# Regular User (customer)
Email: user@dubai.test
Password: password
Access: Public site, user dashboard (when implemented)
```

### Role-Based Access Control (RBAC)
- **Middleware** (already implemented):
  - `AdminMiddleware` — checks `auth()->user()->isAdminLevel()` (allows admin or super_admin)
  - `SuperAdminMiddleware` — checks `auth()->user()->isSuperAdmin()` (only super admin)
  - `SectionAccessMiddleware` — checks `auth()->user()->hasSection('hajj')` (section-specific access)
- **Helper Methods** (in User model):
  ```php
  public function isSuperAdmin(): bool { return $this->role === UserRole::SUPER_ADMIN; }
  public function isAdmin(): bool { return $this->role === UserRole::ADMIN; }
  public function isUser(): bool { return $this->role === UserRole::USER; }
  public function isAdminLevel(): bool { return $this->role?->isAdminLevel() ?? false; }
  public function hasSection(string $section): bool {
      // Super admins have access to all sections
      if ($this->isSuperAdmin()) return true;
      return $this->assignedSections()->where('section', $section)->exists();
  }
  ```
- **Usage in Routes**:
  ```php
  Route::middleware(['auth', 'admin'])->group(function () {
      // Admin-level routes (admin + super_admin)
  });
  
  Route::middleware(['auth', 'admin', 'section:hajj'])->group(function () {
      // Section-specific routes
  });
  
  Route::middleware(['auth', 'admin', 'super_admin'])->group(function () {
      // Super admin only routes
  });
  ```

---

## 🎨 UI/UX Standards (Admin Panel)

### Design Quality Benchmarks
- **Professional, big-tech aesthetic** — clean, modern, polished
- **Fully responsive** — 320px+ mobile-first
- **Smooth animations** — transitions on hover, modal open/close, page changes
- **WCAG 2.1 AA compliance** — accessible to all users
- **Consistent patterns** — reuse Blade components, maintain uniform spacing/colors

### Color Palette (Tailwind Config)
- **Primary (Gold)**: `#D3A762` (amber-600 equivalent) — CTAs, highlights
- **Sidebar BG**: `#1e293b` (slate-800) — navigation background
- **Page BG**: `#f8fafc` (slate-50) — content area
- **Success**: `#22c55e` (green-500) — active, published
- **Warning**: `#f59e0b` (amber-500) — pending
- **Error**: `#ef4444` (red-500) — errors, delete
- **Info**: `#3b82f6` (blue-500) — new items

### Responsive Breakpoints
| Breakpoint | Width | Sidebar | Layout |
|------------|-------|---------|--------|
| Mobile | < 640px | Hidden overlay | Single column |
| Tablet | 640-1023px | Hidden overlay | 2 columns |
| Desktop | ≥ 1024px | Fixed visible | Multi-column |

---

## 🚀 Git Workflow & Branching

### Branch Strategy
- **`main`**: Production-ready code
- **`abrar`**: Abrar's development branch
- **`rizwan`**: Rizwan's development branch

### Workflow
1. Create feature branch from `abrar` or `rizwan` (depending on assignee)
2. Implement changes, commit frequently with descriptive messages
3. Push to origin
4. Create PR targeting `main` when ready

### Commit Message Format (Conventional Commits)
```
<type>: <short summary>

<optional detailed description>

<optional footer with references>
```
**Types**: `feat`, `fix`, `docs`, `refactor`, `test`, `chore`, `style`

**Example**:
```
feat: implement package CRUD admin panel

- Created PackageController with full CRUD
- Implemented PackageService with business logic
- Added Blade views (index, create, edit) with Alpine.js
- Created PackageRequest for validation
- Updated admin sidebar with Packages link

Refs: docs/steps/day-2.md
```

### Auto-Push After Task Completion
- After completing all todos for a task, automatically commit and push
- Use proper conventional commit format
- Ensure all changes are staged and tested

---

## ⚠️ Critical Don'ts

❌ **NEVER rename `hajj&umrah` or `tour&travel` folders** — Inertia routes expect these exact names
❌ **NEVER put business logic in controllers** — use services
❌ **NEVER use `$casts` property** — use `protected function casts(): array` method
❌ **NEVER skip reading docs before implementation** — zero tolerance for spec mismatches
❌ **NEVER stop todos mid-way** — complete ALL todos before ending
❌ **NEVER implement admin panel features using Vue/Inertia** — admin uses Blade + Alpine.js
❌ **NEVER create hardcoded data in views** — fetch from database via services
❌ **NEVER skip validation** — always use FormRequest classes

---

## ✅ Critical Dos

✅ **ALWAYS run `composer dev` for full dev environment** (server + queue + Vite)
✅ **ALWAYS read all related `docs/*.md` files before implementing**
✅ **ALWAYS create comprehensive todo list before starting**
✅ **ALWAYS execute todos one-by-one, updating status**
✅ **ALWAYS cross-check implementation against SRS and technical docs**
✅ **ALWAYS update documentation when requirements change**
✅ **ALWAYS use Service Pattern** — controllers delegate to services
✅ **ALWAYS use `protected function casts(): array`** in models
✅ **ALWAYS validate via FormRequest classes**
✅ **ALWAYS use Enums for status fields**
✅ **ALWAYS follow Blade + Alpine.js for admin panel** (NOT Vue/Inertia)
✅ **ALWAYS generate scrum report after completing task**
✅ **ALWAYS auto-push with proper commit messages**

---

## 📊 Progress Tracking

### Current Implementation Status (as of January 21, 2026)

#### ✅ Public Website (Vue + Inertia) — FULLY IMPLEMENTED
- **Pages**: Welcome, Hajj home, Umrah packages, Articles, Team, Contact
- **Components**: Headers, footers, layouts, UI components
- **Routes**: Configured in `routes/web.php`
- **Status**: Production-ready

#### ✅ Admin Panel (Blade + Alpine.js) — FULLY IMPLEMENTED
- **Database**: All 16 tables migrated and functional
  - ✅ users (with roles, 2FA)
  - ✅ admin_sections (role-based access)
  - ✅ packages, package_gallery
  - ✅ bookings, booking_travelers, booking_status_logs
  - ✅ articles, article_categories
  - ✅ team_members, testimonials
  - ✅ contact_inquiries, faqs
  - ✅ site_settings, office_locations

- **Models & Services**: Complete service pattern implementation
  - ✅ 14 Models with relationships, scopes, enums
  - ✅ 14 Services with full CRUD operations
  - ✅ All business logic properly delegated

- **Controllers**: Admin panel fully functional
  - ✅ `Admin\DashboardController` — Dashboard with stats
  - ✅ `Admin\AdminUserController` — Admin management (super admin only)
  - ✅ `Admin\Auth\AdminLoginController` — Admin authentication
  - ✅ `Admin\Hajj\PackageController` — Full CRUD + toggles
  - ✅ `Admin\Hajj\BookingController` — Booking management
  - ✅ `Admin\Hajj\ArticleController` — Article CRUD + publishing
  - ✅ `Admin\Hajj\ArticleCategoryController` — Category management
  - ✅ `Admin\Hajj\TeamMemberController` — Team CRUD + reordering
  - ✅ `Admin\Hajj\TestimonialController` — Testimonial approval workflow
  - ✅ `Admin\Hajj\InquiryController` — Contact inquiry management
  - ✅ `Admin\Hajj\FaqController` — FAQ CRUD + reordering
  - ✅ `Admin\Hajj\SettingController` — Site settings management

- **Blade Components**: Comprehensive UI library (19 components)
  - ✅ **UI Components** (11): button, card, input, textarea, select, checkbox, toggle, badge, modal, alert, stats-card
  - ✅ **Form Components** (4): image-upload, multi-image-upload, rich-editor, date-picker
  - ✅ **Data Components** (1): table (with sorting)
  - ✅ **Layout Components** (3): app.blade.php, sidebar.blade.php, header.blade.php

- **Views**: All admin pages implemented
  - ✅ `admin/pages/dashboard.blade.php`
  - ✅ `admin/pages/packages/` (index, create, edit)
  - ✅ `admin/pages/bookings/` (index, show)
  - ✅ `admin/pages/articles/` (index, create, edit)
  - ✅ `admin/pages/article-categories/` (index, create, edit)
  - ✅ `admin/pages/team/` (index, create, edit)
  - ✅ `admin/pages/testimonials/` (index, create, edit)
  - ✅ `admin/pages/inquiries/` (index, show)
  - ✅ `admin/pages/faqs/` (index, create, edit)
  - ✅ `admin/pages/settings/` (index with tabs)
  - ✅ `admin/pages/admins/` (index, create, edit)
  - ✅ `admin/auth/` (login)

- **Routes**: Complete admin routing (`routes/admin.php`)
  - ✅ Admin authentication routes
  - ✅ Hajj section routes with `section:hajj` middleware
  - ✅ Super admin routes with `super_admin` middleware
  - ✅ Tour & Travel section placeholder (Phase 2)
  - ✅ Typing Services section placeholder (Phase 3)

- **Middleware & RBAC**: Fully functional
  - ✅ `AdminMiddleware` — Admin-level access control
  - ✅ `SectionAccessMiddleware` — Section-based access
  - ✅ `SuperAdminMiddleware` — Super admin gate
  - ✅ User helper methods: `isSuperAdmin()`, `isAdmin()`, `isUser()`, `hasSection()`, `isAdminLevel()`

- **Form Requests**: Validation layer complete
  - ✅ `AdminUserRequest` — Admin user validation
  - ✅ `PackageRequest` — Package validation
  - ✅ `ArticleRequest` — Article validation
  - ✅ `TeamMemberRequest` — Team member validation
  - ✅ `TestimonialRequest` — Testimonial validation
  - ✅ `FaqRequest` — FAQ validation

- **Seeders**: Comprehensive test data
  - ✅ `HajjSectionSeeder` — 3 users, 6 packages, 5 categories, 5 articles, 5 team members, 5 testimonials, 6 FAQs, 3 offices, 13 settings
  - ✅ `SuperAdminSeeder` — Default super admin
  - ✅ Test credentials: `superadmin@dubai.test` / `hajjadmin@dubai.test` / `user@dubai.test` (all: `password`)

#### ⏳ User Dashboard (Blade + Alpine.js) — NOT YET IMPLEMENTED
- **Status**: Specs defined in `docs/SRS.md` (USER module)
- **Routes**: Planned in `routes/user.php` or `routes/web.php` with `user.` prefix
- **Features**: Dashboard, booking tracking, profile management
- **Implementation**: Defined in `docs/steps/day-3.md` Phase 6 (not yet executed)

### What's Working Right Now
1. ✅ **Admin Login**: `/admin/login` — Full authentication with role checks
2. ✅ **Admin Dashboard**: `/admin` — Stats cards, recent activities
3. ✅ **Package Management**: `/admin/hajj/packages` — Create, edit, toggle status/featured
4. ✅ **Booking Management**: `/admin/hajj/bookings` — View, status updates, payment tracking
5. ✅ **Article System**: `/admin/hajj/articles` — Full blog management with categories
6. ✅ **Team Management**: `/admin/hajj/team` — CRUD with drag-drop reordering
7. ✅ **Testimonials**: `/admin/hajj/testimonials` — Approval workflow
8. ✅ **Contact Inquiries**: `/admin/hajj/inquiries` — View, mark read, respond
9. ✅ **FAQs**: `/admin/hajj/faqs` — CRUD with reordering
10. ✅ **Settings**: `/admin/hajj/settings` — Company, SEO, social, booking configs
11. ✅ **Admin Management**: `/admin/admins` — Super admin can manage admin users

### Next Phase (Future Enhancements)
1. **User Dashboard** — Customer booking tracking, profile management
2. **Tour & Travel Section** — Phase 2 implementation (admin panel for tour packages)
3. **Typing Services Section** — Phase 3 implementation (admin panel for typing services)
4. **Email Notifications** — Queue-based email sending for bookings, inquiries
5. **Payment Gateway Integration** — Stripe/PayPal integration for online booking
6. **Advanced Analytics** — Revenue reports, booking trends, customer insights

---

## 📝 Questions & Clarifications

If anything in this guide is unclear or you need specific examples:
1. Specify which section needs expansion (architecture, patterns, recipes, etc.)
2. Request code snippets for specific scenarios
3. Ask for clarification on workflow steps

**This file is a living document** — update it whenever project structure, patterns, or requirements evolve.
