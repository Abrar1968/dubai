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
   - All relevant `docs/*.md` files (SRS.md, srs-backend.md, srs-frontend.md, hajj-section-overview.md, typing-section-implementation-plan.md)
   - Related report files (`docs/reports/`)
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
  - `docs/typing-section-implementation-plan.md` for typing service specs
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
  1. **Public Website**: Vue 3 + Inertia.js + TypeScript (SPA-like pages) — fully implemented
  2. **Admin Panel**: Laravel Blade + Alpine.js + Tailwind CSS v4 (server-rendered) — fully implemented
  3. **User Dashboard**: Vue 3 + Inertia.js (for customers to track bookings) — ✅ IMPLEMENTED
- **Database**: MySQL with comprehensive schema (19 tables including users, bookings, packages, articles, typing services, family visas, etc.)
- **Authentication**: Laravel Fortify with 2FA support
- **Architecture**: Service Pattern — all business logic in service classes (`app/Services/*`)

### Critical Structural Knowledge
- **Two Active Sections** (Tour & Travel removed): 
  - **Hajj & Umrah** (Phase 1) — ✅ FULLY IMPLEMENTED
  - **Typing Services** (Phase 2) — ✅ FULLY IMPLEMENTED (Jan 2026)
- **Three User Roles**:
  - `super_admin`: full system access, manages admins, assigns sections
  - `admin`: section-specific access (can be assigned hajj, typing, tour), no admin management
  - `user`: customer role, can book packages, track orders via user dashboard
- **Role-Based Sidebar**: Admin panel sidebar dynamically shows only assigned sections based on `admin_sections` table
- **Public Frontend (Vue)**: Uses folder names with special chars like `hajj&umrah/`, `typing/` — **NEVER rename these**
- **Admin Panel (Blade)**: Located at `/admin/*` routes, uses `routes/admin.php`, views in `resources/views/admin/`
- **User Dashboard (Blade)**: Located at `/user/*` routes (planned), views in `resources/views/user/`
- **Image Storage**: Uses Laravel's storage system with `public` disk, symlinked to `public/storage`
- **Test Data Available**: Run seeders for sample data (users, packages, articles, typing services, etc.)

### Database Schema Highlights (see docs/SRS.md Section 7.2)
- **users**: enhanced with `role` enum, profile fields, soft deletes
- **admin_sections**: tracks section assignments for admin users (user_id, section, assigned_by)
- **bookings**: customer package purchases with `booking_number`, status enum, traveler info, status logs
- **booking_travelers**: passenger details per booking
- **booking_status_logs**: audit trail for booking status changes
- **packages**: travel packages with JSON fields (features, inclusions, exclusions, itinerary, hotel_details)
- **articles**: blog content with categories, SEO fields, publishing workflow
- **testimonials**: customer reviews with approval workflow
- **contact_inquiries**: contact form submissions with status tracking per section
- **site_settings**: key-value config per section (hajj, typing, tour)
- **typing_services**: dynamic services management (12 core services + family visa processing)
- **family_visa_emirates**: UAE emirates for visa processing
- **family_visa_types**: visa types per emirate

### Migrations Status
All migrations completed and located in `database/migrations/`:
- ✅ Core tables (2026-01-17 series) — users, packages, bookings, articles, site_settings, etc.
- ✅ Typing section tables (2026-01-28 series) — typing_services, family_visa_emirates, family_visa_types
- ✅ Schema updates (2026-01-20, 2026-01-21) — image fields, meta keywords, section support

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
  - **Existing Services**: `AdminSectionService`, `AdminUserService`, `ArticleCategoryService`, `ArticleService`, `BookingService`, `ContactInquiryService`, `FamilyVisaService`, `FaqService`, `MediaService`, `OfficeLocationService`, `PackageService`, `SettingService`, `SiteSettingService`, `TeamMemberService`, `TestimonialService`, `TypingServiceService`
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
- **Preserve folder naming**: `hajj&umrah` (ampersand is intentional)

### Typing Services Frontend Pattern (IMPORTANT)
All typing services use **ONE generic Vue component**: `typing/services/ServiceDetail.vue`
- Content is fetched from database via `TypingServiceService`
- **Exception**: `family-visa-process` uses `FamilyVisaProcess.vue` (special emirate selection UI)
- **Do NOT create individual Vue files** per service — use the dynamic `ServiceDetail.vue`

