# Frontend Software Requirements Specification (SRS)
# Hajj Section - Admin Panel

**Version:** 1.0  
**Date:** January 14, 2026  
**Project:** Dubai Travel & Services Admin Panel

---

## Table of Contents
1. [Introduction](#1-introduction)
2. [System Overview](#2-system-overview)
3. [Functional Requirements](#3-functional-requirements)
4. [UI/UX Requirements](#4-uiux-requirements)
5. [Component Specifications](#5-component-specifications)
6. [Page Specifications](#6-page-specifications)
7. [State Management](#7-state-management)
8. [Routing Requirements](#8-routing-requirements)
9. [Form Validation](#9-form-validation)
10. [Error Handling](#10-error-handling)
11. [Performance Requirements](#11-performance-requirements)
12. [Accessibility Requirements](#12-accessibility-requirements)

---

## 1. Introduction

### 1.1 Purpose
This document specifies the frontend requirements for the Hajj Section of the Admin Panel. It details all user interfaces, components, interactions, and technical specifications needed for implementation.

### 1.2 Scope
- Admin Dashboard for Hajj & Umrah section
- Package Management (Hajj & Umrah)
- Article/Blog Management
- Team Member Management
- Testimonial Management
- Contact Inquiry Management
- Settings Configuration

### 1.3 Technology Stack
| Technology | Version | Purpose |
|------------|---------|---------|
| Vue.js | 3.5+ | Frontend framework |
| TypeScript | 5.2+ | Type safety |
| Inertia.js | 2.1+ | SPA routing |
| Tailwind CSS | 4.1+ | Styling |
| Alpine.js | 3.x | Lightweight interactions |
| Lucide Icons | Latest | Icon library |

### 1.4 Design System
The admin panel will use the existing UI component library located in `resources/js/components/ui/`.

---

## 2. System Overview

### 2.1 User Roles
| Role | Access Level |
|------|--------------|
| Super Admin | Full access to all sections |
| Admin | Access to assigned sections |
| Editor | Create/Edit content, no delete/settings |

### 2.2 Module Structure
```
Admin Panel
├── Dashboard
├── Hajj & Umrah Module
│   ├── Packages
│   ├── Articles
│   ├── Team
│   ├── Testimonials
│   ├── Inquiries
│   └── Settings
├── Tour & Travel Module (Future)
└── Typing Services Module (Future)
```

---

## 3. Functional Requirements

### 3.1 Dashboard (FR-DASH)

#### FR-DASH-001: Statistics Display
- **Description**: Display summary statistics cards
- **Data Required**:
  - Total Packages (active/inactive count)
  - Total Articles (published/draft count)
  - New Inquiries (unread count)
  - Total Testimonials (approved/pending count)
- **UI Element**: `StatsCard` component grid
- **Refresh**: On page load and manual refresh button

#### FR-DASH-002: Recent Inquiries Widget
- **Description**: Show latest 5 inquiries
- **Columns**: Name, Subject, Date, Status
- **Actions**: Click to view detail
- **Link**: "View All" navigates to Inquiries page

#### FR-DASH-003: Quick Actions
- **Description**: Shortcut buttons for common tasks
- **Actions**:
  - Add New Package → `/admin/hajj/packages/create`
  - Write Article → `/admin/hajj/articles/create`
  - View Inquiries → `/admin/hajj/inquiries`

#### FR-DASH-004: Recent Activity Feed
- **Description**: Timeline of recent actions
- **Events**: Package created, Article published, Inquiry received
- **Display**: Last 10 activities with timestamps

---

### 3.2 Package Management (FR-PKG)

#### FR-PKG-001: Package List View
- **Description**: Tabular view of all packages
- **Columns**:
  | Column | Sortable | Filterable |
  |--------|----------|------------|
  | Thumbnail | No | No |
  | Title | Yes | Search |
  | Type | Yes | Dropdown (Hajj/Umrah) |
  | Price | Yes | Range |
  | Duration | Yes | No |
  | Featured | No | Toggle |
  | Status | Yes | Dropdown |
  | Actions | No | No |
- **Pagination**: 10, 25, 50 items per page
- **Bulk Actions**: Delete selected, Toggle status

#### FR-PKG-002: Package Create Form
- **Sections**:
  1. **Basic Information**
     - Title (required, max 200 chars)
     - Slug (auto-generated from title, editable)
     - Type (required, select: Hajj/Umrah)
     - Price (required, number, min 0)
     - Currency (select: USD, AED, SAR)
     - Duration Days (required, number, min 1)
  
  2. **Media**
     - Main Image (required, max 5MB, jpg/png/webp)
     - Gallery Images (optional, max 10 images)
  
  3. **Features**
     - Dynamic list of feature items
     - Add/Remove buttons
     - Drag to reorder
  
  4. **Description**
     - Rich text editor (required)
     - Min 100 characters
  
  5. **Itinerary**
     - Dynamic day-by-day sections
     - Each day: Day number, Title, Description, Activities list
  
  6. **Hotel Information**
     - Hotel Name (text)
     - Star Rating (1-5 select)
     - Location (text)
     - Distance to Haram (text)
     - Amenities (tag input)
  
  7. **Inclusions/Exclusions**
     - Two separate dynamic lists
  
  8. **Availability**
     - Departure Dates (multi-date picker)
     - Max Capacity (number)
  
  9. **Status**
     - Is Featured (toggle)
     - Is Active (toggle)

#### FR-PKG-003: Package Edit Form
- **Same as Create** with pre-populated data
- **Additional**: Display current bookings count (read-only)
- **Save & Continue**: Stay on edit page
- **Save & Exit**: Return to list

#### FR-PKG-004: Package Delete
- **Confirmation**: Modal dialog required
- **Message**: "Are you sure you want to delete '{Package Title}'? This action cannot be undone."
- **Soft Delete**: Move to trash (recoverable for 30 days)

#### FR-PKG-005: Package Duplicate
- **Action**: Create copy with "Copy of {Title}"
- **Status**: Set to inactive by default
- **Redirect**: To edit page of new package

---

### 3.3 Article Management (FR-ART)

#### FR-ART-001: Article List View
- **Columns**:
  | Column | Sortable | Filterable |
  |--------|----------|------------|
  | Thumbnail | No | No |
  | Title | Yes | Search |
  | Category | Yes | Dropdown |
  | Author | Yes | Dropdown |
  | Status | Yes | Dropdown |
  | Published Date | Yes | Date range |
  | Views | Yes | No |
  | Actions | No | No |
- **Quick Edit**: Inline status toggle
- **Bulk Actions**: Delete, Publish, Unpublish

#### FR-ART-002: Article Create Form
- **Sections**:
  1. **Basic Information**
     - Title (required, max 200 chars)
     - Slug (auto-generated)
     - Category (required, select/create new)
     - Excerpt (required, max 300 chars)
  
  2. **Content**
     - Rich Text Editor (Tiptap/TinyMCE)
     - Image insertion support
     - Embed support (YouTube, etc.)
     - Code blocks
  
  3. **Featured Image**
     - Single image upload
     - Alt text field
  
  4. **SEO**
     - Meta Title (max 60 chars)
     - Meta Description (max 160 chars)
     - Focus Keyphrase
     - Tags (tag input)
  
  5. **Publishing**
     - Status (Draft/Published)
     - Scheduled Publish Date (optional)

#### FR-ART-003: Article Preview
- **Action**: Preview button opens new tab
- **Shows**: Article as it will appear on frontend

#### FR-ART-004: Category Management
- **Inline Modal**: Create/Edit categories without leaving article page
- **Fields**: Name, Slug, Description

---

### 3.4 Team Management (FR-TEAM)

#### FR-TEAM-001: Team List View
- **Display**: Grid view with cards
- **Card Contents**: Avatar, Name, Role, Status badge
- **Drag & Drop**: Reorder team members
- **Actions**: Edit (modal), Delete, Toggle Active

#### FR-TEAM-002: Team Member Form (Modal)
- **Fields**:
  - Name (required)
  - Role/Position (required)
  - Avatar Image (required)
  - Bio (optional, textarea, max 500 chars)
  - Email (optional, email validation)
  - Phone (optional)
  - Social Links (optional):
    - Facebook URL
    - Twitter URL
    - LinkedIn URL
    - Instagram URL
  - Display Order (number)
  - Is Active (toggle)

---

### 3.5 Testimonial Management (FR-TEST)

#### FR-TEST-001: Testimonial List View
- **Columns**: Avatar, Name, Rating, Excerpt, Featured, Status, Actions
- **Filters**: Status (Pending/Approved), Featured, Rating
- **Quick Actions**: Approve, Reject, Feature

#### FR-TEST-002: Testimonial Form (Modal)
- **Fields**:
  - Name (required)
  - Location/Country (required)
  - Avatar (optional)
  - Rating (required, 1-5 stars)
  - Testimonial Text (required, max 500 chars)
  - Linked Package (optional, select)
  - Is Featured (toggle)
  - Is Approved (toggle)

---

### 3.6 Inquiry Management (FR-INQ)

#### FR-INQ-001: Inquiry List View
- **Columns**: Name, Email, Subject, Package, Status, Date, Actions
- **Filters**: Status, Date range, Has Package
- **Status Colors**:
  - New: Blue
  - Read: Gray
  - Responded: Green
  - Closed: Dark

#### FR-INQ-002: Inquiry Detail View
- **Display**:
  - Full message content
  - Contact details (name, email, phone)
  - Linked package info (if any)
  - Timestamp
- **Actions**:
  - Mark as Read
  - Change Status
  - Add Admin Notes
  - Reply via Email (opens email client)
- **Admin Notes**: Text area for internal notes

---

### 3.7 Settings Management (FR-SET)

#### FR-SET-001: General Settings Tab
- **Fields**:
  - Company Name
  - Company Logo (upload)
  - Primary Email
  - Primary Phone
  - Address (textarea)

#### FR-SET-002: Social Media Tab
- **Fields**: (all optional URLs)
  - Facebook
  - Twitter
  - Instagram
  - LinkedIn
  - YouTube

#### FR-SET-003: Office Locations Tab
- **Dynamic List**:
  - Office Name
  - Address
  - Phone
  - Email
- **Add/Remove offices**

#### FR-SET-004: Homepage Settings Tab
- **Fields**:
  - Hero Banner Image
  - Hero Title
  - Hero Subtitle
  - Featured Packages (multi-select)

---

## 4. UI/UX Requirements

### 4.1 Layout Specifications

#### Admin Layout Structure
```
┌─────────────────────────────────────────────────────────┐
│                    Header (60px)                        │
│  [☰ Menu] [Logo] [Search]        [Notifications] [User] │
├──────────┬──────────────────────────────────────────────┤
│          │                                              │
│ Sidebar  │              Main Content                    │
│ (260px)  │                                              │
│          │  ┌─────────────────────────────────────────┐ │
│ [Menu]   │  │ Breadcrumb                              │ │
│          │  ├─────────────────────────────────────────┤ │
│          │  │                                         │ │
│          │  │ Page Content                            │ │
│          │  │                                         │ │
│          │  │                                         │ │
│          │  │                                         │ │
│          │  └─────────────────────────────────────────┘ │
│          │                                              │
└──────────┴──────────────────────────────────────────────┘
```

#### Responsive Breakpoints
| Breakpoint | Sidebar | Header |
|------------|---------|--------|
| Mobile (<768px) | Hidden (toggle) | Compact |
| Tablet (768-1024px) | Mini (icons only) | Full |
| Desktop (>1024px) | Full (260px) | Full |

### 4.2 Color Scheme

```css
/* Primary Colors */
--admin-primary: #D3A762;       /* Gold - CTAs */
--admin-primary-hover: #c29652; /* Gold hover */

/* Neutral Colors */
--admin-bg: #f8fafc;            /* Page background */
--admin-surface: #ffffff;        /* Cards, panels */
--admin-border: #e2e8f0;        /* Borders */

/* Text Colors */
--admin-text: #1e293b;          /* Primary text */
--admin-text-muted: #64748b;    /* Secondary text */
--admin-text-light: #94a3b8;    /* Captions */

/* Status Colors */
--admin-success: #22c55e;       /* Active, Published */
--admin-warning: #f59e0b;       /* Pending */
--admin-error: #ef4444;         /* Error, Delete */
--admin-info: #3b82f6;          /* Info, New */

/* Sidebar */
--admin-sidebar-bg: #1e293b;    /* Dark sidebar */
--admin-sidebar-text: #e2e8f0;  /* Sidebar text */
--admin-sidebar-active: #D3A762; /* Active item */
```

### 4.3 Typography

| Element | Font | Size | Weight |
|---------|------|------|--------|
| Page Title | Instrument Sans | 24px | 700 |
| Section Title | Instrument Sans | 18px | 600 |
| Body Text | Instrument Sans | 14px | 400 |
| Small Text | Instrument Sans | 12px | 400 |
| Button Text | Instrument Sans | 14px | 600 |
| Table Header | Instrument Sans | 12px | 600 |

### 4.4 Spacing System
```css
--space-1: 4px;
--space-2: 8px;
--space-3: 12px;
--space-4: 16px;
--space-5: 20px;
--space-6: 24px;
--space-8: 32px;
--space-10: 40px;
--space-12: 48px;
```

### 4.5 Animation Guidelines
| Animation | Duration | Easing |
|-----------|----------|--------|
| Fade In | 200ms | ease-out |
| Slide In | 300ms | ease-out |
| Button Hover | 150ms | ease-in-out |
| Modal Open | 200ms | ease-out |
| Loading Spinner | 1000ms | linear |

---

## 5. Component Specifications

### 5.1 Admin Layout Component
**File**: `resources/js/layouts/AdminLayout.vue`

```typescript
interface AdminLayoutProps {
  breadcrumbs?: BreadcrumbItem[];
  title?: string;
  showHeader?: boolean;
}

interface BreadcrumbItem {
  label: string;
  href?: string;
}
```

### 5.2 DataTable Component
**File**: `resources/js/components/admin/DataTable.vue`

```typescript
interface DataTableProps {
  columns: TableColumn[];
  data: any[];
  loading?: boolean;
  selectable?: boolean;
  sortable?: boolean;
  pagination?: PaginationConfig;
}

interface TableColumn {
  key: string;
  label: string;
  sortable?: boolean;
  width?: string;
  align?: 'left' | 'center' | 'right';
  render?: (value: any, row: any) => VNode;
}

interface PaginationConfig {
  currentPage: number;
  totalPages: number;
  perPage: number;
  total: number;
}
```

**Features**:
- Column sorting (click header)
- Row selection (checkboxes)
- Bulk actions bar (when rows selected)
- Empty state
- Loading skeleton

### 5.3 ImageUploader Component
**File**: `resources/js/components/admin/ImageUploader.vue`

```typescript
interface ImageUploaderProps {
  modelValue?: string | File;
  accept?: string;        // Default: 'image/*'
  maxSize?: number;       // MB, default: 5
  aspectRatio?: string;   // e.g., '16:9', '1:1'
  placeholder?: string;
}

interface ImageUploaderEmits {
  (e: 'update:modelValue', value: File | null): void;
  (e: 'error', message: string): void;
}
```

**Features**:
- Drag & drop
- Click to upload
- Preview with remove
- Progress indicator
- Size validation
- Format validation

### 5.4 RichTextEditor Component
**File**: `resources/js/components/admin/RichTextEditor.vue`

```typescript
interface RichTextEditorProps {
  modelValue?: string;
  placeholder?: string;
  minHeight?: string;     // Default: '300px'
  maxLength?: number;
}

// Toolbar Options
const toolbar = [
  'bold', 'italic', 'underline', 'strike',
  'heading', 'bulletList', 'orderedList',
  'link', 'image', 'blockquote', 'codeBlock',
  'undo', 'redo'
];
```

### 5.5 DynamicList Component
**File**: `resources/js/components/admin/DynamicList.vue`

```typescript
interface DynamicListProps {
  modelValue: string[];
  label?: string;
  placeholder?: string;
  maxItems?: number;
  draggable?: boolean;
}
```

**Features**:
- Add new item (input + button)
- Remove item (X button)
- Drag to reorder (if draggable)
- Validation per item

### 5.6 StatsCard Component
**File**: `resources/js/components/admin/StatsCard.vue`

```typescript
interface StatsCardProps {
  title: string;
  value: number | string;
  icon: Component;
  trend?: {
    value: number;
    isPositive: boolean;
  };
  color?: 'primary' | 'success' | 'warning' | 'error';
  loading?: boolean;
}
```

### 5.7 StatusBadge Component
**File**: `resources/js/components/admin/StatusBadge.vue`

```typescript
interface StatusBadgeProps {
  status: 'active' | 'inactive' | 'pending' | 'published' | 'draft' | 'new' | 'read' | 'responded' | 'closed';
  size?: 'sm' | 'md';
}

const statusConfig = {
  active: { label: 'Active', color: 'green' },
  inactive: { label: 'Inactive', color: 'gray' },
  pending: { label: 'Pending', color: 'yellow' },
  published: { label: 'Published', color: 'green' },
  draft: { label: 'Draft', color: 'gray' },
  new: { label: 'New', color: 'blue' },
  read: { label: 'Read', color: 'gray' },
  responded: { label: 'Responded', color: 'green' },
  closed: { label: 'Closed', color: 'slate' },
};
```

### 5.8 ConfirmModal Component
**File**: `resources/js/components/admin/ConfirmModal.vue`

```typescript
interface ConfirmModalProps {
  open: boolean;
  title?: string;
  message: string;
  confirmText?: string;   // Default: 'Confirm'
  cancelText?: string;    // Default: 'Cancel'
  variant?: 'danger' | 'warning' | 'info';
  loading?: boolean;
}

interface ConfirmModalEmits {
  (e: 'confirm'): void;
  (e: 'cancel'): void;
}
```

---

## 6. Page Specifications

### 6.1 Dashboard Page
**Route**: `/admin/dashboard`  
**File**: `resources/js/pages/admin/Dashboard.vue`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│ Dashboard                                    [Refresh]  │
├─────────────────────────────────────────────────────────┤
│                                                         │
│ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐    │
│ │ Packages │ │ Articles │ │ Inquiries│ │Testimon. │    │
│ │    12    │ │    24    │ │    5     │ │    8     │    │
│ └──────────┘ └──────────┘ └──────────┘ └──────────┘    │
│                                                         │
│ ┌─────────────────────────┐ ┌─────────────────────────┐ │
│ │    Recent Inquiries     │ │     Quick Actions       │ │
│ │ ┌─────────────────────┐ │ │                         │ │
│ │ │ Name | Subject | .. │ │ │ [+ Add Package]         │ │
│ │ ├─────────────────────┤ │ │ [+ Write Article]       │ │
│ │ │ ...                 │ │ │ [View Inquiries]        │ │
│ │ └─────────────────────┘ │ │                         │ │
│ │           [View All →]  │ │                         │ │
│ └─────────────────────────┘ └─────────────────────────┘ │
│                                                         │
│ ┌───────────────────────────────────────────────────────┐
│ │                  Recent Activity                      │
│ │ • Package "Premium Hajj" created - 2 hours ago       │
│ │ • Article "Packing Tips" published - 5 hours ago     │
│ │ • New inquiry from Ahmed - 1 day ago                 │
│ └───────────────────────────────────────────────────────┘
└─────────────────────────────────────────────────────────┘
```

### 6.2 Package List Page
**Route**: `/admin/hajj/packages`  
**File**: `resources/js/pages/admin/hajj/packages/Index.vue`

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│ Packages                               [+ Add Package]  │
├─────────────────────────────────────────────────────────┤
│ ┌─────────────────────────────────────────────────────┐ │
│ │ [Search...] [Type ▼] [Status ▼] [Bulk Actions ▼]   │ │
│ └─────────────────────────────────────────────────────┘ │
│                                                         │
│ ┌─────────────────────────────────────────────────────┐ │
│ │ □ │ Image │ Title      │ Type │ Price │ Status │ ⋮ │ │
│ ├───┼───────┼────────────┼──────┼───────┼────────┼───┤ │
│ │ □ │ [img] │ Premium H. │ Hajj │ $590  │ Active │ ⋮ │ │
│ │ □ │ [img] │ Ramadan U. │Umrah │ $890  │ Active │ ⋮ │ │
│ │ □ │ [img] │ Family U.  │Umrah │ $199  │ Draft  │ ⋮ │ │
│ └───┴───────┴────────────┴──────┴───────┴────────┴───┘ │
│                                                         │
│                    [< 1 2 3 ... 10 >]                   │
└─────────────────────────────────────────────────────────┘
```

### 6.3 Package Create/Edit Page
**Routes**: 
- Create: `/admin/hajj/packages/create`
- Edit: `/admin/hajj/packages/{id}/edit`

**File**: `resources/js/pages/admin/hajj/packages/Create.vue` (shared for edit)

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│ ← Back   Create Package              [Save Draft][Save] │
├─────────────────────────────────────────────────────────┤
│ ┌────────────────────────────┐ ┌──────────────────────┐ │
│ │                            │ │  Publish Status      │ │
│ │  [ Basic Information ]     │ │  ○ Draft  ● Active   │ │
│ │  Title: [____________]     │ │                      │ │
│ │  Slug: [____________]      │ │  Featured            │ │
│ │  Type: [Hajj      ▼]       │ │  [Toggle]            │ │
│ │  Price: [____] [USD ▼]     │ │                      │ │
│ │  Duration: [__] days       │ ├──────────────────────┤ │
│ │                            │ │  Main Image          │ │
│ │  [ Media ]                 │ │  ┌────────────────┐  │ │
│ │  [ Features ]              │ │  │  Drop image    │  │ │
│ │  [ Description ]           │ │  │  or click      │  │ │
│ │  [ Itinerary ]             │ │  └────────────────┘  │ │
│ │  [ Hotel Info ]            │ │                      │ │
│ │  [ Inclusions ]            │ │                      │ │
│ │  [ Availability ]          │ │                      │ │
│ │                            │ │                      │ │
│ └────────────────────────────┘ └──────────────────────┘ │
└─────────────────────────────────────────────────────────┘
```

---

## 7. State Management

### 7.1 Form State Pattern
```typescript
// Using Inertia useForm
const form = useForm({
  title: '',
  slug: '',
  type: 'hajj',
  price: 0,
  // ... other fields
});

// Submit handler
const submit = () => {
  form.post(route('admin.hajj.packages.store'), {
    onSuccess: () => {
      // Handle success
    },
    onError: () => {
      // Handle error
    },
  });
};
```

### 7.2 Loading States
- Page loading: Show skeleton
- Form submitting: Disable buttons, show spinner
- Table loading: Show skeleton rows
- Image uploading: Show progress bar

### 7.3 Flash Messages
```typescript
interface FlashMessage {
  type: 'success' | 'error' | 'warning' | 'info';
  message: string;
}

// Display using toast notification
```

---

## 8. Routing Requirements

### 8.1 Route Structure
```
/admin
├── /dashboard                    # Dashboard
├── /hajj
│   ├── /packages                 # Package list
│   ├── /packages/create          # Create package
│   ├── /packages/{id}/edit       # Edit package
│   ├── /articles                 # Article list
│   ├── /articles/create          # Create article
│   ├── /articles/{id}/edit       # Edit article
│   ├── /team                     # Team management
│   ├── /testimonials             # Testimonials
│   ├── /inquiries                # Inquiry list
│   ├── /inquiries/{id}           # Inquiry detail
│   └── /settings                 # Section settings
├── /tour (future)
└── /typing (future)
```

### 8.2 Route Guards
- All admin routes require authentication
- Role-based access per section

---

## 9. Form Validation

### 9.1 Client-Side Validation
```typescript
// Package Form Validation Rules
const packageRules = {
  title: {
    required: true,
    maxLength: 200,
    message: 'Title is required and must be under 200 characters'
  },
  type: {
    required: true,
    enum: ['hajj', 'umrah'],
  },
  price: {
    required: true,
    min: 0,
    numeric: true,
  },
  image: {
    required: true,
    maxSize: 5 * 1024 * 1024, // 5MB
    accept: ['image/jpeg', 'image/png', 'image/webp'],
  },
  description: {
    required: true,
    minLength: 100,
  },
};
```

### 9.2 Error Display
- Field-level errors below input
- Summary errors in alert at form top
- Toast for API errors

---

## 10. Error Handling

### 10.1 HTTP Error Handling
| Status | Action |
|--------|--------|
| 401 | Redirect to login |
| 403 | Show "Access Denied" message |
| 404 | Show "Not Found" page |
| 422 | Display validation errors |
| 500 | Show generic error message |

### 10.2 Form Error Display
```vue
<InputError :message="form.errors.title" />
```

### 10.3 Empty States
- Empty table: Show illustration + "No data" message + action button
- Empty search: "No results found for '{query}'"

---

## 11. Performance Requirements

### 11.1 Page Load
- Initial load: < 2 seconds
- Route navigation: < 500ms
- Lazy load images

### 11.2 Form Performance
- Debounce search: 300ms
- Auto-save draft: Every 30 seconds
- Image upload: Show progress

### 11.3 Data Pagination
- Default: 10 items per page
- Max: 100 items per page
- Use server-side pagination

---

## 12. Accessibility Requirements

### 12.1 Keyboard Navigation
- All interactive elements focusable
- Logical tab order
- Enter/Space activates buttons
- Escape closes modals

### 12.2 ARIA Labels
- All buttons have labels
- Form fields have labels
- Icons have aria-hidden or aria-label
- Status badges have sr-only text

### 12.3 Color Contrast
- Text contrast: WCAG AA (4.5:1)
- Large text: 3:1
- Never rely on color alone

---

## Appendix A: Component File Structure

```
resources/js/
├── components/
│   └── admin/
│       ├── layout/
│       │   ├── AdminSidebar.vue
│       │   ├── AdminHeader.vue
│       │   └── AdminBreadcrumb.vue
│       ├── common/
│       │   ├── DataTable.vue
│       │   ├── StatsCard.vue
│       │   ├── StatusBadge.vue
│       │   ├── ConfirmModal.vue
│       │   ├── ImageUploader.vue
│       │   ├── GalleryUploader.vue
│       │   ├── RichTextEditor.vue
│       │   ├── DynamicList.vue
│       │   └── SearchFilter.vue
│       └── forms/
│           ├── PackageForm.vue
│           ├── ArticleForm.vue
│           ├── TeamMemberForm.vue
│           └── TestimonialForm.vue
├── layouts/
│   └── AdminLayout.vue
├── pages/
│   └── admin/
│       ├── Dashboard.vue
│       └── hajj/
│           ├── packages/
│           │   ├── Index.vue
│           │   ├── Create.vue
│           │   └── Edit.vue
│           ├── articles/
│           │   ├── Index.vue
│           │   ├── Create.vue
│           │   └── Edit.vue
│           ├── team/
│           │   └── Index.vue
│           ├── testimonials/
│           │   └── Index.vue
│           ├── inquiries/
│           │   ├── Index.vue
│           │   └── Show.vue
│           └── settings/
│               └── Index.vue
└── types/
    └── admin.d.ts
```

---

## Appendix B: TypeScript Interfaces

See `docs/hajj-section-overview.md` for complete type definitions.

---

**Document Version History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-14 | System | Initial document |
