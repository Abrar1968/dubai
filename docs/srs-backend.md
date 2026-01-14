# Backend Technical Specification
# Hajj Section - Admin Panel

**Version:** 2.0  
**Date:** January 14, 2026  
**Project:** Dubai Travel & Services Admin Panel  
**Architecture:** Service Pattern

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Architecture Overview](#2-architecture-overview)
3. [Directory Structure](#3-directory-structure)
4. [Database Schema](#4-database-schema)
5. [Models & Relationships](#5-models--relationships)
6. [Service Layer](#6-service-layer)
7. [Controllers](#7-controllers)
8. [Form Requests](#8-form-requests)
9. [Routes](#9-routes)
10. [Middleware](#10-middleware)
11. [File Storage](#11-file-storage)
12. [Seeders & Factories](#12-seeders--factories)
13. [Testing](#13-testing)

---

## 1. Introduction

### 1.1 Purpose

This document specifies the backend architecture and implementation details for the Hajj Admin Panel using Laravel 12 with the Service Pattern architecture.

### 1.2 Architecture Pattern

The application follows the **Service Pattern** to separate business logic from controllers:

| Layer | Responsibility |
|-------|----------------|
| Controller | HTTP request/response handling only |
| Service | Business logic, data orchestration |
| Model | Data structure, relationships |
| Repository | Data access (optional, for complex queries) |

### 1.3 Benefits

- **Testability**: Services can be unit tested independently
- **Reusability**: Services can be used by multiple controllers
- **Maintainability**: Clear separation of concerns
- **Scalability**: Easy to add new features

---

## 2. Architecture Overview

### 2.1 Request Flow

```
HTTP Request
    │
    ▼
┌─────────────┐
│  Middleware │  (Auth, CSRF, etc.)
└─────────────┘
    │
    ▼
┌─────────────┐
│ Form Request│  (Validation)
└─────────────┘
    │
    ▼
┌─────────────┐
│ Controller  │  (Thin, delegates to service)
└─────────────┘
    │
    ▼
┌─────────────┐
│  Service    │  (Business logic)
└─────────────┘
    │
    ▼
┌─────────────┐
│   Model     │  (Database operations)
└─────────────┘
    │
    ▼
┌─────────────┐
│  Database   │
└─────────────┘
```

### 2.2 Service Pattern Rules

1. Controllers should only handle HTTP concerns
2. All business logic resides in services
3. Services receive validated data from controllers
4. Services return data, controllers format response
5. Services can call other services
6. Models should not contain business logic

---

## 3. Directory Structure

```
app/
├── Enums/
│   ├── PackageType.php
│   ├── InquiryStatus.php
│   └── PublishStatus.php
│
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       └── Hajj/
│   │           ├── DashboardController.php
│   │           ├── PackageController.php
│   │           ├── ArticleController.php
│   │           ├── ArticleCategoryController.php
│   │           ├── TeamMemberController.php
│   │           ├── TestimonialController.php
│   │           ├── InquiryController.php
│   │           ├── SettingController.php
│   │           └── MediaController.php
│   │
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   │
│   └── Requests/
│       └── Admin/
│           └── Hajj/
│               ├── PackageRequest.php
│               ├── ArticleRequest.php
│               ├── ArticleCategoryRequest.php
│               ├── TeamMemberRequest.php
│               ├── TestimonialRequest.php
│               └── SettingRequest.php
│
├── Models/
│   ├── Package.php
│   ├── PackageGallery.php
│   ├── Article.php
│   ├── ArticleCategory.php
│   ├── TeamMember.php
│   ├── Testimonial.php
│   ├── ContactInquiry.php
│   ├── SiteSetting.php
│   ├── OfficeLocation.php
│   └── Faq.php
│
├── Services/
│   ├── PackageService.php
│   ├── ArticleService.php
│   ├── TeamService.php
│   ├── TestimonialService.php
│   ├── InquiryService.php
│   ├── SettingService.php
│   ├── MediaService.php
│   └── SlugService.php
│
└── View/
    └── Components/
        └── Admin/
            └── (Blade component classes)

database/
├── migrations/
│   ├── xxxx_create_packages_table.php
│   ├── xxxx_create_package_gallery_table.php
│   ├── xxxx_create_article_categories_table.php
│   ├── xxxx_create_articles_table.php
│   ├── xxxx_create_team_members_table.php
│   ├── xxxx_create_testimonials_table.php
│   ├── xxxx_create_contact_inquiries_table.php
│   ├── xxxx_create_site_settings_table.php
│   ├── xxxx_create_office_locations_table.php
│   └── xxxx_create_faqs_table.php
│
├── factories/
│   ├── PackageFactory.php
│   ├── ArticleFactory.php
│   ├── TeamMemberFactory.php
│   └── TestimonialFactory.php
│
└── seeders/
    ├── PackageSeeder.php
    ├── ArticleCategorySeeder.php
    ├── ArticleSeeder.php
    ├── TeamMemberSeeder.php
    ├── TestimonialSeeder.php
    ├── SiteSettingSeeder.php
    └── FaqSeeder.php

routes/
├── web.php
└── admin.php

resources/
└── views/
    └── admin/
        └── (Blade templates)
```

---

## 4. Database Schema

### 4.1 packages

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT |
| title | VARCHAR(200) | NOT NULL |
| slug | VARCHAR(220) | UNIQUE, NOT NULL |
| type | ENUM('hajj', 'umrah', 'tour') | NOT NULL, DEFAULT 'hajj' |
| price | DECIMAL(10,2) | NOT NULL |
| currency | VARCHAR(3) | NOT NULL, DEFAULT 'USD' |
| duration_days | INT UNSIGNED | NOT NULL |
| image | VARCHAR(500) | NULL |
| thumbnail | VARCHAR(500) | NULL |
| features | JSON | NULL |
| description | TEXT | NULL |
| inclusions | JSON | NULL |
| exclusions | JSON | NULL |
| itinerary | JSON | NULL |
| hotel_details | JSON | NULL |
| departure_dates | JSON | NULL |
| max_capacity | INT UNSIGNED | NULL |
| current_bookings | INT UNSIGNED | DEFAULT 0 |
| is_featured | BOOLEAN | DEFAULT FALSE |
| is_active | BOOLEAN | DEFAULT TRUE |
| created_at | TIMESTAMP | NULL |
| updated_at | TIMESTAMP | NULL |
| deleted_at | TIMESTAMP | NULL |

**Indexes:** idx_type, idx_is_active, idx_is_featured

### 4.2 package_gallery

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY |
| package_id | BIGINT UNSIGNED | FK → packages(id) ON DELETE CASCADE |
| image_path | VARCHAR(500) | NOT NULL |
| sort_order | INT UNSIGNED | DEFAULT 0 |
| created_at | TIMESTAMP | NULL |
| updated_at | TIMESTAMP | NULL |

### 4.3 article_categories

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY |
| name | VARCHAR(100) | NOT NULL |
| slug | VARCHAR(120) | UNIQUE, NOT NULL |
| description | TEXT | NULL |
| created_at | TIMESTAMP | NULL |
| updated_at | TIMESTAMP | NULL |

### 4.4 articles

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY |
| title | VARCHAR(200) | NOT NULL |
| slug | VARCHAR(220) | UNIQUE, NOT NULL |
| excerpt | VARCHAR(500) | NULL |
| content | LONGTEXT | NULL |
| category_id | BIGINT UNSIGNED | FK → article_categories(id) ON DELETE SET NULL |
| author_id | BIGINT UNSIGNED | FK → users(id) ON DELETE CASCADE |
| image | VARCHAR(500) | NULL |
| thumbnail | VARCHAR(500) | NULL |
| meta_title | VARCHAR(60) | NULL |
| meta_description | VARCHAR(160) | NULL |
| tags | JSON | NULL |
| status | ENUM('draft', 'published') | DEFAULT 'draft' |
| published_at | TIMESTAMP | NULL |
| views_count | INT UNSIGNED | DEFAULT 0 |
| created_at | TIMESTAMP | NULL |
| updated_at | TIMESTAMP | NULL |
| deleted_at | TIMESTAMP | NULL |

**Indexes:** idx_status, idx_category, idx_published_at

### 4.5 team_members

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY |
| name | VARCHAR(100) | NOT NULL |
| role | VARCHAR(100) | NOT NULL |
| image | VARCHAR(500) | NULL |
| bio | TEXT | NULL |
| email | VARCHAR(255) | NULL |
| phone | VARCHAR(50) | NULL |
| social_links | JSON | NULL |
| sort_order | INT UNSIGNED | DEFAULT 0 |
| is_active | BOOLEAN | DEFAULT TRUE |
| created_at | TIMESTAMP | NULL |
| updated_at | TIMESTAMP | NULL |

**Indexes:** idx_is_active, idx_sort_order

### 4.6 testimonials

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY |
| name | VARCHAR(100) | NOT NULL |
| location | VARCHAR(100) | NULL |
| avatar | VARCHAR(500) | NULL |
| rating | TINYINT UNSIGNED | NOT NULL, DEFAULT 5 |
| content | TEXT | NOT NULL |
| package_id | BIGINT UNSIGNED | FK → packages(id) ON DELETE SET NULL |
| is_featured | BOOLEAN | DEFAULT FALSE |
| is_approved | BOOLEAN | DEFAULT FALSE |
| created_at | TIMESTAMP | NULL |
| updated_at | TIMESTAMP | NULL |

**Indexes:** idx_is_approved, idx_is_featured

### 4.7 contact_inquiries

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY |
| name | VARCHAR(100) | NOT NULL |
| email | VARCHAR(255) | NOT NULL |
| phone | VARCHAR(50) | NULL |
| subject | VARCHAR(200) | NOT NULL |
| message | TEXT | NOT NULL |
| package_id | BIGINT UNSIGNED | FK → packages(id) ON DELETE SET NULL |
| status | ENUM('new', 'read', 'responded', 'closed') | DEFAULT 'new' |
| admin_notes | TEXT | NULL |
| responded_at | TIMESTAMP | NULL |
| created_at | TIMESTAMP | NULL |
| updated_at | TIMESTAMP | NULL |

**Indexes:** idx_status, idx_created_at

### 4.8 site_settings

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY |
| section | VARCHAR(50) | NOT NULL, DEFAULT 'hajj' |
| key | VARCHAR(100) | NOT NULL |
| value | TEXT | NULL |
| type | ENUM('string', 'text', 'json', 'boolean', 'integer') | DEFAULT 'string' |
| created_at | TIMESTAMP | NULL |
| updated_at | TIMESTAMP | NULL |

**Unique:** (section, key)

### 4.9 office_locations

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY |
| section | VARCHAR(50) | NOT NULL, DEFAULT 'hajj' |
| name | VARCHAR(100) | NOT NULL |
| address | TEXT | NOT NULL |
| phone | VARCHAR(50) | NULL |
| email | VARCHAR(255) | NULL |
| map_lat | DECIMAL(10,8) | NULL |
| map_lng | DECIMAL(11,8) | NULL |
| sort_order | INT UNSIGNED | DEFAULT 0 |
| is_active | BOOLEAN | DEFAULT TRUE |
| created_at | TIMESTAMP | NULL |
| updated_at | TIMESTAMP | NULL |

### 4.10 faqs

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY |
| section | VARCHAR(50) | NOT NULL, DEFAULT 'hajj' |
| question | VARCHAR(500) | NOT NULL |
| answer | TEXT | NOT NULL |
| sort_order | INT UNSIGNED | DEFAULT 0 |
| is_active | BOOLEAN | DEFAULT TRUE |
| created_at | TIMESTAMP | NULL |
| updated_at | TIMESTAMP | NULL |

---

## 5. Models & Relationships

### 5.1 Package Model

**Relationships:**

| Relation | Type | Model |
|----------|------|-------|
| gallery | hasMany | PackageGallery |
| testimonials | hasMany | Testimonial |
| inquiries | hasMany | ContactInquiry |

**Casts:**

| Attribute | Cast |
|-----------|------|
| features | array |
| inclusions | array |
| exclusions | array |
| itinerary | array |
| hotel_details | array |
| departure_dates | array |
| is_featured | boolean |
| is_active | boolean |

**Scopes:**

| Scope | Description |
|-------|-------------|
| active() | Where is_active = true |
| featured() | Where is_featured = true |
| hajj() | Where type = 'hajj' |
| umrah() | Where type = 'umrah' |

**Accessors:**

| Accessor | Returns |
|----------|---------|
| image_url | Full URL to image |
| formatted_price | Price with currency symbol |

### 5.2 Article Model

**Relationships:**

| Relation | Type | Model |
|----------|------|-------|
| category | belongsTo | ArticleCategory |
| author | belongsTo | User |

**Scopes:**

| Scope | Description |
|-------|-------------|
| published() | Where status = 'published' |
| draft() | Where status = 'draft' |
| recent() | OrderBy published_at desc |

### 5.3 TeamMember Model

**Casts:**

| Attribute | Cast |
|-----------|------|
| social_links | array |
| is_active | boolean |

**Scopes:**

| Scope | Description |
|-------|-------------|
| active() | Where is_active = true |
| ordered() | OrderBy sort_order asc |

### 5.4 Testimonial Model

**Relationships:**

| Relation | Type | Model |
|----------|------|-------|
| package | belongsTo | Package |

**Scopes:**

| Scope | Description |
|-------|-------------|
| approved() | Where is_approved = true |
| featured() | Where is_featured = true |

### 5.5 ContactInquiry Model

**Relationships:**

| Relation | Type | Model |
|----------|------|-------|
| package | belongsTo | Package |

**Casts:**

| Attribute | Cast |
|-----------|------|
| status | InquiryStatus (enum) |
| responded_at | datetime |

---

## 6. Service Layer

### 6.1 PackageService

**Methods:**

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| getAll | filters, perPage | LengthAwarePaginator | List with filters/pagination |
| find | id | Package | Get single package |
| create | data | Package | Create new package |
| update | Package, data | Package | Update existing |
| delete | Package | bool | Soft delete |
| duplicate | Package | Package | Create copy |
| toggleStatus | Package | Package | Toggle is_active |
| toggleFeatured | Package | Package | Toggle is_featured |
| updateGallery | Package, images | void | Sync gallery images |

### 6.2 ArticleService

**Methods:**

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| getAll | filters, perPage | LengthAwarePaginator | List with filters |
| find | id | Article | Get single article |
| create | data, User | Article | Create new |
| update | Article, data | Article | Update existing |
| delete | Article | bool | Soft delete |
| publish | Article | Article | Set to published |
| unpublish | Article | Article | Set to draft |
| incrementViews | Article | void | Increment view count |

### 6.3 TeamService

**Methods:**

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| getAll | activeOnly | Collection | Get all members |
| find | id | TeamMember | Get single member |
| create | data | TeamMember | Create new |
| update | TeamMember, data | TeamMember | Update existing |
| delete | TeamMember | bool | Delete member |
| reorder | array ids | void | Update sort order |

### 6.4 TestimonialService

**Methods:**

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| getAll | filters, perPage | LengthAwarePaginator | List with filters |
| find | id | Testimonial | Get single |
| create | data | Testimonial | Create new |
| update | Testimonial, data | Testimonial | Update existing |
| delete | Testimonial | bool | Delete |
| approve | Testimonial | Testimonial | Set approved |
| reject | Testimonial | Testimonial | Set rejected |
| toggleFeatured | Testimonial | Testimonial | Toggle featured |

### 6.5 InquiryService

**Methods:**

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| getAll | filters, perPage | LengthAwarePaginator | List with filters |
| find | id | ContactInquiry | Get single |
| create | data | ContactInquiry | Create from form |
| markAsRead | ContactInquiry | ContactInquiry | Update status |
| markAsResponded | ContactInquiry, notes | ContactInquiry | Update status |
| close | ContactInquiry | ContactInquiry | Close inquiry |
| delete | ContactInquiry | bool | Delete |
| getUnreadCount | | int | Count new inquiries |

### 6.6 SettingService

**Methods:**

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| get | key, default | mixed | Get single setting |
| set | key, value, type | SiteSetting | Set single setting |
| getByGroup | group | array | Get settings group |
| setMany | array | void | Update multiple |
| getOffices | | Collection | Get office locations |
| saveOffice | data | OfficeLocation | Create/update office |
| deleteOffice | id | bool | Delete office |

### 6.7 MediaService

**Methods:**

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| uploadImage | UploadedFile, path | string | Upload single image |
| uploadImages | array, path | array | Upload multiple |
| deleteImage | path | bool | Delete from storage |
| createThumbnail | path, size | string | Generate thumbnail |
| getUrl | path | string | Get full URL |

### 6.8 SlugService

**Methods:**

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| generate | string, Model | string | Create unique slug |
| generateFrom | string | string | Convert to slug format |
| isUnique | slug, Model, excludeId | bool | Check uniqueness |

---

## 7. Controllers

### 7.1 Controller Pattern

Controllers should be thin, delegating to services:

**Example Pattern:**

| Method | Responsibility |
|--------|----------------|
| index() | Get list from service, return view |
| create() | Return create form view |
| store() | Pass validated data to service, redirect |
| edit() | Get item from service, return view |
| update() | Pass validated data to service, redirect |
| destroy() | Call service delete, redirect |

### 7.2 PackageController

| Method | Route | Description |
|--------|-------|-------------|
| index | GET /admin/hajj/packages | List packages |
| create | GET /admin/hajj/packages/create | Create form |
| store | POST /admin/hajj/packages | Save new |
| edit | GET /admin/hajj/packages/{id}/edit | Edit form |
| update | PUT /admin/hajj/packages/{id} | Update existing |
| destroy | DELETE /admin/hajj/packages/{id} | Delete package |
| duplicate | POST /admin/hajj/packages/{id}/duplicate | Duplicate |
| toggleStatus | PATCH /admin/hajj/packages/{id}/status | Toggle active |
| toggleFeatured | PATCH /admin/hajj/packages/{id}/featured | Toggle featured |

### 7.3 Other Controllers Follow Same Pattern

- ArticleController
- ArticleCategoryController
- TeamMemberController
- TestimonialController
- InquiryController
- SettingController
- MediaController

---

## 8. Form Requests

### 8.1 PackageRequest

**Validation Rules:**

| Field | Rules |
|-------|-------|
| title | required, string, max:200 |
| slug | nullable, string, max:220, unique:packages,slug,{id} |
| type | required, in:hajj,umrah,tour |
| price | required, numeric, min:0 |
| currency | required, in:USD,AED,SAR |
| duration_days | required, integer, min:1 |
| image | nullable, image, max:10240 |
| features | nullable, array |
| features.* | string, max:200 |
| description | nullable, string |
| inclusions | nullable, array |
| exclusions | nullable, array |
| itinerary | nullable, array |
| hotel_details | nullable, array |
| departure_dates | nullable, array |
| max_capacity | nullable, integer, min:1 |
| is_featured | boolean |
| is_active | boolean |

### 8.2 ArticleRequest

**Validation Rules:**

| Field | Rules |
|-------|-------|
| title | required, string, max:200 |
| slug | nullable, string, max:220, unique:articles,slug,{id} |
| excerpt | nullable, string, max:500 |
| content | nullable, string |
| category_id | nullable, exists:article_categories,id |
| image | nullable, image, max:10240 |
| meta_title | nullable, string, max:60 |
| meta_description | nullable, string, max:160 |
| tags | nullable, array |
| status | in:draft,published |
| published_at | nullable, date |

### 8.3 TeamMemberRequest

**Validation Rules:**

| Field | Rules |
|-------|-------|
| name | required, string, max:100 |
| role | required, string, max:100 |
| image | nullable, image, max:10240 |
| bio | nullable, string |
| email | nullable, email, max:255 |
| phone | nullable, string, max:50 |
| social_links | nullable, array |
| social_links.facebook | nullable, url |
| social_links.twitter | nullable, url |
| social_links.linkedin | nullable, url |
| social_links.instagram | nullable, url |
| sort_order | integer, min:0 |
| is_active | boolean |

### 8.4 TestimonialRequest

**Validation Rules:**

| Field | Rules |
|-------|-------|
| name | required, string, max:100 |
| location | nullable, string, max:100 |
| avatar | nullable, image, max:5120 |
| rating | required, integer, min:1, max:5 |
| content | required, string |
| package_id | nullable, exists:packages,id |
| is_featured | boolean |
| is_approved | boolean |

---

## 9. Routes

### 9.1 Route File Structure

**routes/admin.php:**

| Prefix | Middleware | Description |
|--------|------------|-------------|
| admin | web, auth | All admin routes |
| admin/hajj | web, auth | Hajj section |

### 9.2 Hajj Section Routes

| Method | URI | Action | Name |
|--------|-----|--------|------|
| GET | /admin/hajj | DashboardController@index | admin.hajj.dashboard |
| | | | |
| GET | /admin/hajj/packages | PackageController@index | admin.hajj.packages.index |
| GET | /admin/hajj/packages/create | PackageController@create | admin.hajj.packages.create |
| POST | /admin/hajj/packages | PackageController@store | admin.hajj.packages.store |
| GET | /admin/hajj/packages/{id}/edit | PackageController@edit | admin.hajj.packages.edit |
| PUT | /admin/hajj/packages/{id} | PackageController@update | admin.hajj.packages.update |
| DELETE | /admin/hajj/packages/{id} | PackageController@destroy | admin.hajj.packages.destroy |
| POST | /admin/hajj/packages/{id}/duplicate | PackageController@duplicate | admin.hajj.packages.duplicate |
| PATCH | /admin/hajj/packages/{id}/status | PackageController@toggleStatus | admin.hajj.packages.toggle-status |
| PATCH | /admin/hajj/packages/{id}/featured | PackageController@toggleFeatured | admin.hajj.packages.toggle-featured |
| | | | |
| | (Similar patterns for articles, team, testimonials, inquiries, settings) | | |

### 9.3 Authentication Routes

| Method | URI | Action | Name |
|--------|-----|--------|------|
| GET | /admin/login | Auth\LoginController@showLoginForm | admin.login |
| POST | /admin/login | Auth\LoginController@login | admin.login.submit |
| POST | /admin/logout | Auth\LoginController@logout | admin.logout |

---

## 10. Middleware

### 10.1 AdminMiddleware

**Purpose:** Verify user has admin access

**Logic:**
1. Check user is authenticated
2. Verify user has admin role/permission
3. Redirect to login if not authenticated
4. Return 403 if not authorized

### 10.2 Route Middleware Stack

| Order | Middleware | Purpose |
|-------|------------|---------|
| 1 | EncryptCookies | Cookie encryption |
| 2 | AddQueuedCookiesToResponse | Cookie handling |
| 3 | StartSession | Session management |
| 4 | ShareErrorsFromSession | Validation errors |
| 5 | VerifyCsrfToken | CSRF protection |
| 6 | SubstituteBindings | Route model binding |
| 7 | Authenticate | User authentication |
| 8 | AdminMiddleware | Admin authorization |

---

## 11. File Storage

### 11.1 Storage Structure

```
storage/
└── app/
    └── public/
        └── uploads/
            ├── packages/
            │   ├── images/
            │   ├── thumbnails/
            │   └── gallery/
            ├── articles/
            │   ├── images/
            │   └── thumbnails/
            ├── team/
            ├── testimonials/
            └── settings/
```

### 11.2 Image Specifications

| Context | Max Size | Dimensions | Format |
|---------|----------|------------|--------|
| Package Main | 10MB | 1200x800 | jpg, png, webp |
| Package Thumbnail | Auto | 400x300 | webp |
| Package Gallery | 10MB | 1200x800 | jpg, png, webp |
| Article Image | 10MB | 1200x630 | jpg, png, webp |
| Team Photo | 5MB | 400x400 | jpg, png, webp |
| Testimonial Avatar | 2MB | 200x200 | jpg, png, webp |
| Logo | 5MB | 300x100 | png, svg |

### 11.3 Upload Configuration

| Setting | Value |
|---------|-------|
| Max File Size | 10MB |
| Allowed Types | jpg, jpeg, png, webp, svg |
| Storage Disk | public |
| URL Generation | asset('storage/...') |

---

## 12. Seeders & Factories

### 12.1 Seeders

| Seeder | Records | Description |
|--------|---------|-------------|
| PackageSeeder | 10 | Sample Hajj/Umrah packages |
| ArticleCategorySeeder | 5 | Default categories |
| ArticleSeeder | 15 | Sample articles |
| TeamMemberSeeder | 6 | Sample team members |
| TestimonialSeeder | 10 | Sample testimonials |
| SiteSettingSeeder | 20 | Default settings |
| FaqSeeder | 8 | Sample FAQs |

### 12.2 Factories

**PackageFactory:**

| Field | Generator |
|-------|-----------|
| title | fake()->sentence(4) |
| slug | Str::slug(title) |
| type | fake()->randomElement(['hajj', 'umrah']) |
| price | fake()->numberBetween(3000, 15000) |
| currency | 'USD' |
| duration_days | fake()->numberBetween(7, 30) |
| features | fake()->sentences(5) |
| description | fake()->paragraphs(3, true) |
| is_featured | fake()->boolean(20) |
| is_active | fake()->boolean(90) |

**ArticleFactory:**

| Field | Generator |
|-------|-----------|
| title | fake()->sentence(6) |
| slug | Str::slug(title) |
| excerpt | fake()->paragraph |
| content | fake()->paragraphs(10, true) |
| status | fake()->randomElement(['draft', 'published']) |
| views_count | fake()->numberBetween(0, 1000) |

---

## 13. Testing

### 13.1 Test Categories

| Category | Location | Purpose |
|----------|----------|---------|
| Unit | tests/Unit | Service methods |
| Feature | tests/Feature | HTTP endpoints |
| Integration | tests/Feature | Full workflows |

### 13.2 Test Coverage Requirements

| Category | Minimum Coverage |
|----------|------------------|
| Services | 80% |
| Controllers | 70% |
| Models | 60% |
| Overall | 70% |

### 13.3 Key Test Scenarios

**PackageService Tests:**
- Create package with valid data
- Create package with invalid data (validation)
- Update package
- Delete package (soft delete)
- Duplicate package
- Toggle status
- Toggle featured

**PackageController Tests:**
- List packages (authenticated)
- List packages (unauthenticated - redirect)
- Create package form loads
- Store package (valid data)
- Store package (invalid data - validation errors)
- Edit package form loads with data
- Update package
- Delete package
- Duplicate package

---

*End of Backend Technical Specification*
