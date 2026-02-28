# Hajj Section Overview
# Admin Panel Design & Analysis

**Version:** 3.0  
**Date:** January 14, 2026  
**Project:** Dubai Travel & Services

---

## 1. Project Overview

### 1.1 Purpose

This document provides a comprehensive overview of the Hajj & Umrah section, including the existing public-facing frontend analysis, admin panel design specifications, and user dashboard features.

### 1.2 Sections

The Dubai Travel & Services platform consists of three main sections:

| Section | Status | Priority |
|---------|--------|----------|
| Hajj & Umrah | Active Development | Primary |
| Tour & Travel | Placeholder | Phase 2 |
| Typing Services | Placeholder | Phase 3 |

### 1.3 User Roles

| Role | Access Level | Description |
|------|--------------|-------------|
| Super Admin | Full System | All sections, admin management, full control |
| Admin | Assigned Section(s) | Only assigned sections, no admin management |
| User (Customer) | User Dashboard | Booking tracking, profile management |

---

## 2. Current Frontend Analysis

### 2.1 Public Pages (Vue.js + Inertia)

| File | Route | Purpose |
|------|-------|---------|
| hajjhome.vue | /hajj-umrah | Main landing page |
| hajjpackage.vue | /hajjpackage | Hajj packages listing |
| umrahpackage.vue | /umrahpackage | Umrah packages listing |
| articles.vue | /articles | Blog/article listing |
| article_detail.vue | /articles/{slug} | Single article view |
| team.vue | /hajj-umrah/team | Team members page |
| contactus.vue | /contactus | Contact form |

### 2.2 Current Components

| Component | Purpose |
|-----------|---------|
| hajjheader.vue | Navigation with contact info, social links, menu |
| hajjfooter.vue | Footer with office info, links, copyright, admin login |
| HajjUmrahLayout.vue | Layout wrapper with header/footer |

### 2.3 Data Currently Used (Hardcoded)

**Packages:**
- id, title, price, image, features array

**Articles:**
- id, slug, category, title, excerpt, image

**Team:**
- name, role, image, social links

**Testimonials:**
- name, location, quote, avatar

---

## 3. Admin Panel Overview

### 3.1 Technology Stack Change

**Previous Plan:** Vue.js + Inertia for admin  
**Current Plan:** Laravel Blade + Alpine.js + Tailwind CSS v4

**Rationale:**
- Faster initial page loads
- Simpler deployment
- Better SEO for admin (if needed)
- Reduced JavaScript bundle
- Easier maintenance

### 3.2 Architecture Pattern

**Service Pattern** - All business logic in service classes:

| Layer | Responsibility |
|-------|----------------|
| Controller | HTTP handling only |
| Service | Business logic |
| Model | Data representation |

### 3.3 Admin Access

**Entry Point:** Admin Login link in public website footer