**Service Methods for Public Display**:
```php
// For public homepage - INCLUDES Family Visa in list
$typingServiceService->getActiveWithFamilyVisa();
$typingServiceService->getFeaturedWithFamilyVisa(8);

// For admin panel - EXCLUDES Family Visa (managed separately)
$typingServiceService->getActive();
$typingServiceService->getFeatured(4);
```

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

### Frontend: User Dashboard (Vue + Inertia) — ✅ IMPLEMENTED
- Pages: `resources/js/pages/user/` (Dashboard.vue, Bookings.vue, BookingShow.vue, Profile.vue)
- Routes: `routes/web.php` with `user.` prefix at `/user/*`
- Features: Booking stats, booking list with status, booking details, profile management
- Middleware: `['auth', 'verified', 'user']` — only USER role can access

### Form Requests
- **All form requests are in `app/Http/Requests/Admin/`** (no Hajj subfolder)
- **Existing Requests**:
  - `AdminUserRequest.php` — Admin user create/edit validation
  - `ArticleRequest.php` — Article validation with image rules
  - `FaqRequest.php` — FAQ validation
  - `PackageRequest.php` — Package validation with complex JSON fields
  - `TeamMemberRequest.php` — Team member validation
  - `TestimonialRequest.php` — Testimonial validation
  - `TypingServiceRequest.php` — Typing service validation
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

### Current Implementation Status (as of January 31, 2026)

#### ✅ Public Website (Vue + Inertia) — FULLY IMPLEMENTED
- **Pages**: Welcome, Hajj home, Umrah packages, Articles, Team, Contact, Typing services home
- **Components**: Headers, footers, layouts, UI components
- **Routes**: Configured in `routes/web.php`
- **Status**: Production-ready

#### ✅ Admin Panel (Blade + Alpine.js) — FULLY IMPLEMENTED
- **Database**: All 19+ tables migrated and functional
  - ✅ users (with roles, 2FA)
  - ✅ admin_sections (role-based access)
  - ✅ packages, package_gallery
  - ✅ bookings, booking_travelers, booking_status_logs
  - ✅ articles, article_categories
  - ✅ team_members, testimonials
  - ✅ contact_inquiries, faqs
  - ✅ site_settings, office_locations
  - ✅ typing_services, family_visa_emirates, family_visa_types

- **Models & Services**: Complete service pattern implementation
  - ✅ 16+ Models with relationships, scopes, enums
  - ✅ 16+ Services with full CRUD operations
  - ✅ All business logic properly delegated

- **Controllers**: Admin panel fully functional
  - ✅ `Admin\DashboardController` — Dashboard with stats
  - ✅ `Admin\AdminUserController` — Admin management (super admin only)
  - ✅ `Admin\Auth\AdminLoginController` — Admin authentication
  - ✅ `Admin\Hajj\*` — All Hajj controllers (Package, Booking, Article, etc.)
  - ✅ `Admin\Typing\*` — All Typing controllers (Service, FamilyVisa, Inquiry, etc.)

- **Blade Components**: Comprehensive UI library (19 components)
  - ✅ **UI Components** (11): button, card, input, textarea, select, checkbox, toggle, badge, modal, alert, stats-card
  - ✅ **Form Components** (4): image-upload, multi-image-upload, rich-editor, date-picker
  - ✅ **Data Components** (1): table (with sorting)
  - ✅ **Layout Components** (3): app.blade.php, sidebar.blade.php, header.blade.php

- **Views**: All admin pages implemented for Hajj and Typing sections
  - ✅ Dashboard, Packages, Bookings, Articles, Team, Testimonials, FAQs, Settings
  - ✅ Typing Services CRUD, Family Visa management, Typing Inquiries
  - ✅ Admin user management (super admin only)

- **Routes**: Complete admin routing (`routes/admin.php`)
  - ✅ Admin authentication routes
  - ✅ Hajj section routes with `section:hajj` middleware
  - ✅ Typing section routes with `section:typing` middleware
  - ✅ Super admin routes with `super_admin` middleware

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
  - ✅ `TypingServiceRequest` — Typing service validation

- **Seeders**: Comprehensive test data
  - ✅ `HajjSectionSeeder` — Hajj section complete data set
  - ✅ `SuperAdminSeeder` — Default super admin
  - ✅ Test credentials: `superadmin@dubai.test` / `hajjadmin@dubai.test` / `user@dubai.test` (all: `password`)

