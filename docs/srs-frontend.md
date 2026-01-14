# Frontend Technical Specification
# Hajj Section - Admin Panel

**Version:** 2.0  
**Date:** January 14, 2026  
**Project:** Dubai Travel & Services Admin Panel  
**Stack:** Laravel Blade + Alpine.js + Tailwind CSS v4

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Technology Stack](#2-technology-stack)
3. [Architecture Overview](#3-architecture-overview)
4. [Layout System](#4-layout-system)
5. [Component Library](#5-component-library)
6. [Page Specifications](#6-page-specifications)
7. [Alpine.js Patterns](#7-alpinejs-patterns)
8. [Form Handling](#8-form-handling)
9. [Responsive Design](#9-responsive-design)
10. [Animation & Transitions](#10-animation--transitions)
11. [Accessibility](#11-accessibility)
12. [Asset Management](#12-asset-management)

---

## 1. Introduction

### 1.1 Purpose

This document provides detailed technical specifications for the frontend implementation of the Hajj Admin Panel using Laravel Blade templates with Alpine.js for interactivity and Tailwind CSS v4 for styling.

### 1.2 Design Philosophy

| Principle | Description |
|-----------|-------------|
| Server-Side First | Leverage Blade for server-rendered content |
| Progressive Enhancement | Alpine.js for interactive features only |
| Utility-First CSS | Tailwind CSS for rapid, consistent styling |
| Big Tech Standard | Professional, polished UI matching industry leaders |
| Fully Responsive | Seamless experience across all devices |
| Smooth Interactions | Eye-catching visuals with fluid animations |

### 1.3 Quality Standards

The admin panel shall meet these quality benchmarks:

- **Visual Polish**: Attention to micro-interactions, shadows, spacing
- **Performance**: < 2s page load, < 100ms interaction response
- **Responsiveness**: Perfect display on all screen sizes 320px+
- **Accessibility**: WCAG 2.1 AA compliance
- **Consistency**: Uniform patterns across all modules

---

## 2. Technology Stack

### 2.1 Core Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| Laravel Blade | 12.x | Server-side templating |
| Alpine.js | 3.x | Reactive UI components |
| Tailwind CSS | 4.x | Utility-first styling |
| Lucide Icons | Latest | SVG icon library |

### 2.2 Why This Stack

| Technology | Benefits |
|------------|----------|
| Blade | Fast initial load, SEO-friendly, native Laravel integration, simpler deployment |
| Alpine.js | Lightweight (15KB), declarative syntax, no build required, easy learning curve |
| Tailwind v4 | Modern CSS features, design tokens, excellent developer experience, responsive utilities |

### 2.3 Browser Support

| Browser | Minimum Version |
|---------|-----------------|
| Chrome | 90+ |
| Firefox | 88+ |
| Safari | 14+ |
| Edge | 90+ |
| Mobile Chrome | Latest |
| Mobile Safari | Latest |

---

## 3. Architecture Overview

### 3.1 Directory Structure

```
resources/
├── views/
│   └── admin/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── auth.blade.php
│       │
│       ├── components/
│       │   ├── layout/
│       │   │   ├── sidebar.blade.php
│       │   │   ├── header.blade.php
│       │   │   ├── breadcrumb.blade.php
│       │   │   └── mobile-nav.blade.php
│       │   │
│       │   ├── ui/
│       │   │   ├── button.blade.php
│       │   │   ├── input.blade.php
│       │   │   ├── select.blade.php
│       │   │   ├── textarea.blade.php
│       │   │   ├── checkbox.blade.php
│       │   │   ├── toggle.blade.php
│       │   │   ├── badge.blade.php
│       │   │   ├── card.blade.php
│       │   │   ├── modal.blade.php
│       │   │   ├── dropdown.blade.php
│       │   │   ├── alert.blade.php
│       │   │   └── tooltip.blade.php
│       │   │
│       │   ├── data/
│       │   │   ├── table.blade.php
│       │   │   ├── pagination.blade.php
│       │   │   ├── empty-state.blade.php
│       │   │   └── skeleton.blade.php
│       │   │
│       │   ├── form/
│       │   │   ├── image-upload.blade.php
│       │   │   ├── gallery-upload.blade.php
│       │   │   ├── rich-editor.blade.php
│       │   │   ├── dynamic-list.blade.php
│       │   │   ├── date-picker.blade.php
│       │   │   ├── star-rating.blade.php
│       │   │   └── color-picker.blade.php
│       │   │
│       │   └── dashboard/
│       │       ├── stats-card.blade.php
│       │       ├── activity-feed.blade.php
│       │       └── quick-actions.blade.php
│       │
│       ├── pages/
│       │   ├── dashboard.blade.php
│       │   ├── packages/
│       │   ├── articles/
│       │   ├── team/
│       │   ├── testimonials/
│       │   ├── inquiries/
│       │   └── settings/
│       │
│       └── auth/
│           ├── login.blade.php
│           └── forgot-password.blade.php
│
├── css/
│   └── admin.css
│
└── js/
    └── admin/
        ├── app.js
        └── components/
```

### 3.2 Component Organization

All reusable UI elements are Blade components:

| Category | Components |
|----------|------------|
| Layout | sidebar, header, breadcrumb, mobile-nav |
| UI | button, input, select, modal, dropdown, badge, card, alert, tooltip |
| Data | table, pagination, empty-state, skeleton |
| Form | image-upload, rich-editor, dynamic-list, date-picker |
| Dashboard | stats-card, activity-feed, quick-actions |

---

## 4. Layout System

### 4.1 Main Admin Layout Structure

```
┌─────────────────────────────────────────────────────────────────┐
│                        HEADER (h-16)                            │
│  [☰ Mobile] [Logo]      [Search]      [Notifications] [Profile] │
├────────────┬────────────────────────────────────────────────────┤
│            │                                                    │
│  SIDEBAR   │                 MAIN CONTENT                       │
│  (w-64)    │                                                    │
│            │  ┌──────────────────────────────────────────────┐  │
│  Fixed     │  │  Breadcrumb: Dashboard > Packages            │  │
│  Left      │  ├──────────────────────────────────────────────┤  │
│            │  │                                              │  │
│  Dark BG   │  │  Page Title              [Primary Action]    │  │
│  (#1e293b) │  │                                              │  │
│            │  │  ┌────────────────────────────────────────┐  │  │
│            │  │  │                                        │  │  │
│            │  │  │         Page Content Area              │  │  │
│            │  │  │                                        │  │  │
│            │  │  └────────────────────────────────────────┘  │  │
│            │  │                                              │  │
│            │  └──────────────────────────────────────────────┘  │
│            │                                                    │
└────────────┴────────────────────────────────────────────────────┘
```

### 4.2 Sidebar Navigation

**Structure:**

| Section | Items |
|---------|-------|
| Main | Dashboard |
| Hajj & Umrah | Packages, Articles, Team, Testimonials, Inquiries, Settings |
| Tour & Travel | Coming Soon (disabled) |
| Typing Services | Coming Soon (disabled) |
| Account | Profile, Logout |

**Visual States:**

| State | Style |
|-------|-------|
| Default | text-slate-400 |
| Hover | bg-slate-700 text-white |
| Active | bg-amber-600 text-white |
| Section Header | text-slate-500 uppercase text-xs |

### 4.3 Header Components

| Component | Description |
|-----------|-------------|
| Mobile Menu Toggle | Hamburger icon, visible < 1024px |
| Logo | Company branding |
| Search | Global search input |
| Notifications | Bell icon with count badge |
| Profile Dropdown | User avatar, name, logout |

### 4.4 Responsive Layout Behavior

| Breakpoint | Sidebar | Header | Content |
|------------|---------|--------|---------|
| < 640px | Hidden overlay | Compact | Full width |
| 640-1023px | Hidden overlay | Full | Full width |
| ≥ 1024px | Fixed visible | Full | Offset left |

---

## 5. Component Library

### 5.1 Button Component

**Variants:**

| Variant | Background | Text | Border | Usage |
|---------|------------|------|--------|-------|
| primary | amber-600 | white | none | Main actions |
| secondary | white | slate-700 | slate-300 | Secondary actions |
| danger | red-600 | white | none | Delete actions |
| ghost | transparent | slate-600 | none | Tertiary actions |
| link | transparent | amber-600 | none | Navigation |

**Sizes:**

| Size | Padding | Font Size |
|------|---------|-----------|
| xs | px-2.5 py-1 | text-xs |
| sm | px-3 py-1.5 | text-sm |
| md | px-4 py-2 | text-sm |
| lg | px-6 py-3 | text-base |

**States:**

| State | Effect |
|-------|--------|
| Hover | Darker background, slight scale |
| Active | Darker background, scale down |
| Disabled | Opacity 50%, cursor not-allowed |
| Loading | Spinner icon, disabled |

### 5.2 Input Component

**States:**

| State | Border | Background | Ring |
|-------|--------|------------|------|
| Default | slate-300 | white | none |
| Focus | amber-500 | white | amber-500/20 |
| Error | red-500 | red-50 | red-500/20 |
| Disabled | slate-200 | slate-50 | none |

**Features:**

- Label with optional required indicator
- Placeholder text
- Helper text
- Error message display
- Icon prefix/suffix support

### 5.3 Modal Component

**Sizes:**

| Size | Max Width |
|------|-----------|
| sm | 400px |
| md | 500px |
| lg | 700px |
| xl | 900px |
| full | 100% - 2rem |

**Features:**

- Backdrop blur effect
- Smooth scale + fade animation
- Click outside to close (configurable)
- ESC key to close
- Focus trap for accessibility
- Header with title and close button
- Scrollable body
- Sticky footer for actions

### 5.4 Data Table Component

**Features:**

| Feature | Description |
|---------|-------------|
| Sortable Columns | Click header to sort, indicator arrow |
| Row Selection | Checkbox column, select all |
| Row Hover | Background highlight |
| Loading State | Skeleton rows |
| Empty State | Illustrated message |
| Responsive | Horizontal scroll on mobile |

**Column Types:**

| Type | Display |
|------|---------|
| text | Plain text |
| image | Thumbnail preview |
| badge | Status badge |
| date | Formatted date |
| actions | Action buttons/dropdown |
| toggle | Switch component |

### 5.5 Image Upload Component

**Features:**

| Feature | Description |
|---------|-------------|
| Drag & Drop | Drop zone with visual feedback |
| Click Browse | File picker dialog |
| Preview | Image thumbnail with remove |
| Progress | Upload progress bar |
| Validation | File type and size checking |
| Error Display | Error message styling |

**Accepted Formats:** jpg, jpeg, png, webp  
**Max Size:** 10MB

### 5.6 Dynamic List Component

**Features:**

- Add new items with input
- Remove items with confirmation
- Drag to reorder (SortableJS)
- Animation on add/remove
- Validation per item

### 5.7 Stats Card Component

**Display:**

| Element | Description |
|---------|-------------|
| Icon | Lucide icon in colored circle |
| Title | Metric name |
| Value | Large number |
| Change | Percentage with up/down arrow |
| Link | Optional "View All" action |

**Color Variants:** amber, blue, green, red, purple

---

## 6. Page Specifications

### 6.1 Login Page

**Route:** `/admin/login`

**Elements:**
- Centered card layout
- Logo at top
- Email input with icon
- Password input with show/hide toggle
- Remember me checkbox
- Login button (full width)
- Forgot password link

**Validation:**
- Email required, valid format
- Password required, min 8 chars
- Error messages below inputs

### 6.2 Dashboard Page

**Route:** `/admin/hajj`

**Sections:**

| Section | Layout | Content |
|---------|--------|---------|
| Stats Row | 4-column grid | Package, Article, Inquiry, Testimonial counts |
| Main Area | 2/3 + 1/3 split | Recent Inquiries table, Quick Actions |
| Activity | Full width | Recent activity timeline |

### 6.3 Package List Page

**Route:** `/admin/hajj/packages`

**Components:**

| Component | Description |
|-----------|-------------|
| Header | Page title + Add New button |
| Filters | Search, Type dropdown, Status dropdown |
| Table | Sortable data table with actions |
| Pagination | Page navigation |
| Bulk Actions | Toolbar when rows selected |

**Table Columns:** Image, Title, Type, Price, Duration, Featured, Status, Actions

### 6.4 Package Create/Edit Page

**Route:** `/admin/hajj/packages/create`, `/admin/hajj/packages/{id}/edit`

**Form Structure (Tabs):**

| Tab | Fields |
|-----|--------|
| Basic Info | Title, Slug, Type, Price, Currency, Duration |
| Media | Main Image, Gallery Images |
| Details | Features (list), Description (rich text) |
| Itinerary | Day-by-day planner |
| Inclusions | Inclusions list, Exclusions list |
| Availability | Departure dates, Capacity |
| Status | Featured toggle, Active toggle |

### 6.5 Article List Page

**Route:** `/admin/hajj/articles`

**Similar to Package List with:**
- Category filter instead of Type
- Status filter (Draft/Published)
- Quick publish toggle

### 6.6 Article Create/Edit Page

**Route:** `/admin/hajj/articles/create`, `/admin/hajj/articles/{id}/edit`

**Form Structure:**

| Section | Fields |
|---------|--------|
| Content | Title, Slug, Excerpt, Content (rich editor) |
| Media | Featured Image |
| Categorization | Category select, Tags input |
| SEO | Meta title, Meta description |
| Publishing | Status, Publish date |

### 6.7 Team Management Page

**Route:** `/admin/hajj/team`

**Layout:** Grid of team member cards with drag-to-reorder

**Card Content:** Photo, Name, Role, Actions (Edit, Delete)

**Modal Form:** Name, Role, Photo, Bio, Email, Phone, Social links, Order, Active

### 6.8 Testimonials Page

**Route:** `/admin/hajj/testimonials`

**Table with:** Avatar, Name, Rating (stars), Excerpt, Package, Featured, Status, Actions

**Quick Actions:** Approve, Reject, Feature toggle

### 6.9 Inquiries Page

**Route:** `/admin/hajj/inquiries`

**Table with:** Name, Email, Subject, Package, Status (badge), Date, Actions

**Detail View:** Full message, customer info, admin notes, status controls

### 6.10 Settings Page

**Route:** `/admin/hajj/settings`

**Tabs:**

| Tab | Settings |
|-----|----------|
| General | Company name, Logo, Email, Phone, Address |
| Social | Facebook, Twitter, Instagram, LinkedIn, YouTube, WhatsApp |
| SEO | Meta title, Meta description, Keywords, Analytics ID |
| Appearance | Primary color, Hero image, Hero text |
| Locations | Office locations list (CRUD) |

---

## 7. Alpine.js Patterns

### 7.1 Store Pattern

Global stores for shared state:

| Store | Purpose |
|-------|---------|
| sidebar | Sidebar open/close state |
| toast | Toast notification queue |
| modal | Global modal management |

### 7.2 Component Data Pattern

Each interactive component defines its Alpine data:

| Pattern | Usage |
|---------|-------|
| Toggle | open/close states |
| Form | form data, errors, submitting |
| List | items, selected, loading |
| Modal | open, data, callbacks |

### 7.3 Event Handling

| Event | Handler |
|-------|---------|
| Form Submit | Prevent default, show loading, submit via AJAX or form |
| Delete Click | Show confirmation modal |
| Toggle Change | Update via AJAX, show toast |
| Dropdown Click | Toggle menu, close others |

---

## 8. Form Handling

### 8.1 Submission Flow

1. User clicks submit
2. Button shows loading state
3. Client-side validation (optional)
4. Form submits to server
5. Server validates
6. Success: Redirect with flash message
7. Error: Return with validation errors

### 8.2 Validation Display

| Element | Style |
|---------|-------|
| Invalid Input | Red border, red background tint |
| Error Message | Red text below input, icon prefix |
| Error Summary | Alert box at form top |

### 8.3 Flash Messages

| Type | Color | Icon |
|------|-------|------|
| Success | Green | Check circle |
| Error | Red | X circle |
| Warning | Yellow | Alert triangle |
| Info | Blue | Info circle |

**Behavior:** Auto-dismiss after 5 seconds, manual close button

---

## 9. Responsive Design

### 9.1 Breakpoints

| Name | Width | Tailwind |
|------|-------|----------|
| Mobile | < 640px | default |
| Tablet SM | ≥ 640px | sm: |
| Tablet | ≥ 768px | md: |
| Desktop | ≥ 1024px | lg: |
| Large | ≥ 1280px | xl: |
| XL | ≥ 1536px | 2xl: |

### 9.2 Grid Adaptations

| Component | Mobile | Tablet | Desktop |
|-----------|--------|--------|---------|
| Stats Cards | 1 col | 2 col | 4 col |
| Dashboard Main | Stack | Stack | 2/3 + 1/3 |
| Form Fields | 1 col | 1 col | 2 col |
| Team Grid | 1 col | 2 col | 4 col |

### 9.3 Mobile-Specific

| Element | Mobile Behavior |
|---------|-----------------|
| Sidebar | Overlay, close on navigate |
| Tables | Horizontal scroll |
| Modals | Full width, slide up |
| Actions | Bottom sheet pattern |

---

## 10. Animation & Transitions

### 10.1 Transition Durations

| Type | Duration | Easing |
|------|----------|--------|
| Instant | 100ms | ease-out |
| Fast | 150ms | ease-out |
| Normal | 200ms | ease-out |
| Slow | 300ms | ease-out |

### 10.2 Common Animations

| Animation | Usage |
|-----------|-------|
| Fade | Modal backdrop, toasts |
| Scale | Modal content, dropdowns |
| Slide | Sidebar, mobile menu |
| Spin | Loading indicators |
| Pulse | Skeleton loading |

### 10.3 Hover Effects

| Element | Effect |
|---------|--------|
| Buttons | Darken background, slight scale |
| Cards | Subtle shadow increase, lift |
| Table Rows | Background color change |
| Links | Underline, color change |

### 10.4 Micro-interactions

| Interaction | Animation |
|-------------|-----------|
| Toggle switch | Slide circle, color fade |
| Checkbox | Scale pop, checkmark draw |
| Button click | Quick scale down/up |
| Input focus | Border color transition |

---

## 11. Accessibility

### 11.1 Keyboard Navigation

| Key | Action |
|-----|--------|
| Tab | Move to next focusable element |
| Shift+Tab | Move to previous element |
| Enter/Space | Activate button/link |
| Escape | Close modal/dropdown |
| Arrow keys | Navigate menu items |

### 11.2 ARIA Attributes

| Component | Attributes |
|-----------|------------|
| Modal | role="dialog", aria-modal, aria-labelledby |
| Dropdown | aria-expanded, aria-haspopup |
| Alert | role="alert", aria-live |
| Loading | aria-busy |
| Sidebar | role="navigation", aria-label |

### 11.3 Color Contrast

All text must meet WCAG AA contrast ratios:

| Element | Ratio |
|---------|-------|
| Normal text | 4.5:1 minimum |
| Large text | 3:1 minimum |
| UI components | 3:1 minimum |

---

## 12. Asset Management

### 12.1 CSS Organization

Admin-specific styles in `admin.css`:

| Layer | Purpose |
|-------|---------|
| Base | Typography, resets |
| Components | Reusable UI patterns |
| Utilities | Custom utility classes |

### 12.2 JavaScript Organization

| File | Content |
|------|---------|
| app.js | Alpine initialization, stores |
| components/*.js | Complex component logic |

### 12.3 Image Optimization

| Context | Format | Max Size |
|---------|--------|----------|
| Thumbnails | WebP | 50KB |
| Gallery | WebP/JPEG | 500KB |
| Uploads | Original | 10MB |

---

*End of Frontend Technical Specification*