| Route | Purpose |
|-------|---------|
| /admin/login | Admin authentication |
| /admin/dashboard | Dashboard after login |
| /admin/hajj/* | Hajj section management |
| /admin/users | Admin user management (Super Admin only) |

### 3.4 User Access

**Entry Point:** Login/Register in public website header

| Route | Purpose |
|-------|---------|
| /login | User login |
| /register | User registration |
| /user/dashboard | User dashboard |
| /user/bookings | Booking history |
| /user/profile | Profile management |

---

## 4. Data Models

### 4.1 Package

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Primary key |
| title | string | Package name |
| slug | string | URL slug |
| type | enum | hajj, umrah, tour |
| price | decimal | Base price |
| currency | string | USD, AED, SAR |
| duration_days | integer | Trip duration |
| image | string | Main image path |
| features | json | Feature list |
| description | text | Full description |
| inclusions | json | What's included |
| exclusions | json | What's not included |
| itinerary | json | Day-by-day plan |
| hotel_details | json | Hotel information |
| departure_dates | json | Available dates |
| max_capacity | integer | Max travelers |
| is_featured | boolean | Featured flag |
| is_active | boolean | Active flag |

### 4.2 Article

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Primary key |
| title | string | Article title |
| slug | string | URL slug |
| excerpt | string | Short summary |
| content | text | Full content (HTML) |
| category_id | integer | FK to categories |
| author_id | integer | FK to users |
| image | string | Featured image |
| meta_title | string | SEO title |
| meta_description | string | SEO description |
| tags | json | Tag list |
| status | enum | draft, published |
| published_at | datetime | Publish date |
| views_count | integer | View counter |

### 4.3 Article Category

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Primary key |
| name | string | Category name |
| slug | string | URL slug |
| description | text | Description |

### 4.4 Team Member

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Primary key |
| name | string | Full name |
| role | string | Position title |
| image | string | Photo path |
| bio | text | Biography |
| email | string | Contact email |
| phone | string | Contact phone |
| social_links | json | Social media URLs |
| sort_order | integer | Display order |
| is_active | boolean | Active flag |

### 4.5 Testimonial

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Primary key |
| name | string | Customer name |
| location | string | Customer location |
| avatar | string | Photo path |
| rating | integer | 1-5 stars |
| content | text | Testimonial text |
| package_id | integer | FK to packages |
| is_featured | boolean | Featured flag |
| is_approved | boolean | Approval status |

### 4.6 Contact Inquiry

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Primary key |
| name | string | Customer name |
| email | string | Customer email |
| phone | string | Customer phone |
| subject | string | Inquiry subject |
| message | text | Full message |
| package_id | integer | FK to packages |
| status | enum | new, read, responded, closed |
| admin_notes | text | Internal notes |
| responded_at | datetime | Response timestamp |

### 4.7 Site Setting

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Primary key |
| section | string | Module (hajj, tour, etc.) |
| key | string | Setting key |
| value | text | Setting value |
| type | enum | string, text, json, boolean |

### 4.8 Office Location

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Primary key |
| section | string | Module |
| name | string | Office name |
| address | text | Full address |
| phone | string | Contact phone |
| email | string | Contact email |
| map_lat | decimal | Latitude |
| map_lng | decimal | Longitude |
| sort_order | integer | Display order |
| is_active | boolean | Active flag |

### 4.9 FAQ

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Primary key |
| section | string | Module |
| question | string | Question text |
| answer | text | Answer text |
| sort_order | integer | Display order |
| is_active | boolean | Active flag |

---

## 5. Admin Panel Modules

### 5.1 Dashboard

**Content:**
- Statistics cards (packages, articles, inquiries, testimonials)
- Recent inquiries table
- Quick action buttons
- Activity feed

**Statistics Displayed:**

| Metric | Description |
|--------|-------------|
| Total Packages | Active/inactive breakdown |
| Total Articles | Published/draft breakdown |
| New Inquiries | Unread count highlighted |
| Testimonials | Approved/pending breakdown |

### 5.2 Package Management

**List Features:**
- Sortable table columns
- Search by title
- Filter by type (Hajj/Umrah)
- Filter by status (Active/Inactive)
- Quick toggle for featured/active
- Bulk actions

**Form Sections:**
1. Basic Information
2. Media (main image, gallery)
3. Features (dynamic list)
4. Description (rich text)
5. Itinerary (day-by-day)
6. Hotel Information
7. Inclusions/Exclusions
8. Availability

### 5.3 Article Management

**List Features:**
- Search and filter
- Category filter
- Status filter
- Quick publish toggle

**Form Sections:**
1. Content (title, excerpt, body)
2. Media (featured image)
3. Categorization (category, tags)
4. SEO (meta fields)
5. Publishing (status, date)

### 5.4 Team Management

**Display:** Grid view with drag-to-reorder

**Form Fields:**
- Name, role, photo
- Bio, email, phone
- Social media links
- Display order, active status

### 5.5 Testimonial Management

**List Features:**
- Filter by approval status
- Filter by featured status
- Quick approve/reject
- Quick feature toggle

**Form Fields:**
- Name, location, avatar
- Rating (1-5 stars)
- Content
- Package association
- Status toggles

### 5.6 Inquiry Management

**List Features:**
- Status badges (color-coded)
- Filter by status
- Date range filter

**Detail View:**
- Full message display
- Customer information
- Associated package
- Admin notes
- Status management

### 5.7 Settings Management

**Tabs:**
1. General (company info, logo)
2. Social Media (platform URLs)
3. SEO (default meta tags)
4. Appearance (colors, hero)
5. Office Locations (CRUD list)

---

## 6. UI/UX Guidelines

### 6.1 Design Quality Standards

| Standard | Description |
|----------|-------------|
| Professional | Clean, modern, big-tech aesthetic |
| Responsive | Perfect on all devices 320px+ |
| Smooth | Fluid animations, transitions |
| Accessible | WCAG 2.1 AA compliant |
| Consistent | Uniform patterns throughout |

### 6.2 Color Palette

| Color | Hex | Usage |
|-------|-----|-------|
| Primary (Gold) | #D3A762 | CTAs, highlights |
| Primary Hover | #c29652 | Hover states |
| Sidebar BG | #1e293b | Navigation |
| Page BG | #f8fafc | Content area |
| Surface | #ffffff | Cards, modals |
| Success | #22c55e | Active, published |
| Warning | #f59e0b | Pending |
| Error | #ef4444 | Errors, delete |
| Info | #3b82f6 | New items |

### 6.3 Responsive Breakpoints

| Breakpoint | Width | Sidebar | Layout |
|------------|-------|---------|--------|
| Mobile | < 640px | Hidden overlay | Single column |
| Tablet | 640-1023px | Hidden overlay | 2 columns |
| Desktop | ≥ 1024px | Fixed visible | Multi-column |

### 6.4 Animation Standards

| Animation | Duration | Easing |
|-----------|----------|--------|
| Fade | 200ms | ease-out |
| Slide | 300ms | ease-out |
| Scale | 200ms | ease-out |
| Hover | 150ms | ease-in-out |
| Loading | 1000ms | linear |

---

## 7. Navigation Structure

### 7.1 Sidebar Menu

```
📊 Dashboard
───────────────
📿 HAJJ & UMRAH
   ├── 📦 Packages
   ├── 📝 Articles
   ├── 👥 Team
   ├── 💬 Testimonials
   ├── 📧 Inquiries
   └── ⚙️ Settings
───────────────
✈️ TOUR & TRAVEL
   └── (Coming Soon)
───────────────
📄 TYPING SERVICES
   └── (Coming Soon)
───────────────
👤 Profile
🚪 Logout
```

### 7.2 Breadcrumb Pattern

Format: `Dashboard > Section > Page > Action`

Examples:
- Dashboard > Packages
- Dashboard > Packages > Create
- Dashboard > Packages > Edit: Premium Hajj 2025
- Dashboard > Settings > Social Media

---

## 8. Public Website Integration

### 8.1 Admin Login Access

**Location:** Footer of public website (hajjfooter.vue)

**Implementation:**
- Add "Admin Login" link in footer links section
- Link points to `/admin/login`
- Subtle placement, not prominent

### 8.2 Data Flow

Public pages will receive data from admin-managed database:

| Public Page | Data Source |
|-------------|-------------|
| hajjhome.vue | Packages, Articles, Testimonials from DB |
| hajjpackage.vue | Hajj packages from DB |
| umrahpackage.vue | Umrah packages from DB |
| articles.vue | Published articles from DB |
| article_detail.vue | Single article from DB |
| team.vue | Active team members from DB |
| contactus.vue | Submits to contact_inquiries table |

---

## 9. Implementation Priorities

### 9.1 Phase 1 (Day 1)
- Database migrations
- Models with relationships
- Service classes
- Admin route setup
- Admin layout (Blade)
- Authentication

### 9.2 Phase 2 (Day 2)
- Package CRUD
- Article CRUD
- Blade components
- Form handling
- Image uploads

### 9.3 Phase 3 (Day 3)
- Team management
- Testimonials
- Inquiries
- Settings
- Dashboard statistics
- Testing & polish

---

## 10. Future Considerations

### 10.1 Tour & Travel Module

When implemented, will share:
- Admin layout
- UI components
- Service patterns
- Media handling

Unique features:
- Destination management
- Tour categories
- Booking integration
- Guide management

### 10.2 Typing Services Module

When implemented, will share:
- Admin layout
- UI components
- Service patterns

Unique features:
- Service catalog
- Pricing tiers
- Order tracking
- Document management

### 10.3 Shared Features to Build Now

| Feature | Benefit |
|---------|---------|
| Reusable Blade components | Used by all modules |
| Media service | Shared image handling |
| Setting service | Multi-section support |
| Admin layout | Consistent experience |

---

*End of Hajj Section Overview*