#### ✅ User Dashboard (Vue + Inertia) — IMPLEMENTED
- **Status**: Fully functional at `/user/*` routes
- **Routes**: Configured in `routes/web.php` with `user.` prefix and `['auth', 'verified', 'user']` middleware
- **Features**: Dashboard with booking stats, booking list, booking details, profile management
- **Pages**: `resources/js/pages/user/` (Dashboard.vue, Bookings.vue, BookingShow.vue, Profile.vue)

### What's Working Right Now
1. ✅ **Admin Login**: `/admin/login` — Full authentication with role checks
2. ✅ **Admin Dashboard**: `/admin` — Stats cards, recent activities
3. ✅ **Hajj Section**: All modules fully functional
   - Package Management: `/admin/hajj/packages` — Create, edit, toggle status/featured
   - Booking Management: `/admin/hajj/bookings` — View, status updates, payment tracking
   - Article System: `/admin/hajj/articles` — Full blog management with categories
   - Team Management: `/admin/hajj/team` — CRUD with drag-drop reordering
   - Testimonials: `/admin/hajj/testimonials` — Approval workflow
   - Contact Inquiries: `/admin/hajj/inquiries` — View, mark read, respond
   - FAQs: `/admin/hajj/faqs` — CRUD with reordering
   - Settings: `/admin/hajj/settings` — Company, SEO, social, booking configs
4. ✅ **Typing Section**: All modules fully functional
   - Services Management: `/admin/typing/services` — CRUD for 11 core typing services
   - Family Visa Processing: `/admin/typing/family-visa` — Separate management for UAE emirates & visa types
   - Inquiries: `/admin/typing/inquiries` — Contact inquiry management
   - Settings: `/admin/typing/settings` — Section-specific configurations
5. ✅ **Global Admin Features**:
   - Admin Management: `/admin/admins` — Super admin can manage admin users
   - Office Locations: `/admin/office-locations` — Multi-section office management
   - User Management: `/admin/users` — Customer account management

### Next Phase (Future Enhancements)
1. **Email Notifications** — Queue-based email sending for bookings, inquiries
2. **Payment Gateway Integration** — Stripe/PayPal integration for online booking
3. **Advanced Analytics** — Revenue reports, booking trends, customer insights

---

## 📝 Questions & Clarifications

If anything in this guide is unclear or you need specific examples:
1. Specify which section needs expansion (architecture, patterns, recipes, etc.)
2. Request code snippets for specific scenarios
3. Ask for clarification on workflow steps

**This file is a living document** — update it whenever project structure, patterns, or requirements evolve.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.2.12
- inertiajs/inertia-laravel (INERTIA) - v2
- laravel/fortify (FORTIFY) - v1
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- laravel/wayfinder (WAYFINDER) - v0
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v3
- phpunit/phpunit (PHPUNIT) - v11
- @inertiajs/vue3 (INERTIA) - v2
- alpinejs (ALPINEJS) - v3
- tailwindcss (TAILWINDCSS) - v4
- vue (VUE) - v3
- @laravel/vite-plugin-wayfinder (WAYFINDER) - v0
- eslint (ESLINT) - v9
- prettier (PRETTIER) - v3

## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs
- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches when dealing with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The `search-docs` tool is perfect for all Laravel-related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless there is something very complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

=== tests rules ===

## Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== inertia-laravel/core rules ===

## Inertia

- Inertia.js components should be placed in the `resources/js/Pages` directory unless specified differently in the JS bundler (`vite.config.js`).
- Use `Inertia::render()` for server-side routing instead of traditional Blade views.
- Use the `search-docs` tool for accurate guidance on all things Inertia.

<code-snippet name="Inertia Render Example" lang="php">
// routes/web.php example
Route::get('/users', function () {
    return Inertia::render('Users/Index', [
        'users' => User::all()
    ]);
});
</code-snippet>

=== inertia-laravel/v2 rules ===

## Inertia v2

- Make use of all Inertia features from v1 and v2. Check the documentation before making any changes to ensure we are taking the correct approach.

### Inertia v2 New Features
- Deferred props.
- Infinite scrolling using merging props and `WhenVisible`.
- Lazy loading data on scroll.
- Polling.
- Prefetching.

### Deferred Props & Empty States
- When using deferred props on the frontend, you should add a nice empty state with pulsing/animated skeleton.

