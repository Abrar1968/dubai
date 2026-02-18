# Typing Section Implementation Plan

**Document Version:** 1.0  
**Date:** January 28, 2026  
**Module:** Typing Services Admin Panel  
**Status:** Planning

---

## Table of Contents

1. [Overview](#1-overview)
2. [Current State Analysis](#2-current-state-analysis)
3. [Site Settings Requirements](#3-site-settings-requirements)
4. [Services Module Requirements](#4-services-module-requirements)
5. [Database Schema Changes](#5-database-schema-changes)
6. [Implementation Architecture](#6-implementation-architecture)
7. [Files to Create](#7-files-to-create)
8. [Frontend Data Flow](#8-frontend-data-flow)

---

## 1. Overview

### 1.1 Purpose

This document outlines the complete implementation plan for the Typing Services section in the admin panel. The goal is to:

1. **Site Settings**: Allow admin to manage typing-specific settings (company info, SEO, social links) that display on the typing frontend pages
2. **Services Management**: Create a new database table and admin interface to manage typing services dynamically (instead of hardcoded Vue content)

### 1.2 Reference Architecture

The Hajj section serves as the reference implementation. Key patterns to follow:
- Controller delegates to Service class
- Service handles business logic
- Settings stored in `site_settings` table with `section='typing'`
- Frontend receives data via Inertia props from controller

---

## 2. Current State Analysis

### 2.1 Admin Panel - Typing Section

**Current State**: Placeholder "Coming Soon" page

```php
// routes/admin.php - Line 144-149
Route::prefix('typing')->name('typing.')->middleware('section:typing')->group(function () {
    Route::get('/', function () {
        return view('admin.pages.coming-soon', ['section' => 'Typing Services']);
    })->name('index');
});
```

### 2.2 Frontend - Typing Vue Files

| File | Content Type | Data Source |
|------|--------------|-------------|
| `typinghome.vue` | Hero image, 12 services grid, welcome text, mission/vision/values | **Hardcoded** |
| `typingheader.vue` | Logo, email, phone, social links | Props (expects `settings`, `auth`) - **Empty** |
| `typingfooter.vue` | Logo, address, email, phone, social links | Props (expects `settings`) - **Empty** |
| 12 service pages | Title, description, sub-services | **Hardcoded** |
| 4 family sub-pages | Title, description, action items | **Hardcoded** |

### 2.3 Database Support

The database already supports typing section:
- `site_settings.section` enum includes `'typing'`
- `faqs.section` enum includes `'typing'`
- `contact_inquiries.section` enum includes `'typing'`
- `office_locations.section` enum includes `'typing'`

---

## 3. Site Settings Requirements

### 3.1 Settings Categories (Same as Hajj)

#### Company Settings
| Setting Key | Type | Frontend Usage |
|-------------|------|----------------|
| `company_name` | string | Header logo text, footer brand |
| `company_tagline` | string | Footer tagline |
| `company_email` | string | Header top bar, footer contact |
| `company_phone` | string | Header top bar, footer contact |
| `company_whatsapp` | string | WhatsApp floating button |
| `company_address` | text | Footer office section |
| `company_logo` | string (path) | Header & footer logo |
| `company_description` | text | About section |
| `banner_image` | string (path) | Not used in typing |
| `hero_image` | string (path) | Homepage hero background |

#### SEO Settings
| Setting Key | Type | Frontend Usage |
|-------------|------|----------------|
| `meta_title` | string | `<title>` tag |
| `meta_description` | string | Meta description |
| `meta_keywords` | string | Meta keywords |
| `og_image` | string (path) | Social share image |
| `google_analytics` | string | GA tracking ID |

#### Social Media Settings
| Setting Key | Type | Frontend Usage |
|-------------|------|----------------|
| `social_facebook` | string (url) | Header/footer social icons |
| `social_twitter` | string (url) | Header/footer social icons |
| `social_instagram` | string (url) | Header/footer social icons |
| `social_linkedin` | string (url) | Header social icons |
| `social_youtube` | string (url) | Footer social icons |
| `contact_description` | text | Contact page intro |

#### Policies (Optional for Typing)
| Setting Key | Type | Frontend Usage |
|-------------|------|----------------|
| `terms_conditions` | text | Terms page |
| `privacy_policy` | text | Privacy page |

### 3.2 Settings Admin Flow

```
Admin Panel → /admin/typing/settings
    ↓
TypingSettingController::index() → SettingService::getGrouped('typing')
    ↓
Blade View: admin/pages/typing/settings/index.blade.php
    ↓
Form Submit → TypingSettingController::updateCompany/SEO/Social()
    ↓
SettingService::setMany($data, 'typing')
    ↓
site_settings table (section='typing')
```

---

## 4. Services Module Requirements

### 4.1 Services Identified from Vue Files

Based on `typinghome.vue` and service page analysis:

| # | Service Name | Slug | Vue Page | Sub-Services Count |
|---|--------------|------|----------|-------------------|
| 1 | Immigration | immigration | Immigration.vue | 5 |
| 2 | Labour Ministry | labour-ministry | LabourMinistry.vue | ~3-4 |
| 3 | Tasheel Services | tasheel-services | TasheelServices.vue | ~3-4 |
| 4 | Domestic Workers Visa | domestic-workers-visa | DomesticWorkersVisa.vue | ~3-4 |
| 5 | Family Visa | family-visa-process | FamilyVisaProcess.vue | 4 (+ emirate variations) |
| 6 | Health Insurance | health-insurance | HealthInsurance.vue | ~3-4 |
| 7 | Ministry of Interior | ministry-of-interior | MinistryOfInterior.vue | ~3-4 |
| 8 | Certificate Attestation | certificate-attestation | CertificateAttestation.vue | 3 |
| 9 | VAT Registration | vat-registration | VATRegistration.vue | 3 |
| 10 | CT Registration | ct-registration | CTRegistration.vue | ~3-4 |
| 11 | Passport Renewal | passport-renewal | PassportRenewal.vue | ~3-4 |
| 12 | Immigration Card | immigration-card | ImmigrationCard.vue | ~3-4 |

### 4.2 Service Data Structure

Each service needs:
- **Title**: Display name (e.g., "Immigration")
- **Slug**: URL-friendly identifier (e.g., "immigration")
- **Short Description**: Grid card description (~100 chars)
- **Long Description**: Full page intro paragraph
- **Icon/Image**: Optional icon or image
- **Sub-Services**: JSON array of items with title + description
- **CTA Button**: Link/text for apply button
- **Sort Order**: Display position
- **Is Active**: Toggle visibility

### 4.3 Service Page Content Pattern

From Immigration.vue analysis:
```
Service Page Structure:
├── Title: "★ Immigration (Services)"
├── Intro: "We help individuals and companies..."
├── Sub-Services:
│   ├── Establishment card (title + description)
│   ├── NEW VISA APPLY (title + description)
│   ├── Medical (fitness test) (title + description)
│   ├── Emirates ID (title + description)
│   └── RESIDENCE (title + description)
└── CTA: Apply button → /contactus
```

---

## 5. Database Schema Changes

### 5.1 New Table: `typing_services`

```sql
CREATE TABLE typing_services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    short_description TEXT NULL,
    long_description TEXT NULL,
    icon VARCHAR(255) NULL,          -- Icon class or image path
    image VARCHAR(255) NULL,          -- Featured image path
    sub_services JSON NULL,           -- Array of {title, description}
    cta_text VARCHAR(100) DEFAULT 'Apply',
    cta_link VARCHAR(255) DEFAULT '/contactus',
    sort_order INT UNSIGNED DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_active_order (is_active, sort_order),
    INDEX idx_slug (slug)
);
```

### 5.2 Sub-Services JSON Structure

```json
{
  "sub_services": [
    {
      "title": "Establishment card",
      "description": "Register your company as a legal sponsor. Requirements typically include..."
    },
    {
      "title": "NEW VISA APPLY",
      "description": "We handle new visa applications (employment, family, visit, residency)..."
    }
  ]
}
```

### 5.3 Migration File

Create: `database/migrations/2026_01_28_000001_create_typing_services_table.php`

---

## 6. Implementation Architecture

### 6.1 Backend Components

| Component | Path | Purpose |
|-----------|------|---------|
| **Migration** | `database/migrations/2026_01_28_000001_create_typing_services_table.php` | Create table |
| **Model** | `app/Models/TypingService.php` | Eloquent model |
| **Service** | `app/Services/TypingServiceService.php` | Business logic |
| **Admin Controller** | `app/Http/Controllers/Admin/Typing/SettingController.php` | Settings management |
| **Admin Controller** | `app/Http/Controllers/Admin/Typing/ServiceController.php` | Services CRUD |
| **Public Controller** | `app/Http/Controllers/Public/TypingController.php` | Frontend data |
| **Form Request** | `app/Http/Requests/Admin/TypingServiceRequest.php` | Validation |

### 6.2 Blade Views

| View | Purpose |
|------|---------|
| `admin/pages/typing/settings/index.blade.php` | Settings management tabs |
| `admin/pages/typing/services/index.blade.php` | Services listing table |
| `admin/pages/typing/services/create.blade.php` | Create service form |
| `admin/pages/typing/services/edit.blade.php` | Edit service form |

### 6.3 Routes

```php
// routes/admin.php - Typing Section
Route::prefix('typing')->name('typing.')->middleware('section:typing')->group(function () {
    // Settings
    Route::get('settings', [TypingSettingController::class, 'index'])->name('settings.index');
    Route::put('settings/company', [TypingSettingController::class, 'updateCompany'])->name('settings.update-company');
    Route::put('settings/seo', [TypingSettingController::class, 'updateSeo'])->name('settings.update-seo');
    Route::put('settings/social', [TypingSettingController::class, 'updateSocial'])->name('settings.update-social');
    Route::post('settings/clear-cache', [TypingSettingController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Services
    Route::resource('services', TypingServiceController::class);
    Route::patch('services/{service}/toggle-active', [TypingServiceController::class, 'toggleActive'])->name('services.toggle-active');
    Route::patch('services/{service}/toggle-featured', [TypingServiceController::class, 'toggleFeatured'])->name('services.toggle-featured');
    Route::post('services/reorder', [TypingServiceController::class, 'reorder'])->name('services.reorder');
});
```

---

## 7. Files to Create

### 7.1 Backend Files (9 files)

| # | File Path | Type |
|---|-----------|------|
| 1 | `database/migrations/2026_01_28_000001_create_typing_services_table.php` | Migration |
| 2 | `app/Models/TypingService.php` | Model |
| 3 | `app/Services/TypingServiceService.php` | Service |
| 4 | `app/Http/Controllers/Admin/Typing/SettingController.php` | Controller |
| 5 | `app/Http/Controllers/Admin/Typing/ServiceController.php` | Controller |
| 6 | `app/Http/Controllers/Public/TypingController.php` | Controller |
| 7 | `app/Http/Requests/Admin/TypingServiceRequest.php` | FormRequest |
| 8 | `database/seeders/TypingSectionSeeder.php` | Seeder |
| 9 | `routes/admin.php` (update) | Routes |

### 7.2 Blade View Files (4 files)

| # | File Path | Type |
|---|-----------|------|
| 1 | `resources/views/admin/pages/typing/settings/index.blade.php` | Settings page |
| 2 | `resources/views/admin/pages/typing/services/index.blade.php` | Services list |
| 3 | `resources/views/admin/pages/typing/services/create.blade.php` | Create form |
| 4 | `resources/views/admin/pages/typing/services/edit.blade.php` | Edit form |

### 7.3 Files to Update (3 files)

| # | File Path | Change |
|---|-----------|--------|
| 1 | `routes/admin.php` | Add typing routes |
| 2 | `routes/web.php` | Update typing routes to use controller |
| 3 | `resources/views/admin/components/layout/sidebar-content.blade.php` | Add typing menu items |

---

## 8. Frontend Data Flow

### 8.1 Current Flow (Broken)

```
routes/web.php → Inline closure → Inertia::render('typing/typinghome')
                                         ↓
                              No props passed (empty settings)
                                         ↓
                              typingheader.vue shows fallback values
```

### 8.2 Target Flow (Working)

```
routes/web.php → TypingController::home()
                         ↓
                 Fetch from database:
                 - SiteSetting::where('section', 'typing')
                 - TypingService::active()->ordered()->get()
                         ↓
                 Inertia::render('typing/typinghome', [
                     'settings' => $settings,
                     'services' => $services,
                     'auth' => [...],
                 ])
                         ↓
                 typinghome.vue receives props
                         ↓
                 typingheader.vue & typingfooter.vue display real data
```

### 8.3 Service Page Flow

```
routes/web.php → TypingController::service($slug)
                         ↓
                 TypingService::where('slug', $slug)->firstOrFail()
                         ↓
                 Inertia::render('typing/services/ServiceDetail', [
                     'service' => $service,
                     'settings' => $settings,
                 ])
                         ↓
                 Dynamic service page with data from DB
```

---

## 9. Summary

### What This Plan Achieves

1. **Site Settings**: Complete parity with Hajj section - admin can manage all typing-specific settings that flow to frontend
2. **Services Management**: New database-driven system to manage 12+ typing services instead of hardcoded Vue files
3. **Dynamic Frontend**: Typing pages receive real data from database via Inertia props
4. **Scalability**: Easy to add/edit/remove services without code changes

### Implementation Estimate

| Phase | Components | Estimated Time |
|-------|------------|----------------|
| Phase 1 | Migration + Model + Service | 1-2 hours |
| Phase 2 | Admin Controllers + Routes | 2-3 hours |
| Phase 3 | Blade Views (Settings + Services) | 3-4 hours |
| Phase 4 | Public Controller + Frontend Integration | 2-3 hours |
| Phase 5 | Seeder + Testing | 1-2 hours |
| **Total** | | **9-14 hours** |

---

*Document prepared for Dubai Travel & Services Platform - Typing Section Implementation*