### Inertia Form General Guidance
- The recommended way to build forms when using Inertia is with the `<Form>` component - a useful example is below. Use the `search-docs` tool with a query of `form component` for guidance.
- Forms can also be built using the `useForm` helper for more programmatic control, or to follow existing conventions. Use the `search-docs` tool with a query of `useForm helper` for guidance.
- `resetOnError`, `resetOnSuccess`, and `setDefaultsOnSuccess` are available on the `<Form>` component. Use the `search-docs` tool with a query of `form component resetting` for guidance.

=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation
- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues
- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization
- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error
- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version-specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure
- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== wayfinder/core rules ===

## Laravel Wayfinder

Wayfinder generates TypeScript functions and types for Laravel controllers and routes which you can import into your client-side code. It provides type safety and automatic synchronization between backend routes and frontend code.

### Development Guidelines
- Always use the `search-docs` tool to check Wayfinder correct usage before implementing any features.
- Always prefer named imports for tree-shaking (e.g., `import { show } from '@/actions/...'`).
- Avoid default controller imports (prevents tree-shaking).
- Run `php artisan wayfinder:generate` after route changes if Vite plugin isn't installed.

### Feature Overview
- Form Support: Use `.form()` with `--with-form` flag for HTML form attributes — `<form {...store.form()}>` → `action="/posts" method="post"`.
- HTTP Methods: Call `.get()`, `.post()`, `.patch()`, `.put()`, `.delete()` for specific methods — `show.head(1)` → `{ url: "/posts/1", method: "head" }`.
- Invokable Controllers: Import and invoke directly as functions. For example, `import StorePost from '@/actions/.../StorePostController'; StorePost()`.
- Named Routes: Import from `@/routes/` for non-controller routes. For example, `import { show } from '@/routes/post'; show(1)` for route name `post.show`.
- Parameter Binding: Detects route keys (e.g., `{post:slug}`) and accepts matching object properties — `show("my-post")` or `show({ slug: "my-post" })`.
- Query Merging: Use `mergeQuery` to merge with `window.location.search`, set values to `null` to remove — `show(1, { mergeQuery: { page: 2, sort: null } })`.
- Query Parameters: Pass `{ query: {...} }` in options to append params — `show(1, { query: { page: 1 } })` → `"/posts/1?page=1"`.
- Route Objects: Functions return `{ url, method }` shaped objects — `show(1)` → `{ url: "/posts/1", method: "get" }`.
- URL Extraction: Use `.url()` to get URL string — `show.url(1)` → `"/posts/1"`.

### Example Usage

<code-snippet name="Wayfinder Basic Usage" lang="typescript">
    // Import controller methods (tree-shakable)...
    import { show, store, update } from '@/actions/App/Http/Controllers/PostController'

    // Get route object with URL and method...
    show(1) // { url: "/posts/1", method: "get" }

    // Get just the URL...
    show.url(1) // "/posts/1"

    // Use specific HTTP methods...
    show.get(1) // { url: "/posts/1", method: "get" }
    show.head(1) // { url: "/posts/1", method: "head" }

    // Import named routes...
    import { show as postShow } from '@/routes/post' // For route name 'post.show'
    postShow(1) // { url: "/posts/1", method: "get" }
</code-snippet>

### Wayfinder + Inertia
If your application uses the `<Form>` component from Inertia, you can use Wayfinder to generate form action and method automatically.
<code-snippet name="Wayfinder Form Component (Vue)" lang="vue">

<Form v-bind="store.form()"><input name="title" /></Form>

</code-snippet>

=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.

=== pest/core rules ===

## Pest
### Testing
- If you need to verify a feature is working, write or update a Unit / Feature test.

### Pest Tests
- All tests must be written using Pest. Use `php artisan make:test --pest {name}`.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files - these are core to the application.
- Tests should test all of the happy paths, failure paths, and weird paths.
- Tests live in the `tests/Feature` and `tests/Unit` directories.
- Pest tests look and behave like this:
<code-snippet name="Basic Pest Test Example" lang="php">
it('is true', function () {
    expect(true)->toBeTrue();
});
</code-snippet>

### Running Tests
- Run the minimal number of tests using an appropriate filter before finalizing code edits.
- To run all tests: `php artisan test --compact`.
- To run all tests in a file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --compact --filter=testName` (recommended after making a change to a related file).
- When the tests relating to your changes are passing, ask the user if they would like to run the entire test suite to ensure everything is still passing.

### Pest Assertions
- When asserting status codes on a response, use the specific method like `assertForbidden` and `assertNotFound` instead of using `assertStatus(403)` or similar, e.g.:
<code-snippet name="Pest Example Asserting postJson Response" lang="php">
it('returns all', function () {
    $response = $this->postJson('/api/docs', []);

    $response->assertSuccessful();
});
</code-snippet>

### Mocking
- Mocking can be very helpful when appropriate.
- When mocking, you can use the `Pest\Laravel\mock` Pest function, but always import it via `use function Pest\Laravel\mock;` before using it. Alternatively, you can use `$this->mock()` if existing tests do.
- You can also create partial mocks using the same import or self method.

### Datasets
- Use datasets in Pest to simplify tests that have a lot of duplicated data. This is often the case when testing validation rules, so consider this solution when writing tests for validation rules.

<code-snippet name="Pest Dataset Example" lang="php">
it('has emails', function (string $email) {
    expect($email)->not->toBeEmpty();
})->with([
    'james' => 'james@laravel.com',
    'taylor' => 'taylor@laravel.com',
]);
</code-snippet>

=== inertia-vue/core rules ===

## Inertia + Vue

- Vue components must have a single root element.
- Use `router.visit()` or `<Link>` for navigation instead of traditional links.

<code-snippet name="Inertia Client Navigation" lang="vue">

    import { Link } from '@inertiajs/vue3'
    <Link href="/">Home</Link>

</code-snippet>

=== inertia-vue/v2/forms rules ===

## Inertia v2 + Vue Forms

<code-snippet name="`<Form>` Component Example" lang="vue">

<Form
    action="/users"
    method="post"
    #default="{
        errors,
        hasErrors,
        processing,
        progress,
        wasSuccessful,
        recentlySuccessful,
        setError,
        clearErrors,
        resetAndClearErrors,
        defaults,
        isDirty,
        reset,
        submit,
  }"
>
    <input type="text" name="name" />

    <div v-if="errors.name">
        {{ errors.name }}
    </div>

    <button type="submit" :disabled="processing">
        {{ processing ? 'Creating...' : 'Create User' }}
    </button>

    <div v-if="wasSuccessful">User created successfully!</div>
</Form>

</code-snippet>

=== tailwindcss/core rules ===

## Tailwind CSS

- Use Tailwind CSS classes to style HTML; check and use existing Tailwind conventions within the project before writing your own.
- Offer to extract repeated patterns into components that match the project's conventions (i.e. Blade, JSX, Vue, etc.).
- Think through class placement, order, priority, and defaults. Remove redundant classes, add classes to parent or child carefully to limit repetition, and group elements logically.
- You can use the `search-docs` tool to get exact examples from the official documentation when needed.

### Spacing
- When listing items, use gap utilities for spacing; don't use margins.

<code-snippet name="Valid Flex Gap Spacing Example" lang="html">
    <div class="flex gap-8">
        <div>Superior</div>
        <div>Michigan</div>
        <div>Erie</div>
    </div>
</code-snippet>

### Dark Mode
- If existing pages and components support dark mode, new pages and components must support dark mode in a similar way, typically using `dark:`.

=== tailwindcss/v4 rules ===

## Tailwind CSS 4

- Always use Tailwind CSS v4; do not use the deprecated utilities.
- `corePlugins` is not supported in Tailwind v4.
- In Tailwind v4, configuration is CSS-first using the `@theme` directive — no separate `tailwind.config.js` file is needed.

<code-snippet name="Extending Theme in CSS" lang="css">
@theme {
  --color-brand: oklch(0.72 0.11 178);
}
</code-snippet>

- In Tailwind v4, you import Tailwind using a regular CSS `@import` statement, not using the `@tailwind` directives used in v3:

<code-snippet name="Tailwind v4 Import Tailwind Diff" lang="diff">
   - @tailwind base;
   - @tailwind components;
   - @tailwind utilities;
   + @import "tailwindcss";
</code-snippet>

### Replaced Utilities
- Tailwind v4 removed deprecated utilities. Do not use the deprecated option; use the replacement.
- Opacity values are still numeric.

| Deprecated |	Replacement |
|------------+--------------|
| bg-opacity-* | bg-black/* |
| text-opacity-* | text-black/* |
| border-opacity-* | border-black/* |
| divide-opacity-* | divide-black/* |
| ring-opacity-* | ring-black/* |
| placeholder-opacity-* | placeholder-black/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |
</laravel-boost-guidelines>
